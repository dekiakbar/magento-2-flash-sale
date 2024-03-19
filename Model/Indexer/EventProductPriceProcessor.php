<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Model\Indexer;

use Deki\FlashSale\Model\ResourceModel\Event\CollectionFactory;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Store\Model\StoreManagerInterface;
use Deki\FlashSale\Model\ResourceModel\EventProductPrice\CollectionFactory as PriceCollectionFactory;
use Deki\FlashSale\Model\ResourceModel\EventProductPrice\Collection as PriceCollection;
use Magento\Framework\Indexer\AbstractProcessor;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFActory;
use Deki\FlashSale\Model\Config;

class EventProductPriceProcessor extends AbstractProcessor
{
    /**
    * Indexer id
    */
    const INDEXER_ID = 'flash_sale_product_price';

    /**
     * @var CollectionFactory
     */
    protected $eventFactory;

    /**
     * @var TimezoneInterface
     */
    protected $timeZone;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var PriceCollectionFactory
     */
    protected $priceCollectionFactory;

    /**
     * @var ProductCollectionFActory
     */
    protected $productCollectionFActory;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param \Magento\Framework\Indexer\IndexerRegistry $indexerRegistry
     * @param CollectionFactory $eventFactory
     * @param TimezoneInterface $timeZone
     * @param StoreManagerInterface $storeManager
     * @param PriceCollectionFactory $priceCollectionFactory
     * @param ProductCollectionFActory $productCollectionFActory
     * @param Config $config
     */
    public function __construct(
        \Magento\Framework\Indexer\IndexerRegistry $indexerRegistry,
        CollectionFactory $eventFactory,
        TimezoneInterface $timeZone,
        StoreManagerInterface $storeManager,
        PriceCollectionFactory $priceCollectionFactory,
        ProductCollectionFActory $productCollectionFActory,
        Config $config
    ) {
        parent::__construct($indexerRegistry);
        $this->eventFactory = $eventFactory;
        $this->timeZone = $timeZone;
        $this->storeManager = $storeManager;
        $this->priceCollectionFactory = $priceCollectionFactory;
        $this->productCollectionFActory = $productCollectionFActory;
        $this->config = $config;
    }

    /**
     * Get all active and started event and products data
     *
     * @param int $storeId
     * @return array|null
     */
    public function getAvailableSale($storeId = null)
    {
        if (empty($storeId)) {
            $storeId = $this->storeManager->getStore()->getId();
        }

        /**
         * Timezone is converted to UTC, magento save flas sale date
         * in UTC
         */
        $currentDate = $this->timeZone->convertConfigTimeToUtc(
            $this->timeZone->scopeDate($storeId, null, true),
            "Y-m-d H:i:s"
        );

        /** @var \Deki\FlashSale\Model\ResourceModel\Event\Collection $events */
        $events = $this->eventFactory->create();
        $events->addFieldToFilter('is_enabled', 1);
        $events->addFieldToFilter('date_time_from', ['lteq' => $currentDate]);
        $events->addFieldToFilter('date_time_to', ['gteq' => $currentDate]);

        $uniqueProducts = [];

        /** @var \Deki\FlashSale\Model\Event $event */
        foreach ($events as $event) {
            // Get event products for the current event
            $eventProducts = $event->getEventProducts();

            /** @var \Deki\FlashSale\Model\EventProduct $product */
            foreach ($eventProducts as $product) {
                /**
                 * Skip / exclude product with limit 0 from flash sale
                 */
                if ($product->getQty() <= 0) {
                    continue;
                }

                $productId = $product->getProductId();

                // If product already exists in the uniqueProducts array
                if (isset($uniqueProducts[$productId])) {
                    // Get the associated event and its sort order
                    $existingEvent = $uniqueProducts[$productId]['event'];
                    $existingSortOrder = $existingEvent->getSortOrder();

                    // Compare the sort orders of the existing and current events
                    if ($event->getSortOrder() > $existingSortOrder) {
                      // Update the product in uniqueProducts with the current event
                        $uniqueProducts[$productId] = [
                          'event' => $event,
                          'product' => $product
                        ];
                    }
                } else {
                    // If product doesn't exist in uniqueProducts array, add it
                    $uniqueProducts[$productId] = [
                    'event' => $event,
                    'product' => $product
                    ];
                }
            }
        }
        
        return $uniqueProducts;
    }

    /**
     * Do Reindex flash sale price
     *
     * @param int|null $storeId
     * @return void
     */
    public function reindexFull($storeId = null)
    {
        if (!$this->config->isEnabled()) {
            return;
        }

        $uniqueProducts = $this->getAvailableSale($storeId);

        $this->deleteIndexedFlashSale();

        if (count($uniqueProducts) <= 0) {
            return;
        }

        $bulkSaleProductPrice = [];
        $col = $this->productCollectionFActory->create();
        $col->addAttributeToSelect('price');
        $col->addAttributeToFilter('entity_id', ['in' => array_keys($uniqueProducts)]);
        $regularPrices = $col->toArray();

        foreach ($uniqueProducts as $productId => $data) {
            $event = $data['event'];
            $product = $data['product'];
      
            $bulkSaleProductPrice[] = [
            'event_id' => $event->getId(),
            'product_id' => $productId,
            'price' => $product->getPrice(),
            'discount_amount' => $this->calculateDiscount(
                $regularPrices[$productId]['price'],
                $product->getPrice()
            ),
            'start_date' => $event->getDateTimeFrom(),
            'end_date' => $event->getDateTimeTo()
            ];
        }

        /** @var PriceCollection $priceCollection */
        $priceCollection = $this->priceCollectionFactory->create();
        $tableName = $priceCollection->getMainTable();
        $priceCollection->getConnection()->insertMultiple($tableName, $bulkSaleProductPrice);
    }

    /**
     * Delete all indexed flash sale price
     *
     * @return void
     */
    public function deleteIndexedFlashSale()
    {
        /** @var PriceCollection $priceCollection */
        $priceCollection = $this->priceCollectionFactory->create();
        $connection = $priceCollection->getConnection();
        $tableName = $priceCollection->getMainTable();
        $connection->truncateTable($tableName);
    }

    /**
     * Calculate Dicount amount
     *
     * @param float $regularPrice
     * @param float $flashSalePrice
     * @return float
     */
    public function calculateDiscount($regularPrice, $flashSalePrice)
    {
        return (float)(100 - ($flashSalePrice / $regularPrice * 100));
    }
}

<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Model;

use Deki\FlashSale\Model\EventProductRepository;
use Deki\FlashSale\Api\Data\EventProductInterface;
use Deki\FlashSale\Model\ResourceModel\EventProductPriceFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Quote\Model\Quote\Item;
use Magento\CatalogInventory\Helper\Data;

class FlashSaleService
{
    public const IS_FLASH_SALE = 'is_flash_sale';
    public const EVENT_ID = 'flash_sale_event_id';
    public const EVENT_PRODUCT_ID = 'flash_sale_event_product_id';
    public const FLASH_SALE_DISCOUNT = 'flash_sale_dicount_amount';
    public const ERROR_QUOTE_ORIGIN = 'flash_sale';
    public const ERROR_QUOTE_MESSAGE = 'You have Invalid product(s).';
    public const ERROR_QUOTE_ITEM_QTY = 'Exceeded maximum allowed flash sale quantity.';
    public const ERROR_QUOTE_ITEM_INVALID = 'Flash sale ended or quantity sold out.';

    /**
     * @var EventProductRepository
     */
    protected $eventProductRepository;

    /**
     * @var EventProductPriceFactory
     */
    protected $eventProductPriceFactory;

    /**
     * @var TimezoneInterface
     */
    protected $timeZoneInterface;

    /**
     * @param EventProductRepository $eventProductRepository
     * @param EventProductPriceFactory $eventProductPriceFactory
     * @param TimezoneInterface $timeZoneInterface
     */
    public function __construct(
        EventProductRepository $eventProductRepository,
        EventProductPriceFactory $eventProductPriceFactory,
        TimezoneInterface $timeZoneInterface
    ) {
        $this->eventProductRepository = $eventProductRepository;
        $this->eventProductPriceFactory = $eventProductPriceFactory;
        $this->timeZoneInterface = $timeZoneInterface;
    }

    /**
     * Deduct flash sale product Qty
     *
     * @param int $eventProductId
     * @param int $qty
     * @return EventProductInterface
     */
    public function deductEventProductQty($eventProductId, $qty)
    {
        $eventProduct = $this->eventProductRepository->get($eventProductId);
        $eventProduct->setQty(($eventProduct->getQty() - (int)$qty));
        return $this->eventProductRepository->save($eventProduct);
    }

    /**
     * Check is requested qty exceeded maximum allowed in flash sale
     *
     * @param int $eventProductId
     * @param int $qty
     * @return boolean
     */
    public function isQtyExceeded($eventProductId, $qty)
    {
        $eventProduct = $this->eventProductRepository->get($eventProductId);
        return ($qty > $eventProduct->getQty());
    }

    /**
     * Validate if quote item is in flash sale. If invalid,
     * error message will be added to item and quote.
     * Make sure if quoteItem is marked as flash sale,
     * otherwise will throw an exception.
     *
     * @param Item $quoteItem
     * @return boolean
     */
    public function validateQuoteItem($quoteItem)
    {
        if (!$quoteItem ||
            !$quoteItem->getData(self::IS_FLASH_SALE)
        ) {
            throw new LocalizedException(__("Seems like quote item is not flash sale item"));
        }

        $productId = $quoteItem->getProductId();
        $storeId = $quoteItem->getStoreId();
        $date = $this->timeZoneInterface->scopeDate($storeId, null, true);
        $flashSaleInfo = $this->eventProductPriceFactory->create()->getFlashSalePriceInfo($date, $productId);

        /**
         * Product is not in flash sale
         * Flash sale is end
         * Flash sale product is out of stock
         */
        if ($flashSaleInfo === false) {
            $this->addErrorInfoToQuote($quoteItem, self::ERROR_QUOTE_ITEM_INVALID);
            return false;
        }

        /**
         * Validate item in quote is exceeded flash sale qty.
         */
        $isQtyExceeded = $this->isQtyExceeded(
            $quoteItem->getData(self::EVENT_PRODUCT_ID),
            $quoteItem->getQty()
        );

        if ($isQtyExceeded) {
            $this->addErrorInfoToQuote($quoteItem, self::ERROR_QUOTE_ITEM_QTY);
            return false;
        }

        /**
         * Sometime quote save qty in parent item ID.
         * So we also need to check parent item.
         */
        if ($quoteItem->getParentItemId()) {
            $parentItem = $quoteItem->getParentItem();

            $parentIsQtyExceeded = $this->isQtyExceeded(
                $quoteItem->getData(self::EVENT_PRODUCT_ID),
                $parentItem->getQty()
            );

            if ($parentIsQtyExceeded) {
                $this->addErrorInfoToQuote($parentItem, self::ERROR_QUOTE_ITEM_QTY);
                return false;
            }
        }

        return true;
    }

    /**
     * Add error information to Quote Item
     *
     * @param Item $quoteItem
     * @param \Magento\Framework\Phrase $itemMessage
     * @return void
     */
    private function addErrorInfoToQuote($quoteItem, $itemMessage)
    {
        $quoteItem->addErrorInfo(
            self::ERROR_QUOTE_ORIGIN,
            Data::ERROR_QTY,
            __($itemMessage)
        );

        $quoteItem->getQuote()->addErrorInfo(
            null,
            self::ERROR_QUOTE_ORIGIN,
            Data::ERROR_QTY,
            __(self::ERROR_QUOTE_MESSAGE)
        );
    }
}

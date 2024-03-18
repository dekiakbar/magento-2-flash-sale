<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Deki\FlashSale\Model\ResourceModel\EventProductPriceFactory;
use Deki\FlashSale\Model\Config;

/**
 * Observer for applying flash sale price on product for frontend area
 *
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 */
class ProcessFrontFinalPriceObserver implements ObserverInterface
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $localeDate;

    /**
     * @var EventProductPriceFactory
     */
    private $eventProductPriceFactory;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param StoreManagerInterface $storeManager
     * @param TimezoneInterface $localeDate
     * @param EventProductPriceFactory $eventProductPriceFactory
     * @param Config $config
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        TimezoneInterface $localeDate,
        EventProductPriceFactory $eventProductPriceFactory,
        Config $config
    ) {
        $this->storeManager = $storeManager;
        $this->localeDate = $localeDate;
        $this->eventProductPriceFactory = $eventProductPriceFactory;
        $this->config = $config;
    }

    /**
     * Apply flash sale price to product on frontend (e.g: add to cart)
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->config->isEnabled()) {
            return $this;
        }

        $product = $observer->getEvent()->getProduct();
        $productId = $product->getId();
        $storeId = $product->getStoreId();

        if ($observer->hasDate()) {
            $date = new \DateTime($observer->getEvent()->getDate());
        } else {
            $date = $this->localeDate->scopeDate($storeId, null, true);
        }

        $flashSalePrice = $this->eventProductPriceFactory->create()->getFlashSalePriceInfo($date, $productId);
        if ($flashSalePrice !== false) {
            $finalPrice = min($product->getData('final_price'), $flashSalePrice['price']);
            $product->setFinalPrice($finalPrice);

            /**
             * Inject product attribute will be used in quote item.
             * e.g: Deki\FlashSale\Observer\CopyAtrributeToQuoteItem
             */
            $product->setData('is_flash_sale', true);
            $product->setData('flash_sale_event_id', $flashSalePrice['event_id']);
            $product->setData('flash_sale_event_product_id', $flashSalePrice['flash_sale_event_product_id']);
            $product->setData('flash_sale_qty', $flashSalePrice['qty']);
        }
        return $this;
    }
}

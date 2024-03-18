<?php
/**
 * Product inventory data validator
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Deki\FlashSale\Observer;

use Magento\Framework\Event\ObserverInterface;
use Deki\FlashSale\Model\FlashSaleService;
use Deki\FlashSale\Model\Config;

class QuoteValidator implements ObserverInterface
{
    /**
     * @var FlashSaleService $flashSaleService
     */
    protected $flashSaleService;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param FlashSaleService $flashSaleService
     * @param Config $config
     */
    public function __construct(
        FlashSaleService $flashSaleService,
        Config $config
    ) {
        $this->flashSaleService = $flashSaleService;
        $this->config = $config;
    }

    /**
     * Check quote item.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->config->isEnabled()) {
            return $this;
        }

        /** @var \Magento\Quote\Model\Quote\Item $quoteItem */
        $quoteItem = $observer->getEvent()->getItem();
        if ($quoteItem->getData(FlashSaleService::IS_FLASH_SALE)) {
            $this->flashSaleService->validateQuoteItem($quoteItem);
        }
    }
}

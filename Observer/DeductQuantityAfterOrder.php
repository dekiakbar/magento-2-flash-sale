<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Observer;

use Deki\FlashSale\Model\FlashSaleService;
use Deki\FlashSale\Model\Config;

class DeductQuantityAfterOrder implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var FlashSaleService
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
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        if (!$this->config->isEnabled()) {
            return $this;
        }

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getQuote();
        /** @var \Magento\Quote\Model\Quote\Item[] $items */
        $items = $quote->getItemsCollection();

        foreach ($items as $item) {
            if ($item->getData('is_flash_sale')) {

                /**
                 * Deduct qty, magento save item qty on parent (configurable product)
                 */
                if($item->getParentItemId()){
                    $parent = $item->getParentItem();
                    $this->flashSaleService->deductEventProductQty(
                        $item->getData('flash_sale_event_product_id'),
                        $parent->getQty()
                    );
                }else{
                    // simple product
                    $this->flashSaleService->deductEventProductQty(
                        $item->getData('flash_sale_event_product_id'),
                        $item->getQty()
                    );
                }
            }
        }
    }
}

<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Deki\FlashSale\Plugin\Indexer\Product\Save;

use Deki\FlashSale\Model\Indexer\EventProductPriceProcessor;
use Deki\FlashSale\Model\Config;

class ApplyFlashSale
{
    /**
     * @var EventProductPriceProcessor
     */
    protected $eventProductPriceProcessor;

    /**
     * @var Config
     */
    private $config;

    /**
     *
     * @param EventProductPriceProcessor $eventProductPriceProcessor
     * @param Config $config
     */
    public function __construct(
        EventProductPriceProcessor $eventProductPriceProcessor,
        Config $config
    ) {
        $this->eventProductPriceProcessor = $eventProductPriceProcessor;
        $this->config = $config;
    }

    /**
     * Apply flash sale price after product resource model save
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product $subject
     * @param \Magento\Catalog\Model\ResourceModel\Product $productResource
     * @param \Magento\Framework\Model\AbstractModel $product
     * @return \Magento\Catalog\Model\ResourceModel\Product
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSave(
        \Magento\Catalog\Model\ResourceModel\Product $subject,
        \Magento\Catalog\Model\ResourceModel\Product $productResource,
        \Magento\Framework\Model\AbstractModel $product
    ) {
        if (!$this->config->isEnabled()) {
            return $productResource;
        }

        if (!$product->getIsMassupdate()) {
            // TODO: maybe index only 1 product
            $this->eventProductPriceProcessor->markIndexerAsInvalid();
        }

        return $productResource;
    }
}

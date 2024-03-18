<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Deki\FlashSale\Plugin\Indexer;

use Deki\FlashSale\Model\Indexer\EventProductPriceProcessor;
use Magento\ImportExport\Model\Import;
use Deki\FlashSale\Model\Config;

class ImportExport
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
     * Invalidate flash sale indexer
     *
     * @param Import $subject
     * @param bool $result
     * @return bool
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterImportSource(Import $subject, $result)
    {
        if (!$this->config->isEnabled()) {
            return $result;
        }

        if (!$this->eventProductPriceProcessor->isIndexerScheduled()) {
            $this->eventProductPriceProcessor->markIndexerAsInvalid();
        }

        return $result;
    }
}

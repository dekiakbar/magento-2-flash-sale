<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Cron;

use Deki\FlashSale\Model\Indexer\EventProductPriceProcessor;

class ApplyFlashSale
{
    /**
     * @var EventProductPriceProcessor
     */
    protected $eventProductPriceProcessor;


    /**
     * @param EventProductPriceProcessor $eventProductPriceProcessor
     */
    public function __construct(
        EventProductPriceProcessor $eventProductPriceProcessor,
    ) {
        $this->eventProductPriceProcessor = $eventProductPriceProcessor;
    }

    /**
     * Check Flash sale event periodically (every minute).
     *
     * This method is called from cron process, cron is working in UTC time
     *
     * @return void
     */
    public function execute()
    {
        $availableSale = $this->eventProductPriceProcessor->getAvailableSale();
        if (count($availableSale) >= 0) {
            $this->eventProductPriceProcessor->markIndexerAsInvalid();
        }
    }
}

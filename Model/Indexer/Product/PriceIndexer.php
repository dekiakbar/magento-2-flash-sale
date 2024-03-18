<?php
namespace Deki\FlashSale\Model\Indexer\Product;

use Deki\FlashSale\Model\Indexer\EventProductPriceProcessor;

class PriceIndexer implements \Magento\Framework\Indexer\ActionInterface, \Magento\Framework\Mview\ActionInterface
{
    /**
     * @var EventProductPriceProcessor
     */
    protected $eventProductPriceProcessor;

    public function __construct(
        EventProductPriceProcessor $eventProductPriceProcessor
    ) {
        $this->eventProductPriceProcessor = $eventProductPriceProcessor;
    }

    /*
    * Used by mview, allows process indexer in the "Update on schedule" mode
    */
    public function execute($ids)
    {
        $this->eventProductPriceProcessor->reindexFull();
    }

    /*
    * Will take all of the data and reindex
    * Will run when reindex via command line
    */
    public function executeFull()
    {
        $this->eventProductPriceProcessor->reindexFull();
    }

    /*
    * Works with a set of entity changed (may be massaction)
    */
    public function executeList(array $ids)
    {
        $this->eventProductPriceProcessor->reindexFull();
    }

    /*
    * Works in runtime for a single entity using plugins
    */
    public function executeRow($id)
    {
        $this->eventProductPriceProcessor->reindexFull();
    }
}

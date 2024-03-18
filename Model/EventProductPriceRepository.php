<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Model;

use Deki\FlashSale\Api\Data\EventProductPriceInterface;
use Deki\FlashSale\Api\Data\EventProductPriceInterfaceFactory;
use Deki\FlashSale\Api\Data\EventProductPriceSearchResultsInterfaceFactory;
use Deki\FlashSale\Api\EventProductPriceRepositoryInterface;
use Deki\FlashSale\Model\ResourceModel\EventProductPrice as ResourceEventProductPrice;
use Deki\FlashSale\Model\ResourceModel\EventProductPrice\CollectionFactory as EventProductPriceCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class EventProductPriceRepository implements EventProductPriceRepositoryInterface
{

    /**
     * @var EventProductPriceCollectionFactory
     */
    protected $eventProductPriceCollectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var EventProductPrice
     */
    protected $searchResultsFactory;

    /**
     * @var ResourceEventProductPrice
     */
    protected $resource;

    /**
     * @var EventProductPriceInterfaceFactory
     */
    protected $eventProductPriceFactory;


    /**
     * @param ResourceEventProductPrice $resource
     * @param EventProductPriceInterfaceFactory $eventProductPriceFactory
     * @param EventProductPriceCollectionFactory $eventProductPriceCollectionFactory
     * @param EventProductPriceSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceEventProductPrice $resource,
        EventProductPriceInterfaceFactory $eventProductPriceFactory,
        EventProductPriceCollectionFactory $eventProductPriceCollectionFactory,
        EventProductPriceSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->eventProductPriceFactory = $eventProductPriceFactory;
        $this->eventProductPriceCollectionFactory = $eventProductPriceCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(
        EventProductPriceInterface $eventProductPrice
    ) {
        try {
            $this->resource->save($eventProductPrice);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the eventProductPrice: %1',
                $exception->getMessage()
            ));
        }
        return $eventProductPrice;
    }

    /**
     * @inheritDoc
     */
    public function get($eventProductPriceId)
    {
        $eventProductPrice = $this->eventProductPriceFactory->create();
        $this->resource->load($eventProductPrice, $eventProductPriceId);
        if (!$eventProductPrice->getId()) {
            throw new NoSuchEntityException(__('event_product_price with id "%1" does not exist.', $eventProductPriceId));
        }
        return $eventProductPrice;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->eventProductPriceCollectionFactory->create();
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(
        EventProductPriceInterface $eventProductPrice
    ) {
        try {
            $eventProductPriceModel = $this->eventProductPriceFactory->create();
            $this->resource->load($eventProductPriceModel, $eventProductPrice->getEventProductPriceId());
            $this->resource->delete($eventProductPriceModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the event_product_price: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($eventProductPriceId)
    {
        return $this->delete($this->get($eventProductPriceId));
    }
}

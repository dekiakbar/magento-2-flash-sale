<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Model;

use Deki\FlashSale\Api\Data\EventInterface;
use Deki\FlashSale\Api\Data\EventInterfaceFactory;
use Deki\FlashSale\Api\Data\EventSearchResultsInterfaceFactory;
use Deki\FlashSale\Api\EventRepositoryInterface;
use Deki\FlashSale\Model\ResourceModel\Event as ResourceEvent;
use Deki\FlashSale\Model\ResourceModel\Event\CollectionFactory as EventCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class EventRepository implements EventRepositoryInterface
{

    /**
     * @var Event
     */
    protected $searchResultsFactory;

    /**
     * @var EventCollectionFactory
     */
    protected $eventCollectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var EventInterfaceFactory
     */
    protected $eventFactory;

    /**
     * @var ResourceEvent
     */
    protected $resource;


    /**
     * @param ResourceEvent $resource
     * @param EventInterfaceFactory $eventFactory
     * @param EventCollectionFactory $eventCollectionFactory
     * @param EventSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceEvent $resource,
        EventInterfaceFactory $eventFactory,
        EventCollectionFactory $eventCollectionFactory,
        EventSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->eventFactory = $eventFactory;
        $this->eventCollectionFactory = $eventCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(EventInterface $event)
    {
        try {
            $this->resource->save($event);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the event: %1',
                $exception->getMessage()
            ));
        }
        return $event;
    }

    /**
     * @inheritDoc
     */
    public function get($eventId)
    {
        $event = $this->eventFactory->create();
        $this->resource->load($event, $eventId);
        if (!$event->getId()) {
            throw new NoSuchEntityException(__('event with id "%1" does not exist.', $eventId));
        }
        return $event;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->eventCollectionFactory->create();
        
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
    public function delete(EventInterface $event)
    {
        try {
            $eventModel = $this->eventFactory->create();
            $this->resource->load($eventModel, $event->getEventId());
            $this->resource->delete($eventModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the event: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($eventId)
    {
        return $this->delete($this->get($eventId));
    }
}

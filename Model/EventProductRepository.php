<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Model;

use Deki\FlashSale\Api\Data\EventProductInterface;
use Deki\FlashSale\Api\Data\EventProductInterfaceFactory;
use Deki\FlashSale\Api\Data\EventProductSearchResultsInterfaceFactory;
use Deki\FlashSale\Api\EventProductRepositoryInterface;
use Deki\FlashSale\Model\ResourceModel\EventProduct as ResourceEventProduct;
use Deki\FlashSale\Model\ResourceModel\EventProduct\CollectionFactory as EventProductCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class EventProductRepository implements EventProductRepositoryInterface
{

    /**
     * @var EventProduct
     */
    protected $searchResultsFactory;

    /**
     * @var EventProductCollectionFactory
     */
    protected $eventProductCollectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var EventProductInterfaceFactory
     */
    protected $eventProductFactory;

    /**
     * @var ResourceEventProduct
     */
    protected $resource;


    /**
     * @param ResourceEventProduct $resource
     * @param EventProductInterfaceFactory $eventProductFactory
     * @param EventProductCollectionFactory $eventProductCollectionFactory
     * @param EventProductSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceEventProduct $resource,
        EventProductInterfaceFactory $eventProductFactory,
        EventProductCollectionFactory $eventProductCollectionFactory,
        EventProductSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->eventProductFactory = $eventProductFactory;
        $this->eventProductCollectionFactory = $eventProductCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(EventProductInterface $eventProduct)
    {
        try {
            $this->resource->save($eventProduct);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the eventProduct: %1',
                $exception->getMessage()
            ));
        }
        return $eventProduct;
    }

    /**
     * @inheritDoc
     */
    public function get($eventProductId)
    {
        $eventProduct = $this->eventProductFactory->create();
        $this->resource->load($eventProduct, $eventProductId);
        if (!$eventProduct->getId()) {
            throw new NoSuchEntityException(__('event_product with id "%1" does not exist.', $eventProductId));
        }
        return $eventProduct;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->eventProductCollectionFactory->create();
        
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
    public function delete(EventProductInterface $eventProduct)
    {
        try {
            $eventProductModel = $this->eventProductFactory->create();
            $this->resource->load($eventProductModel, $eventProduct->getEventProductId());
            $this->resource->delete($eventProductModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the event_product: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($eventProductId)
    {
        return $this->delete($this->get($eventProductId));
    }
}

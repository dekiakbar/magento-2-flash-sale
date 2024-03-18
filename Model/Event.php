<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Model;

use Deki\FlashSale\Api\Data\EventInterface;
use Magento\Framework\Model\AbstractModel;
use Deki\FlashSale\Model\ResourceModel\EventProduct\CollectionFactory as EventProductCollectionFactory;
use Deki\FlashSale\Model\ResourceModel\EventProduct\Collection as EventProductCollection;

class Event extends AbstractModel implements EventInterface
{
    protected $_eventProductCollectionFactory;

    public function __construct(
        EventProductCollectionFactory $eventProductCollectionFactory,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->_eventProductCollectionFactory = $eventProductCollectionFactory;
    }

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Deki\FlashSale\Model\ResourceModel\Event::class);
    }

    /**
     * @inheritDoc
     */
    public function getEventId()
    {
        return $this->getData(self::EVENT_ID);
    }

    /**
     * @inheritDoc
     */
    public function setEventId($eventId)
    {
        return $this->setData(self::EVENT_ID, $eventId);
    }

    /**
     * @inheritDoc
     */
    public function getIsEnabled()
    {
        return $this->getData(self::IS_ENABLED);
    }

    /**
     * @inheritDoc
     */
    public function setIsEnabled($isEnabled)
    {
        return $this->setData(self::IS_ENABLED, $isEnabled);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritDoc
     */
    public function getDateTimeFrom()
    {
        return $this->getData(self::DATE_TIME_FROM);
    }

    /**
     * @inheritDoc
     */
    public function setDateTimeFrom($dateTimeFrom)
    {
        return $this->setData(self::DATE_TIME_FROM, $dateTimeFrom);
    }

    /**
     * @inheritDoc
     */
    public function getDateTimeTo()
    {
        return $this->getData(self::DATE_TIME_TO);
    }

    /**
     * @inheritDoc
     */
    public function setDateTimeTo($dateTimeTo)
    {
        return $this->setData(self::DATE_TIME_TO, $dateTimeTo);
    }

    /**
     * @inheritDoc
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }

    /**
     * @inheritDoc
     */
    public function setSortOrder($sortOrder)
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * Get Event Products
     *
     * @return EventProductCollection
     */
    public function getEventProducts()
    {
        $collection = $this->_eventProductCollectionFactory->create();
        $collection->addFieldToFilter('event_id', $this->getId());
        return $collection;
    }

    /**
     * Get Event Products
     *
     * @return array
     */
    public function getEventProductsArray()
    {
        $eventProductDatas = $this->getEventProducts();
        $result = [];
        foreach ($eventProductDatas as $eventProduct) {
            $result[$eventProduct->getProductId()] =  [
                $eventProduct->getPrice(),
                $eventProduct->getQty()
            ];
        }

        return $result;
    }
}

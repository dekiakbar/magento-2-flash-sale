<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Model;

use Deki\FlashSale\Api\Data\EventProductInterface;
use Magento\Framework\Model\AbstractModel;

class EventProduct extends AbstractModel implements EventProductInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Deki\FlashSale\Model\ResourceModel\EventProduct::class);
    }

    /**
     * @inheritDoc
     */
    public function getEventProductId()
    {
        return $this->getData(self::EVENT_PRODUCT_ID);
    }

    /**
     * @inheritDoc
     */
    public function setEventProductId($eventProductId)
    {
        return $this->setData(self::EVENT_PRODUCT_ID, $eventProductId);
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
    public function getProductId()
    {
        return $this->getData(self::PRODUCT_ID);
    }

    /**
     * @inheritDoc
     */
    public function setProductId($productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * @inheritDoc
     */
    public function getPrice()
    {
        return $this->getData(self::PRICE);
    }

    /**
     * @inheritDoc
     */
    public function setPrice($price)
    {
        return $this->setData(self::PRICE, $price);
    }

    /**
     * @inheritDoc
     */
    public function getQty()
    {
        return $this->getData(self::QTY);
    }

    /**
     * @inheritDoc
     */
    public function setQty($qty)
    {
        return $this->setData(self::QTY, $qty);
    }
}

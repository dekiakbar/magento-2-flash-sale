<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Model;

use Deki\FlashSale\Api\Data\EventProductPriceInterface;
use Magento\Framework\Model\AbstractModel;

class EventProductPrice extends AbstractModel implements EventProductPriceInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Deki\FlashSale\Model\ResourceModel\EventProductPrice::class);
    }

    /**
     * @inheritDoc
     */
    public function getEventProductPriceId()
    {
        return $this->getData(self::EVENT_PRODUCT_PRICE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setEventProductPriceId($eventProductPriceId)
    {
        return $this->setData(self::EVENT_PRODUCT_PRICE_ID, $eventProductPriceId);
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
    public function getDiscountAmount()
    {
        return $this->getData(self::DISCOUNT_AMOUNT);
    }

    /**
     * @inheritDoc
     */
    public function setDiscountAmount($discountAmount)
    {
        return $this->setData(self::DISCOUNT_AMOUNT, $discountAmount);
    }

    /**
     * @inheritDoc
     */
    public function getStartDate()
    {
        return $this->getData(self::START_DATE);
    }

    /**
     * @inheritDoc
     */
    public function setStartDate($startDate)
    {
        return $this->setData(self::START_DATE, $startDate);
    }

    /**
     * @inheritDoc
     */
    public function getEndDate()
    {
        return $this->getData(self::END_DATE);
    }

    /**
     * @inheritDoc
     */
    public function setEndDate($endDate)
    {
        return $this->setData(self::END_DATE, $endDate);
    }
}

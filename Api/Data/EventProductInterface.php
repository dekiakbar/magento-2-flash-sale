<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Api\Data;

interface EventProductInterface
{
    const EVENT_ID = 'event_id';
    const QTY = 'qty';
    const PRICE = 'price';
    const EVENT_PRODUCT_ID = 'event_product_id';
    const PRODUCT_ID = 'product_id';

    /**
     * Get event_product_id
     * @return string|null
     */
    public function getEventProductId();

    /**
     * Get event_id
     * @return string|null
     */
    public function getEventId();

    /**
     * Set event_id
     * @param string $eventId
     * @return \Deki\FlashSale\EventProduct\Api\Data\EventProductInterface
     */
    public function setEventId($eventId);

    /**
     * Set event_product_id
     * @param string $eventProductId
     * @return \Deki\FlashSale\EventProduct\Api\Data\EventProductInterface
     */
    public function setEventProductId($eventProductId);

    /**
     * Get product_id
     * @return string|null
     */
    public function getProductId();

    /**
     * Set product_id
     * @param string $productId
     * @return \Deki\FlashSale\EventProduct\Api\Data\EventProductInterface
     */
    public function setProductId($productId);

    /**
     * Get price
     * @return string|null
     */
    public function getPrice();

    /**
     * Set price
     * @param string $price
     * @return \Deki\FlashSale\EventProduct\Api\Data\EventProductInterface
     */
    public function setPrice($price);

    /**
     * Get qty
     * @return string|null
     */
    public function getQty();

    /**
     * Set qty
     * @param string $qty
     * @return \Deki\FlashSale\EventProduct\Api\Data\EventProductInterface
     */
    public function setQty($qty);
}

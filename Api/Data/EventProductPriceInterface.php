<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Api\Data;

interface EventProductPriceInterface
{

    const PRICE = 'price';
    const EVENT_ID = 'event_id';
    const START_DATE = 'start_date';
    const DISCOUNT_AMOUNT = 'discount_amount';
    const EVENT_PRODUCT_PRICE_ID = 'event_product_price_id';
    const PRODUCT_ID = 'product_id';
    const END_DATE = 'end_date';

    /**
     * Get event_product_price_id
     * @return string|null
     */
    public function getEventProductPriceId();

    /**
     * Set event_product_price_id
     * @param string $eventProductPriceId
     * @return \Deki\FlashSale\EventProductPrice\Api\Data\EventProductPriceInterface
     */
    public function setEventProductPriceId($eventProductPriceId);

    /**
     * Get event_id
     * @return string|null
     */
    public function getEventId();

    /**
     * Set event_id
     * @param string $eventId
     * @return \Deki\FlashSale\EventProductPrice\Api\Data\EventProductPriceInterface
     */
    public function setEventId($eventId);

    /**
     * Get product_id
     * @return string|null
     */
    public function getProductId();

    /**
     * Set product_id
     * @param string $productId
     * @return \Deki\FlashSale\EventProductPrice\Api\Data\EventProductPriceInterface
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
     * @return \Deki\FlashSale\EventProductPrice\Api\Data\EventProductPriceInterface
     */
    public function setPrice($price);

    /**
     * Get discount_amount
     * @return string|null
     */
    public function getDiscountAmount();

    /**
     * Set discount_amount
     * @param string $discountAmount
     * @return \Deki\FlashSale\EventProductPrice\Api\Data\EventProductPriceInterface
     */
    public function setDiscountAmount($discountAmount);

    /**
     * Get start_date
     * @return string|null
     */
    public function getStartDate();

    /**
     * Set start_date
     * @param string $startDate
     * @return \Deki\FlashSale\EventProductPrice\Api\Data\EventProductPriceInterface
     */
    public function setStartDate($startDate);

    /**
     * Get end_date
     * @return string|null
     */
    public function getEndDate();

    /**
     * Set end_date
     * @param string $endDate
     * @return \Deki\FlashSale\EventProductPrice\Api\Data\EventProductPriceInterface
     */
    public function setEndDate($endDate);
}

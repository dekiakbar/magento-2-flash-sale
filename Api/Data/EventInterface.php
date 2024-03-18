<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Api\Data;

interface EventInterface
{

    const DATE_TIME_FROM = 'date_time_from';
    const EVENT_ID = 'event_id';
    const NAME = 'name';
    const DATE_TIME_TO = 'date_time_to';
    const IS_ENABLED = 'is_enabled';
    const SORT_ORDER = 'sort_order';

    /**
     * Get event_id
     * @return string|null
     */
    public function getEventId();

    /**
     * Set event_id
     * @param string $eventId
     * @return \Deki\FlashSale\Event\Api\Data\EventInterface
     */
    public function setEventId($eventId);

    /**
     * Get is_enabled
     * @return string|null
     */
    public function getIsEnabled();

    /**
     * Set is_enabled
     * @param string $isEnabled
     * @return \Deki\FlashSale\Event\Api\Data\EventInterface
     */
    public function setIsEnabled($isEnabled);

    /**
     * Get name
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     * @param string $name
     * @return \Deki\FlashSale\Event\Api\Data\EventInterface
     */
    public function setName($name);

    /**
     * Get date_time_from
     * @return string|null
     */
    public function getDateTimeFrom();

    /**
     * Set date_time_from
     * @param string $dateTimeFrom
     * @return \Deki\FlashSale\Event\Api\Data\EventInterface
     */
    public function setDateTimeFrom($dateTimeFrom);

    /**
     * Get date_time_to
     * @return string|null
     */
    public function getDateTimeTo();

    /**
     * Set date_time_to
     * @param string $dateTimeTo
     * @return \Deki\FlashSale\Event\Api\Data\EventInterface
     */
    public function setDateTimeTo($dateTimeTo);

    /**
     * Get sort_order
     * @return string|null
     */
    public function getSortOrder();

    /**
     * Set sort_order
     * @param string $sortOrder
     * @return \Deki\FlashSale\Event\Api\Data\EventInterface
     */
    public function setSortOrder($sortOrder);
}

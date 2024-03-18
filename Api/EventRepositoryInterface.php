<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface EventRepositoryInterface
{

    /**
     * Save event
     * @param \Deki\FlashSale\Api\Data\EventInterface $event
     * @return \Deki\FlashSale\Api\Data\EventInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Deki\FlashSale\Api\Data\EventInterface $event
    );

    /**
     * Retrieve event
     * @param string $eventId
     * @return \Deki\FlashSale\Api\Data\EventInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($eventId);

    /**
     * Retrieve event matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Deki\FlashSale\Api\Data\EventSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete event
     * @param \Deki\FlashSale\Api\Data\EventInterface $event
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Deki\FlashSale\Api\Data\EventInterface $event
    );

    /**
     * Delete event by ID
     * @param string $eventId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($eventId);
}

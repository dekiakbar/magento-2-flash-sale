<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface EventProductRepositoryInterface
{

    /**
     * Save event_product
     * @param \Deki\FlashSale\Api\Data\EventProductInterface $eventProduct
     * @return \Deki\FlashSale\Api\Data\EventProductInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Deki\FlashSale\Api\Data\EventProductInterface $eventProduct
    );

    /**
     * Retrieve event_product
     * @param string $eventProductId
     * @return \Deki\FlashSale\Api\Data\EventProductInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($eventProductId);

    /**
     * Retrieve event_product matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Deki\FlashSale\Api\Data\EventProductSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete event_product
     * @param \Deki\FlashSale\Api\Data\EventProductInterface $eventProduct
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Deki\FlashSale\Api\Data\EventProductInterface $eventProduct
    );

    /**
     * Delete event_product by ID
     * @param string $eventProductId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($eventProductId);
}

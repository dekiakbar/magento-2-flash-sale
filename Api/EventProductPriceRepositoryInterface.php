<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface EventProductPriceRepositoryInterface
{

    /**
     * Save event_product_price
     * @param \Deki\FlashSale\Api\Data\EventProductPriceInterface $eventProductPrice
     * @return \Deki\FlashSale\Api\Data\EventProductPriceInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Deki\FlashSale\Api\Data\EventProductPriceInterface $eventProductPrice
    );

    /**
     * Retrieve event_product_price
     * @param string $eventProductPriceId
     * @return \Deki\FlashSale\Api\Data\EventProductPriceInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($eventProductPriceId);

    /**
     * Retrieve event_product_price matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Deki\FlashSale\Api\Data\EventProductPriceSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete event_product_price
     * @param \Deki\FlashSale\Api\Data\EventProductPriceInterface $eventProductPrice
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Deki\FlashSale\Api\Data\EventProductPriceInterface $eventProductPrice
    );

    /**
     * Delete event_product_price by ID
     * @param string $eventProductPriceId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($eventProductPriceId);
}

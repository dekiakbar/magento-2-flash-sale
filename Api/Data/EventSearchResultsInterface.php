<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Api\Data;

interface EventSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get event list.
     * @return \Deki\FlashSale\Api\Data\EventInterface[]
     */
    public function getItems();

    /**
     * Set is_enabled list.
     * @param \Deki\FlashSale\Api\Data\EventInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

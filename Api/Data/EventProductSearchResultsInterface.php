<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Api\Data;

interface EventProductSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get event_product list.
     * @return \Deki\FlashSale\Api\Data\EventProductInterface[]
     */
    public function getItems();

    /**
     * Set product_id list.
     * @param \Deki\FlashSale\Api\Data\EventProductInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

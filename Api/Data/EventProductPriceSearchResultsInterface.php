<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Api\Data;

interface EventProductPriceSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get event_product_price list.
     * @return \Deki\FlashSale\Api\Data\EventProductPriceInterface[]
     */
    public function getItems();

    /**
     * Set event_id list.
     * @param \Deki\FlashSale\Api\Data\EventProductPriceInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

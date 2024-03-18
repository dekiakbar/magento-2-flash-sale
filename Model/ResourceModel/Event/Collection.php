<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Model\ResourceModel\Event;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'event_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Deki\FlashSale\Model\Event::class,
            \Deki\FlashSale\Model\ResourceModel\Event::class
        );
    }
}

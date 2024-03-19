<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
class EventProductPrice extends AbstractDb
{
    /**
     * @var TimezoneInterface
     */
    protected $timeZone;

    /**
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param TimezoneInterface $timeZone
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        TimezoneInterface $timeZone
    ){
        $this->timeZone = $timeZone;
        parent::__construct(
            $context
        );
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('deki_flashsale_event_product_price', 'event_product_price_id');
    }

    /**
     * Get flash sale product price for specific date
     *
     * @param \DateTimeInterface $date
     * @param int $productId
     * @return array|false
     */
    public function getFlashSalePriceInfo($date, $productId)
    {
        $data = $this->getFlashSalePrices($date, [$productId]);
        if (isset($data[$productId])) {
            return $data[$productId];
        }

        return false;
    }

    /**
     * Retrieve product prices by flash sale for specific date
     * Collect data with  product Id => price pairs
     *
     * @param \DateTimeInterface $date
     * @param array $productIds
     * @return array
     */
    public function getFlashSalePrices(\DateTimeInterface $date, $productIds)
    {
        $connection = $this->getConnection();

        /**
         * Timezone is converted to UTC, magento save flash sale date
         * in UTC
         */
        if($date->getTimezone() != $this->timeZone->getDefaultTimezone()){
            $date = $this->timeZone->convertConfigTimeToUtc(
                $date,
                "Y-m-d H:i:s"
            );
        }

        $select = $connection->select()
            ->from($this->getTable($this->getMainTable()), ['product_id', 'price', 'event_id', 'discount_amount'])
            ->where('`start_date` <= ?', $date)
            ->where('`end_date` >= ?', $date)
            ->where('deki_flashsale_event_product_price.product_id IN(?)', $productIds, \Zend_Db::INT_TYPE);

        /**
         * Exclude from flash sale if qty is 0 (real time).
         * if qty 0, exclude product from flash sale
         */
        $select->join(
            ['ev' => $this->getTable('deki_flashsale_event_product')],
            'ev.product_id = deki_flashsale_event_product_price.product_id'
            .' AND ev.event_id = deki_flashsale_event_product_price.event_id',
            [
                'qty' => 'ev.qty',
                'flash_sale_event_product_id' => 'ev.event_product_id',
            ]
        );

        $select->where('qty > 0');

        return $connection->fetchAssoc($select);
    }
}

<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Event extends AbstractDb
{
    /**
     * Event products table name
     *
     * @var string
     */
    protected $_eventProductTable;

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('deki_flashsale_event', 'event_id');
    }

    /**
     * Process product data after save event
     *
     * Save related products ids and update path value
     *
     * @param \Magento\Framework\DataObject $object
     * @return $this
     */
    protected function _afterSave(\Magento\Framework\DataObject $object)
    {
        $this->_saveEventProducts($object);
        return parent::_afterSave($object);
    }

    /**
     * Save event products relation
     *
     * @param \Deki\FlashSale\Model\Event $event
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function _saveEventProducts($event)
    {
        $event->setIsChangedProductList(false);
        $id = $event->getId();
        /**
         * new event-product relationships
         */
        $products = $event->getPostedProducts();
        
        /**
         * Example re-save event
         */
        if ($products === null) {
            return $this;
        }
        
        /**
         * old event-product relationships
         */
        $oldProducts = $event->getEventProductsArray();
        
        $insert = array_diff_key($products, $oldProducts);
        $delete = array_diff_key($oldProducts, $products);
        
        /**
         * Find product ids which are presented in both arrays
         * and saved before (check $oldProducts array)
         */
        $update = array_intersect_key($products, $oldProducts);
        $update = array_map(
            'unserialize',
            array_diff(
                array_map('serialize', $update),
                array_map('serialize', $oldProducts)
            )
        );

        $connection = $this->getConnection();

        /**
         * Delete products from event
         */
        if (!empty($delete)) {
            $cond = ['product_id IN(?)' => array_keys($delete), 'event_id=?' => $id];
            $connection->delete($this->getEventProductTable(), $cond);
        }

        /**
         * Add products to event
         */
        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $productId => $dataColumn) {
                $data[] = [
                    'event_id' => (int)$id,
                    'product_id' => (int)$productId,
                    'price' => (int)$dataColumn[0],
                    'qty' => (int)$dataColumn[1],
                ];
            }
            $connection->insertMultiple($this->getEventProductTable(), $data);
        }

        /**
         * Update product in event
         */
        if (!empty($update)) {
            foreach ($update as $productId => $updateData) {
                $bind = [
                    'price' => new \Zend_Db_Expr("$updateData[0]"),
                    'qty' => new \Zend_Db_Expr("$updateData[1]")
                ];
                $where = ['event_id = ?' => (int)$id, 'product_id = ?' => $productId];
                $connection->update($this->getEventProductTable(), $bind, $where);
            }
        }

        return $this;
    }

    /**
     * Event product table name getter
     *
     * @return string
     */
    public function getEventProductTable()
    {
        if (!$this->_eventProductTable) {
            $this->_eventProductTable = $this->getTable('deki_flashsale_event_product');
        }
        return $this->_eventProductTable;
    }
}

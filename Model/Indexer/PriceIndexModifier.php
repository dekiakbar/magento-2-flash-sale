<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Model\Indexer;

use Magento\Catalog\Model\ResourceModel\Product\Indexer\Price\PriceModifierInterface;
use Magento\Catalog\Model\ResourceModel\Product\Indexer\Price\IndexTableStructure;
use Deki\FlashSale\Model\ResourceModel\EventProductPrice;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\ObjectManager;

/**
 * Class for adding flash sale prices to price index table.
 * copy flash sale price from deki_flashsale_event_product_price to
 * catalog_product_index_price
 */
class PriceIndexModifier implements PriceModifierInterface
{
    /**
     * @var EventProductPrice
     */
    private $eventProductPrice;

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var string
     */
    private $connectionName;

    /**
     * @param EventProductPrice $eventProductPrice
     * @param ResourceConnection $resourceConnection
     * @param string $connectionName
     */
    public function __construct(
        EventProductPrice $eventProductPrice,
        ResourceConnection $resourceConnection,
        $connectionName = 'indexer'
    ) {
        $this->eventProductPrice = $eventProductPrice;
        $this->resourceConnection = $resourceConnection ?: ObjectManager::getInstance()->get(ResourceConnection::class);
        $this->connectionName = $connectionName;
    }

    /**
     * @inheritdoc
     */
    public function modifyPrice(IndexTableStructure $priceTable, array $entityIds = []) : void
    {
        $connection = $this->resourceConnection->getConnection($this->connectionName);

        $select = $connection->select();

        $select->join(
            ['fsepp' => $this->eventProductPrice->getMainTable()],
            'fsepp.product_id = i.' . $priceTable->getEntityField(),
            []
        );
        if ($entityIds) {
            $select->where('i.entity_id IN (?)', $entityIds, \Zend_Db::INT_TYPE);
        }

        $finalPrice = $priceTable->getFinalPriceField();
        $finalPriceExpr = $select->getConnection()->getLeastSql([
            $priceTable->getFinalPriceField(),
            $select->getConnection()->getIfNullSql('fsepp.price', 'i.' . $finalPrice),
        ]);
        $minPrice = $priceTable->getMinPriceField();
        $minPriceExpr = $select->getConnection()->getLeastSql([
            $priceTable->getMinPriceField(),
            $select->getConnection()->getIfNullSql('fsepp.price', 'i.' . $minPrice),
        ]);
        $select->columns([
            $finalPrice => $finalPriceExpr,
            $minPrice => $minPriceExpr,
        ]);

        $query = $connection->updateFromSelect($select, ['i' => $priceTable->getTableName()]);
        $connection->query($query);
    }
}

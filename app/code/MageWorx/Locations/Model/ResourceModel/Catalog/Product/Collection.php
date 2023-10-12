<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Model\ResourceModel\Catalog\Product;

class Collection extends \Magento\Catalog\Model\ResourceModel\Product\Collection
{
    /**
     * @param string[] $skus
     * @return string[]
     */
    public function getProductIdsBySkus($skus)
    {
        $connection = $this->getConnection();
        $select     = $connection->select()
                                 ->from($this->getMainTable(), ['entity_id', 'sku'])
                                 ->where('sku IN (?)', $skus);

        $data   = $connection->fetchAll($select);
        $result = [];
        foreach ($data as $row) {
            $result[$row['sku']] = $row['entity_id'];
        }

        return $result;
    }

    /**
     * @param string $sourceCode
     * @return string[]
     */
    public function getItemsQty($sourceCode)
    {
        $connection = $this->getConnection();
        $select     = $connection->select()
                                 ->from($this->getTable('inventory_source_item'), ['quantity', 'sku'])
                                 ->where('source_code = ?', $sourceCode);

        $data   = $connection->fetchAll($select);
        $result = [];
        foreach ($data as $row) {
            $result[$row['sku']] = $row['quantity'];
        }

        return $result;
    }

    /**
     * @param string $sourceCode
     * @param bool $addOutOfStock
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductIdsBySourceCode($sourceCode, $addOutOfStock = true)
    {
        $connection = $this->getConnection();
        $select     = $connection->select()
                                 ->from(['e' => $this->getTable('catalog_product_entity')], ['entity_id', 'sku']);
        $select->joinLeft(
            ['inventory' => $this->getTable('inventory_source_item')],
            'e.' . 'sku' . ' = inventory.sku',
            []
        )->where(
            'inventory.source_code = ?',
            $sourceCode
        )->group('e.sku');

        if (!$addOutOfStock) {
            $select->where('inventory.status = 1 AND inventory.quantity > 0');
        }

        $data   = $connection->fetchAll($select);
        $result = [];
        foreach ($data as $row) {
            $result[$row['sku']] = $row['entity_id'];
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getAllSkus()
    {
        $connection = $this->getConnection();
        $select     = $connection->select()->from($this->getMainTable(), ['sku']);

        $data   = $connection->fetchAll($select);
        $result = [];
        foreach ($data as $row) {
            $result[] = $row['sku'];
        }

        return $result;
    }
}

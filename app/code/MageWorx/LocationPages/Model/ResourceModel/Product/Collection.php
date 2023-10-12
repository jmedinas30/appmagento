<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Model\ResourceModel\Product;

/**
 * {@inheritdoc}
 */
class Collection extends \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection
{

    /**
     * @param string[] $selected
     * @return $this
     */
    public function addSkuFilter($selected)
    {
        $this->addFieldToFilter('sku', $selected);

        return $this;
    }
}

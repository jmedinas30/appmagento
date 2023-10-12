<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Model\Layer\Filter;

/**
 * Layer category filter
 */
class Category extends \Magento\Catalog\Model\Layer\Filter\Category
{
    /**
     * Get data array for building category filter items
     *
     * @return string[]
     */
    protected function _getItemsData()
    {
        return $this->itemDataBuilder->build();
    }
}

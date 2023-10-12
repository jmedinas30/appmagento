<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Model\Source;

use MageWorx\Locations\Model\Source;
use Magento\Catalog\Model\Category\Attribute\Source\Sortby as CategorySortby;

/**
 * Used in creating options for config value selection
 *
 */
class Sortby extends Source
{
    /**
     * @var CategorySortby
     */
    protected $categorySortby;

    public function __construct(CategorySortby $categorySortby)
    {
        $this->categorySortby = $categorySortby;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return $this->categorySortby->getAllOptions();
    }
}

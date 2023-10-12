<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Model\Source;

use MageWorx\Locations\Model\Source;
use Magento\Widget\Block\Adminhtml\Widget\Instance\Edit\Chooser\Container;

/**
 * Used in creating options for config value selection
 *
 */
class Layout extends Source
{
    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => '',
                'label' => __('-- Please Select --')
            ],
            [
                'value' => 'empty',
                'label' => __('Empty')
            ],
            [
                'value' => '1column',
                'label' => __('1 column')
            ],
            [
                'value' => Container::PAGE_LAYOUT_2COLUMNS_LEFT,
                'label' => __('2 columns with left bar')
            ],
            [
                'value' => Container::PAGE_LAYOUT_2COLUMNS_RIGHT,
                'label' => __('2 columns with right bar')
            ],
            [
                'value' => Container::PAGE_LAYOUT_3COLUMNS,
                'label' => __('3 columns')
            ],
        ];
    }
}

<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreLocator\Model\Source;

use MageWorx\Locations\Model\Source;

/**
 * Used in creating options for config value selection
 *
 */
class StockStatusDisplayMode extends Source
{
    const DISABLE       = 0;
    const STOCK_STATUS  = 1;
    const AVAILABLE_QTY = 2;

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::DISABLE,
                'label' => __('Disable')
            ],
            [
                'value' => self::STOCK_STATUS,
                'label' => __('Stock Status')
            ],
            [
                'value' => self::AVAILABLE_QTY,
                'label' => __('Available Quantity')
            ]
        ];
    }
}

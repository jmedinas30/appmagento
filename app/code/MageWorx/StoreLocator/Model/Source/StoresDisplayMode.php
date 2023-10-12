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
class StoresDisplayMode extends Source
{
    const SIMPLE   = 'simple';
    const DETAILED = 'detailed';

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::SIMPLE,
                'label' => __('Simple')
            ],
            [
                'value' => self::DETAILED,
                'label' => __('Detailed')
            ]
        ];
    }
}

<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Model\Source;

use MageWorx\Locations\Model\Source;

/**
 * Used in creating options for config value selection
 *
 */
class DefaultMapView extends Source
{
    const CUSTOMER_LOCATION    = '1';
    const DEFAULT_LOCATION = '2';

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::CUSTOMER_LOCATION,
                'label' => __('Customer location')
            ],
            [
                'value' => self::DEFAULT_LOCATION,
                'label' => __('Default location')
            ]
        ];
    }
}

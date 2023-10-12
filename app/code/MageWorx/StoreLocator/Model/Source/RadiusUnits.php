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
class RadiusUnits extends Source
{
    const KM    = 'km';
    const MILES = 'miles';

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::MILES,
                'label' => __('Miles')
            ],
            [
                'value' => self::KM,
                'label' => __('Kilometers')
            ]
        ];
    }
}

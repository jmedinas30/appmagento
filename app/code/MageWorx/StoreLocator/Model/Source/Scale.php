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
class Scale extends Source
{
    const WORLD   = 'world';
    const COUNTRY = 'country';
    const REGION  = 'region';
    const CITY    = 'city';
    const STORE   = 'store';

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::COUNTRY,
                'label' => __('Country')
            ],
            [
                'value' => self::REGION,
                'label' => __('Region')
            ],
            [
                'value' => self::CITY,
                'label' => __('City')
            ],
        ];
    }
}

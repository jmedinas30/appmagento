<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Model\Source;

/**
 * Used in creating options for config value selection
 *
 */
class UrlPath extends \MageWorx\StoreLocator\Model\Source\Scale
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

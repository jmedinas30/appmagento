<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Model\Source;

use MageWorx\Locations\Model\Source;
use MageWorx\Locations\Api\Data\LocationInterface;

/**
 * Used in creating options for config value selection
 *
 */
class UrlKey extends Source
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
                'value' => LocationInterface::ADDRESS,
                'label' => __('Store Address')
            ],
            [
                'value' => LocationInterface::NAME,
                'label' => __('Store Name')
            ],
            [
                'value' => LocationInterface::CODE,
                'label' => __('Store Code')
            ]
        ];
    }
}

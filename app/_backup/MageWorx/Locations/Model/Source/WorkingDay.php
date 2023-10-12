<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Model\Source;

use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\Locations\Model\Source;

/**
 * Used in creating options for config value selection
 *
 */
class WorkingDay extends Source
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
                'value' => 'monday',
                'label' => __('Monday')
            ],
            [
                'value' => 'tuesday',
                'label' => __('Tuesday')
            ],
            [
                'value' => 'wednesday',
                'label' => __('Wednesday')
            ],
            [
                'value' => 'thursday',
                'label' => __('Thursday')
            ],
            [
                'value' => 'friday',
                'label' => __('Friday')
            ],
            [
                'value' => 'saturday',
                'label' => __('Saturday')
            ],
            [
                'value' => 'sunday',
                'label' => __('Sunday')
            ],
        ];
    }
}

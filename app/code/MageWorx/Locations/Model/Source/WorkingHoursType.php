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
class WorkingHoursType extends Source
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
                'value' => LocationInterface::WORKING_EVERYDAY,
                'label' => __('Everyday')
            ],
            [
                'value' => LocationInterface::WORKING_PER_DAY_OF_WEEK,
                'label' => __('Per Day Of Week')
            ],
            [
                'value' => LocationInterface::WORKING_24_HOURS_A_DAY,
                'label' => __('Open 24 hours a day')
            ]
        ];
    }
}

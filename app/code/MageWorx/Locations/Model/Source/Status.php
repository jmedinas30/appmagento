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
class Status extends Source
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
                'value' => LocationInterface::ACTIVE,
                'label' => __('Enable')
            ],
            [
                'value' => LocationInterface::INACTIVE,
                'label' => __('Disable')
            ],
        ];
    }
}

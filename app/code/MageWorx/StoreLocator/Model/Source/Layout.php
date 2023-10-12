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
class Layout extends Source
{
    const FILTER_ON_MAP    = 'filter_on_map';
    const FILTER_LEFT_MAP  = 'filter_left_map';
    const LIST_BEFORE_MAP  = 'list_before_map';
    const LIST_AFTER_MAP   = 'list_after_map';
    const LIST_WITHOUT_MAP = 'list_without_map';

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::LIST_WITHOUT_MAP,
                'label' => __('Store List Without Map')
            ],
            [
                'value' => self::LIST_BEFORE_MAP,
                'label' => __('Store List Before Map')
            ],
            [
                'value' => self::LIST_AFTER_MAP,
                'label' => __('Store List After Map')
            ],
            [
                'value' => self::FILTER_ON_MAP,
                'label' => __('Store Filter On Map')
            ],
            [
                'value' => self::FILTER_LEFT_MAP,
                'label' => __('Store Filter Left Side Of Map')
            ],
        ];
    }
}

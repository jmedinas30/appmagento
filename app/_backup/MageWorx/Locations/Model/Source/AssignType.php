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
class AssignType extends Source
{
    /**
     * @var \MageWorx\Locations\Model\MsiResolver
     */
    protected $msiResolver;

    /**
     * AssignType constructor.
     *
     * @param \MageWorx\Locations\Model\MsiResolver $msiResolver
     */
    public function __construct(
        \MageWorx\Locations\Model\MsiResolver $msiResolver
    ) {
        $this->msiResolver = $msiResolver;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $array = [
            [
                'value' => LocationInterface::ASSIGN_TYPE_ALL,
                'label' => __('All Products')
            ],
            [
                'value' => LocationInterface::ASSIGN_TYPE_CONDITION,
                'label' => __('By Condition')
            ],
            [
                'value' => LocationInterface::ASSIGN_TYPE_SPECIFIC_PRODUCTS,
                'label' => __('Specific Products')
            ]
        ];

        if ($this->msiResolver->isMsiEnabled()) {
            $array[] = [
                'value' => LocationInterface::ASSIGN_TYPE_PRODUCTS_FROM_SOURCE,
                'label' => __('Products From Source')
            ];
        }

        return $array;
    }
}

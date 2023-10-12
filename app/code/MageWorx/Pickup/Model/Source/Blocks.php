<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Pickup\Model\Source;

use MageWorx\Locations\Model\Source;
use Magento\Cms\Model\BlockFactory;

/**
 * Used in creating options for config value selection
 *
 */
class Blocks extends Source
{
    /**
     * @var BlockFactory
     */
    protected $blockFactory;

    /**
     * Attribute constructor.
     *
     * @param BlockFactory $blockFactory
     */
    public function __construct(
        BlockFactory $blockFactory
    ) {
        $this->blockFactory = $blockFactory;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $blocks[] = [
            'value' => 0,
            'label' => __('Without CMS Block')
        ];

        $blockCollection = $this->blockFactory->create()->getCollection();
        foreach ($blockCollection as $block) {
            /* @var $block \Magento\Cms\Model\Block */

            $blocks[] = [
                'value' => $block->getIdentifier(),
                'label' => $block->getTitle()
            ];
        }

        return $blocks;
    }
}

<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Model\Source;

use MageWorx\Locations\Model\Source;

/**
 * Used in creating options for config value selection
 *
 */
class Sources extends Source
{
    const CREATE_NEW_SOURCE = 'create_new_source';

    /**
     * @var \Magento\InventoryApi\Api\SourceRepositoryInterface
     */
    protected $sourceRepository;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * Sources constructor.
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->objectManager    = $objectManager;
        $this->sourceRepository = $this->objectManager->create(
            '\Magento\InventoryApi\Api\SourceRepositoryInterface'
        );
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $result  = [];
        $sources = $this->sourceRepository->getList();
        /** @var  \Magento\InventoryApi\Api\Data\SourceInterface $source */
        foreach ($sources->getItems() as $source) {
            $result[] = [
                'value' => $source->getSourceCode(),
                'label' => $source->getName()
            ];
        }

        $result[] = [
            'value' => self::CREATE_NEW_SOURCE,
            'label' => __('Create New Source')
        ];

        return $result;
    }
}

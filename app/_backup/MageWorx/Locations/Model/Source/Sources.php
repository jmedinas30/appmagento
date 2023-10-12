<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Model\Source;

use MageWorx\Locations\Model\Source;
use Magento\InventoryApi\Api\SourceRepositoryInterface;

/**
 * Used in creating options for config value selection
 *
 */
class Sources extends Source
{
    const CREATE_NEW_SOURCE = 'create_new_source';

    /**
     * @var SourceRepositoryInterface
     */
    protected $sourceRepository;

    /**
     * Sources constructor.
     *
     * @param SourceRepositoryInterface $sourceRepository
     */
    public function __construct(SourceRepositoryInterface $sourceRepository)
    {
        $this->sourceRepository = $sourceRepository;
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

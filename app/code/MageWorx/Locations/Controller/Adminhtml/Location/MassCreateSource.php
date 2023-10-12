<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Controller\Adminhtml\Location;

use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\Locations\Api\LocationRepositoryInterface;
use MageWorx\Locations\Api\LocationSourceManagementInterface;
use MageWorx\Locations\Model\Registry;

class MassCreateSource extends MassAction
{

    /**
     * @var LocationSourceManagementInterface
     */
    protected $locationSourceManagement;

    /**
     * MassCreateSource constructor.
     *
     * @param LocationSourceManagementInterface $locationSourceManagement
     * @param Registry $registry
     * @param Context $context
     * @param Filter $filter
     * @param LocationRepositoryInterface $locationRepository
     */
    public function __construct(
        LocationSourceManagementInterface $locationSourceManagement,
        Registry $registry,
        Context $context,
        Filter $filter,
        LocationRepositoryInterface $locationRepository
    ) {
        $this->locationSourceManagement = $locationSourceManagement;
        parent::__construct($registry, $context, $filter, $locationRepository);
    }

    /**
     * @param LocationInterface $location
     * @return $this
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Validation\ValidationException
     */
    protected function executeAction(LocationInterface $location): MassAction
    {
        $this->locationSourceManagement->createLocationSource($location);

        return $this;
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    protected function getErrorMessage(): \Magento\Framework\Phrase
    {
        return __('An error occurred while creating sources.');
    }

    /**
     * @param int $collectionSize
     * @return \Magento\Framework\Phrase
     */
    protected function getSuccessMessage(int $collectionSize): \Magento\Framework\Phrase
    {
        return __('A total of %1 sources have been created.', $collectionSize);
    }
}

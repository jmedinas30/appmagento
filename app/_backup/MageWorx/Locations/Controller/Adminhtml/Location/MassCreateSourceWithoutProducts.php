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

class MassCreateSourceWithoutProducts extends MassCreateSource
{
    /**
     * @param LocationInterface $location
     * @return $this
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Validation\ValidationException
     */
    protected function executeAction(LocationInterface $location): MassAction
    {
        $this->locationSourceManagement->createLocationSource($location, false);

        return $this;
    }
}

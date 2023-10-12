<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Model\MsiResolver;

/**
 * Used LocationSourceManagement class only if MSI enabled
 *
 */
class LocationSourceManagement extends \MageWorx\Locations\Model\MsiResolver
{
    /**
     * @return bool|\MageWorx\Locations\Api\LocationSourceManagementInterface
     */
    public function getInstance()
    {
        return $this->getInstanceByClass(\MageWorx\Locations\Api\LocationSourceManagementInterface::class);
    }
}

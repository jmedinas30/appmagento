<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Model\MsiResolver;

/**
 * Used GetSourceItemsBySku class only if MSI enabled
 *
 */
class GetSourceItemsBySku extends \MageWorx\Locations\Model\MsiResolver
{
    /**
     * @return bool|\MageWorx\Locations\Api\LocationSourceManagementInterface
     */
    public function getInstance()
    {
        return $this->getInstanceByClass(\Magento\Inventory\Model\SourceItem\Command\GetSourceItemsBySku::class);
    }
}

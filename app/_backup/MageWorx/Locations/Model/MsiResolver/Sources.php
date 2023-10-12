<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Model\MsiResolver;

/**
 * Used Sources class only if MSI enabled
 *
 */
class Sources extends \MageWorx\Locations\Model\MsiResolver
{
    /**
     * @return bool|\MageWorx\Locations\Model\Source\Sources
     */
    public function getInstance()
    {
        return $this->getInstanceByClass(\MageWorx\Locations\Model\Source\Sources::class);
    }
}

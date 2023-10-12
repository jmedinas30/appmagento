<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Controller\Adminhtml\Location;

use MageWorx\Locations\Api\Data\LocationInterface;

class MassEnable extends MassDisable
{
    /**
     * @return int
     */
    protected function getActionValue(): int
    {
        return LocationInterface::ACTIVE;
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    protected function getErrorMessage(): \Magento\Framework\Phrase
    {
        return __('An error occurred while enabling stores.');
    }

    /**
     * @param int $collectionSize
     * @return \Magento\Framework\Phrase
     */
    protected function getSuccessMessage(int $collectionSize): \Magento\Framework\Phrase
    {
        return __('A total of %1 stores have been enabled.', $collectionSize);
    }
}

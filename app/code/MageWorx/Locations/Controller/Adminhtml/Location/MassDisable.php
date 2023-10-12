<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Controller\Adminhtml\Location;

use MageWorx\Locations\Api\Data\LocationInterface;

class MassDisable extends MassAction
{
    /**
     * @param LocationInterface $location
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function executeAction(LocationInterface $location): MassAction
    {
        $location->setIsActive($this->getActionValue());
        $this->locationRepository->save($location);

        return $this;
    }

    /**
     * @return int
     */
    protected function getActionValue(): int
    {
        return LocationInterface::INACTIVE;
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    protected function getErrorMessage(): \Magento\Framework\Phrase
    {
        return __('An error occurred while disabling stores.');
    }

    /**
     * @param int $collectionSize
     * @return \Magento\Framework\Phrase
     */
    protected function getSuccessMessage(int $collectionSize): \Magento\Framework\Phrase
    {
        return __('A total of %1 stores have been disabled.', $collectionSize);
    }
}

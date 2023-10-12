<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Controller\Adminhtml\Location;

use MageWorx\Locations\Api\Data\LocationInterface;

class MassDelete extends MassAction
{
    /**
     * @param LocationInterface $location
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function executeAction(LocationInterface $location): MassAction
    {
        $this->locationRepository->delete($location);

        return $this;
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    protected function getErrorMessage(): \Magento\Framework\Phrase
    {
        return __('An error occurred while deleting record(s).');
    }

    /**
     * @param int $collectionSize
     * @return \Magento\Framework\Phrase
     */
    protected function getSuccessMessage(int $collectionSize): \Magento\Framework\Phrase
    {
        return __('A total of %1 record(s) have been deleted.', $collectionSize);
    }
}

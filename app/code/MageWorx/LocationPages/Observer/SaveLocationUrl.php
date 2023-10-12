<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Observer;

use MageWorx\LocationPages\Api\LocationListRepositoryInterface;

/**
 * Observer class
 */
class SaveLocationUrl implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var LocationListRepositoryInterface
     */
    protected $locationListRepository;

    /**
     * SaveLocationUrl constructor.
     *
     * @param LocationListRepositoryInterface $locationListRepository
     */
    public function __construct(
        LocationListRepositoryInterface $locationListRepository
    ) {
        $this->locationListRepository = $locationListRepository;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $location = $observer->getData('location');

        if ($location) {
            $this->locationListRepository->createLocationPage($location);
        }
    }
}

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
class SaveLocationListUrl implements \Magento\Framework\Event\ObserverInterface
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
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $location = $observer->getData('location');

        if ($location) {
            $path = $this->locationListRepository->createLocationListPages($location);
            $location->setLocationPagePath($path);
            $observer->setData('location', $location);
        }
    }
}

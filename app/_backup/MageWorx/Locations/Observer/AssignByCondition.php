<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Observer;

use MageWorx\Locations\Api\LocationRepositoryInterface;
use MageWorx\Locations\Model\ResourceModel\Location;

/**
 * Observer class
 */
class AssignByCondition implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * @var Location
     */
    protected $locationResource;

    /**
     * AssignByCondition constructor.
     *
     * @param Location $locationResource
     * @param LocationRepositoryInterface $locationRepository
     */
    public function __construct(
        Location $locationResource,
        LocationRepositoryInterface $locationRepository
    ) {
        $this->locationResource   = $locationResource;
        $this->locationRepository = $locationRepository;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $collection = $this->locationRepository->getListLocationForCron();
        foreach ($collection as $location) {
            $this->locationResource->saveProductRelation($location);
        }
    }
}

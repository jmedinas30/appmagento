<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Plugin;

use Magento\Store\Model\Store as StoreStore;
use MageWorx\LocationPages\Api\LocationListRepositoryInterface;
use MageWorx\Locations\Api\LocationRepositoryInterface;

class UpdateLocationsOnStoreSavingPlugin
{
    /**
     * @var LocationListRepositoryInterface
     */
    protected $locationListRepository;

    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * ChangeUrlsByConfigSave constructor.
     *
     * @param LocationListRepositoryInterface $locationListRepository
     * @param LocationRepositoryInterface $locationRepository
     */
    public function __construct(
        LocationListRepositoryInterface $locationListRepository,
        LocationRepositoryInterface $locationRepository
    ) {
        $this->locationListRepository = $locationListRepository;
        $this->locationRepository     = $locationRepository;
    }

    /**
     * @param StoreStore $subject
     * @param StoreStore $result
     * @return StoreStore
     */
    public function afterSave(StoreStore $subject, $result)
    {
        $this->locationListRepository->updateLocationListCollection();

        return $result;
    }
}

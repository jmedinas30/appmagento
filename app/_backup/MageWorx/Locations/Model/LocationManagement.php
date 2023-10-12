<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Model;

use MageWorx\Locations\Api\LocationManagementInterface;
use MageWorx\Locations\Api\Data\LocationInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class LocationManagement implements LocationManagementInterface
{
    /**
     * @var LocationRepository
     */
    protected $locationRepository;

    /**
     * LocationManagement constructor.
     *
     * @param LocationRepository $locationRepository
     */
    public function __construct(
        LocationRepository $locationRepository
    ) {
        $this->locationRepository = $locationRepository;
    }

    /**
     * @param string|string[] $productSkus
     * @param string $code
     * @return LocationInterface $location
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function addProductsToLocation($productSkus, $code)
    {
        $productSkus = is_array($productSkus) ? $productSkus : explode(',', $productSkus);

        /** @var LocationInterface $location */
        $location       = $this->locationRepository->getByCode($code);
        $oldProductSkus = $location->getProductSkus();
        $location->setProductSkus(array_unique(array_merge($productSkus, $oldProductSkus)));
        $this->locationRepository->save($location);

        return $location;
    }

    /**
     * @param string|string[] $productSkus
     * @param string $code
     * @return LocationInterface $location
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function deleteProductsFromLocation($productSkus, $code)
    {
        $productSkus = is_array($productSkus) ? $productSkus : explode(',', $productSkus);

        /** @var LocationInterface $location */
        $location       = $this->locationRepository->getByCode($code);
        $oldProductSkus = $location->getProductSkus();
        $location->setProductSkus(array_diff($oldProductSkus, $productSkus));

        $this->locationRepository->save($location);

        return $location;
    }

    /**
     * @param string|string[] $productSkus
     * @param string $code
     * @return LocationInterface $location
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function updateProductInLocation($productSkus, $code)
    {
        $productSkus = is_array($productSkus) ? $productSkus : explode(',', $productSkus);

        /** @var LocationInterface $location */
        $location = $this->locationRepository->getByCode($code);
        $location->setProductSkus(array_unique($productSkus));

        $this->locationRepository->save($location);

        return $location;
    }
}

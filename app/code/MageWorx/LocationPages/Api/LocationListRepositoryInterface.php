<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
declare(strict_types=1);

namespace MageWorx\LocationPages\Api;

use Magento\Framework\Exception\CouldNotDeleteException;
use MageWorx\LocationPages\Model\ResourceModel\LocationList\Collection;
use MageWorx\LocationPages\Api\Data\LocationListInterface;

interface LocationListRepositoryInterface
{
    /**
     * @param LocationListInterface $locationList
     * @return LocationListInterface
     */
    public function save(LocationListInterface $locationList): LocationListInterface;

    /**
     * Retrieve LocationList.
     *
     * @param int $id
     *
     * @return \MageWorx\LocationPages\Api\Data\LocationListInterface
     */
    public function getById($id): LocationListInterface;

    /**
     * Get empty LocationList
     *
     * @return \MageWorx\LocationPages\Api\Data\LocationListInterface
     */
    public function getEmptyEntity(): LocationListInterface;

    /**
     * Delete LocationList
     *
     * @param \MageWorx\LocationPages\Api\Data\LocationListInterface $locationList
     *
     * @return bool
     *
     * @throws CouldNotDeleteException
     */
    public function delete(\MageWorx\LocationPages\Api\Data\LocationListInterface $locationList): bool;

    /**
     * Delete Location by given LocationList Identity
     *
     * @param string $id
     *
     * @return bool
     *
     * @throws CouldNotDeleteException
     */
    public function deleteById($id): bool;

    /**
     * Get list Location
     *
     * @return \MageWorx\LocationPages\Model\ResourceModel\LocationList\Collection
     */
    public function getLocationListCollection(): Collection;

    /**
     * Get list Location
     *
     * @param int[]
     * @return \MageWorx\LocationPages\Model\ResourceModel\LocationList\Collection
     */
    public function getLocationListCollectionByIds($ids): Collection;

    /**
     * Get list Location
     *
     * @param string
     * @return \MageWorx\LocationPages\Model\ResourceModel\LocationList\Collection
     */
    public function getLocationListCollectionByType($type): Collection;

    /**
     * Get list Location
     *
     * @param string
     * @return int
     */
    public function getEmptyCoordinatesCount($type): int;

    /**
     * @param string $type
     * @param int $page
     * @return mixed
     */
    public function getLocationsWithEmptyCoordinates($type, $page = 0): Collection;


    /**
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @return mixed
     */
    public function createLocationPage($location): LocationListRepositoryInterface;

    /**
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @return mixed
     */
    public function createLocationListPages($location): string;

    /**
     * @param string $type
     * @param string $name
     * @return LocationListInterface
     */
    public function getByName(string $type, string $name): LocationListInterface;
}

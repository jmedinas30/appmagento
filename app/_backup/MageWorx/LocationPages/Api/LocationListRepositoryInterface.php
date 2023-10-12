<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Api;

use Magento\Framework\Exception\CouldNotDeleteException;

interface LocationListRepositoryInterface
{
    /**
     * @param \MageWorx\LocationPages\Api\Data\LocationListInterface $locationList
     * @return mixed
     */
    public function save(\MageWorx\LocationPages\Api\Data\LocationListInterface $locationList);

    /**
     * Retrieve LocationList.
     *
     * @param int $id
     *
     * @return \MageWorx\LocationPages\Api\Data\LocationListInterface
     */
    public function getById($id);

    /**
     * Get empty LocationList
     *
     * @return \MageWorx\LocationPages\Api\Data\LocationListInterface
     */
    public function getEmptyEntity();

    /**
     * Delete LocationList
     *
     * @param \MageWorx\LocationPages\Api\Data\LocationListInterface $locationList
     *
     * @return bool
     *
     * @throws CouldNotDeleteException
     */
    public function delete(\MageWorx\LocationPages\Api\Data\LocationListInterface $locationList);

    /**
     * Delete Location by given LocationList Identity
     *
     * @param string $id
     *
     * @return bool
     *
     * @throws CouldNotDeleteException
     */
    public function deleteById($id);

    /**
     * Get list Location
     *
     * @return \MageWorx\LocationPages\Model\ResourceModel\LocationList\Collection
     */
    public function getLocationListCollection();

    /**
     * Get list Location
     *
     * @param int[]
     * @return \MageWorx\LocationPages\Model\ResourceModel\LocationList\Collection
     */
    public function getLocationListCollectionByIds($ids);

    /**
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @return mixed
     */
    public function createLocationPage($location);

    /**
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @return mixed
     */
    public function createLocationListPages($location);
}

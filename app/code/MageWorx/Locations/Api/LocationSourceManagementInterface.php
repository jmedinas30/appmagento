<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Api;

interface LocationSourceManagementInterface
{
    /**
     * @param Data\LocationInterface $location
     * @param string $sourceCode
     * @param bool $isTransferProducts
     * @return \MageWorx\Locations\Api\Data\LocationInterface
     */
    public function assignSourceToLocation(
        \MageWorx\Locations\Api\Data\LocationInterface $location,
        string $sourceCode,
        bool $isTransferProducts = true
    ): \MageWorx\Locations\Api\Data\LocationInterface;

    /**
     * @param string $locationCode
     * @param string $sourceCode
     * @param bool $isTransferProducts
     * @return string
     */
    public function assignSourceToLocationByCode(
        string $locationCode,
        string $sourceCode,
        bool $isTransferProducts = true
    ): string;

    /**
     * @param Data\LocationInterface $location
     * @param bool $isTransferProducts
     * @return \Magento\InventoryApi\Api\Data\SourceInterface
     */
    public function createLocationSource(
        \MageWorx\Locations\Api\Data\LocationInterface $location,
        bool $isTransferProducts = true
    );

    /**
     * @param string $locationCode
     * @return string
     */
    public function createLocationSourceByCode(
        string $locationCode
    ): string;

    /**
     * @param string $sourceCode
     * @return bool
     */
    public function isSourceExist(string $sourceCode): bool;

    /**
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @return \Magento\InventoryApi\Api\Data\SourceInterface|null
     */
    public function getSourceForLocation(
        \MageWorx\Locations\Api\Data\LocationInterface $location
    );

    /**
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @return void
     */
    public function updateLocationSourceItems(\MageWorx\Locations\Api\Data\LocationInterface $location): void;
}

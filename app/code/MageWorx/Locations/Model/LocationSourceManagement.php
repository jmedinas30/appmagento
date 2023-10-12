<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Model;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\Locations\Api\LocationSourceManagementInterface;
use MageWorx\Locations\Model\Source\Sources;
use MageWorx\Locations\Model\ResourceModel\Catalog\Product\Collection as ProductCollection;

class LocationSourceManagement implements LocationSourceManagementInterface
{
    /**
     * @var LocationRepository
     */
    protected $locationRepository;

    /**
     * @var \Magento\InventoryApi\Api\SourceRepositoryInterface
     */
    protected $sourceRepository;

    /**
     * @var \Magento\InventoryAdminUi\Model\OptionSource\RegionSource
     */
    protected $regionSource;

    /**
     * @var \Magento\Inventory\Model\SourceItem\Command\SourceItemsSave
     */
    protected $sourceItemsSave;

    /**
     * @var \Magento\InventoryApi\Api\SourceItemRepositoryInterface
     */
    protected $sourceItemRepository;

    /**
     * @var \Magento\Inventory\Model\SourceItem\Command\SourceItemsDelete
     */
    protected $sourceItemsDelete;

    /**
     * @var ProductCollection
     */
    protected $productCollection;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * LocationSourceManagement constructor.
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ProductCollection $productCollection
     * @param LocationRepository $locationRepository
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ProductCollection $productCollection,
        LocationRepository $locationRepository
    ) {
        $this->objectManager         = $objectManager;
        $this->sourceItemRepository  = $this->objectManager->create(
            '\Magento\InventoryApi\Api\SourceItemRepositoryInterface'
        );
        $this->productCollection     = $productCollection;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sourceItemsDelete     = $this->objectManager->create(
            '\Magento\Inventory\Model\SourceItem\Command\SourceItemsDelete'
        );
        $this->sourceItemsSave       = $this->objectManager->create(
            '\Magento\Inventory\Model\SourceItem\Command\SourceItemsSave'
        );
        $this->regionSource          = $this->objectManager->create(
            '\Magento\InventoryAdminUi\Model\OptionSource\RegionSource'
        );
        $this->sourceRepository      = $this->objectManager->create(
            '\Magento\InventoryApi\Api\SourceRepositoryInterface'
        );
        $this->locationRepository    = $locationRepository;
    }

    /**
     * @param LocationInterface $location
     * @param bool $isTransferProducts
     * @return \Magento\InventoryApi\Api\Data\SourceInterface
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Validation\ValidationException
     */
    public function createLocationSource(LocationInterface $location, bool $isTransferProducts = true)
    {
        $sourceCode = $this->getSourceCodeForLocation($location);

        $source = $this->prepareSource($sourceCode, $location);
        $this->sourceRepository->save($source);

        $location->setSourceCode($sourceCode);

        if ($isTransferProducts) {
            $this->updateLocationSourceItems($location);
        }

        $location->setAssignType(LocationInterface::ASSIGN_TYPE_PRODUCTS_FROM_SOURCE);
        $location->setProductSkus([]);
        $this->locationRepository->save($location);

        return $source;
    }

    /**
     * @param LocationInterface $location
     * @param string $sourceCode
     * @param bool $isTransferProducts
     * @return LocationInterface
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Validation\ValidationException
     */
    public function assignSourceToLocation(
        \MageWorx\Locations\Api\Data\LocationInterface $location,
        string $sourceCode,
        bool $isTransferProducts = true
    ): LocationInterface {
        if ($sourceCode === Sources::CREATE_NEW_SOURCE) {
            $this->createLocationSource($location, $isTransferProducts);
        } else {
            $location->setSourceCode($sourceCode);
            $location->setAssignType(LocationInterface::ASSIGN_TYPE_PRODUCTS_FROM_SOURCE);
            $location->setProductSkus([]);
        }

        return $location;
    }

    /**
     *
     * Assign source with $sourceCode to location with $sourceCode. It also transfer products from
     * location to source if $isTransferProducts is true.
     * Return source code
     *
     * @param string $locationCode
     * @param string $sourceCode
     * @param bool $isTransferProducts
     * @return string
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Validation\ValidationException
     */
    public function assignSourceToLocationByCode(
        string $locationCode,
        string $sourceCode,
        bool $isTransferProducts = true
    ): string {
        $location = $this->locationRepository->getByCode($locationCode);
        $source   = $this->assignSourceToLocation($location, $sourceCode, $isTransferProducts);
        $this->locationRepository->save($location);

        return $source->getSourceCode();
    }

    /**
     * Create source for location with $sourceCode with address and code from location.
     * It also transfer products from location to new source if $isTransferProducts is true.
     * Return source code
     *
     * @param string $locationCode
     * @param bool $isTransferProducts
     * @return string
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Validation\ValidationException
     */
    public function createLocationSourceByCode(string $locationCode, bool $isTransferProducts = true): string
    {
        $location = $this->locationRepository->getByCode($locationCode);
        $source   = $this->createLocationSource($location, $isTransferProducts);
        $this->locationRepository->save($location);

        return $source->getSourceCode();
    }

    /**
     * @param LocationInterface $location
     * @return string
     * @throws LocalizedException
     */
    protected function getSourceCodeForLocation(LocationInterface $location): string
    {
        $sourceCode = $location->getSourceCode() !== 'create_new_source' ? $location->getSourceCode() : '';

        if (!$sourceCode) {
            $sourceCode = $location->getCode();
        }

        if ($this->isSourceExist($sourceCode)) {
            $sourceCode = $location->getCode() . '-1';
        }

        if ($this->isSourceExist($sourceCode)) {
            throw new LocalizedException(
                __(
                    'Source "%1" for store %2 already created.',
                    $sourceCode,
                    $location->getCode()
                )
            );
        }

        return $sourceCode;
    }

    /**
     * @param string $sourceCode
     * @return bool
     */
    public function isSourceExist(string $sourceCode): bool
    {
        try {
            $this->sourceRepository->get($sourceCode);

            return true;
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return false;
        }
    }

    /**
     * @param string $sourceCode
     * @param LocationInterface $location
     * @return \Magento\InventoryApi\Api\Data\SourceInterface
     */
    protected function prepareSource(string $sourceCode, LocationInterface $location)
    {
        if (!$location->getPostcode()) {
            throw new \Exception(
                __('Postcode is required field. Please, save store with postcode before create new source.')
            );
        }

        /** @var \Magento\Inventory\Model\Source $source */
        $source = $this->objectManager->create(
            '\Magento\Inventory\Model\Source'
        );

        $source->setData($location->getData());

        foreach ($this->regionSource->toOptionArray() as $region) {
            if ($region['value']) {
                $options[$region['label']] = $region['value'];
            }
        }

        if (isset($options[$location->getRegion()])) {
            $source->setRegionId($options[$location->getRegion()]);
        }

        $source->setStreet($location->getAddress());
        $source->setSourceCode($sourceCode);

        return $source;
    }

    /**
     * @param LocationInterface $location
     * @return \Magento\InventoryApi\Api\Data\SourceInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getSourceForLocation(LocationInterface $location)
    {
        return $this->sourceRepository->get($location->getSourceCode());
    }

    /**
     * @param LocationInterface $location
     * @return void
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Validation\ValidationException
     */
    public function updateLocationSourceItems(LocationInterface $location): void
    {
        if (!$location->getSourceCode()) {
            return;
        }

        if (empty($location->getProductSkus()) && $location->getOrigData(
                'assign_type'
            ) !== LocationInterface::ASSIGN_TYPE_ALL) {
            return;
        }

        if ($location->getOrigData('assign_type') === LocationInterface::ASSIGN_TYPE_ALL) {
            $locationSkus = $this->productCollection->getAllSkus();
        } else {
            $locationSkus = $location->getProductSkus();
        }

        $oldSkus = $this->productCollection->getProductIdsBySourceCode($location->getSourceCode());

        $sourceItemsToDelete = [];
        foreach ($oldSkus as $sku) {
            if (array_search($sku, $locationSkus) !== false) { //delete not saved items
                /** @var \Magento\Inventory\Model\SourceItem $sourceItem */
                $sourceItem = $this->objectManager->create(
                    '\Magento\Inventory\Model\SourceItem'
                );
                $sourceItem->setSku($sku);
                $sourceItem->setSourceCode($location->getSourceCode());
                $sourceItemsToDelete[] = $sourceItem;
            }
        }

        if (!empty($sourceItemsToDelete)) {
            $this->sourceItemsDelete->execute($sourceItemsToDelete);
        }

        $sourceItems = [];
        foreach ($locationSkus as $sku) {
            if (array_search($sku, $oldSkus) !== false) { //skip already saved items
                continue;
            }
            /** @var \Magento\Inventory\Model\SourceItem $sourceItem */
            $sourceItem = $this->objectManager->create(
                '\Magento\Inventory\Model\SourceItem'
            ); //add new items
            $sourceItem->setQuantity(0);
            $sourceItem->setStatus(1);
            $sourceItem->setSku($sku);
            $sourceItem->setSourceCode($location->getSourceCode());
            $sourceItems[] = $sourceItem;
        }

        if (!empty($sourceItems)) {
            $this->sourceItemsSave->execute($sourceItems);
        }
    }

    /**
     * @param string $sourceCode
     * @return \Magento\InventoryApi\Api\Data\SourceItemInterface[]
     */
    public function getSourceItemsByCode(string $sourceCode): array
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(\Magento\InventoryApi\Api\Data\SourceItemInterface::SOURCE_CODE, $sourceCode)
            ->create();

        return $this->sourceItemRepository->getList($searchCriteria)->getItems();
    }
}

<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Model;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\InventoryApi\Api\Data\SourceItemInterface;
use Magento\InventoryApi\Api\SourceItemRepositoryInterface;
use MageWorx\Locations\Api\Data\LocationInterface;
use Magento\Inventory\Model\SourceFactory;
use Magento\InventoryApi\Api\Data\SourceInterface;
use Magento\InventoryApi\Api\SourceRepositoryInterface;
use MageWorx\Locations\Api\LocationSourceManagementInterface;
use Magento\InventoryAdminUi\Model\OptionSource\RegionSource;
use Magento\Inventory\Model\SourceItem\Command\SourceItemsSave;
use Magento\Inventory\Model\SourceItem\Command\SourceItemsDelete;
use Magento\Inventory\Model\SourceItemFactory;
use MageWorx\Locations\Model\Source\Sources;
use MageWorx\Locations\Model\ResourceModel\Catalog\Product\Collection as ProductCollection;

class LocationSourceManagement implements LocationSourceManagementInterface
{
    /**
     * @var LocationRepository
     */
    protected $locationRepository;

    /**
     * @var SourceFactory
     */
    protected $sourceFactory;

    /**
     * @var SourceRepositoryInterface
     */
    protected $sourceRepository;

    /**
     * @var RegionSource
     */
    protected $regionSource;

    /**
     * @var SourceItemFactory
     */
    protected $sourceItemFactory;

    /**
     * @var SourceItemsSave
     */
    protected $sourceItemsSave;

    /**
     * @var SourceItemRepositoryInterface
     */
    protected $sourceItemRepository;

    /**
     * @var SourceItemsDelete
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
     * LocationSourceManagement constructor.
     *
     * @param SourceItemRepositoryInterface $sourceItemRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ProductCollection $productCollection
     * @param SourceItemsDelete $sourceItemsDelete
     * @param SourceItemsSave $sourceItemsSave
     * @param SourceItemFactory $sourceItemFactory
     * @param RegionSource $regionSource
     * @param SourceFactory $sourceFactory
     * @param SourceRepositoryInterface $sourceRepository
     * @param LocationRepository $locationRepository
     */
    public function __construct(
        SourceItemRepositoryInterface $sourceItemRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ProductCollection $productCollection,
        SourceItemsDelete $sourceItemsDelete,
        SourceItemsSave $sourceItemsSave,
        SourceItemFactory $sourceItemFactory,
        RegionSource $regionSource,
        SourceFactory $sourceFactory,
        SourceRepositoryInterface $sourceRepository,
        LocationRepository $locationRepository
    ) {
        $this->sourceItemRepository  = $sourceItemRepository;
        $this->productCollection     = $productCollection;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sourceItemsDelete     = $sourceItemsDelete;
        $this->sourceItemsSave       = $sourceItemsSave;
        $this->sourceItemFactory     = $sourceItemFactory;
        $this->regionSource          = $regionSource;
        $this->sourceFactory         = $sourceFactory;
        $this->sourceRepository      = $sourceRepository;
        $this->locationRepository    = $locationRepository;
    }

    /**
     * @param LocationInterface $location
     * @param bool $isTransferProducts
     * @return SourceInterface
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Validation\ValidationException
     */
    public function createLocationSource(LocationInterface $location, bool $isTransferProducts = true): SourceInterface
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
     * @return SourceInterface
     */
    protected function prepareSource(string $sourceCode, LocationInterface $location): SourceInterface
    {
        if (!$location->getPostcode()) {
            throw new \Exception(
                __('Postcode is required field. Please, save store with postcode before create new source.')
            );
        }

        /** @var SourceInterface $source */
        $source = $this->sourceFactory->create();
        $source->setData($location->getData());

        $options = array_column($this->regionSource->toOptionArray(), 'value', 'label');
        if (isset($options[$location->getRegion()])) {
            $source->setRegionId($options[$location->getRegion()]);
        }

        $source->setStreet($location->getAddress());
        $source->setSourceCode($sourceCode);

        return $source;
    }

    /**
     * @param LocationInterface $location
     * @return SourceInterface|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getSourceForLocation(LocationInterface $location): ?SourceInterface
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

        if (empty($location->getProductSkus()) && $location->getOrigData('assign_type') !== LocationInterface::ASSIGN_TYPE_ALL) {
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
                $sourceItem = $this->sourceItemFactory->create();
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
            $sourceItem = $this->sourceItemFactory->create(); //add new items
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
            ->addFilter(SourceItemInterface::SOURCE_CODE, $sourceCode)
            ->create();

        return $this->sourceItemRepository->getList($searchCriteria)->getItems();
    }
}

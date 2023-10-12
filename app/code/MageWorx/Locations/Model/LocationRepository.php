<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Model;

use MageWorx\Locations\Api\LocationRepositoryInterface;
use MageWorx\Locations\Model\ResourceModel\Location\CollectionFactory;
use MageWorx\Locations\Model\ResourceModel\Location\Collection;
use MageWorx\Locations\Model\ResourceModel\Location as ResourceLocation;
use Magento\Store\Model\Store;
use Magento\Framework\Exception\InputException;
use MageWorx\Locations\Api\Data\LocationInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use MageWorx\Locations\Model\Source\MetaRobots as MetaRobotsOptions;
use MageWorx\Locations\Model\Source\Timezone as TimezoneOptions;

class LocationRepository implements LocationRepositoryInterface
{
    /**
     * @var ResourceLocation
     */
    protected $resource;

    /**
     * @var LocationFactory
     */
    protected $locationFactory;

    /**
     * @var CollectionFactory
     */
    protected $locationCollectionFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Customer session
     *
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var MetaRobotsOptions
     */
    protected $metaRobotsOptions;

    /**
     * @var TimezoneOptions
     */
    protected $timezoneOptions;

    /**
     * LocationRepository constructor.
     *
     * @param \Magento\Customer\Model\Session $customerSession
     * @param StoreManagerInterface $storeManager
     * @param CollectionFactory $locationCollectionFactory
     * @param ResourceLocation $resource
     * @param LocationFactory $locationFactory
     * @param MetaRobotsOptions $metaRobotsOptions
     * @param TimezoneOptions $timezoneOptions
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        StoreManagerInterface $storeManager,
        CollectionFactory $locationCollectionFactory,
        ResourceLocation $resource,
        LocationFactory $locationFactory,
        MetaRobotsOptions $metaRobotsOptions,
        TimezoneOptions $timezoneOptions
    ) {
        $this->customerSession           = $customerSession;
        $this->storeManager              = $storeManager;
        $this->resource                  = $resource;
        $this->locationFactory           = $locationFactory;
        $this->locationCollectionFactory = $locationCollectionFactory;
        $this->metaRobotsOptions         = $metaRobotsOptions;
        $this->timezoneOptions           = $timezoneOptions;
    }

    /**
     * Save location.
     *
     * @param LocationInterface $location
     *
     * @return LocationInterface
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(LocationInterface $location)
    {
        try {
            $this->validate($location);

            if ($location->getCurrentStoreId()) {
                $this->resource->saveDataForStores($location);
                $location->setHasDataChanges(false);
            }

            $this->resource->save($location);
        } catch (\Exception $exception) {
            throw new  \Magento\Framework\Exception\CouldNotSaveException(
                __(
                    'Could not save the location: %1',
                    $exception->getMessage()
                )
            );
        }

        return $location;
    }

    /**
     *
     * @param string[] $data
     * @param string $code
     * @return LocationInterface
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function saveByCode($data, $code)
    {
        $location = $this->getByCode($code);

        foreach ($data as $code => $value) {
            $location->setData($code, $value);
        }

        $this->save($location);

        return $location;
    }

    /**
     * @param string $code
     * @param string[] $everyday
     * @param string[] $monday
     * @param string[] $tuesday
     * @param string[] $wednesday
     * @param string[] $thursday
     * @param string[] $friday
     * @param string[] $saturday
     * @param string[] $sunday
     * @return \MageWorx\Locations\Api\Data\LocationInterface
     */
    public function updateWorkingHoursByCode(
        $code,
        $everyday = [],
        $monday = [],
        $tuesday = [],
        $wednesday = [],
        $thursday = [],
        $friday = [],
        $saturday = [],
        $sunday = []
    ) {
        $workingHours = [];

        $location = $this->getByCode($code);
        if (!empty($everyday)) {
            $location->setWorkingHoursType(LocationInterface::WORKING_EVERYDAY);
            $workingHours['everyday'] = $this->prepareWorkingHour($everyday);
        } else {
            $location->setWorkingHoursType(LocationInterface::WORKING_PER_DAY_OF_WEEK);
            $workingHours['monday']    = $this->prepareWorkingHour($monday);
            $workingHours['tuesday']   = $this->prepareWorkingHour($tuesday);
            $workingHours['wednesday'] = $this->prepareWorkingHour($wednesday);
            $workingHours['thursday']  = $this->prepareWorkingHour($thursday);
            $workingHours['friday']    = $this->prepareWorkingHour($friday);
            $workingHours['saturday']  = $this->prepareWorkingHour($saturday);
            $workingHours['sunday']    = $this->prepareWorkingHour($sunday);
        }
        $location->setWorkingHours($workingHours);

        $this->save($location);

        return $location;
    }

    /**
     * @param string[] $workingDay
     * @return string[]
     */
    private function prepareWorkingHour($workingDay)
    {
        $workingDay['from'] = $workingDay['from'] ?? '1:00 am';
        $workingDay['to']   = $workingDay['to'] ?? '1:00 am';
        $workingDay['off']  = $workingDay['off'] ?? 0;

        $workingDay['lunch_from']     = $workingDay['lunch_from'] ?? '1:00 am';
        $workingDay['lunch_to']       = $workingDay['lunch_to'] ?? '1:00 am';
        $workingDay['has_lunch_time'] = $workingDay['has_lunch_time'] ?? 0;

        return $workingDay;
    }

    /**
     * @param string[] $location
     * @return LocationInterface
     * @throws InputException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function createWithSpecifiedCode($location)
    {
        $location = $this->getEmptyEntity()->setData($location);
        $this->checkLocationByCode($location->getCode());
        $location->validateRequiredFields();

        $location->setId(null);

        $this->save($location);

        return $location;
    }

    /**
     * @param string $code
     * @throws InputException
     */
    public function checkLocationByCode($code)
    {
        $locationCode = $this->resource->getIdByLocationCode($code);

        if ($locationCode) {
            throw new InputException(__('A location with code "%1" already exists.', $code));
        }
    }

    /**
     * Retrieve Location.
     *
     * @param int $id
     *
     * @return LocationInterface
     */
    public function getById($id)
    {
        /** @var LocationInterface $location */
        $location = $this->locationFactory->create();
        $this->resource->load($location, $id);

        return $location;
    }

    /**
     * @param string $code
     * @return LocationInterface
     * @throws NoSuchEntityException
     */
    public function getByCode($code)
    {
        $id = $this->resource->getIdByLocationCode($code);
        if (!$id) {
            throw new NoSuchEntityException(__('Requested Location with "code=%1" doesn\'t exist', $code));
        }

        /** @var LocationInterface $location */
        $location = $this->locationFactory->create();
        $this->resource->load($location, $id);

        return $location;
    }

    /**
     * Get empty Location
     *
     * @return LocationInterface
     */
    public function getEmptyEntity()
    {
        /** @var LocationInterface $location */
        $location = $this->locationFactory->create();

        return $location;
    }

    /**
     * Delete Location
     *
     * @param LocationInterface $location
     *
     * @return bool
     *
     * @throws CouldNotDeleteException
     */
    public function delete(LocationInterface $location)
    {
        try {
            $this->resource->delete($location);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __(
                    'Could not delete the location: %1',
                    $exception->getMessage()
                )
            );
        }

        return true;
    }

    /**
     * Delete Location by given Location Identity
     *
     * @param string $id
     *
     * @return bool
     *
     * @throws CouldNotDeleteException
     */
    public function deleteById($id)
    {
        return $this->delete($this->getById($id));
    }

    /**
     * Delete Location by given Location Code
     *
     * @param string $code
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteByCode($code)
    {
        $id = $this->resource->getIdByLocationCode($code);
        if (!$id) {
            throw new NoSuchEntityException(__('Requested Location doesn\'t exist'));
        }

        return $this->delete($this->getById($id));
    }

    /**
     * Get list Location
     *
     * @return Collection
     */
    public function getListLocation()
    {
        /** @var Collection $locationCollection */
        $locationCollection = $this->locationCollectionFactory->create();
        $locationCollection->addAttributeToSelect('*');

        return $locationCollection;
    }

    /**
     * @param null|int $storeId
     * @param array $filters
     * @return Collection
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getListLocationForFront($storeId = null, $filters = [])
    {
        /** @var Collection $locationCollection */
        $locationCollection = $this->locationCollectionFactory->create();
        $locationCollection->addAttributeToSelect('*');
        $locationCollection->addFieldToFilter(
            LocationInterface::IS_ACTIVE,
            LocationInterface::ACTIVE
        );

        $storeId = $storeId ?? $this->storeManager->getStore()->getId();
        $locationCollection->addStoreFilter($storeId);
        $locationCollection->setOrderByOrderField($filters);

        if (empty($filters)) {
         //   $locationCollection = $this->addFiltersFromSession($locationCollection);
        } else {
            $locationCollection->addDistanceField($filters);
            $locationCollection->addSearchFilters($filters);
        }

        return $locationCollection;
    }

    /**
     * @param Collection $locationCollection
     * @return Collection
     */
    protected function addFiltersFromSession(Collection $locationCollection): Collection
    {
        if ($this->customerSession->getData(self::LOCATOR_COORDINATES)) {
            $filters = $this->customerSession->getData(self::LOCATOR_COORDINATES);

            $locationCollection->addSearchFilters($filters);
            $locationCollection->addDistanceField($filters);
        }

        return $locationCollection;
    }

    /**
     * @param array|int $ids
     * @param null $limit
     * @param bool $addOutOfStockItems
     * @param array $filters
     * @param string $sku
     * @return Collection|string[]
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getListLocationByProductIds(
        $ids,
        $limit = null,
        $addOutOfStockItems = true,
        $filters = [],
        $sku = ''
    ) {
        /** @var Collection $locationCollection */
        $locationCollection = $this->getListLocationForFront($this->storeManager->getStore()->getId(), $filters);
        if (is_object($ids)) {
            $ids = [$ids->getId()];
        }
        $locationCollection->addProductIdsFilter($ids, $addOutOfStockItems, $sku);
        if ($limit) {
            $locationCollection->setLimit($limit);
        }

        if (!empty($filters)) {
            $locationCollection->addSearchFilters($filters);
        }

        return $locationCollection;
    }

    /**
     * @param int $product
     * @param bool $addOutOfStockItems
     * @return int
     */
    public function getLocationCountForProduct($product, $addOutOfStockItems = true)
    {
        /** @var Collection $locationCollection */
        $locationCollection = $this->locationCollectionFactory->create();
        $locationCollection = $this->addFiltersFromSession($locationCollection);

        return $locationCollection->getLocationCountForProduct(
            $product,
            $addOutOfStockItems,
            $this->storeManager->getStore()->getId()
        );
    }

    /**
     * @param string[] $ids
     * @param int $storeId
     * @return Collection
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getListLocationByIds($ids, $storeId = 0)
    {
        /** @var Collection $locationCollection */
        $locationCollection = $this->locationCollectionFactory->create();
        $locationCollection->addAttributeToSelect('*');
        $locationCollection->addFieldToFilter('entity_id', ['in' => $ids]);
        $storeId = $storeId ?? $this->storeManager->getStore()->getId();
        $locationCollection->addStoreFilter($storeId);
        $locationCollection->setOrderByOrderField();

        return $locationCollection;
    }

    /**
     * Get list Location for cron
     *
     * @return Collection
     */
    public function getListLocationForCron()
    {
        /** @var Collection $locationCollection */
        $locationCollection = $this->locationCollectionFactory->create();
        $locationCollection->addAttributeToSelect('*');
        $locationCollection->addFieldToFilter(
            LocationInterface::IS_ACTIVE,
            LocationInterface::ACTIVE
        );
        $locationCollection->addFieldToFilter(
            LocationInterface::ASSIGN_TYPE,
            LocationInterface::ASSIGN_TYPE_CONDITION
        );
        $locationCollection->addFieldToFilter(
            LocationInterface::APPLY_BY_CRON,
            1
        );

        return $locationCollection;
    }

    /**
     * Return codes of all existing locations
     *
     * @return string[]
     */
    public function getAllCodes()
    {
        $locationCollection = $this->locationCollectionFactory->create();

        return $locationCollection->getAllCodes();
    }

    /**
     * @param string $type
     * @param string $name
     * @param int|null $parentId
     * @return Collection
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getLocationsByList($type, $name, $parentId = null)
    {
        /** @var Collection $locationCollection */
        $locationCollection = $this->locationCollectionFactory->create();
        $locationCollection->addAttributeToSelect('*');
        $locationCollection->addFieldToFilter(
            LocationInterface::IS_ACTIVE,
            LocationInterface::ACTIVE
        );

        $locationCollection->addFieldToFilter($type, $name);

        if ($parentId) {
            $locationCollection->addFieldToFilter('location_page_path', $parentId);
        }

        $storeId = $this->storeManager->getStore()->getId();
        if ($storeId != Store::DEFAULT_STORE_ID) {
            $locationCollection->addStoreFilter($storeId);
        }

        return $locationCollection;
    }

    /**
     * @param string[] $pathInfo
     * @return $this
     */
    public function updatePathInfo($pathInfo)
    {
        $this->resource->updatePathInfo($pathInfo);

        return $this;
    }

    /**
     * @param LocationInterface $location
     * @throws InputException
     */
    protected function validate(LocationInterface $location)
    {
        if ($location->getTimezone()
            && !in_array($location->getTimezone(), array_keys($this->timezoneOptions->toArray()))
        ) {
            throw new InputException(__('Timezone value is invalid.'));
        }

        if ($location->getMetaRobots()
            && !in_array($location->getMetaRobots(), $this->metaRobotsOptions->toArray())
        ) {
            throw new InputException(__('Meta Robots value is invalid.'));
        }
    }
}

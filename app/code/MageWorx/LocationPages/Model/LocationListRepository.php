<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
declare(strict_types=1);

namespace MageWorx\LocationPages\Model;

use MageWorx\LocationPages\Api\LocationListRepositoryInterface;
use MageWorx\LocationPages\Api\Data\LocationPageInterface;
use MageWorx\LocationPages\Api\Data\LocationListInterface;
use MageWorx\LocationPages\Model\ResourceModel\LocationList\CollectionFactory;
use MageWorx\LocationPages\Model\ResourceModel\LocationList\Collection;
use MageWorx\LocationPages\Model\ResourceModel\LocationList as ResourceLocationList;
use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\Locations\Api\LocationRepositoryInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

class LocationListRepository implements LocationListRepositoryInterface
{
    /**
     * @var \MageWorx\LocationPages\Helper\Data
     */
    protected $helper;

    /**
     * @var ResourceLocationList
     */
    protected $resource;

    /**
     * @var LocationListFactory
     */
    protected $locationListFactory;

    /**
     * @var CollectionFactory
     */
    protected $locationListCollectionFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var LocationPageFactory
     */
    protected $locationPageFactory;

    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * LocationListRepository constructor.
     *
     * @param \MageWorx\LocationPages\Model\LocationPageFactory $locationPageFactory
     * @param StoreManagerInterface $storeManager
     * @param CollectionFactory $locationListCollectionFactory
     * @param ResourceLocationList $resource
     * @param \MageWorx\LocationPages\Helper\Data $helper
     * @param \MageWorx\LocationPages\Model\LocationListFactory $locationListFactory
     */
    public function __construct(
        LocationRepositoryInterface $locationRepository,
        LocationPageFactory $locationPageFactory,
        StoreManagerInterface $storeManager,
        CollectionFactory $locationListCollectionFactory,
        ResourceLocationList $resource,
        \MageWorx\LocationPages\Helper\Data $helper,
        LocationListFactory $locationListFactory
    ) {
        $this->locationRepository            = $locationRepository;
        $this->locationPageFactory           = $locationPageFactory;
        $this->storeManager                  = $storeManager;
        $this->resource                      = $resource;
        $this->helper                        = $helper;
        $this->locationListFactory           = $locationListFactory;
        $this->locationListCollectionFactory = $locationListCollectionFactory;
    }

    /**
     * Save locationList.
     *
     * @param LocationListInterface $locationList
     *
     * @return LocationListInterface
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(LocationListInterface $locationList): LocationListInterface
    {
        try {
            $this->resource->save($locationList);
        } catch (\Exception $exception) {
            throw new  \Magento\Framework\Exception\CouldNotSaveException(
                __(
                    'Could not save the locationList: %1',
                    $exception->getMessage()
                )
            );
        }

        return $locationList;
    }

    /**
     * Retrieve locationList.
     *
     * @param int $id
     *
     * @return LocationListInterface
     */
    public function getById($id): LocationListInterface
    {
        /** @var LocationListInterface $locationList */
        $locationList = $this->locationListFactory->create();
        $this->resource->load($locationList, $id);

        return $locationList;
    }

    /**
     * @param string $type
     * @param string $name
     * @return LocationListInterface
     */
    public function getByName(string $type, string $name): LocationListInterface
    {
        $locationListCollection = $this->locationListCollectionFactory->create();

        return $locationListCollection->getByName($type, $name);
    }

    /**
     * Get empty locationList
     *
     * @return LocationListInterface
     */
    public function getEmptyEntity(): LocationListInterface
    {
        /** @var locationListInterface $locationList */
        $locationList = $this->locationListFactory->create();

        return $locationList;
    }

    /**
     * Delete locationList
     *
     * @param LocationListInterface $locationList
     *
     * @return bool
     *
     * @throws CouldNotDeleteException
     */
    public function delete(LocationListInterface $locationList): bool
    {
        try {
            $this->resource->delete($locationList);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __(
                    'Could not delete the locationList: %1',
                    $exception->getMessage()
                )
            );
        }

        return true;
    }

    /**
     * Delete locationList by given locationList Identity
     *
     * @param string $id
     *
     * @return bool
     *
     * @throws CouldNotDeleteException
     */
    public function deleteById($id): bool
    {
        return $this->delete($this->getById($id));
    }

    /**
     * Get list locationList
     *
     * @return Collection
     */
    public function getLocationListCollection(): Collection
    {
        /** @var Collection $locationListCollection */
        $locationListCollection = $this->locationListCollectionFactory->create();

        return $locationListCollection;
    }

    /**
     * @param string[] $ids
     * @return Collection
     * @throws NoSuchEntityException
     */
    public function getLocationListCollectionByIds($ids): Collection
    {
        /** @var Collection $locationListCollection */
        $locationListCollection = $this->locationListCollectionFactory->create();
        $locationListCollection->addFieldToFilter('id', ['in' => $ids]);
        $locationListCollection->setOrder("LENGTH(path) - LENGTH(REPLACE(path, '/', ''))", 'ASC');

        return $locationListCollection;
    }

    /**
     * @param string $type
     * @return Collection
     * @throws NoSuchEntityException
     */
    public function getLocationListCollectionByType($type): Collection
    {
        /** @var Collection $locationListCollection */
        $locationListCollection = $this->locationListCollectionFactory->create();
        $locationListCollection->addFieldToFilter('type', $type);

        return $locationListCollection;
    }

    /**
     * @param LocationInterface $location
     * @return $this
     * @throws \Magento\Framework\Exception\StateException
     */
    public function createLocationPage($location): LocationListRepositoryInterface
    {
        /** @var LocationPageInterface $locationPage */
        $locationPage = $this->locationPageFactory->create();
        $locationPage->setLocation($location);

        $this->resource->saveUrlRewriteLocationPage($locationPage);

        return $this;
    }

    /**
     * @param LocationInterface $location
     * @return $this
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteLocationPage($location)
    {
        $this->resource->deleteUrlRewrites($location, LocationInterface::ENTITY);

        $locationListCollection = array_reverse(
            $this->getLocationListCollectionByIds(
                explode('/', $location->getLocationPagePath())
            )->getItems()
        );

        foreach ($locationListCollection as $locationList) {
            if (!count($this->getChildLocationsForList($locationList))) {
                $this->delete($locationList);
            }
        }

        return $this;
    }

    /**
     *
     * Created locationList pages (if they don't exist) for location country, region, city
     *
     * Return location page path which contains parents locationList ids
     *
     * @param LocationInterface $location
     * @return string
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function createLocationListPages($location): string
    {
        /** @var LocationPageInterface $locationPage */
        $locationPage = $this->locationPageFactory->create();
        $locationPage->setLocation($location);

        $parentId = $this->resource->getIdByTypeName(
            'base',
            str_replace('-', ' ', ucfirst($this->helper->getBasePath()))
        );

        if (!$parentId) {
            /** @var LocationListInterface $list */
            $list = $this->getEmptyEntity();
            $list->setType('base');
            $list->setName(str_replace('-', ' ', ucfirst($this->helper->getBasePath())));
            $parentId = $this->save($list)->getId();
        }
        $path = $parentId . '/';

        foreach ($locationPage->prepareLocationPath($this->helper->getUrlPathParts()) as $type => $name) {
            $id = $this->resource->getIdByTypeName($type, $name, $parentId);

            if (!$id) {
                /** @var LocationListInterface $list */
                $list = $this->getEmptyEntity();
                $list->setType($type);
                $list->setName((string)$name);
                $list->setParentId((int)$parentId);
                $list->setPath($path);
                $id       = $this->save($list)->getId();
                $parentId = $id;
            } else {
                $list = $this->getById($id);
                $list->setParentId((int)$parentId);
                $list->setPath($path);
                $this->save($list);
                $parentId = $id;
            }
            $path .= $id . '/';
        }

        return $path;
    }

    /**
     * @param LocationListInterface $list
     * @return Collection
     */
    public function getChildLocationsForList($list)
    {
        if ($list->getType() != $this->helper->getLastListType()) {
            $collection = $this->locationListCollectionFactory->create();
            $collection->addFieldToFilter('parent_id', ['in' => $list->getId()]);
            $collection->load();
            if ($type = $this->helper->getChildListType($list->getType())) {
                $collection->loadLocationCount($type, $list->getId());
            }
        } else {
            $locationCollection = $this->locationRepository->getLocationsByList(
                $list->getType(),
                $list->getName(),
                $list->getId()
            );
            $collection         = [];
            foreach ($locationCollection as $location) {
                $collection[] = $this->locationPageFactory->create()
                                                          ->setName($location->getName())
                                                          ->setLocation($location);
            }
        }

        return $collection;
    }

    /**
     * @return string[]
     * @throws CouldNotDeleteException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function updateLocationListCollection()
    {
        //delete old location list paged
        foreach ($this->getLocationListCollection() as $locationList) {
            $this->delete($locationList);
        }

        //create new pages for locations
        $pathInfo = [];
        foreach ($this->locationRepository->getListLocation() as $location) {
            $this->createLocationPage($location);
            $pathInfo[$location->getId()] = $this->createLocationListPages($location);
        }

        return $pathInfo;
    }

    /**
     * @param string $type
     * @return int
     */
    public function getEmptyCoordinatesCount($type): int
    {
        $locationListCollection = $this->getLocationsWithEmptyCoordinates($type);

        return count($locationListCollection);
    }

    /**
     * @param string $type
     * @param int $page
     * @return Collection
     * @throws NoSuchEntityException
     */
    public function getLocationsWithEmptyCoordinates($type, $page = 0): Collection
    {
        /** @var Collection $locationListCollection */
        $locationListCollection = $this->getLocationListCollectionByType($type);
        $locationListCollection->addFieldToFilter('latitude', '0.00000000000000');
        $locationListCollection->addFieldToFilter('longitude', '0.00000000000000');
        if ($page) {
            $locationListCollection->setPageSize(10)
                                   ->setCurPage($page);
        }

        return $locationListCollection;
    }

    /**
     * @param LocationListInterface $location
     * @return string
     */
    public function getAddressByLocationList(LocationListInterface $location): string
    {
        if ($location->getType()!== 'country') {
            $parentLocation = $this->getById($location->getParentId());

            return $parentLocation->getName() . ', ' . $location->getName();
        }

        return $location->getName();
    }
}

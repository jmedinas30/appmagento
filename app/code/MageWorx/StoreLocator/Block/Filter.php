<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreLocator\Block;

use Magento\Directory\Model\RegionFactory;
use Magento\Framework\View\Element\Template;
use MageWorx\LocationPages\Api\LocationListRepositoryInterface;
use MageWorx\StoreLocator\Helper\Data;
use MageWorx\StoreLocator\Model\Source\Scale;

class Filter extends Template
{
    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    protected $driver;

    /**
     * @var Scale
     */
    protected $scale;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var LocationListRepositoryInterface
     */
    protected $locationListRepository;

    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \MageWorx\LocationPages\Model\ResourceModel\LocationList\Collection
     */
    protected $parentFilterList;

    /**
     * @var \MageWorx\Locations\Model\ResourceModel\Location
     */
    protected $locationResource;

    /**
     * @var RegionFactory
     */
    protected $regionFactory;

    /**
     * @var array
     */
    protected $scales = ['city', 'region', 'country_id'];

    /**
     * @param \Magento\Framework\Filesystem\Driver\File $driver
     * @param \MageWorx\Locations\Model\ResourceModel\Location $locationResource
     * @param LocationListRepositoryInterface $locationListRepository
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param Scale $scale
     * @param Data $helper
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        RegionFactory $regionFactory,
        \Magento\Framework\Filesystem\Driver\File $driver,
        \MageWorx\Locations\Model\ResourceModel\Location $locationResource,
        LocationListRepositoryInterface $locationListRepository,
        \Magento\Framework\Module\Manager $moduleManager,
        Scale $scale,
        Data $helper,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->regionFactory          = $regionFactory;
        $this->driver                 = $driver;
        $this->locationResource       = $locationResource;
        $this->locationListRepository = $locationListRepository;
        $this->helper                 = $helper;
        $this->scale                  = $scale;
        $this->moduleManager          = $moduleManager;
    }

    /**
     * @param string $code
     * @return false|string
     */
    public function getAttributeIcon($code)
    {
        $appCodePath = BP . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'code' . DIRECTORY_SEPARATOR
            . 'MageWorx' . DIRECTORY_SEPARATOR . 'Locations';
        $vendorPath = BP . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'mageworx' . DIRECTORY_SEPARATOR
            . 'module-locations';
        $filePath = DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR
            . 'frontend' . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR
            . 'icons' . DIRECTORY_SEPARATOR . $code .'.svg' ;

        if ($this->driver->isExists($appCodePath . $filePath)
            || ($this->driver->isExists($vendorPath . $filePath)))
        {
            return $this->getViewFileUrl(
                'MageWorx_Locations::images/icons/' . $code . '.svg'
            );
        }

        return false;
    }

    /**
     * @return array
     */
    public function getExtraAttributes()
    {
        return $this->locationResource->getCustomAttributes();
    }

    /**
     * @return string
     */
    public function getCurrentPlace()
    {
        $filters      = $this->helper->getSearchFiltersFromSession();
        $defaultScale = $this->helper->getFilterBy();

        if (!empty($filters[$defaultScale])) {
            return $filters[$defaultScale];
        }
        if (!empty($filters['autocomplete'][$defaultScale])) {
            return $filters['autocomplete'][$defaultScale];
        }

        foreach ($this->scales as $scale) {
            if (!empty($filters['autocomplete'][$scale])) {
                return $filters['autocomplete'][$scale];
            }
        }

        if ($this->helper->getPlaceByGeoIp()) {
            return $this->helper->getPlaceByGeoIp();
        }

        return $this->helper->getDefaultPlace();
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCurrentPlaceName()
    {
        return $this->getCurrentPlace();
    }

    /**
     * @return \MageWorx\LocationPages\Model\ResourceModel\LocationList\Collection
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getParentFilterList()
    {
        if (!$this->parentFilterList) {
            $places = [];

            foreach ($this->getLocations() as $location) {
                $places[] = $this->helper->getLocationPlaceIdByScale($location, $this->getFilterScale());
            }

            $this->parentFilterList = $this->locationListRepository->getLocationListCollectionByIds(
                array_unique($places)
            );
        }

        return $this->parentFilterList;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getFilterScale()
    {
        if ($this->getScale()) {
            return $this->getScale();
        }

        return $this->helper->getFilterBy();
    }

    /**
     * @return bool
     */
    public function isShowMap()
    {
        return $this->helper->isShowMap();
    }

    /**
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getLocationInfoHtml($location)
    {
        $block = $this->getLayout()->createBlock(\MageWorx\StoreLocator\Block\LocationInfo::class)
                      ->setTemplate('MageWorx_StoreLocator::location_info.phtml');
        $block->setData('location', $location);
        $block->setData('is_checkout', $this->getIsCheckout());

        $html = $block->toHtml();

        return $html;
    }

    /**
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @param string $label
     * @return string
     */
    public function getLocationPageLink($location, $label)
    {
        if ($location->getPageFullUrl() && $this->moduleManager->isEnabled('MageWorx_LocationPages')) {
            $label = '<a href="' . $location->getPageFullUrl() . '">' . $label . '</a>';
        }

        return $label;
    }

    /**
     * @return string
     */
    public function getJsLink()
    {
        return $this->getViewFileUrl('MageWorx_StoreLocator::js/locator.js');
    }

    /**
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @return string
     */
    public function getLocationCoordinate($location)
    {
        return '{lat: ' . $location->getLatitude() . ', lng: ' . $location->getLongitude() . '}';
    }

    /**
     * @param \MageWorx\LocationPages\Api\Data\LocationListInterface $place
     * @return string
     */
    public function getPlaceNameWithRegion( \MageWorx\LocationPages\Api\Data\LocationListInterface $place): string
    {
        if ($place->getType() == \MageWorx\Locations\Api\Data\LocationInterface::CITY) {
            $region = $this->locationListRepository->getById($place->getParentId());
            $country = $this->locationListRepository->getById($region->getParentId());
            if ($country->getName() == 'United States') {
                /** @var \Magento\Directory\Model\Region $region */
                $regionShort = $this->regionFactory->create();
                try {
                    $regionShort->loadByName($region->getName(), 'US');
                } catch (\Exception $e) {
                    return $place->getName() . ', ' . $region->getName();
                }

                return $place->getName() . ', ' . $regionShort->getCode();
            }

            return $place->getName();
        }

        return $place->getName();
    }
}

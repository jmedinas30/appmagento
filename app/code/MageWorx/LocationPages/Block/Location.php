<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Block;

use Magento\Directory\Model\RegionFactory;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\LocationPages\Api\LocationListRepositoryInterface;
use MageWorx\Locations\Helper\Data as LocationHelper;

/**
 * Class Location
 */
class Location extends \Magento\Framework\View\Element\Template
{
    const CACHE_TAG = 'location_page';

    /**
     * Core registry
     *
     * @var \MageWorx\Locations\Model\Registry
     */
    protected $coreRegistry;

    /**
     * Catalog layer
     *
     * @var \Magento\Catalog\Model\Layer
     */
    protected $catalogLayer;

    /**
     * @var \Magento\Catalog\Helper\Category
     */
    protected $categoryHelper;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \MageWorx\LocationPages\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    protected $driver;

    /**
     * @var LocationHelper
     */
    protected $locationHelper;

    /**
     * @var \Magento\Widget\Model\Template\Filter
     */
    protected $templateProcessor;

    /** @var EventManagerInterface */
    protected $eventManager;

    /**
     * @var LocationListRepositoryInterface
     */
    protected $locationListRepository;

    /**
     * @var \MageWorx\Locations\Model\ResourceModel\Location
     */
    protected $locationResource;

    /**
     * @var RegionFactory
     */
    protected $regionFactory;

    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $filterProvider;

    /**
     * @param RegionFactory $regionFactory
     * @param \Magento\Framework\Filesystem\Driver\File $driver
     * @param \MageWorx\Locations\Model\ResourceModel\Location $locationResource
     * @param LocationListRepositoryInterface $locationListRepository
     * @param EventManagerInterface $eventManager
     * @param \MageWorx\LocationPages\Helper\Data $helper
     * @param LocationHelper $locationHelper
     * @param \Magento\Widget\Model\Template\Filter $templateProcessor
     * @param \MageWorx\Locations\Model\Registry $coreRegistry
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param \MageWorx\Locations\Model\Registry $registry
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Helper\Category $categoryHelper
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param array $data
     */
    public function __construct(
        RegionFactory $regionFactory,
        \Magento\Framework\Filesystem\Driver\File $driver,
        \MageWorx\Locations\Model\ResourceModel\Location $locationResource,
        LocationListRepositoryInterface $locationListRepository,
        EventManagerInterface $eventManager,
        \MageWorx\LocationPages\Helper\Data $helper,
        LocationHelper $locationHelper,
        \Magento\Widget\Model\Template\Filter $templateProcessor,
        \MageWorx\Locations\Model\Registry $coreRegistry,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \MageWorx\Locations\Model\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Helper\Category $categoryHelper,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        array $data = []
    ) {
        $this->regionFactory          = $regionFactory;
        $this->catalogLayer           = $layerResolver->get();
        $this->coreRegistry           = $coreRegistry;
        $this->storeManager           = $storeManager;
        $this->locationResource       = $locationResource;
        $this->eventManager           = $eventManager;
        $this->driver                 = $driver;
        $this->templateProcessor      = $templateProcessor;
        $this->categoryHelper         = $categoryHelper;
        $this->locationHelper         = $locationHelper;
        $this->helper                 = $helper;
        $this->locationListRepository = $locationListRepository;
        $this->filterProvider         = $filterProvider;
        parent::__construct($context, $data);
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $storeId = $this->helper->getStoreId();

        /** @var LocationInterface $location */
        $location = $this->getCurrentLocation();

        if ($location) {
            $title = $location->getMetaTitle();
            if ($title) {
                $this->pageConfig->getTitle()->set($title);
            } else {
                $this->pageConfig->getTitle()->set($location->getName());
            }
            $description = $location->getMetaDescription();
            if ($description) {
                $this->pageConfig->setDescription($description);
            }
            $keywords = $location->getMetaKeywords();
            if ($keywords) {
                $this->pageConfig->setKeywords($keywords);
            }

            $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
            if ($pageMainTitle) {
                $pageMainTitle->setPageTitle(' ');
            }

            $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
            if ($breadcrumbs && $this->helper->isAddBreadcrumbs($storeId)) {
                $this->addBreadcrumbs($breadcrumbs);
            }
        }

        return $this;
    }

    /**
     * @return bool|LocationInterface
     */
    public function getCurrentLocation()
    {
        return $this->helper->getCurrentLocation();
    }

    /**
     * @param LocationInterface $location
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getMapHtml($location)
    {
        $block = $this->getLayout()->createBlock(\MageWorx\LocationPages\Block\Map::class);
        $block->setData('location', $location);

        return $block->toHtml();
    }

    /**
     * @param string $image
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLocationImageUrl($image)
    {
        return $this->storeManager->getStore()->getBaseUrl('media') . 'mageworx/locations/' . $image;
    }

    /**
     * @param string $image
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getWorkingHoursUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl() . 'store_locator/location/workinghours';
    }

    /**
     * @param \Magento\Theme\Block\Html\Breadcrumbs $breadcrumbs
     * @return mixed
     */
    protected function addBreadcrumbs($breadcrumbs)
    {
        $breadcrumbs->addCrumb(
            'home',
            [
                'label' => __('Home'),
                'title' => __('Go to Home Page'),
                'link'  => $this->_storeManager->getStore()->getBaseUrl()
            ]
        );

        $location               = $this->getCurrentLocation();
        $locationListCollection = $this->locationListRepository->getLocationListCollectionByIds(
            explode('/', $location->getLocationPagePath())
        );

        /** @var \MageWorx\LocationPages\Api\Data\LocationListInterface $locationList */
        foreach ($locationListCollection as $locationList) {
            $breadcrumbs->addCrumb(
                $locationList->getType() . '_' . $this->helper->prepareStringToUrl($locationList->getName()),
                [
                    'label' => __($locationList->getName()),
                    'title' => __($locationList->getName()),
                    'link'  => $locationList->getFullUrl()
                ]
            );
        }

        $header = $location->getName();
        $url    = $location->getFullUrl();
        $breadcrumbs->addCrumb(
            'mageworx_location',
            [
                'label' => $header,
                'title' => $header,
                'link'  => $url
            ]
        );

        return $breadcrumbs;
    }

    /**
     * @return string
     */
    public function getProductListHtml()
    {
        return $this->getChildHtml('product_list');
    }

    /**
     * @return bool
     */
    public function isProductEnabled()
    {
        return $this->helper->isShowProducts();
    }

    /**
     * Get identities
     *
     * @return string[]
     */
    public function getIdentities()
    {
        $identities = [
            self::CACHE_TAG . '_' . $this->getId(),
        ];
        if (!$this->getId() || $this->hasDataChanges() || $this->isDeleted()) {
            $identities[] = self::CACHE_TAG;
            $identities[] = \Magento\Catalog\Model\Product::CACHE_PRODUCT_CATEGORY_TAG . '_location_' . $this->getId();
        }

        return $identities;
    }

    /**
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @return string
     */
    public function getRouteUrl($location)
    {
        $region = $location->getRegion() == \MageWorx\Locations\Model\Source\Region::NO_REGIONS ?
            '' : $location->getRegion();

        return "//maps.google.com/maps?saddr=current+location&amp;daddr=" . $location->getCountry() . "," .
            $region . "," . $location->getCity() . "," . $location->getName();
    }

    /**
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrentStoreId(): int
    {
        return (int)$this->helper->getStoreId();
    }

    /**
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getWorkingHoursHtml(LocationInterface $location): string
    {
        $block = $this->getLayout()->createBlock(\MageWorx\StoreLocator\Block\LocationInfo\WorkingHours::class)
                      ->setTemplate('MageWorx_LocationPages::location/working_hours.phtml');
        $block->setLocation($location);

        return $block->toHtml();
    }

    /**
     * @param LocationInterface $location
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isWorkingHoursEnabled(LocationInterface $location): bool
    {
        if (!$this->locationHelper->isWorkingHoursEnabled()
            || !$location->getIsWorkingHoursEnabled()
        ) {
            return false;
        }

        return true;
    }

    /**
     * @param string $code
     * @return string
     */
    public function getAttributeIcon($code)
    {
        $appCodePath = BP . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'code' . DIRECTORY_SEPARATOR
            . 'MageWorx' . DIRECTORY_SEPARATOR . 'Locations';
        $vendorPath  = BP . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'mageworx' . DIRECTORY_SEPARATOR
            . 'module-locations';
        $filePath    = DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR
            . 'frontend' . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR
            . 'icons' . DIRECTORY_SEPARATOR . $code . '.svg';

        if ($this->driver->isExists($appCodePath . $filePath)
            || ($this->driver->isExists($vendorPath . $filePath))) {
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
     * @param LocationInterface $location
     * @return string
     */
    public function getLocationsRegion(LocationInterface $location)
    {
        if ($location->getCountryId() == 'US') {
            /** @var \Magento\Directory\Model\Region $region */
            $region = $this->regionFactory->create();
            try {
                $region->loadByName($location->getRegion(), $location->getCountryId());
            } catch (\Exception $e) {
                return $location->getRegion();
            }

            return $region->getCode();
        }

        return $location->getRegion();
    }

    /**
     * @param LocationInterface $location
     * @return string
     * @throws \Exception
     */
    public function getDescriptionHtml(LocationInterface $location): string
    {
        if ($location->getDescription() !== null) {
            return $this->filterProvider->getPageFilter()->filter($location->getDescription());
        }

        return '';
    }
}

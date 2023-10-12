<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreLocator\Block;

use Magento\CatalogInventory\Model\Configuration;
use Magento\Framework\View\Element\Template;
use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\StoreLocator\Helper\Data;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Directory\Model\RegionFactory;

class LocationInfo extends Template
{
    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    protected $driver;

    /**
     * @var Configuration
     */
    protected $inventoryConfig;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \MageWorx\Locations\Model\ResourceModel\Location
     */
    protected $locationResource;

    /**
     * @var RegionFactory
     */
    protected $regionFactory;

    /**
     * @param RegionFactory $regionFactory
     * @param \Magento\Framework\Filesystem\Driver\File $driver
     * @param \MageWorx\Locations\Model\ResourceModel\Location $locationResource
     * @param Configuration $inventoryConfig
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param StoreManagerInterface $storeManager
     * @param Data $helper
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        RegionFactory $regionFactory,
        \Magento\Framework\Filesystem\Driver\File $driver,
        \MageWorx\Locations\Model\ResourceModel\Location $locationResource,
        Configuration $inventoryConfig,
        \Magento\Framework\Module\Manager $moduleManager,
        StoreManagerInterface $storeManager,
        Data $helper,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->regionFactory    = $regionFactory;
        $this->driver           = $driver;
        $this->locationResource = $locationResource;
        $this->inventoryConfig  = $inventoryConfig;
        $this->moduleManager    = $moduleManager;
        $this->helper           = $helper;
        $this->storeManager     = $storeManager;
    }

    /**
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @return string
     */
    public function getLocationName($location)
    {
        $name = $this->escapeHtml($location->getName());

        return $name;
    }

    /**
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @param string $scale
     * @return string
     */
    public function getCodeByScale($location, $scale)
    {
        return $this->helper->getLocationPlaceIdByScale($location, $scale);
    }

    /**
     * @return string
     */
    public function getScale()
    {
        return $this->helper->getFilterBy();
    }

    /**
     * @return string
     */
    public function getParentScale()
    {
        return $this->helper->getFilterBy();
    }

    /**
     * @param string $image
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLocationImageUrl($image)
    {
        if (!$image) {
            return $this->getViewFileUrl('MageWorx_StoreLocator::images/svg/default_store_img.svg');
        }

        return $this->storeManager->getStore()->getBaseUrl('media') . 'mageworx/locations/' . $image;
    }

    /**
     * @return bool
     */
    public function isCheckout()
    {
        if ($this->getIsCheckout()) {
            return true;
        }

        if ($this->getRequest()->getFullActionName() == 'paypal_express_review') {
            return true;
        }

        return strpos($this->getRequest()->getFullActionName(), 'checkout') !== false;
    }

    /**
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @return string
     */
    public function getRouteUrl($location)
    {
        $region = $location->getRegion() == \MageWorx\Locations\Model\Source\Region::NO_REGIONS ?
            '' : $location->getRegion();

        return "//maps.google.com/maps/dir/?api=1&destination=" . $location->getAddress() .
            ", " . $location->getCity() . ", " . $region . ", " . $location->getCountry();
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
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @return int
     */
    public function isLocationAvailable($location)
    {
        if (!$this->inventoryConfig->getManageStock()) {
            return true;
        }
        $sourceCode = $location->getSourceCode();
        if ($sourceCode) {
            foreach ($this->helper->getSourceItemsForCurrentQuote() as $sourceItem) {
                if ($sourceItem->getSourceCode() == $sourceCode) {
                    foreach ($this->helper->getQuote()->getAllItems() as $item) {
                        if ($sourceItem->getSku() == $item->getSku() &&
                            $sourceItem->getQuantity() < $item->getQty()) {
                            return false;
                        }
                    }
                }
            }
        }

        return true;
    }

    /**
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getWorkingHoursHtml($location)
    {
        $block = $this->getLayout()->createBlock(\MageWorx\StoreLocator\Block\LocationInfo\WorkingHours::class)
                      ->setTemplate('MageWorx_StoreLocator::location_info/working_hours.phtml');
        $block->setLocation($location);

        return $block->toHtml();
    }

    /**
     * @param string $code
     * @return false|string
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
}

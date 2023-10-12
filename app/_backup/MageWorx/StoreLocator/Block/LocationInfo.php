<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreLocator\Block;

use Magento\CatalogInventory\Model\Configuration;
use Magento\Framework\View\Element\Template;
use MageWorx\StoreLocator\Helper\Data;
use Magento\Store\Model\StoreManagerInterface;

class LocationInfo extends Template
{
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
     * LocationInfo constructor.
     *
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param StoreManagerInterface $storeManager
     * @param Data $helper
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Configuration $inventoryConfig,
        \Magento\Framework\Module\Manager $moduleManager,
        StoreManagerInterface $storeManager,
        Data $helper,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->inventoryConfig = $inventoryConfig;
        $this->moduleManager   = $moduleManager;
        $this->helper          = $helper;
        $this->storeManager    = $storeManager;
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
        return $this->helper->getChildForScale($this->helper->getDefaultScale());
    }

    /**
     * @return string
     */
    public function getParentScale()
    {
        return $this->helper->getDefaultScale();
    }

    /**
     * @param string $image
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLocationImageUrl($image)
    {
        if (!$image) {
            return false;
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
            ", ". $location->getCity() . ", " . $region . ", " . $location->getCountry();
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
     * @param string $code
     * @return string|string[]|null
     */
    public function prepareCode($code)
    {
        return $this->helper->prepareCode($code);
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
}

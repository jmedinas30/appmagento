<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreLocator\Block;

use Magento\Framework\View\Element\Template;
use MageWorx\StoreLocator\Helper\Data;
use MageWorx\StoreLocator\Model\Source\Scale;

class Filter extends Template
{
    /**
     * @var Scale
     */
    protected $scale;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * Filter constructor.
     *
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param Scale $scale
     * @param Data $helper
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Module\Manager $moduleManager,
        Scale $scale,
        Data $helper,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper        = $helper;
        $this->scale         = $scale;
        $this->moduleManager = $moduleManager;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCurrentPlace()
    {
        if ($this->getPlace()) {
            return $this->getPlace();
        }

        if ($this->helper->getPlaceByGeoIp()) {
            $this->setPlace($this->helper->getPlaceByGeoIp());
        } else {
            $this->setPlace($this->helper->getDefaultPlace());
        }

        return $this->getPlace();
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
     * @param int|null $id
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getFilterList($id = null)
    {
        return $this->helper->getPlacesListByScale($this->getLocations(), $this->getFilterScale(), $id);
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getParentFilterList()
    {
        return $this->helper->getPlacesListByScale($this->getLocations(), $this->getParentScale());
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getParentScale()
    {
        return $this->helper->getParentForScale($this->getFilterScale());
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

        return $this->helper->getChildForScale($this->helper->getDefaultScale());
    }

    /**
     * @return bool
     */
    public function isShowMap()
    {
        return $this->helper->isShowMap();
    }

    /**
     * @param string $place
     * @return bool
     */
    public function isPlaceAvailable($place)
    {
        foreach ($this->getParentFilterList() as $filter) {
            if ($filter == $place) {
                return true;
            }
        }

        return false;
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
     * @param string $code
     * @return string|string[]|null
     */
    public function prepareCode($code)
    {
        return $this->helper->prepareCode($code);
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
}

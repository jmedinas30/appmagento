<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreLocator\Block;

use Magento\Framework\View\Element\Template;
use MageWorx\StoreLocator\Helper\Data;
use MageWorx\StoreLocator\Model\Source\Scale;

class Map extends Template
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Map constructor.
     *
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param Data $helper
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Data $helper,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper       = $helper;
        $this->storeManager = $storeManager;
    }

    /**
     * Prepare layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->setTemplate('map.phtml');

        return parent::_prepareLayout();
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->helper->getMapApiKey();
    }

    /**
     * @return bool|string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCountriesForAutocomplete()
    {
        if (!count($this->helper->getCountriesForAutocomplete())) {
            return false;
        }

        return $this->helper->getCountriesForAutocomplete();
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
        $block = $this->getLayout()->createBlock(\MageWorx\StoreLocator\Block\LocationInfo::class);
        $block->setData('location', $location);

        $html = $block->toHtml();

        return $html;
    }

    /**
     * Used Google zoom scale, where 1 is all map view
     *
     * @return int
     */
    public function getMapZoom()
    {
        return $this->getMapScale();
    }

    /**
     * @return string
     */
    public function getMapScale()
    {
        if ($this->getScale()) {
            return $this->getScale();
        }

        if (!$this->getDefaultScale()) {
            return '13';
        }

        return $this->helper->getDefaultScale();
    }

    /**
     * @return string
     */
    public function getMapCenter()
    {
        $coordinates = $this->helper->getSearchFiltersFromSession()['autocomplete'];

        if ($coordinates['lat'] || $coordinates['lng']) {
            $lat = $coordinates['lat'];
            $lng = $coordinates['lng'];
        } else {
            return false;
        }

        return '{lat: ' . $lat . ', lng: ' . $lng . '}';
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
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @return string
     */
    public function isLocationCoordinateCorrect($location)
    {
        return !($location->getLatitude() === '0.00000000000000' && $location->getLongitude() === '0.00000000000000');
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMapIcon()
    {
        $configImage = $this->helper->getMapMarker();
        if ($configImage) {
            return $this->storeManager->getStore()->getBaseUrl('media') . 'mageworx/marker_icon/' . $configImage;
        }

        return $this->getViewFileUrl('MageWorx_StoreLocator::images/svg/pin-w-bg.svg');
    }

    /**
     * @return string
     */
    public function getClusterIcon()
    {
        return $this->getViewFileUrl('MageWorx_StoreLocator::images/m');
    }

    /**
     * @return string
     */
    public function getDefaultPlace()
    {
        return $this->helper->getDefaultPlace();
    }

    /**
     * @return string
     */
    public function getDefaultScale(): string
    {
        return $this->helper->getDefaultScale();
    }
}

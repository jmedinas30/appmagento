<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreLocator\Block\Adminhtml\Coordinates;

use Magento\Framework\View\Element\Template;
use MageWorx\StoreLocator\Helper\Data;
use MageWorx\Locations\Helper\Data as HelperLocations;
use MageWorx\StoreLocator\Model\Source\Scale;

class Map extends Template
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var HelperLocations
     */
    protected $helperLocations;

    /**
     * Map constructor.
     *
     * @param HelperLocations $helperLocations
     * @param Data $helper
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        HelperLocations $helperLocations,
        Data $helper,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper          = $helper;
        $this->helperLocations = $helperLocations;
    }

    /**
     * @return string
     */
    public function getMapCenter()
    {
        $coordinates        = [];
        $currentLocation    = $this->helperLocations->getCurrentLocation();
        $coordinates['lng'] = $currentLocation->getLongitude();
        $coordinates['lat'] = $currentLocation->getLatitude();

        if (!$coordinates['lng'] && !$coordinates['lat']) {
            $coordinates = $this->helper->getCoordinatesByGeoIp();
        }

        return $coordinates;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->helper->getMapApiKey();
    }

    /**
     * @return int
     */
    public function getMapZoom()
    {
        $zoom = 1;

        switch ($this->helper->getDefaultScale()) {
            case Scale::WORLD:
                $zoom = 2;
                break;
            case Scale::COUNTRY:
                $zoom = 5;
                break;
            case Scale::REGION:
                $zoom = 8;
                break;
            case Scale::CITY:
                $zoom = 10;
                break;
            case Scale::STORE:
                $zoom = 18;
                break;
        }

        return $zoom;
    }

    /**
     * @return bool
     */
    public function isShowMap()
    {
        $currentLocation = $this->helperLocations->getCurrentLocation();
        if ($currentLocation->getCurrentStoreId() != null) {
            return false;
        }

        return $this->helper->isShowMap();
    }
}

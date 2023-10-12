<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreLocator\Block\Adminhtml\Coordinates;

use Magento\Framework\Exception\NoSuchEntityException;
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
     * @return array
     */
    public function getMapCenter(): array
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
    public function getApiKey(): string
    {
        return (string)$this->helper->getMapApiKey();
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    public function getMapZoom(): string
    {
        return $this->helper->getDefaultScale() ?: '13';
    }

    /**
     * @return bool
     */
    public function isShowMap(): bool
    {
        $currentLocation = $this->helperLocations->getCurrentLocation();
        if ($currentLocation->getCurrentStoreId() != null) {
            return false;
        }

        return $this->helper->isShowMap();
    }
}

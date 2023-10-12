<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreLocator\Block;

/**
 * Class Search
 */
class Search extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \MageWorx\StoreLocator\Helper\Data
     */
    protected $helper;

    /**
     * Search constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \MageWorx\StoreLocator\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \MageWorx\StoreLocator\Helper\Data $helper,
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    /**
     * @return array|bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isSetToUseCurrentLocation()
    {
        return $this->helper->isSetToUseCurrentLocation();
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPredefinedRadiusValues(): array
    {
        return $this->helper->getPredefinedRadiusValues();
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getRadiusUnit(): string
    {
        return $this->helper->getRadiusUnit();
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getRadiusValueFromSession(): ?string
    {
        return $this->helper->getRadiusValueFromSession();
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->helper->getMapApiKey();
    }

    /**
     * @return string
     */
    public function getCurrentPlaceName()
    {
        if (!empty($this->helper->getSearchFiltersFromSession()['city'])) {
            return $this->helper->getSearchFiltersFromSession()['city'];
        }
        if ($this->helper->getPlaceByGeoIp()) {
            return $this->helper->getPlaceByGeoIp();
        }

        return $this->helper->getDefaultPlace();
    }

    /**
     * @return double
     */
    public function getCurrentLat()
    {
        $coordinates = $this->helper->getCoordinatesByGeoIp();

        return $coordinates['lat'];
    }

    /**
     * @return double
     */
    public function getCurrentLng()
    {
        $coordinates = $this->helper->getCoordinatesByGeoIp();

        return $coordinates['lng'];
    }
}

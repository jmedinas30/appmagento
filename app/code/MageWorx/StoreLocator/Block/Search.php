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
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var array
     */
    protected $scales = ['city', 'region', 'country_id'];

    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \MageWorx\StoreLocator\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\View\Element\Template\Context $context,
        \MageWorx\StoreLocator\Helper\Data $helper,
        array $data = []
    ) {
        $this->helper       = $helper;
        $this->storeManager = $storeManager;
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
    public function getCurrentRadiusValue(): ?string
    {
        return $this->helper->getRadiusValueFromSession() ?? $this->helper->getDefaultRadiusValue();
    }

    /**
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getDefaultRadiusValue(): ?string
    {
        return $this->helper->getDefaultRadiusValue();
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
     * @return string
     */
    public function getDefaultPlace()
    {
        if ($this->helper->getDefaultMapView() == \MageWorx\Locations\Model\Source\DefaultMapView::DEFAULT_LOCATION) {
            return $this->helper->getDefaultPlace();
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
        if (!empty($this->helper->getSearchFiltersFromSession()['autocomplete']['lat'])) {
            return $this->helper->getSearchFiltersFromSession()['autocomplete']['lat'];
        }

        $coordinates = $this->helper->getCoordinatesByGeoIp();

        return $coordinates['lat'];
    }

    /**
     * @return double
     */
    public function getCurrentLng()
    {
        if (!empty($this->helper->getSearchFiltersFromSession()['autocomplete']['lng'])) {
            return $this->helper->getSearchFiltersFromSession()['autocomplete']['lng'];
        }

        $coordinates = $this->helper->getCoordinatesByGeoIp();

        return $coordinates['lng'];
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrentSmallCity()
    {
        if (!empty($this->helper->getSearchFiltersFromSession()['autocomplete']['small_city'])) {
            return $this->helper->getSearchFiltersFromSession()['autocomplete']['small_city'];
        }

        return '';
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrentCity()
    {
        if (!empty($this->helper->getSearchFiltersFromSession()['autocomplete']['city'])) {
            return $this->helper->getSearchFiltersFromSession()['autocomplete']['city'];
        }

        return '';
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrentRegion()
    {
        if (!empty($this->helper->getSearchFiltersFromSession()['autocomplete']['region'])) {
            return $this->helper->getSearchFiltersFromSession()['autocomplete']['region'];
        }

        return '';
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrentPostCode()
    {
        if (!empty($this->helper->getSearchFiltersFromSession()['autocomplete']['postcode'])) {
            return $this->helper->getSearchFiltersFromSession()['autocomplete']['postcode'];
        }

        return '';
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrentCountryId()
    {
        if (!empty($this->helper->getSearchFiltersFromSession()['autocomplete']['country_id'])) {
            return $this->helper->getSearchFiltersFromSession()['autocomplete']['country_id'];
        }

        return '';
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMapIcon() : string
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
}

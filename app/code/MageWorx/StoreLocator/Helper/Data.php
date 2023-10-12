<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

/**
 * Location data helper
 *
 */
declare(strict_types=1);

namespace MageWorx\StoreLocator\Helper;

use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use MageWorx\GeoIP\Model\Geoip;
use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\Locations\Api\LocationRepositoryInterface;
use MageWorx\Locations\Model\MsiResolver\GetSourceItemsBySku;
use MageWorx\Locations\Model\Source\Country as CountryOptions;
use MageWorx\StoreLocator\Model\Source\Layout;
use MageWorx\StoreLocator\Model\Source\Scale;

/**
 * Location data helper
 *
 * @package MageWorx\StoreLocator\Helper
 */
class Data extends AbstractHelper
{
    const LOCATOR_RADIUS = 'mw_store_locator_radius';

    /**
     * XML config path default scale
     */
    const XML_PATH_DEFAULT_SCALE = 'mageworx_locations/general/default_scale';

    /**
     * XML config path default country
     */
    const XML_PATH_DEFAULT_COUNTRY = 'mageworx_locations/general/default_country';

    /**
     * XML config path default region
     */
    const XML_PATH_DEFAULT_REGION = 'mageworx_locations/general/default_region';

    /**
     * XML config path default city
     */
    const XML_PATH_DEFAULT_CITY = 'mageworx_locations/general/default_city';

    /**
     * XML config path default city
     */
    const XML_PATH_DEFAULT_LNG = 'mageworx_locations/general/default_lng';

    /**
     * XML config path default city
     */
    const XML_PATH_DEFAULT_LAT = 'mageworx_locations/general/default_lat';

    /**
     * XML config path default map view
     */
    const XML_PATH_DEFAULT_MAP_VIEW = 'mageworx_locations/general/default_map_view';

    /**
     * XML config path default scale
     */
    const XML_PATH_FILTER_BY = 'mageworx_locations/filter/filter_by';

    /**
     * XML config path enable find store
     */
    const XML_PATH_SHOW_LOCATION_ON_PRODUCT = 'mageworx_locations/product_page/show_locations_on_product';

    /**
     * XML config path stores display mode
     */
    const XML_PATH_STORES_DISPLAY_MODE_ON_PRODUCT = 'mageworx_locations/product_page/stores_display_mode';

    /**
     * XML config path qty message
     */
    const XML_PATH_QTY_MESSAGE_ON_PRODUCT = 'mageworx_locations/product_page/qty_message';

    /**
     * XML config path display stock status
     */
    const XML_PATH_DISPLAY_STOCK_STATUS = 'mageworx_locations/product_page/display_stock_status';

    /**
     * XML config path default stores amount
     */
    const XML_PATH_DEFAULT_STORES_AMOUNT_ON_PRODUCT = 'mageworx_locations/product_page/default_stores_amount';

    /**
     * XML config path image
     */
    const XML_PATH_LOCATION_ON_PRODUCT_IMAGE = 'mageworx_locations/product_page/image';

    /**
     * XML config path for find store text
     */
    const XML_PATH_LOCATION_ON_PRODUCT_TEXT = 'mageworx_locations/product_page/locations_on_product_text';

    /**
     * XML config path for find store text
     */
    const XML_PATH_LOCATIONS_ON_PRODUCT_SELECT_OPTION_TEXT
        = 'mageworx_locations/product_page/locations_on_product_select_option_text';

    /**
     * XML config path for find store text
     */
    const XML_PATH_LOCATION_ON_PRODUCT_NOT_AVAILABLE_TEXT =
        'mageworx_locations/product_page/locations_on_product_not_available_text';

    /**
     * XML config path enable find store
     */
    const XML_PATH_SHOW_LOCATIONS_NAME = 'mageworx_locations/product_page/show_locations_name';

    /**
     * XML config path for popup layout
     */
    const XML_PATH_POPUP_LAYOUT = 'mageworx_locations/product_page/popup_layout';

    /**
     * XML config path for link enable
     */
    const XML_PATH_SHOW_LINK = 'mageworx_locations/link_settings/show_link';

    /**
     * XML config path for link titlet
     */
    const XML_PATH_LINK_TITLE = 'mageworx_locations/link_settings/link_title';

    /**
     * XML config path for link url
     */
    const XML_PATH_LINK_URL = 'mageworx_locations/link_settings/link_url';

    /**
     * XML config path for page layout
     */
    const XML_PATH_PAGE_LAYOUT = 'mageworx_locations/link_settings/page_layout';

    /**
     * XML config path for checkout layout
     */
    const XML_PATH_CHECKOUT_LAYOUT = 'mageworx_locations/checkout/layout';

    /**
     * XML config order ouf of stock
     */
    const XML_PATH_ORDER_OUT_OF_STOCK = 'mageworx_locations/checkout/order_out_of_stock';

    /**
     * XML config path enable map
     */
    const XML_PATH_SHOW_MAP = 'mageworx_locations/map/show_map';

    /**
     * XML config path marker icon
     */
    const XML_PATH_MAP_MARKER = 'mageworx_locations/map/marker_icon';

    /**
     * XML config radius values for filter
     */
    const XML_PATH_FILTER_USE_CURRENT_LOCATION = 'mageworx_locations/filter/use_current_location';

    /**
     * XML config radius values for filter
     */
    const XML_PATH_FILTER_RADIUS_VALUES = 'mageworx_locations/filter/radius_values';

    /**
     * XML config radius unit for filter
     */
    const XML_PATH_FILTER_RADIUS_UNIT = 'mageworx_locations/filter/radius_unit';

    /**
     * XML config default radius value for filter
     */
    const XML_PATH_FILTER_DEFAULT_RADIUS_VALUE = 'mageworx_locations/filter/default_radius_value';

    const XML_PATH_DEFAULT_COUNTRY_CODE = 'general/country/default';

    /**
     * Scale for filter
     */
    const SCALE_COUNTRY = 1;
    const SCALE_REGION  = 2;
    const SCALE_CITY    = 3;

    /**
     * Core registry
     *
     * @var \MageWorx\Locations\Model\Registry
     */
    protected $registry;

    /**
     * @var Geoip
     */
    protected $geoIp;

    /**
     * @var GetSourceItemsBySku
     */
    protected $getSourceItemsBySku;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Magento\Backend\Model\Session\Quote
     */
    protected $backendSession;

    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    /**
     * @var \MageWorx\GoogleApi\Helper\Data
     */
    protected $helperGoogleApi;

    /**
     * Customer session
     *
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var CountryOptions
     */
    protected $countryOptions;

    /**
     * Data constructor.
     *
     * @param \Magento\Customer\Model\Session $customerSession
     * @param GetSourceItemsBySku $getSourceItemsBySku
     * @param StoreManagerInterface $storeManager
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Backend\Model\Session\Quote $backendSession
     * @param LocationRepositoryInterface $locationRepository
     * @param Geoip $geoIp
     * @param \MageWorx\GoogleApi\Helper\Data $helperGoogleApi
     * @param \MageWorx\Locations\Model\Registry $registry
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\App\State $state
     * @param CountryOptions $countryOptions
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Customer\Model\Session       $customerSession,
        GetSourceItemsBySku                   $getSourceItemsBySku,
        StoreManagerInterface                 $storeManager,
        \Magento\Checkout\Model\Session       $checkoutSession,
        \Magento\Backend\Model\Session\Quote  $backendSession,
        LocationRepositoryInterface           $locationRepository,
        Geoip                                 $geoIp,
        \MageWorx\GoogleApi\Helper\Data       $helperGoogleApi,
        \MageWorx\Locations\Model\Registry    $registry,
        \Magento\Framework\Registry           $coreRegistry,
        \Magento\Framework\App\State          $state,
        CountryOptions                        $countryOptions,
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
        $this->customerSession     = $customerSession;
        $this->getSourceItemsBySku = $getSourceItemsBySku;
        $this->storeManager        = $storeManager;
        $this->checkoutSession     = $checkoutSession;
        $this->backendSession      = $backendSession;
        $this->locationRepository  = $locationRepository;
        $this->helperGoogleApi     = $helperGoogleApi;
        $this->geoIp               = $geoIp;
        $this->registry            = $registry;
        $this->state               = $state;
        $this->coreRegistry        = $coreRegistry;
        $this->countryOptions      = $countryOptions;
    }

    /**
     * @return \Magento\Catalog\Api\Data\ProductInterface|null
     */
    public function getCurrentProduct()
    {
        return $this->coreRegistry->registry('current_product');
    }

    /**
     * @param int|null $storeId
     * @return bool
     * @throws NoSuchEntityException
     */
    public function isLocationOnProductEnabled(?int $storeId = 0): bool
    {
        $storeId = $storeId ?: $this->getStoreId();

        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SHOW_LOCATION_ON_PRODUCT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return bool
     * @throws NoSuchEntityException
     */
    public function isLocationsNamesEnabled(?int $storeId = 0): bool
    {
        $storeId = $storeId ?: $this->getStoreId();

        return (bool)$this->scopeConfig->isSetFlag(
            self::XML_PATH_SHOW_LOCATIONS_NAME,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return string
     * @throws NoSuchEntityException
     */
    public function getLocationOnProductText(?int $storeId = 0): string
    {
        $storeId = $storeId ?: $this->getStoreId();

        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_LOCATION_ON_PRODUCT_TEXT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return string
     * @throws NoSuchEntityException
     */
    public function getQtyMessageOnProductText(?int $storeId = 0): string
    {
        $storeId = $storeId ?: $this->getStoreId();

        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_QTY_MESSAGE_ON_PRODUCT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return string
     * @throws NoSuchEntityException
     */
    public function getSelectOptionText(?int $storeId = 0): string
    {
        $storeId = $storeId ?: $this->getStoreId();

        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_LOCATIONS_ON_PRODUCT_SELECT_OPTION_TEXT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return string
     * @throws NoSuchEntityException
     */
    public function getLocationOnProductNotAvailableText(?int $storeId = 0): string
    {
        $storeId = $storeId ?: $this->getStoreId();

        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_LOCATION_ON_PRODUCT_NOT_AVAILABLE_TEXT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return string
     * @throws NoSuchEntityException
     */
    public function getStoresDisplayModeOnProduct(?int $storeId = 0): string
    {
        $storeId = $storeId ?: $this->getStoreId();

        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_STORES_DISPLAY_MODE_ON_PRODUCT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return int
     * @throws NoSuchEntityException
     */
    public function getDisplayStockStatus(?int $storeId = 0): int
    {
        $storeId = $storeId ?: $this->getStoreId();

        return (int)$this->scopeConfig->getValue(
            self::XML_PATH_DISPLAY_STOCK_STATUS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return string
     * @throws NoSuchEntityException
     */
    public function getDefaultStoresAmountOnProduct(?int $storeId = 0): string
    {
        $storeId = $storeId ?: $this->getStoreId();

        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_DEFAULT_STORES_AMOUNT_ON_PRODUCT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getLocationOnProductImage(?int $storeId = 0): ?string
    {
        $storeId = $storeId ?: $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_LOCATION_ON_PRODUCT_IMAGE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return bool|null
     * @throws NoSuchEntityException
     */
    public function isShowLink(?int $storeId = 0): ?bool
    {
        $storeId = $storeId ?: $this->getStoreId();

        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SHOW_LINK,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getLinkTitle(?int $storeId = 0): ?string
    {
        $storeId = $storeId ?: $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_LINK_TITLE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param string $storeId
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getDefaultLatitude(?int $storeId = null): string
    {
        $storeId = isset($storeId) ? $storeId : $this->getStoreId();

        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_DEFAULT_LAT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return string
     * @throws NoSuchEntityException
     */
    public function getDefaultLongitude(?int $storeId = null): string
    {
        $storeId = isset($storeId) ? $storeId : $this->getStoreId();

        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_DEFAULT_LNG,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getLinkUrl(?int $storeId = 0): ?string
    {
        $storeId = $storeId ?: $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_LINK_URL,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getLocationsPageLayout(?int $storeId = 0): ?string
    {
        $storeId = $storeId ?: $this->getStoreId();
        $layout  = $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_LAYOUT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        return $this->isShowMap() ? $layout : Layout::LIST_WITHOUT_MAP;
    }

    /**
     * @param int|null $storeId
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getCheckoutLayout(?int $storeId = 0): ?string
    {
        $storeId = $storeId ?: $this->getStoreId();

        $layout = $this->scopeConfig->getValue(
            self::XML_PATH_CHECKOUT_LAYOUT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        return $this->isShowMap() ? $layout : Layout::LIST_WITHOUT_MAP;
    }

    /**
     * @param int|null $storeId
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getPopupLayout(?int $storeId = 0): ?string
    {
        $storeId = $storeId ?: $this->getStoreId();

        $layout = $this->scopeConfig->getValue(
            self::XML_PATH_POPUP_LAYOUT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        return $this->isShowMap() ? $layout : Layout::LIST_WITHOUT_MAP;
    }

    /**
     * @param int|null $storeId
     * @return bool|null
     * @throws NoSuchEntityException
     */
    public function isMapEnabled(?int $storeId = 0): ?bool
    {
        $storeId = $storeId ?: $this->getStoreId();

        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SHOW_MAP,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return bool|null
     * @throws NoSuchEntityException
     */
    public function canShowMap(?int $storeId = 0): ?bool
    {
        return $this->isMapEnabled($storeId) || !$this->getMapApiKey($storeId);
    }

    /**
     * @param int|null $storeId
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getMapMarker(?int $storeId = 0): ?string
    {
        $storeId = $storeId ?: $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_MAP_MARKER,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return string
     * @throws NoSuchEntityException
     */
    public function getDefaultScale(?int $storeId = null): string
    {
        $storeId = isset($storeId) ? $storeId : $this->getStoreId();

        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_DEFAULT_SCALE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return bool
     * @throws NoSuchEntityException
     */
    public function isPossibleToOrderOutOfStockItem(?int $storeId = 0): bool
    {
        $storeId = $storeId ?: $this->getStoreId();

        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ORDER_OUT_OF_STOCK,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return int
     * @throws NoSuchEntityException
     */
    public function getDefaultMapView(?int $storeId = 0): int
    {
        $storeId = $storeId ?: $this->getStoreId();

        return (int)$this->scopeConfig->getValue(
            self::XML_PATH_DEFAULT_MAP_VIEW,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getFilterBy(?int $storeId = 0): ?string
    {
        $storeId = $storeId ?: $this->getStoreId();

        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_FILTER_BY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return string
     * @throws NoSuchEntityException
     */
    public function getDefaultPlace(?int $storeId = null): string
    {
        $storeId = isset($storeId) ? $storeId : $this->getStoreId();

        $result = $this->scopeConfig->getValue(
            self::XML_PATH_DEFAULT_COUNTRY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        $countries = $this->countryOptions->toArray();
        $result    = $countries[$result] ?? $result;

        $result .= ', ' . $this->scopeConfig->getValue(
                self::XML_PATH_DEFAULT_REGION,
                ScopeInterface::SCOPE_STORE,
                $storeId
            );

        $result .= ', ' . $this->scopeConfig->getValue(
                self::XML_PATH_DEFAULT_CITY,
                ScopeInterface::SCOPE_STORE,
                $storeId
            );

        return $result;
    }

    /**
     * @param int|null $storeId
     * @return string
     */
    public function getDefaultCity(?int $storeId = null): string
    {
        $storeId = isset($storeId) ? $storeId : $this->getStoreId();

        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_DEFAULT_CITY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return string
     */
    public function getDefaultRegion(?int $storeId = null): string
    {
        $storeId = isset($storeId) ? $storeId : $this->getStoreId();

        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_DEFAULT_REGION,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return string
     */
    public function getDefaultCountry(?int $storeId = null): string
    {
        $storeId = isset($storeId) ? $storeId : $this->getStoreId();

        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_DEFAULT_COUNTRY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param string $scale
     * @return string
     */
    public function getChildForScale(string $scale): string
    {
        $result = '';
        switch ($scale) {
            case Scale::WORLD:
                $result = Scale::COUNTRY;
                break;
            case Scale::COUNTRY:
                $result = Scale::REGION;
                break;
            case Scale::REGION:
                $result = Scale::CITY;
                break;
            case Scale::CITY:
                $result = Scale::STORE;
                break;
        }

        return $result;
    }

    /**
     * @param string $scale
     * @return string
     */
    public function getParentForScale(string $scale): string
    {
        $result = '';
        switch ($scale) {
            case Scale::WORLD:
                $result = '';
                break;
            case Scale::COUNTRY:
                $result = Scale::WORLD;
                break;
            case Scale::REGION:
                $result = Scale::COUNTRY;
                break;
            case Scale::CITY:
                $result = Scale::REGION;
                break;
            case Scale::STORE:
                $result = Scale::CITY;
                break;
        }

        return $result;
    }

    /**
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @param string $scale
     * @return string
     */
    public function getLocationPlaceByScale(
        \MageWorx\Locations\Api\Data\LocationInterface $location,
        string                                         $scale
    ): string {
        $checkName = false;
        switch ($scale) {
            case Scale::WORLD:
            case Scale::COUNTRY:
                $place = $location->getCountry();
                break;
            case Scale::REGION:
                $place = $location->getRegion();
                break;
            case Scale::CITY:
                $place = $location->getCity();
                break;
            case Scale::STORE:
                $place = $location->getName();
                break;
            default:
                $checkName = true;
                $place     = $location->getName();
                break;
        }

        if (!$place && !$checkName) {
            $place = $this->getLocationPlaceByScale($location, $scale);
        }

        return $place;
    }

    /**
     * @param \MageWorx\Locations\Model\ResourceModel\Location\Collection $locations
     * @param string $scale
     * @param null $filter
     * @return array
     */
    public function getPlacesListByScale($locations, $scale, $filter = null): array
    {
        $places = [];
        /** @var \MageWorx\Locations\Api\Data\LocationInterface $location */
        foreach ($locations as $location) {
            if ($filter !== null) {
                if ($this->getLocationPlaceIdByScale($location, $scale) != $filter) {
                    continue;
                }
            }

            $placeId          = $this->getLocationPlaceIdByScale($location, $scale);
            $places[$placeId] = $this->getLocationPlaceByScale($location, $scale);
        }

        return $places;
    }

    /**
     * @param LocationInterface $location
     * @param string $scale
     * @return string
     */
    public function getLocationPlaceIdByScale(LocationInterface $location, string $scale): string
    {
        switch ($scale) {
            case Scale::REGION:
                $scaleId = self::SCALE_REGION;
                break;
            case Scale::STORE:
            case Scale::CITY:
                $scaleId = self::SCALE_CITY;
                break;
            case Scale::WORLD:
            case Scale::COUNTRY:
                $scaleId = self::SCALE_COUNTRY;
                break;
            default:
                $scaleId = self::SCALE_CITY;
        }

        $placeIds = explode('/', $location->getLocationPagePath());

        return $placeIds[$scaleId] ?? '0';
    }

    /**
     * @return bool
     */
    public function isShowMap(): bool
    {
        return $this->isMapEnabled() && $this->getMapApiKey();
    }

    /**
     * @return string
     */
    public function getPlaceByGeoIp(): string
    {
        $result          = '';
        $currentLocation = $this->geoIp->getCurrentLocation();
        switch ($this->getFilterBy()) {
            case Scale::COUNTRY:
                $result = $currentLocation->getCountry();
                break;
            case Scale::REGION:
                $result = $currentLocation->getRegion();
                $result = $result ?? $currentLocation->getCountry();
                break;
            case Scale::CITY:
                $result = $currentLocation->getCity();
                $result = $result ?? $currentLocation->getRegion();
                $result = $result ?? $currentLocation->getCountry();
                break;
        }

        return $result ?? '';
    }

    /**
     * @return array
     */
    public function getCoordinatesByGeoIp(): array
    {
        $currentLocation = $this->geoIp->getCurrentLocation();
        $result['lat']   = $currentLocation->getLatitude() ?: '0';
        $result['lng']   = $currentLocation->getLongitude() ?: '0';

        return $result;
    }

    /**
     * @param array $filters
     * @return string[]
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws NoSuchEntityException
     */
    public function getLocationsForCurrentQuote($filters = [])
    {
        $ids = [];
        foreach ($this->getQuote()->getAllItems() as $item) {
            if ($item->getProductType() !== Configurable::TYPE_CODE) {
                $ids[] = $item->getProductId();
            }
        }

        $availableLocations = $this->locationRepository->getListLocationByProductIds(
            array_unique($ids),
            null,
            $this->isPossibleToOrderOutOfStockItem(),
            $filters
        );

        return $availableLocations;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws NoSuchEntityException
     */
    public function getSourceItemsForCurrentQuote(): array
    {
        $skus        = [];
        $sourceItems = [];
        foreach ($this->getQuote()->getAllItems() as $item) {
            if ($item->getProductType() !== Configurable::TYPE_CODE) {
                $skus[] = $item->getSku();
            }
        }
        // load source items only if MSI enabled
        $getSourceItemsBySkuInstance = $this->getSourceItemsBySku->getInstance();
        if ($getSourceItemsBySkuInstance) {
            foreach ($skus as $sku) {
                $sourceItems = array_merge(
                    $sourceItems,
                    $getSourceItemsBySkuInstance->execute((string)$sku)
                );
            }
        }

        return $sourceItems;
    }

    /**
     * @return \Magento\Quote\Model\Quote
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws NoSuchEntityException
     */
    public function getQuote(): \Magento\Quote\Model\Quote
    {
        $session = $this->state->getAreaCode() == 'adminhtml' ? $this->backendSession : $this->checkoutSession;

        return $session->getQuote();
    }

    /**
     * @return int
     * @throws NoSuchEntityException
     */
    public function getStoreId(): int
    {
        return (int)$this->storeManager->getStore()->getId();
    }

    /**
     * @param int|null $storeId
     * @return bool
     * @throws NoSuchEntityException
     */
    public function isSetToUseCurrentLocation(?int $storeId = 0): bool
    {
        $storeId = $storeId ?: $this->getStoreId();

        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_FILTER_USE_CURRENT_LOCATION,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return array
     * @throws NoSuchEntityException
     */
    public function getPredefinedRadiusValues(?int $storeId = 0): array
    {
        $storeId = $storeId ?: $this->getStoreId();

        return explode(
            ',',
            $this->scopeConfig->getValue(
                self::XML_PATH_FILTER_RADIUS_VALUES,
                ScopeInterface::SCOPE_STORE,
                $storeId
            )
        );
    }

    /**
     * @param int|null $storeId
     * @return string
     * @throws NoSuchEntityException
     */
    public function getRadiusUnit(?int $storeId = 0): string
    {
        $storeId = $storeId ?: $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_FILTER_RADIUS_UNIT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getDefaultRadiusValue(?int $storeId = 0): ?string
    {
        $storeId = $storeId ?: $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_FILTER_DEFAULT_RADIUS_VALUE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getRadiusValueFromSession(): ?string
    {
        if ($this->getSearchFiltersFromSession()) {
            return $this->getSearchFiltersFromSession()['radius'] ?? $this->getDefaultRadiusValue();
        }

        return $this->getDefaultRadiusValue();
    }

    /**
     * @return array
     * @throws NoSuchEntityException
     */
    public function getSearchFilters(): array
    {
        $filters = [];

        $geoIpCoord = $this->getCoordinatesByGeoIp();
        if ($this->getDefaultMapView() == \MageWorx\Locations\Model\Source\DefaultMapView::DEFAULT_LOCATION) {
            $filters[$this->getFilterBy()] = $this->getDefaultPlace();
            $filters['autocomplete']       = $this->getDefaultPlaceCoordinates();
        } else {
            $filters['autocomplete'] = $geoIpCoord;
        }

        if (empty($filters['autocomplete']['lat']) && empty($filters['autocomplete']['lng'])) {
            //search by default place if can't load GeoIP DB
            $filters[$this->getFilterBy()] = $this->getDefaultPlace();
            $filters['autocomplete']       = $this->getDefaultPlaceCoordinates();
        }

        if ($this->registry->registry(self::LOCATOR_RADIUS)) {
            $filters['radius'] = $this->registry->registry(self::LOCATOR_RADIUS);
        } else {
            $filters['radius'] = $this->getDefaultRadiusValue();
        }
        $filters['unit'] = $this->getRadiusUnit();

        $this->customerSession->setData(LocationRepositoryInterface::LOCATOR_COORDINATES, $filters);

        return $filters;
    }

    /**
     * @return array|null
     * @throws NoSuchEntityException
     */
    public function getSearchFiltersFromSession(): ?array
    {
        if ($this->customerSession->getData(LocationRepositoryInterface::LOCATOR_COORDINATES)) {
            return $this->customerSession->getData(LocationRepositoryInterface::LOCATOR_COORDINATES);
        }

        return $this->getSearchFilters();
    }

    /**
     * @param int|null $storeId
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getMapApiKey(?int $storeId = 0): ?string
    {
        return $this->helperGoogleApi->getMapApiKey($storeId);
    }

    /**
     * @param int|null $storeId
     * @return array
     * @throws NoSuchEntityException
     */
    public function getCountriesForAutocomplete(?int $storeId = 0): array
    {
        return $this->helperGoogleApi->getCountriesForAutocomplete($storeId);
    }

    /**
     * @return array
     */
    public function getDefaultPlaceCoordinates(): array
    {
        $defaultPlaceCoordinates['lat'] = $this->getDefaultLatitude();
        $defaultPlaceCoordinates['lng'] = $this->getDefaultLongitude();

        return $defaultPlaceCoordinates;
    }

    /**
     * @param string $currentPage
     * @return bool
     * @throws NoSuchEntityException
     */
    public function skipRadiusFilter(string $currentPage): bool
    {
        switch ($currentPage) {
            case 'mageworx_store_locator_location_updatemainpage':
            case 'cms_page_view':
                $skip = $this->getLocationsPageLayout(
                    ) === \MageWorx\StoreLocator\Model\Source\Layout::LIST_WITHOUT_MAP;
                break;
            case 'checkout_index_index':
                $skip = $this->getCheckoutLayout() === \MageWorx\StoreLocator\Model\Source\Layout::LIST_WITHOUT_MAP;
                break;
            case 'mageworx_store_locator_location_updatepopupcontent':
            case 'catalog_product_view':
            default:
                $skip = $this->getPopupLayout() === \MageWorx\StoreLocator\Model\Source\Layout::LIST_WITHOUT_MAP;
                break;
        }

        return $skip;
    }
}

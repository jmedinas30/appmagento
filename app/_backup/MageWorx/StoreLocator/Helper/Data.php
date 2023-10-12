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

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use MageWorx\Locations\Model\MsiResolver\GetSourceItemsBySku;
use MageWorx\StoreLocator\Model\Source\Layout;
use MageWorx\StoreLocator\Model\Source\Scale;
use MageWorx\GeoIP\Model\Geoip;
use MageWorx\Locations\Api\LocationRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use MageWorx\Locations\Model\Source\Country as CountryOptions;

/**
 * Location data helper
 *
 * @package MageWorx\StoreLocator\Helper
 */
class Data extends AbstractHelper
{
    const LOCATOR_RADIUS = 'mw_store_locator_radius';

    const LOCATOR_SEARCH_TEXT = 'mw_store_locator_search_text';

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
        \Magento\Customer\Model\Session $customerSession,
        GetSourceItemsBySku $getSourceItemsBySku,
        StoreManagerInterface $storeManager,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Backend\Model\Session\Quote $backendSession,
        LocationRepositoryInterface $locationRepository,
        Geoip $geoIp,
        \MageWorx\GoogleApi\Helper\Data $helperGoogleApi,
        \MageWorx\Locations\Model\Registry $registry,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\State $state,
        CountryOptions $countryOptions,
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
     * @param int $storeId
     * @return bool|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isLocationOnProductEnabled($storeId = 0): ?bool
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SHOW_LOCATION_ON_PRODUCT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return bool|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isLocationsNamesEnabled($storeId = 0): ?bool
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SHOW_LOCATIONS_NAME,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLocationOnProductText($storeId = 0): string
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_LOCATION_ON_PRODUCT_TEXT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getQtyMessageOnProductText($storeId = 0): string
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_QTY_MESSAGE_ON_PRODUCT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getSelectOptionText($storeId = 0): string
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_LOCATIONS_ON_PRODUCT_SELECT_OPTION_TEXT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLocationOnProductNotAvailableText($storeId = 0): string
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_LOCATION_ON_PRODUCT_NOT_AVAILABLE_TEXT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStoresDisplayModeOnProduct($storeId = 0): string
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_STORES_DISPLAY_MODE_ON_PRODUCT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return int|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getDisplayStockStatus(int $storeId = 0): ?int
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return (int)$this->scopeConfig->getValue(
            self::XML_PATH_DISPLAY_STOCK_STATUS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getDefaultStoresAmountOnProduct($storeId = 0): string
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_DEFAULT_STORES_AMOUNT_ON_PRODUCT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLocationOnProductImage($storeId = 0): ?string
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_LOCATION_ON_PRODUCT_IMAGE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return bool|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isShowLink($storeId = 0): ?bool
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SHOW_LINK,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLinkTitle($storeId = 0): ?string
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_LINK_TITLE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLinkUrl($storeId = 0): ?string
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_LINK_URL,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLocationsPageLayout($storeId = 0): ?string
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();
        $layout  = $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_LAYOUT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        return $this->isShowMap() ? $layout : Layout::LIST_WITHOUT_MAP;
    }

    /**
     * @param int $storeId
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCheckoutLayout($storeId = 0): ?string
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        $layout = $this->scopeConfig->getValue(
            self::XML_PATH_CHECKOUT_LAYOUT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        return $this->isShowMap() ? $layout : Layout::LIST_WITHOUT_MAP;
    }

    /**
     * @param int $storeId
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPopupLayout($storeId = 0): ?string
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        $layout = $this->scopeConfig->getValue(
            self::XML_PATH_POPUP_LAYOUT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        return $this->isShowMap() ? $layout : Layout::LIST_WITHOUT_MAP;
    }

    /**
     * @param int $storeId
     * @return bool|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isMapEnabled($storeId = 0): ?bool
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SHOW_MAP,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return bool|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function canShowMap($storeId = 0): ?bool
    {
        return $this->isMapEnabled($storeId) || !$this->getMapApiKey($storeId);
    }

    /**
     * @param int $storeId
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMapMarker($storeId = 0): ?string
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_MAP_MARKER,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getDefaultScale($storeId = 0): ?string
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_DEFAULT_SCALE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isPossibleToOrderOutOfStockItem($storeId = 0): bool
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ORDER_OUT_OF_STOCK,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string
     */
    public function getDefaultPlace($storeId = 0)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        $result = '';
        switch ($this->getDefaultScale($storeId)) {
            case Scale::COUNTRY:
                $result = $this->scopeConfig->getValue(
                    self::XML_PATH_DEFAULT_COUNTRY,
                    ScopeInterface::SCOPE_STORE,
                    $storeId
                );

                $countries = $this->countryOptions->toArray();
                $result    = isset($countries[$result]) ? $countries[$result] : $result;

                break;
            case Scale::REGION:
                $result = $this->scopeConfig->getValue(
                    self::XML_PATH_DEFAULT_REGION,
                    ScopeInterface::SCOPE_STORE,
                    $storeId
                );
                break;
            case Scale::CITY:
                $result = $this->scopeConfig->getValue(
                    self::XML_PATH_DEFAULT_CITY,
                    ScopeInterface::SCOPE_STORE,
                    $storeId
                );
                break;
        }

        return $result;
    }

    /**
     * @param string $scale
     * @return string
     */
    public function getChildForScale($scale)
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
    public function getParentForScale($scale)
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
    public function getLocationPlaceByScale($location, $scale)
    {
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
            $place = $this->getLocationPlaceByScale($location, $this->getChildForScale($scale));
        }

        return $place;
    }

    /**
     * @param \MageWorx\Locations\Model\ResourceModel\Location\Collection $locations
     * @param string $scale
     * @param null $filter
     * @return array
     */
    public function getPlacesListByScale($locations, $scale, $filter = null)
    {
        $places = [];
        /** @var \MageWorx\Locations\Api\Data\LocationInterface $location */
        foreach ($locations as $location) {
            if ($filter !== null) {
                if ($this->getLocationPlaceIdByScale($location, $this->getParentForScale($scale)) != $filter) {
                    continue;
                }
            }

            $placeId          = $this->getLocationPlaceIdByScale($location, $scale);
            $places[$placeId] = $this->getLocationPlaceByScale($location, $scale);
        }

        return $places;
    }

    /**
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @param string $scale
     * @return string
     */
    public function getLocationPlaceIdByScale($location, $scale)
    {
        switch ($scale) {
            case Scale::WORLD:
            case Scale::COUNTRY:
                $place = $location->getCountryId();
                break;
            case Scale::REGION:
                $place = str_replace(' ', '_', $location->getRegion());
                break;
            case Scale::CITY:
                $place = str_replace(' ', '_', $location->getCity());
                break;
            case Scale::STORE:
                $place = $location->getCode();
                break;
            default:
                $place = $location->getCode();
        }

        return $place;
    }

    /**
     * @return bool
     */
    public function isShowMap(): ?bool
    {
        return $this->isMapEnabled() && $this->getMapApiKey();
    }

    /**
     * @return string
     */
    public function getPlaceByGeoIp()
    {
        $result          = '';
        $currentLocation = $this->geoIp->getCurrentLocation();
        switch ($this->getDefaultScale()) {
            case Scale::WORLD:
                $result = $currentLocation->getCountry();
                break;
            case Scale::COUNTRY:
                $result = $currentLocation->getCountry();
                break;
            case Scale::REGION:
                $result = $currentLocation->getRegion();
                $result = $result ?? $currentLocation->getCountry();
                break;
            case Scale::CITY:
            case Scale::STORE:
                $result = $currentLocation->getCity();
                $result = $result ?? $currentLocation->getRegion();
                $result = $result ?? $currentLocation->getCountry();
                break;
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getCoordinatesByGeoIp()
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
     * @throws \Magento\Framework\Exception\NoSuchEntityException
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
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getSourceItemsForCurrentQuote()
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
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getQuote()
    {
        $session = $this->state->getAreaCode() == 'adminhtml' ? $this->backendSession : $this->checkoutSession;

        return $session->getQuote();
    }

    /**
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStoreMediaUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl('media');
    }

    /**
     * @param string $code
     * @return string|null
     */
    public function prepareCode($code)
    {
        $code = str_replace(' ', '_', $code);

        return preg_replace('/[^A-Za-z0-9\_]/', '', $code);
    }

    /**
     * @param int $storeId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isSetToUseCurrentLocation($storeId = 0): bool
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_FILTER_USE_CURRENT_LOCATION,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPredefinedRadiusValues($storeId = 0): array
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

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
     * @param int $storeId
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getRadiusUnit($storeId = 0): string
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_FILTER_RADIUS_UNIT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getDefaultRadiusValue($storeId = 0): ?string
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_FILTER_DEFAULT_RADIUS_VALUE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getRadiusValueFromSession($storeId = 0): ?string
    {
        if ($this->getSearchFiltersFromSession()) {
            return $this->getSearchFiltersFromSession()['radius'] ?? $this->getDefaultRadiusValue();
        }

        return $this->getDefaultRadiusValue();
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getSearchFilters()
    {
        $filters = [];

        $geoIpCoord              = $this->getCoordinatesByGeoIp();
        $filters['autocomplete'] = $geoIpCoord;

        if (empty($filters['autocomplete']['lat']) && empty($filters['autocomplete']['lng'])) {
            //search by default place if can't load GeoIP DB
            $filters[$this->getDefaultScale()] = $this->getDefaultPlace();
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
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getSearchFiltersFromSession(): ?array
    {
        if ($this->customerSession->getData(LocationRepositoryInterface::LOCATOR_COORDINATES)) {
            return $this->customerSession->getData(LocationRepositoryInterface::LOCATOR_COORDINATES);
        }

        return $this->getSearchFilters();
    }

    /**
     * @param int $storeId
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMapApiKey($storeId = 0)
    {
        return $this->helperGoogleApi->getMapApiKey($storeId);
    }

    /**
     * @param int $storeId
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCountriesForAutocomplete($storeId = 0)
    {
        return $this->helperGoogleApi->getCountriesForAutocomplete($storeId);
    }
}

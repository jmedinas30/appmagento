<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

/**
 * Location data helper
 *
 */

namespace MageWorx\LocationPages\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\Store;
use MageWorx\LocationPages\Api\Data\LocationPageInterface;
use MageWorx\LocationPages\Api\Data\LocationListInterface;
use Magento\Framework\Filter\TranslitUrl;
use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\Locations\Model\Source\Country;

class Data extends AbstractHelper
{
    /**
     * XML config path default scale
     */
    const XML_PATH_BASE_PATH = 'mageworx_locations/pages/base_path';

    /**
     * XML config path default country
     */
    const XML_PATH_URL_PATH = 'mageworx_locations/pages/url_path';

    /**
     * XML config path default country
     */
    const XML_PATH_URL_KEY = 'mageworx_locations/pages/url_key';

    /**
     * XML config path add breadcrumbs
     */
    const XML_PATH_ADD_BREADCRUMBS = 'mageworx_locations/pages/add_breadcrumbs';

    /**
     * XML config path add breadcrumbs
     */
    const XML_PATH_CREATE_REDIRECTS = 'mageworx_locations/pages/create_redirects';
    /**
     * XML config path show products
     */
    const XML_PATH_SHOW_PRODUCTS = 'mageworx_locations/pages/show_products';

    /**
     * XML config path show products
     */
    const XML_PATH_PRODUCTS_LAYOUT = 'mageworx_locations/pages/layout';

    /**
     * XML config path show products
     */
    const XML_PATH_PRODUCTS_INCLUDE_LN = 'mageworx_locations/pages/include_ln';

    /**
     * XML config path show products
     */
    const XML_PATH_PRODUCTS_DISPLAY_MODE = 'mageworx_locations/pages/display_mode';

    /**
     * XML config path show products
     */
    const XML_PATH_PRODUCTS_DEFAULT_SORT = 'mageworx_locations/pages/default_sort';

    /**
     * XML config path add to sitemap
     */
    const XML_PATH_ADD_TO_SITEMAP = 'mageworx_locations/pages/add_to_sitemap';

    /**
     * XML config pathpage meta robots
     */
    const XML_PATH_DEFAULT_META_ROBOTS = 'mageworx_locations/pages/default_meta_robots';

    /**
     * XML config path list meta robots
     */
    const XML_PATH_LIST_META_ROBOTS = 'mageworx_locations/list/meta_robots';

    /**
     * XML config path list meta title
     */
    const XML_PATH_LIST_META_TITLE = 'mageworx_locations/list/meta_title';

    /**
     * XML config path list meta description
     */
    const XML_PATH_LIST_META_DESCRIPTION = 'mageworx_locations/list/meta_description';

    /**
     * XML config path list meta keywords
     */
    const XML_PATH_LIST_META_KEYWORDS = 'mageworx_locations/list/meta_keywords';

    /**
     * Core registry
     *
     * @var \MageWorx\Locations\Model\Registry
     */
    protected $registry;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var TranslitUrl
     */
    protected $translitUrl;

    /**
     * @var Country
     */
    protected $country;

    /**
     * Data constructor.
     *
     * @param Country $country
     * @param TranslitUrl $translitUrl
     * @param StoreManagerInterface $storeManager
     * @param \MageWorx\Locations\Model\Registry $registry
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        Country $country,
        TranslitUrl $translitUrl,
        StoreManagerInterface $storeManager,
        \MageWorx\Locations\Model\Registry $registry,
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
        $this->country      = $country;
        $this->storeManager = $storeManager;
        $this->registry     = $registry;
        $this->translitUrl  = $translitUrl;
    }

    /**
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    public function getCurrentProduct()
    {
        return $this->registry->registry('current_product');
    }

    /**
     * @return bool|LocationPageInterface
     */
    public function getCurrentLocationPage()
    {
        return $this->registry->registry(LocationPageInterface::CURRENT_LOCATION_PAGE);
    }

    /**
     * @return bool|LocationListInterface
     */
    public function getCurrentLocationList()
    {
        return $this->registry->registry(LocationListInterface::CURRENT_LOCATION_LIST);
    }

    /**
     * @return bool|\MageWorx\Locations\Api\Data\LocationInterface
     */
    public function getCurrentLocation()
    {
        $locationPage = $this->getCurrentLocationPage();
        if ($locationPage) {
            return $locationPage->getLocation();
        }

        return false;
    }

    /**
     * @param int $storeId
     * @return bool
     */
    public function isAddBreadcrumbs($storeId = 0)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ADD_BREADCRUMBS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return bool
     */
    public function isCreateRedirects($storeId = 0)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_CREATE_REDIRECTS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return bool
     */
    public function isAddToSitemap($storeId = 0)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ADD_TO_SITEMAP,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return bool
     */
    public function isShowProducts($storeId = 0)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SHOW_PRODUCTS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string
     */
    public function getBasePath($storeId = 0)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_BASE_PATH,
            ScopeInterface::SCOPE_STORE,
            $storeId
        ) ?? 'stores';
    }

    /**
     * @param int $storeId
     * @return string[]
     */
    public function getUrlPathParts($storeId = 0)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        $result = $this->scopeConfig->getValue(
            self::XML_PATH_URL_PATH,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        return explode(',', $result);
    }

    /**
     * @param int $storeId
     * @return string[]
     */
    public function getUrlKeyParts($storeId = 0)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        $result = $this->scopeConfig->getValue(
            self::XML_PATH_URL_KEY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        return explode(',', $result);
    }

    /**
     * @param int $storeId
     * @return string
     */
    public function getProductLayout($storeId = 0)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_PRODUCTS_LAYOUT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string
     */
    public function isIncludeLn($storeId = 0)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_PRODUCTS_INCLUDE_LN,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string
     */
    public function getProductDisplayMode($storeId = 0)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_PRODUCTS_DISPLAY_MODE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string
     */
    public function getProductDefaultSort($storeId = 0)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_PRODUCTS_DEFAULT_SORT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string
     */
    public function getDefaultMetaRobots($storeId = 0)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_DEFAULT_META_ROBOTS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getListMetaRobots($storeId = 0)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_LIST_META_ROBOTS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string
     */
    public function getListMetaTitle($storeId = 0)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->replaceListName(
            $this->scopeConfig->getValue(
                self::XML_PATH_LIST_META_TITLE,
                ScopeInterface::SCOPE_STORE,
                $storeId
            )
        );
    }

    /**
     * @param int $storeId
     * @return string
     */
    public function getListMetaDescription($storeId = 0)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->replaceListName(
            $this->scopeConfig->getValue(
                self::XML_PATH_LIST_META_DESCRIPTION,
                ScopeInterface::SCOPE_STORE,
                $storeId
            )
        );
    }

    /**
     * @param int $storeId
     * @return string
     */
    public function getListMetaKeywords($storeId = 0)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->replaceListName(
            $this->scopeConfig->getValue(
                self::XML_PATH_LIST_META_KEYWORDS,
                ScopeInterface::SCOPE_STORE,
                $storeId
            )
        );
    }

    /**
     * @param string $result
     * @return string
     */
    protected function replaceListName($result)
    {

        if ($this->getCurrentLocationList()) {
            $result = $result ? str_replace('[name]', $this->getCurrentLocationList()->getName(), $result) : '';
            if (!$result) {
                return $this->getCurrentLocationList()->getName();
            }
        }

        return $result;
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
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStoreUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl();
    }

    /**
     * @return int
     */
    public function getStoreIdForSingleStoreMode()
    {
        $defaultStore = $this->storeManager->getDefaultStoreView();
        if ($defaultStore) {
            return $defaultStore->getId();
        }

        return Store::DEFAULT_STORE_ID;
    }

    /**
     * @return string[]
     */
    public function getAllStoreIds()
    {
        if ($this->storeManager->isSingleStoreMode()) {
            return [Store::DEFAULT_STORE_ID];
        }

        $storeIds = array_keys($this->storeManager->getStores());
        array_push($storeIds, Store::DEFAULT_STORE_ID);

        return $storeIds;
    }

    /**
     * @return bool
     */
    public function isSingleStoreMode()
    {
        return $this->storeManager->isSingleStoreMode();
    }

    /**
     * @param string $str
     * @return string
     */
    public function prepareStringToUrl($str)
    {
        return $this->translitUrl->filter($str);
    }

    /**
     * @param int $storeId
     * @return string
     */
    public function getLastListType($storeId = 0)
    {
        $parts = $this->getUrlPathParts($storeId);

        return end($parts);
    }

    /**
     * @param string $type
     * @return string
     */
    public function getChildListType($type)
    {
        $result = '';
        $flag   = false;
        foreach ($this->getUrlPathParts() as $path) {
            if ($flag || $type == 'base') {
                $result = $path;
                break;
            }
            if ($type == $path) {
                $flag = true;
                continue;
            }
        }

        if ($result == 'country') {
            $result = LocationInterface::COUNTRY_ID;
        }

        return $result;
    }

    /**
     * @param string $name
     * @return string
     */
    public function prepareCountryCode($name)
    {
        $code = '';

        if ($name !== null) {
            foreach ($this->country->toArray() as $countryCode => $countryLabel) {
                if ((string)$countryLabel == $name) {
                    return $countryCode;
                }
            }
        }

        return $code;
    }
}

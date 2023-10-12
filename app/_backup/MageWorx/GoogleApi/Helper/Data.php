<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

/**
 * GoogleApi data helper
 *
 */

namespace MageWorx\GoogleApi\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Location data helper
 *
 * @package MageWorx\StoreLocator\Helper
 */
class Data extends AbstractHelper
{
    /**
     * XML config path api key
     */
    const XML_PATH_MAP_API_KEY = 'mageworx_google_api/map/map_api_key';

    /**
     * XML config path for autocomplete countries
     */
    const XML_PATH_AUTOCOMPLETE_RESTRICT_COUNTRIES = 'mageworx_google_api/map/autocomplete_restrict_countries';

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Data constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param Context $context
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Context $context
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * @param int $storeId
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMapApiKey($storeId = 0)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_MAP_API_KEY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCountriesForAutocomplete($storeId = 0)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        $result = (string)$this->scopeConfig->getValue(
            self::XML_PATH_AUTOCOMPLETE_RESTRICT_COUNTRIES,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        $result = strtolower($result);

        return array_filter(explode(',', $result));
    }

    /**
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }
}

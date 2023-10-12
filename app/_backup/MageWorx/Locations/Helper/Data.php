<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

/**
 * Location data helper
 *
 */

namespace MageWorx\Locations\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Stdlib\DateTime\DateTimeFactory;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\Store;
use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\Locations\Model\Source\Country;

class Data extends AbstractHelper
{
    const BASE_MEDIA_PATH_STORE_IMAGE = 'mageworx/locations';
    /**
     * Core registry
     *
     * @var \MageWorx\Locations\Model\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTimeFactory
     */
    protected $dateTimeFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $product;

    /**
     * @var int
     */
    protected $currentStoreIdForLocation;

    /**
     * @var null|array
     */
    protected $allStores;

    /**
     * @var Country
     */
    protected $country;

    /**
     * Data constructor.
     *
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param DateTimeFactory $dateTimeFactory
     * @param Country $country
     * @param \MageWorx\Locations\Model\Registry $registry
     * @param \Magento\Catalog\Model\Product $product
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        DateTimeFactory $dateTimeFactory,
        Country $country,
        \MageWorx\Locations\Model\Registry $registry,
        \Magento\Catalog\Model\Product $product,
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
        $this->registry        = $registry;
        $this->dateTimeFactory = $dateTimeFactory;
        $this->country         = $country;
        $this->storeManager    = $storeManager;
        $this->product         = $product;
    }

    /**
     * @return LocationInterface
     */
    public function getCurrentLocation()
    {
        /** @var LocationInterface $location */
        $location = $this->registry->registry(LocationInterface::CURRENT_LOCATION);

        return $location;
    }

    /**
     * @return int
     */
    public function getCurrentStoreIdForLocation()
    {
        if ($this->currentStoreIdForLocation !== null) {
            return $this->currentStoreIdForLocation;
        }
        $storeId = $this->registry->registry(LocationInterface::CURRENT_STORE_ID_FOR_LOCATION);

        return $storeId ?? Store::DEFAULT_STORE_ID;
    }

    /**
     * @param int $storeId
     * @return $this
     */
    public function setCurrentStoreIdForLocation($storeId)
    {
        $this->currentStoreIdForLocation = $storeId;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrentDate()
    {
        $dateModel = $this->dateTimeFactory->create();

        return $dateModel->gmtDate();
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
     * @param string[] $storeIds
     * @return string[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getWebsiteIdsFromStoreIds($storeIds)
    {
        $websiteIds = [];
        $storeIds   = is_array($storeIds) ? $storeIds : [$storeIds];

        if (in_array(Store::DEFAULT_STORE_ID, $storeIds)) {
            $websiteIds = array_keys($this->storeManager->getWebsites());
        } else {
            foreach ($storeIds as $storeId) {
                $websiteIds[] = $this->storeManager->getStore($storeId)->getWebsiteId();
            }
        }

        return array_unique($websiteIds);
    }

    /**
     * @param string $file
     *
     * @return string
     */
    public function getMediaUrl($file)
    {
        return $this->getBaseMediaUrl() . '/' . $this->prepareFile($file);
    }

    /**
     * @return string
     */
    public function getBaseMediaUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(
            UrlInterface::URL_TYPE_MEDIA
        ) . $this->getBaseMediaPath();
    }

    /**
     * @param string $file
     *
     * @return string
     */
    protected function prepareFile($file)
    {
        return ltrim(str_replace('\\', '/', $file), '/');
    }

    /**
     * Filesystem directory path of option value images
     * relatively to media folder
     *
     * @return string
     */
    public function getBaseMediaPath()
    {
        return static::BASE_MEDIA_PATH_STORE_IMAGE;
    }

    /**
     * @param int $storeId
     * @return bool|string
     */
    public function getStoreCodeById($storeId)
    {
        if (!$this->allStores) {
            $this->prepareStoresData();
        }
        $code = $this->allStores[$storeId] ?? false;
        $code = $code == 'admin' ? 'all' : $code;

        return $code;
    }

    /**
     * @param string $code
     * @return bool|int
     */
    public function getStoreIdByCode($code)
    {
        if (!$this->allStores) {
            $this->prepareStoresData();
        }

        $flip = array_flip($this->allStores);

        return $flip[$code] ?? false;
    }

    /**
     * @return string[]
     */
    public function getAllStoreIds()
    {
        if (!$this->allStores) {
            $this->prepareStoresData();
        }

        return array_keys($this->allStores);
    }

    /**
     * load all stores id and codes
     */
    protected function prepareStoresData()
    {
        $result = [];

        foreach ($this->storeManager->getStores(true) as $id => $store) {
            $code        = $store->getCode() == 'admin' ? 'all' : $store->getCode();
            $result[$id] = $code;
        }
        ksort($result);
        $this->allStores = $result;
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

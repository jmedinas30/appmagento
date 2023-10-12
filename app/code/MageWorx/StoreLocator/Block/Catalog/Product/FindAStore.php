<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreLocator\Block\Catalog\Product;

use Magento\CatalogInventory\Model\Configuration;
use Magento\Directory\Model\RegionFactory;
use Magento\Framework\View\Element\Template;
use MageWorx\StoreLocator\Helper\Data;
use MageWorx\Locations\Api\LocationRepositoryInterface;
use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\Locations\Model\ResourceModel\Location\Collection;
use Magento\Store\Model\StoreManagerInterface;
use MageWorx\StoreLocator\Model\Source\Layout;
use MageWorx\StoreLocator\Model\Source\StoresDisplayMode;
use MageWorx\Locations\Model\MsiResolver\GetSourceItemsBySku;

class FindAStore extends Template
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var \MageWorx\Locations\Model\ResourceModel\Location\Collection
     */
    protected $locations;

    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Locale\Resolver
     */
    protected $locale;

    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var GetSourceItemsBySku
     */
    protected $getSourceItemsBySku;

    /**
     * @var array
     */
    protected $sourceItems = [];

    /**
     * @var Configuration
     */
    protected $inventoryConfig;

    /**
     * @var \Magento\CatalogInventory\Model\Stock\StockItemRepository
     */
    protected $stockItemRepository;

    /**
     * @var RegionFactory
     */
    protected $regionFactory;

    /**
     * @param RegionFactory $regionFactory
     * @param \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository
     * @param Configuration $inventoryConfig
     * @param GetSourceItemsBySku $getSourceItemsBySku
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param StoreManagerInterface $storeManager
     * @param LocationRepositoryInterface $locationRepository
     * @param Data $helper
     * @param \Magento\Framework\Locale\Resolver $locale
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        RegionFactory $regionFactory,
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository,
        Configuration $inventoryConfig,
        GetSourceItemsBySku $getSourceItemsBySku,
        \Magento\Framework\Module\Manager $moduleManager,
        StoreManagerInterface $storeManager,
        LocationRepositoryInterface $locationRepository,
        Data $helper,
        \Magento\Framework\Locale\Resolver $locale,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->regionFactory       = $regionFactory;
        $this->stockItemRepository = $stockItemRepository;
        $this->inventoryConfig     = $inventoryConfig;
        $this->getSourceItemsBySku = $getSourceItemsBySku;
        $this->moduleManager       = $moduleManager;
        $this->locationRepository  = $locationRepository;
        $this->helper              = $helper;
        $this->locale              = $locale;
        $this->storeManager        = $storeManager;
    }

    /**
     * @param int $productId
     * @return \Magento\CatalogInventory\Api\Data\StockItemInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getStockItem($productId)
    {
        return $this->stockItemRepository->get($productId);
    }

    /**
     * Prepare layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        if (!$this->getTemplate()) {
            switch ($this->helper->getStoresDisplayModeOnProduct()) {
                case StoresDisplayMode::DETAILED:
                    $this->setTemplate('MageWorx_StoreLocator::catalog/product/find_a_store_detailed.phtml');
                    break;
                case StoresDisplayMode::SIMPLE:
                default:
                    $this->setTemplate('MageWorx_StoreLocator::catalog/product/find_a_store.phtml');
                    break;
            }
        }

        return parent::_prepareLayout();
    }

    /**
     * @return bool
     */
    public function isLocationOnProductEnabled(): bool
    {
        return $this->helper->isLocationOnProductEnabled();
    }

    /**
     *
     * @return Collection
     */
    public function getLocations(): Collection
    {
        if ($this->locations === null) {
            $product           = $this->getCurrentProduct();
            $filters           = $this->helper->getSearchFiltersFromSession();
            $filters['radius'] = 0;
            $filters['skip_radius'] = $this->helper->skipRadiusFilter('cms_page_view');
            if ($product) {
                $locations = $this->locationRepository->getListLocationByProductIds(
                    $this->getProductId(),
                    $this->helper->getDefaultStoresAmountOnProduct(),
                    $this->helper->getDisplayStockStatus(),
                    $filters,
                    $this->getProductSku()
                );
                // load source items only if MSI enabled
                $getSourceItemsBySkuInstance = $this->getSourceItemsBySku->getInstance();
                if ($getSourceItemsBySkuInstance) {
                    $sourceItems = [];
                    if ($product instanceof \Magento\Catalog\Model\Product) {
                        $sourceItems = $getSourceItemsBySkuInstance->execute((string)$product->getSku());
                    } else {
                        foreach ($product as $item) {
                            $sourceItems = array_merge(
                                $sourceItems,
                                $getSourceItemsBySkuInstance->execute((string)$item->getSku())
                            );
                        }
                    }
                    $this->sourceItems = $sourceItems;
                }
            } else {
                $locations = $this->locationRepository->getListLocationForFront();
            }
            $this->locations = $locations;
        }

        return $this->locations;
    }

    /**
     * @return \Magento\Catalog\Api\Data\ProductInterface|null
     */
    protected function getCurrentProduct()
    {
        //ajax saved child or grouped product in block
        if ($this->getProduct()) {
            return $this->getProduct();
        }

        // parent product or simple available from registry
        return $this->helper->getCurrentProduct();
    }

    /**
     * @return int|null
     */
    public function getProductId()
    {
        $product = $this->getCurrentProduct();
        if ($product instanceof \Magento\Catalog\Api\Data\ProductInterface) {
            return $product->getId();
        }

        //for grouped product
        return $product->getAllIds();
    }

    /**
     *
     * @return int
     */
    public function getLocationsCount(): int
    {
        $count   = 0;
        $product = $this->getCurrentProduct();
        if ($product) {
            $count = $this->locationRepository->getLocationCountForProduct(
                $this->getProductId(),
                false
            );
        }

        return $count;
    }

    /**
     * @return string
     */
    public function getLocationsPlaces(): string
    {
        $locations = $this->getLocations();
        $scale     = $this->helper->getFilterBy();
        $places    = $this->helper->getPlacesListByScale($locations, $scale);

        return implode(', ', $places);
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLocationOnProductImageUrl(): string
    {
        $configImage = $this->helper->getLocationOnProductImage();
        if ($configImage) {
            return $this->storeManager->getStore()->getBaseUrl('media') . 'mageworx/location_image/' . $configImage;
        }

        return $this->getViewFileUrl('MageWorx_StoreLocator::images/svg/pin.svg');
    }

    /**
     * @return bool
     */
    public function isLocationsNamesEnabled(): bool
    {
        return $this->helper->isLocationsNamesEnabled();
    }

    /**
     * @return string
     */
    public function getLocationOnProductText(): string
    {
        $configText = $this->helper->getLocationOnProductText();

        return str_replace('[count]', $this->getLocationsCount(), $configText);
    }

    /**
     * @return string
     */
    public function getLocationOnProductNotAvailableText(): string
    {
        return $this->helper->getLocationOnProductNotAvailableText();
    }

    /**
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @param string $label
     * @return string
     */
    public function getLocationPageLink($location, $label): string
    {
        if ($location->getPageFullUrl() && $this->moduleManager->isEnabled('MageWorx_LocationPages')) {
            $label = '<a href="' . $this->escapeUrl($location->getPageFullUrl()) .
                '"  target="_blank">' . $label . '</a>';
        }

        return $label;
    }

    /**
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @return string
     */
    public function getRouteUrl($location): string
    {
        $region = $location->getRegion() == \MageWorx\Locations\Model\Source\Region::NO_REGIONS ?
            '' : $location->getRegion();

        return "//maps.google.com/maps/dir/?api=1&destination=" . $location->getAddress() .
            ", " . $location->getCity() . ", " . $region . ", " . $location->getCountry();
    }

    /**
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @return int
     */
    public function isProductInStock($location): int
    {
        $status = 0;

        $sourceCode = $location->getSourceCode();
        if ($sourceCode) {
            foreach ($this->sourceItems as $sourceItem) {
                if ($sourceItem->getSourceCode() == $sourceCode) {
                    if ($this->inventoryConfig->getManageStock()) {
                        $status = $sourceItem->getStatus() && $sourceItem->getQuantity() > 0;
                    } else {
                        $status = $sourceItem->getStatus();
                    }
                }
            }
        } else {
            if ($this->getCurrentProduct() instanceof \Magento\Catalog\Api\Data\ProductInterface) {
                $status = $this->getCurrentProduct()->getStatus();
            } else {
                foreach ($this->getCurrentProduct() as $product) {
                    $status += $product->getStatus();
                }

                $status = (bool)$status;
            }
        }

        return $status;
    }

    /**
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @return int
     */
    public function getProductQty($location): int
    {
        $status = 0;
        $qty    = 0;

        $sourceCode = $location->getSourceCode();
        if ($sourceCode) {
            foreach ($this->sourceItems as $sourceItem) {
                if ($sourceItem->getSourceCode() == $sourceCode) {
                    if ($this->inventoryConfig->getManageStock()) {
                        $qty    = $sourceItem->getQuantity();
                        $status = $sourceItem->getStatus() && $sourceItem->getQuantity() > 0;
                    } else {
                        $status = $sourceItem->getStatus();
                    }
                }
            }
        } else {
            if ($this->getCurrentProduct() instanceof \Magento\Catalog\Api\Data\ProductInterface) {
                $data   = $this->getCurrentProduct()->getQuantityAndStockStatus();
                $status = $data['is_in_stock'] ?? false;
                $qty    = $data['qty'] ?? 0;
            } else {
                foreach ($this->getCurrentProduct() as $product) {
                    $status                  += $data['is_in_stock'] ?? false;
                    $qty[$product->getSku()] = $data['qty'] ?? 0;
                }

                $status = (bool)$status;
            }
        }

        return $status ? $qty : 0;
    }

    /**
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getQtyMessage($location): string
    {
        $text = $this->helper->getQtyMessageOnProductText();

        return str_replace('[stock]', $this->getProductQty($location), $text);
    }

    /**
     * @return int
     */
    public function getDisplayStockStatus(): int
    {
        return (int)$this->helper->getDisplayStockStatus();
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isDetailedStoresDisplayMode(): string
    {
        return $this->helper->getStoresDisplayModeOnProduct() == StoresDisplayMode::DETAILED;
    }

    /**
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isShowMap(): bool
    {
        $layout = $this->helper->getPopupLayout();

        return $this->helper->isShowMap() && $layout && $layout !== Layout::LIST_WITHOUT_MAP;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getGoogleMapsApiLibraryUrl(): string
    {
        $url = 'https://maps.googleapis.com/maps/api/js?v=quarterly&key=';
        $url .= $this->helper->getMapApiKey();
        $url .= '&libraries=places&';
        $url .= 'language=' . $this->getLanguage() . '&';
        $url .= 'region=' . $this->getRegion();

        return $url;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        $locale = explode('_', $this->locale->getLocale());

        return $locale[0] ?? 'en';
    }

    /**
     * @return string
     */
    public function getRegion(): string
    {
        $locale = explode('_', $this->locale->getLocale());

        return $locale[1] ?? 'US';
    }

    /**
     * @return string
     */
    protected function getProductSku(): string
    {
        $product = $this->getCurrentProduct();
        if ($product instanceof \Magento\Catalog\Api\Data\ProductInterface) {
            return $product->getSku() ?? '';
        }

        return '';
    }

    /**
     * @param LocationInterface $location
     * @return string
     */
    public function getLocationsRegion(LocationInterface $location): string
    {
        if ($location->getCountryId() == 'US') {
            /** @var \Magento\Directory\Model\Region $region */
            $region = $this->regionFactory->create();
            try {
                $region->loadByName($location->getRegion(), $location->getCountryId());
            } catch (\Exception $e) {
                return $location->getRegion();
            }

            return $region->getCode();
        }

        return $location->getRegion();
    }
}

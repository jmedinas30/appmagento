<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreLocator\Block;

use Magento\CatalogInventory\Model\Configuration;
use Magento\Framework\View\Element\Template;
use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\StoreLocator\Helper\Data;
use MageWorx\StoreLocator\Model\Source\Layout;
use MageWorx\Locations\Api\LocationRepositoryInterface;
use MageWorx\Locations\Model\ResourceModel\Location\Collection;
use Magento\Store\Model\StoreManagerInterface;

class Locations extends Template
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
     * @var Configuration
     */
    protected $inventoryConfig;

    /**
     * Locations constructor.
     *
     * @param Configuration $inventoryConfig
     * @param StoreManagerInterface $storeManager
     * @param LocationRepositoryInterface $locationRepository
     * @param Data $helper
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Configuration $inventoryConfig,
        StoreManagerInterface $storeManager,
        LocationRepositoryInterface $locationRepository,
        Data $helper,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->inventoryConfig    = $inventoryConfig;
        $this->locationRepository = $locationRepository;
        $this->helper             = $helper;
        $this->storeManager       = $storeManager;
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        if (!$this->helper->canShowMap()) {
            $this->setTemplate('MageWorx_StoreLocator::locations/list_without_map.phtml');
        } else {
            switch ($this->getRequest()->getFullActionName()) {
                case 'mageworx_store_locator_location_updatepopupcontent':
                case 'catalog_product_view':
                    $layoutType = $this->helper->getPopupLayout();
                    break;
                case 'mageworx_store_locator_location_updatemainpage':
                case 'cms_page_view':
                    $layoutType = $this->helper->getLocationsPageLayout();
                    break;
                case 'checkout_index_index':
                default:
                    $layoutType = $this->helper->getCheckoutLayout();
                    break;
            }

            switch ($layoutType) {
                case Layout::FILTER_ON_MAP:
                    $this->setTemplate('MageWorx_StoreLocator::locations/filter_on_map.phtml');
                    break;
                case Layout::FILTER_LEFT_MAP:
                    $this->setTemplate('MageWorx_StoreLocator::locations/filter_left_map.phtml');
                    break;
                case Layout::LIST_AFTER_MAP:
                    $this->setTemplate('MageWorx_StoreLocator::locations/list_after_map.phtml');
                    break;
                case Layout::LIST_BEFORE_MAP:
                    $this->setTemplate('MageWorx_StoreLocator::locations/list_before_map.phtml');
                    break;
                case Layout::LIST_WITHOUT_MAP:
                    $this->setTemplate('MageWorx_StoreLocator::locations/list_without_map.phtml');
                    break;
                default:
                    $this->setTemplate('MageWorx_StoreLocator::locations/list_without_map.phtml');
                    break;
            }
        }

        return parent::_prepareLayout();
    }

    /**
     * @return Collection|string[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLocations()
    {
        if ($this->locations === null) {
            $filters = $this->helper->getSearchFiltersFromSession();
            switch ($this->getRequest()->getFullActionName()) {
                case 'mageworx_store_locator_location_updatepopupcontent':
                case 'catalog_product_view':
                    $product = $this->getCurrentProduct();
                    if ($product) {
                        $locations = $this->locationRepository->getListLocationByProductIds(
                            $this->getCurrentProductId(),
                            null,
                            $this->helper->getDisplayStockStatus(),
                            $filters
                        );
                    } else {
                        $locations = $this->locationRepository->getListLocationForFront(
                            $this->getCurrentStoreId(),
                            $filters
                        );
                    }
                    break;
                case 'mageworx_store_locator_location_updatemainpage':
                case 'cms_page_view':
                    $locations = $this->locationRepository->getListLocationForFront(
                        $this->getCurrentStoreId(),
                        $filters
                    );
                    break;
                case 'checkout_index_index':
                default:
                    $locations = $this->helper->getLocationsForCurrentQuote($filters);
                    break;
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
        if ($this->getProduct()) {
            return $this->getProduct();
        }

        return $this->helper->getCurrentProduct();
    }

    /**
     * @return array
     */
    public function getLocationsIds()
    {
        $ids = [];

        if ($this->getLocations() !== null) {
            $locationCollection  = $this->getLocations();
            $ids['location_ids'] = $locationCollection->getConnection()->fetchCol($locationCollection->getSelect());
        }

        return $ids;
    }

    /**
     * @return bool
     */
    public function isShowMap()
    {
        return $this->helper->isShowMap();
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getLocationsFilterHtml()
    {
        $block = $this->getLayout()->createBlock(\MageWorx\StoreLocator\Block\Filter::class)
                      ->setTemplate('MageWorx_StoreLocator::filter.phtml');
        $block->setData('locations', $this->getLocations());

        return $block->toHtml();
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getLocationsFilterForListHtml()
    {
        $block = $this->getLayout()->createBlock(\MageWorx\StoreLocator\Block\Filter::class)
                      ->setTemplate('MageWorx_StoreLocator::filter_for_list.phtml');
        $block->setData('locations', $this->getLocations());

        return $block->toHtml();
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getLocationsMapHtml()
    {
        $block = $this->getLayout()->createBlock(\MageWorx\StoreLocator\Block\Map::class);
        $block->setData('locations', $this->getLocations());

        return $block->toHtml();
    }

    /**
     * @param string $type
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLocationsWrapperHtml($type)
    {
        $block = $this->getLayout()->createBlock(\MageWorx\StoreLocator\Block\Locations::class);
        $block->setTemplate('MageWorx_StoreLocator::wrapper.phtml');
        $block->setLocations($this->getLocations());
        $block->setProduct($this->getProduct());
        $block->setData('filter_type', $type);

        return $block->toHtml();
    }

    /**
     * @param $locations
     * @return $this
     */
    public function setLocations($locations)
    {
        $this->locations = $locations;

        return $this;
    }

    /**
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrentStoreId()
    {
        return $this->helper->getStoreId();
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getSearchBoxHtml()
    {
        $block = $this->getLayout()->createBlock(\MageWorx\StoreLocator\Block\Search::class)
                      ->setTemplate('MageWorx_StoreLocator::search.phtml');

        return $block->toHtml();
    }

    /**
     * @return string
     */
    public function getCurrentPageType()
    {
        if ($this->getRequest()->getFullActionName() == 'mageworx_store_locator_location_updatepopupcontent') {
            return 'catalog_product_view';
        }

        if ($this->getRequest()->getFullActionName() == 'mageworx_store_locator_location_updatemainpage') {
            return 'cms_page_view';
        }

        return $this->getRequest()->getFullActionName();
    }

    /**
     * @return int|null
     */
    public function getCurrentProductId()
    {
        $product = $this->getCurrentProduct();

        if (!$product) {
            return '';
        }

        if ($product instanceof \Magento\Catalog\Model\Product) {
            return $product->getId();
        }

        return $product->getAllIds();
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getLocationsListHtml()
    {
        $block = $this->getLayout()->createBlock(\MageWorx\StoreLocator\Block\LocationsList::class);

        $block->setData('locations', $this->getLocations());

        return $block->toHtml();
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getGoogleMapScript()
    {
        if ($this->getIsPopupUpdate() === true) {
            return '';
        }

        $block = $this->getLayout()->createBlock(\MageWorx\StoreLocator\Block\GoogleMap::class);
        $block->setInitializeByLoad(true);

        return $block->toHtml();
    }

    /**
     * @return array
     */
    public function getAvailableLocationsIds()
    {
        $availableLocationIds = [];
        if (!$this->inventoryConfig->getManageStock()) {
            if (isset($this->getLocationsIds()['location_ids'])) {
                return $this->getLocationsIds()['location_ids'];
            }
        }
        foreach ($this->getLocations() as $location) {
            switch ($location->getAssignType()) {
                case LocationInterface::ASSIGN_TYPE_PRODUCTS_FROM_SOURCE:
                    $sourceCode = $location->getSourceCode();
                    if ($sourceCode) {
                        $availableLocationIds = array_merge(
                            $availableLocationIds,
                            $this->getAvailableLocationsWithSourceCode($location, $sourceCode)
                        );
                    }
                    break;
                case LocationInterface::ASSIGN_TYPE_CONDITION:
                case LocationInterface::ASSIGN_TYPE_ALL:
                case LocationInterface::ASSIGN_TYPE_SPECIFIC_PRODUCTS:
                default:
                    if (isset($this->getLocationsIds()['location_ids'])) {
                        return $this->getLocationsIds()['location_ids'];
                    }
            }
        }

        return $availableLocationIds;
    }

    /**
     * @param LocationInterface $location
     * @param string $sourceCode
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getAvailableLocationsWithSourceCode($location, $sourceCode)
    {
        $availableLocationIds = [];

        foreach ($this->helper->getSourceItemsForCurrentQuote() as $sourceItem) {
            if ($sourceItem->getSourceCode() == $sourceCode) {
                foreach ($this->helper->getQuote()->getAllItems() as $item) {
                    if ($sourceItem->getSku() == $item->getSku() &&
                        $sourceItem->getQuantity() >= $item->getQty()) {
                        $availableLocationIds[] = $location->getId();
                    }
                }
            }
        }

        return $availableLocationIds;
    }
}

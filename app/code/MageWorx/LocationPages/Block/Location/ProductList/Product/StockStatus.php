<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Block\Location\ProductList\Product;

use Magento\CatalogInventory\Model\Configuration;
use Magento\Framework\View\Element\Template\Context;
use MageWorx\LocationPages\Api\Data\LocationPageInterface;
use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\StoreLocator\Helper\Data;
use MageWorx\Locations\Api\LocationSourceManagementInterface;

/**
 * Class StockStatus
 */
class StockStatus extends \Magento\Framework\View\Element\Template
{
    /**
     * Core registry
     *
     * @var \MageWorx\Locations\Model\Registry
     */
    protected $coreRegistry;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var LocationSourceManagementInterface
     */
    protected $locationSourceManager;

    /**
     * @var array
     */
    protected $sourceItems = [];


    /** @var Configuration */
    protected $inventoryConfig;


    /**
     * StockStatus constructor.
     *
     * @param Configuration $inventoryConfig
     * @param LocationSourceManagementInterface $locationSourceManager
     * @param Data $helper
     * @param \MageWorx\Locations\Model\Registry $coreRegistry
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Configuration $inventoryConfig,
        LocationSourceManagementInterface $locationSourceManager,
        Data $helper,
        \MageWorx\Locations\Model\Registry $coreRegistry,
        Context $context,
        array $data = []
    ) {
        $this->inventoryConfig       = $inventoryConfig;
        $this->locationSourceManager = $locationSourceManager;
        $this->helper                = $helper;
        $this->coreRegistry          = $coreRegistry;
        parent::__construct($context, $data);
    }

    /**
     * Prepare layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->setTemplate('location/product_status.phtml');
        $locationPage = $this->coreRegistry->registry(LocationPageInterface::CURRENT_LOCATION_PAGE);
        if ($locationPage && $locationPage->getLocation()) {
            /** @var LocationInterface $location */
            $location = $locationPage->getLocation();
            if ($location->getAssignType() == LocationInterface::ASSIGN_TYPE_PRODUCTS_FROM_SOURCE
                && $location->getSourceCode()
            ) {
                $this->sourceItems = $this->locationSourceManager->getSourceItemsByCode($location->getSourceCode());
            }
        }

        return parent::_prepareLayout();
    }

    /**
     * @return bool
     */
    public function isProductInStock()
    {
        $product = $this->getProduct();

        if (!$product) {
            $product = $this->helper->getCurrentProduct();
        }

        if ($product) {
            /** @var \Magento\InventoryApi\Api\Data\SourceItemInterface $sourceItem */
            foreach ($this->sourceItems as $sourceItem) {
                if ($sourceItem->getSku() == $product->getSku()) {
                    if ($this->inventoryConfig->getManageStock()) {
                        return $sourceItem->getStatus() && $sourceItem->getQuantity() > 0;
                    } else {
                        return $sourceItem->getStatus();
                    }
                }
            }
        }

        return false;
    }
}

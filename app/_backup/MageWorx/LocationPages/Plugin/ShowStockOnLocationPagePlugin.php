<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Plugin;

use MageWorx\LocationPages\Api\Data\LocationPageInterface;
use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\StoreLocator\Helper\Data as Helper;

class ShowStockOnLocationPagePlugin
{
    /**
     * @var \Magento\Framework\App\ViewInterface
     */
    protected $view;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $httpRequest;

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * Core registry
     *
     * @var \MageWorx\Locations\Model\Registry
     */
    protected $coreRegistry;

    /**
     * ShowBrandOnCategoryPlugin constructor.
     *
     * @param Helper $helper
     * @param \Magento\Framework\App\ViewInterface $view
     * @param \Magento\Framework\App\Request\Http $httpRequest
     */
    public function __construct(
        Helper $helper,
        \MageWorx\Locations\Model\Registry $coreRegistry,
        \Magento\Framework\App\ViewInterface $view,
        \Magento\Framework\App\Request\Http $httpRequest
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->helper       = $helper;
        $this->view         = $view;
        $this->httpRequest  = $httpRequest;
    }

    /**
     * @param \Magento\Catalog\Block\Product\AbstractProduct $subject
     * @param string $result
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function afterGetProductPrice(
        \Magento\Catalog\Block\Product\AbstractProduct $subject,
        $result,
        \Magento\Catalog\Model\Product $product
    ) {
        if ($this->httpRequest->getFullActionName() !== 'mageworx_locationpages_location_view'
            || !$this->helper->getDisplayStockStatus()
        ) {
            return $result;
        }

        $locationPage = $this->coreRegistry->registry(LocationPageInterface::CURRENT_LOCATION_PAGE);
        if (!$locationPage || !$locationPage->getLocation()) {
            return $result;
        }

        /** @var LocationInterface $location */
        $location = $locationPage->getLocation();
        if ($location->getAssignType() !== LocationInterface::ASSIGN_TYPE_PRODUCTS_FROM_SOURCE
            || !$location->getSourceCode()
        ) {
            return $result;
        }

        $block = $this->view->getLayout()->createBlock(
            \MageWorx\LocationPages\Block\Location\ProductList\Product\StockStatus::class
        );
        $block->setProduct($product);

        return $result . $block->toHtml();
    }
}

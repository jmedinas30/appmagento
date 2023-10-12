<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Block\Location;

use Magento\Catalog\Block\Product\ProductList\Toolbar;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Catalog\Block\Product\ListProduct;

/**
 * Product list
 *
 */
class ProductList extends ListProduct implements IdentityInterface
{
    /**
     * @var \MageWorx\LocationPages\Helper\Data
     */
    protected $helper;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager = null;

    /**
     * ProductList constructor.
     *
     * @param \MageWorx\LocationPages\Helper\Data $helper
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
     * @param \Magento\Framework\Url\Helper\Data $urlHelper
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param array $data
     */
    public function __construct(
        \MageWorx\LocationPages\Helper\Data $helper,
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        array $data = []
    ) {
        $this->helper        = $helper;
        $this->objectManager = $objectManager;

        if (class_exists('\Magento\Catalog\ViewModel\Product\OptionsData')) {
            $data['viewModel'] = $this->objectManager->create('\Magento\Catalog\ViewModel\Product\OptionsData');
        }
        parent::__construct(
            $context,
            $postDataHelper,
            $layerResolver,
            $categoryRepository,
            $urlHelper,
            $data
        );
    }

    /**
     * Return identifiers for produced content
     *
     * @return string[]
     */
    public function getIdentities()
    {
        $identities = [];
        foreach ($this->_getProductCollection() as $item) {
            $identities = array_merge($identities, $item->getIdentities());
        }
        /** @var \MageWorx\LocationPages\Model\LocationPage $locationPage */
        $locationPage = $this->helper->getCurrentLocationPage();
        if ($locationPage) {
            $identities[] = Product::CACHE_PRODUCT_CATEGORY_TAG . '_' . $locationPage->getId();
        }

        return $identities;
    }

    /**
     * Retrieve loaded category collection
     *
     * @return AbstractCollection
     */
    public function getLoadedProductCollection()
    {
        return $this->_getProductCollection();
    }

    /**
     * @return Collection|AbstractCollection
     */
    protected function _getProductCollection()
    {
        if ($this->_productCollection === null) {
            $this->_productCollection = $this->initializeProductCollection();
        }

        return $this->_productCollection;
    }

    /**
     * @return Collection
     */
    private function initializeProductCollection()
    {
        $layer = $this->getLayer();
        /* @var $layer \Magento\Catalog\Model\Layer */
        if ($this->getShowRootCategory()) {
            $this->setCategoryId($this->_storeManager->getStore()->getRootCategoryId());
        }

        $collection = $layer->getProductCollection();
        $this->prepareSortableFieldsByCategory($layer->getCurrentCategory());

        $toolbar = $this->getToolbarBlock();
        $this->configureToolbar($toolbar, $collection);

        return $collection;
    }

    /**
     * @param Toolbar $toolbar
     * @param Collection $collection
     */
    private function configureToolbar(Toolbar $toolbar, Collection $collection)
    {
        // use sortable parameters
        $orders = $this->getAvailableOrders();
        if ($orders) {
            $toolbar->setAvailableOrders($orders);
        }

        $sort = $this->helper->getProductDefaultSort();
        if ($sort) {
            $toolbar->setDefaultOrder($sort);
        }
        $dir = $this->getDefaultDirection();
        if ($dir) {
            $toolbar->setDefaultDirection($dir);
        }
        $modes = $this->getModes();
        if ($modes) {
            $toolbar->setModes($modes);
        }
        // set collection to toolbar and apply sort
        $toolbar->setCollection($collection);
        $this->setChild('toolbar', $toolbar);
    }
}

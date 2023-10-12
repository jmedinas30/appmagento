<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Block\Adminhtml\Location\Edit\Products;

use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Visibility;
use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\Locations\Api\LocationRepositoryInterface;
use MageWorx\Locations\Model\ResourceModel\Catalog\Product\Collection;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * @var Status
     */
    private $status;

    /**
     * @var Visibility
     */
    private $visibility;

    /**
     * Core registry
     *
     * @var \MageWorx\Locations\Model\Registry
     */
    protected $registry;

    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * @var Collection
     */
    protected $productCollection;

    /**
     * Grid constructor.
     *
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \MageWorx\Locations\Model\Registry $registry
     * @param LocationRepositoryInterface $locationRepository
     * @param Visibility $visibility
     * @param Status $status
     * @param Collection $productCollection
     * @param string[] $data
     */
    public function __construct(
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \MageWorx\Locations\Model\Registry $registry,
        LocationRepositoryInterface $locationRepository,
        Visibility $visibility,
        Status $status,
        Collection $productCollection,
        array $data = []
    ) {
        $this->productFactory     = $productFactory;
        $this->registry           = $registry;
        $this->locationRepository = $locationRepository;
        $this->visibility         = $visibility;
        $this->status             = $status;
        $this->productCollection  = $productCollection;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('mageworx_location_products');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
    }

    /**
     * @return \Magento\Catalog\Api\Data\CategoryInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLocation()
    {
        $location = $this->registry->registry(LocationInterface::CURRENT_LOCATION);

        if (!$location) {
            if ($this->getEntityId()) {
                $location = $this->locationRepository->getById($this->getEntityId());
            } else {
                $location = $this->locationRepository->getEmptyEntity();
            }

            $this->registry->register(LocationInterface::CURRENT_LOCATION, $location);
        }

        return $location;
    }

    /**
     * @param Column $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_location') {
            $productSkus = $this->getSelectedProducts();
            if (empty($productSkus)) {
                $productSkus = [];
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', ['in' => $productSkus]);
            } elseif (!empty($productSkus)) {
                $this->getCollection()->addFieldToFilter('entity_id', ['nin' => $productSkus]);
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }

        return $this;
    }

    /**
     * @return Grid
     */
    protected function _prepareCollection()
    {
        if ($this->getLocation()->getId()) {
            $this->setDefaultFilter(['in_location' => 1]);
        }
        $collection = $this->productFactory->create()->getCollection()->addAttributeToSelect(
            'name'
        )->addAttributeToSelect(
            'sku'
        )->addAttributeToSelect(
            'visibility'
        )->addAttributeToSelect(
            'status'
        )->addAttributeToSelect(
            'price'
        );

        $storeId = (int)$this->getRequest()->getParam('store', 0);
        if ($storeId > 0) {
            $collection->addStoreFilter($storeId);
        }
        $this->setCollection($collection);
        if ($this->getLocation()) {
            $productSkus = $this->getSelectedProducts();
            if (!empty($productIds)) {
                $this->getCollection()->addFieldToFilter('sku', ['in' => $productSkus]);
            }
        }

        return parent::_prepareCollection();
    }

    /**
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_location',
            [
                'type'             => 'checkbox',
                'name'             => 'in_location',
                'values'           => $this->getSelectedProducts(),
                'index'            => 'entity_id',
                'header_css_class' => 'col-select col-massaction',
                'column_css_class' => 'col-select col-massaction'
            ]
        );

        $this->addColumn(
            'entity_id',
            [
                'header'           => __('ID'),
                'sortable'         => true,
                'index'            => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
        $this->addColumn('name', ['header' => __('Name'), 'index' => 'name']);
        $this->addColumn('sku', ['header' => __('SKU'), 'index' => 'sku']);
        $this->addColumn(
            'visibility',
            [
                'header'           => __('Visibility'),
                'index'            => 'visibility',
                'type'             => 'options',
                'options'          => $this->visibility->getOptionArray(),
                'header_css_class' => 'col-visibility',
                'column_css_class' => 'col-visibility'
            ]
        );

        $this->addColumn(
            'status',
            [
                'header'  => __('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => $this->status->getOptionArray()
            ]
        );

        $this->addColumn(
            'price',
            [
                'header'        => __('Price'),
                'type'          => 'currency',
                'currency_code' => (string)$this->_scopeConfig->getValue(
                    \Magento\Directory\Model\Currency::XML_PATH_CURRENCY_BASE,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                ),
                'index'         => 'price'
            ]
        );
        $this->addColumn(
            'sku_input',
            [
                'header'           => __('Sku'),
                'type'             => 'input',
                'index'            => 'sku',
                'column_css_class' => 'mageworx-hidden-col',
                'header_css_class' => 'mageworx-hidden-col',
                'editable'         => false
            ]
        );

        $location = $this->getLocation();
        if ($location->getSourceCode()) {
            $this->addColumn(
                'quantity',
                [
                    'header'           => __('Source Quantity'),
                    'name'             => 'quantity',
                    'index'            => 'quantity',
                    'renderer'         => \MageWorx\Locations\Block\Adminhtml\Location\Edit\Products\Grid\Renderer::class,
                    'header_css_class' => 'col-product',
                    'column_css_class' => 'col-product'
                ]
            );
        }

        return parent::_prepareColumns();
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/products_grid/', ['_current' => true]);
    }

    /**
     * @return string[]
     */
    protected function getSelectedProducts()
    {
        $products = $this->getRequest()->getPost('selected_products');
        if ($products === null) {
            $location = $this->getLocation();
            if ($location->getSourceCode()) {
                return $this->productCollection->getProductIdsBySourceCode($location->getSourceCode());
            } else {
                $productSkus = $location->getProductSkus();

                return $this->productCollection->getProductIdsBySkus($productSkus);
            }
        }

        return $products;
    }
}

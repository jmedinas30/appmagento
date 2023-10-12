<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreLocator\Controller\Location;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Type;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\GroupedProduct\Model\Product\Type\Grouped;
use MageWorx\StoreLocator\Helper\Data;

/**
 * Class UpdateLocatorOnProductPage
 */
abstract class UpdateLocatorOnProductPage extends Action
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * UpdateLocatorOnProductPage constructor.
     *
     * @param Data $helper
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param ProductRepositoryInterface $productRepository
     * @param Context $context
     */
    public function __construct(
        Data $helper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        ProductRepositoryInterface $productRepository,
        Context $context
    ) {
        $this->helper            = $helper;
        $this->storeManager      = $storeManager;
        $this->productRepository = $productRepository;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\View\Element\BlockInterface
     */
    abstract protected function getBlock();

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        if (!$this->getRequest()->isAjax()) {
            return;
        }

        $productId      = $this->getRequest()->getParam('product');
        $superAttribute = $this->getRequest()->getParam('super_attribute') ?? [];
        $product        = $this->productRepository->getById(
            $productId,
            false,
            $this->storeManager->getStore()->getId()
        );

        $block = $this->getBlock();

        if ($product->getTypeId() === Type::TYPE_BUNDLE) {
            $product = $product->getTypeInstance(true)
                               ->getSelectionsCollection(
                                   $product->getTypeInstance(true)->getOptionsIds($product),
                                   $product
                               );
        } elseif ($product->getTypeId() === Grouped::TYPE_CODE) {
            $product = $product->getTypeInstance()->getAssociatedProducts($product);
        } elseif ($product->getTypeId() === Configurable::TYPE_CODE) {
            if (empty($superAttribute)) {
                $this->getResponse()->setHeader('Content-Type', 'text/html', true);
                $this->getResponse()->setContent(
                    '<div class="location-button__select_option">' .
                    $this->helper->getSelectOptionText() . '</div>'
                );

                return;
            }

            $product = $this->getProductForConfigurableType($product, $superAttribute);
        }

        $block->setProduct($product);
        $this->_view->renderLayout();

        $this->getResponse()->setHeader('Content-Type', 'text/html', true);
        $this->getResponse()->setContent($block->toHtml());
    }

    /**
     * @param ProductInterface $product
     * @param array $attributeCodes
     * @return ProductInterface
     */
    protected function getProductForConfigurableType($product, $attributeCodes)
    {
        $childCollection = $product->getTypeInstance()->getUsedProductCollection($product);
        $childCollection->setProductFilter($product);
        foreach ($attributeCodes as $id => $value) {
            $attribute = $product->getTypeInstance()->getAttributeById($id, $product);
            if ($attribute->getAttributeCode()) {
                $childCollection->addFieldToFilter($attribute->getAttributeCode(), $value);
            }
        }

        return $childCollection->getFirstItem();
    }
}

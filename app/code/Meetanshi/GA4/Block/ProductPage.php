<?php

namespace Meetanshi\GA4\Block;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Meetanshi\GA4\Helper\Data;

/**
 * Class ProductPage
 * @package Meetanshi\GA4\Block
 */
class ProductPage extends Template
{
    /**
     * @var Registry
     */
    protected $registry;
    /**
     * @var Data
     */
    protected $helper;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $categoryCollection;

    /**
     * Product constructor.
     * @param Template\Context $context
     * @param Registry $registry
     * @param Data $helper
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Registry $registry,
        Data $helper,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $collectionFactory,
        array $data
    ) {
        $this->registry = $registry;
        $this->helper = $helper;
        $this->categoryCollection = $collectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        try {
            if ($this->registry->registry('product') != null) {
                return $this->registry->registry('product')->getId();
            }
        }catch (\Exception $e){
            $this->helper->logMessage($e->getMessage());
        }
        return null;
    }

    /**
     * @return string|array|null
     */
    public function getProductData()
    {
        $price = '';
        try {
            /** @var $product \Magento\Catalog\Model\Product */

            $product = $this->registry->registry('product');
            $multiCategoryArray = [];
            $productSku = $product->getData('sku');
            if ($this->helper->getProductIdentifier() == 1) {
                $productSku = $product->getData('product_id');
            }

            $productName = $product->getName();
            /*$productName = str_replace("'"," ", $productName);
            $productName = str_replace('"'," ", $productName);*/
            $price = number_format((float)$product->getFinalPrice(), 2, '.', '');

            $isProductBrand = false;
            if ($this->helper->isProductBrand()) {
                $isProductBrand = true;
            }

            $categoryIds = $product->getCategoryIds();
            $categories = [];

            if ($categoryIds != null && sizeof($categoryIds) > 0) {
                $categoryFactory = $this->categoryCollection;
                $categories = $categoryFactory->create()
                    ->addAttributeToSelect('*')
                    ->addAttributeToFilter('entity_id', ['in' => [$categoryIds]]);
            }
            foreach ($categories as $category) {
                $categoryName = $category->getName();
                $multiCategoryArray[] = $categoryName;
            }

            $brand = $itemStr = '';
            $itemData = [];

            $attribute = $this->helper->getProductBrandAttribute();
            if ($isProductBrand && $attribute != null) {
                try {
                    $brand = $product->getAttributeText($attribute);
                    if ($brand == null) {
                        $brand = $product->getData($attribute);
                    }
                }catch (\Exception $e){
                    $brand = $product->getData($attribute);
                }
            }

            $itemData['item_name'] = $productName;
            $itemData['item_id'] = $productSku;
            $itemData['price'] = $price;

            if ($this->helper->isProductBrand() && $brand != null) {
                $itemData['item_brand'] = $brand;
            }

            foreach ($multiCategoryArray as $key => $cat) {
                if ($cat != null) {
                    if ($key == 0) {
                        $itemData['item_category'] = $cat;
                    } else {
                        $j = $key + 1;
                        $itemData['item_category'.$j] = $cat;
                    }
                }
            }
        } catch (\Exception $e) {
            $itemData = [];
        }

        $currency = $this->helper->getCurrencyCode();

        $data = [
            'event' => 'view_item',
            'currency' => $currency,
            'value' => $price,
            'ecommerce' => [
                'items' => [$itemData]
            ]
        ];
        return $data;
    }
}

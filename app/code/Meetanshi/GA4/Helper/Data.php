<?php

namespace Meetanshi\GA4\Helper;

use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory as AttributeCollection;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Registry;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\App\ProductMetadataInterface;

/**
 * Class Data
 * @package Meetanshi\GA4\Helper
 */
class Data extends AbstractHelper
{
    /**
     *
     */
    const MODULE_ENABLE = 'mt_ga4/general/enable';
    /**
     *
     */
    const GA_JS_CODE = 'mt_ga4/general/gt_jscode';
    /**
     *
     */
    const GA_NONJS_CODE = 'mt_ga4/general/gt_nonjscode';
    /**
     *
     */
    const IMPRESSION_CHUNK_SIZE = 'mt_ga4/general/impressionc_size';
    /**
     *
     */
    const PRODUCT_IDENTIFIER = 'mt_ga4/general/product_identifier';
    /**
     *
     */
    const PRODUCT_BRAND = 'mt_ga4/general/product_brand';
    /**
     *
     */
    const PRODUCT_BRAND_ATTRIBUTE = 'mt_ga4/general/product_brand_attribute';
    /**
     *
     */
    const ENABLE_VARIANT = 'mt_ga4/general/enable_variant';
    /**
     *
     */
    const ORDER_TOTAL_CALCULATION = 'mt_ga4/general/order_total';
    /**
     *
     */
    const EXCLUDE_TAX_TRANSACTION = 'mt_ga4/general/exclude_order_trans';
    /**
     *
     */
    const EXCLUDE_SHIP_TRANSACTION = 'mt_ga4/general/exclude_shipping_trans';
    /**
     *
     */
    const EXCLUDE_SHIP_INCLUDING_TAX = 'mt_ga4/general/exclude_shipping_includetax';
    /**
     *
     */
    const SUCCESS_PATH = 'mt_ga4/general/success_path';
    /**
     *
     */
    const EXCLUDE_ZERO_ORDER = 'mt_ga4/general/exclude_order_zero';
    /**
     *
     */
    const MEASURE_PRODUCT_CLICK = 'mt_ga4/general/measure_product_click';
    /**
     *
     */
    const CHILD_PARENT = 'mt_ga4/general/child_parent';
    /**
     *
     */
    const GTM_ACCOUNT_ID = 'mt_ga4/gtm_api/account_id';
    /**
     *
     */
    const GTM_CONTAINER_ID = 'mt_ga4/gtm_api/container_id';
    /**
     *
     */
    const GTM_MEASUREMENT_ID = 'mt_ga4/gtm_api/measurement_id';
    /**
     *
     */
    const JSON_EXPORT_PUBLIC_ID = 'mt_ga4/json_export/public_id';
    /**
     *
     */
    const IS_MOVE_JS_BOTTOM = 'dev/js/move_script_to_bottom';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var AttributeCollection
     */
    protected $productAttributes;
    /**
     * @var \Magento\Framework\View\Element\BlockFactory
     */
    protected $block;
    /**
     * @var Registry
     */
    protected $registry;
    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $orderCollection;
    /**
     * @var \Magento\Quote\Model\QuoteFactory
     */
    protected $quoteFactory;
    /**
     * @var \Magento\Quote\Model\ResourceModel\Quote\Item\CollectionFactory
     */
    protected $quoteItemCollectionFactory;
    /**
     * @var \Magento\Checkout\Model\SessionFactory
     */
    protected $checkoutSession;
    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;
    /**
     * @var Configurable
     */
    protected $configurable;
    /**
     * @var
     */
    protected $categoryCollection;
    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrencyInterFace;
    /**
     * @var ProductFactory
     */
    protected $productFactory;
    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonData;
    /**
     * @var ProductMetadataInterface
     */
    protected $productMetadata;

    /**
     * Data constructor.
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param AttributeCollection $productAttributes
     * @param \Magento\Framework\View\Element\BlockFactory $blockFactory
     * @param Registry $registry
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     * @param \Magento\Quote\Model\QuoteFactory $quoteFactory
     * @param \Magento\Quote\Model\ResourceModel\Quote\Item\CollectionFactory $quoteItemCollectionFactory
     * @param \Magento\Checkout\Model\SessionFactory $checkoutSession
     * @param \Magento\Framework\Escaper $escaper
     * @param CollectionFactory $collectionFactory
     * @param Configurable $configurable
     * @param PriceCurrencyInterface $priceCurrency
     * @param ProductFactory $productFactory
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        AttributeCollection $productAttributes,
        \Magento\Framework\View\Element\BlockFactory $blockFactory,
        Registry $registry,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        \Magento\Quote\Model\ResourceModel\Quote\Item\CollectionFactory $quoteItemCollectionFactory,
        \Magento\Checkout\Model\SessionFactory $checkoutSession,
        \Magento\Framework\Escaper $escaper,
        CollectionFactory $collectionFactory,
        Configurable $configurable,
        PriceCurrencyInterface $priceCurrency,
        ProductFactory $productFactory,
        \Magento\Framework\Json\Helper\Data $jsonData,
        ProductMetadataInterface $productMetadata
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->productAttributes = $productAttributes;
        $this->block = $blockFactory;
        $this->registry = $registry;
        $this->orderCollection = $orderCollectionFactory;
        $this->quoteFactory = $quoteFactory;
        $this->quoteItemCollectionFactory = $quoteItemCollectionFactory;
        $this->checkoutSession = $checkoutSession;
        $this->escaper = $escaper;
        $this->categoryCollection = $collectionFactory;
        $this->configurable = $configurable;
        $this->priceCurrencyInterFace = $priceCurrency;
        $this->productFactory = $productFactory;
        $this->jsonData = $jsonData;
        $this->productMetadata = $productMetadata;
        parent::__construct($context);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function isEnable($storeId = null)
    {
        return $this->scopeConfig->getValue(self::MODULE_ENABLE, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getGAJSCode($storeId = null)
    {
        return $this->scopeConfig->getValue(self::GA_JS_CODE, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getGANonJSCode($storeId = null)
    {
        return $this->scopeConfig->getValue(self::GA_NONJS_CODE, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getImpressionChunkSize($storeId = null)
    {
        return $this->scopeConfig->getValue(self::IMPRESSION_CHUNK_SIZE, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getProductIdentifier($storeId = null)
    {
        return $this->scopeConfig->getValue(self::PRODUCT_IDENTIFIER, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function isProductBrand($storeId = null)
    {
        return $this->scopeConfig->getValue(self::PRODUCT_BRAND, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getProductBrandAttribute($storeId = null)
    {
        return $this->scopeConfig->getValue(self::PRODUCT_BRAND_ATTRIBUTE, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function isEnableVariant($storeId = null)
    {
        return $this->scopeConfig->getValue(self::ENABLE_VARIANT, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getOrderSuccessTotalCalculation($storeId = null)
    {
        return $this->scopeConfig->getValue(self::ORDER_TOTAL_CALCULATION, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function isExcludeTaxFromTransation($storeId = null)
    {
        return $this->scopeConfig->getValue(self::EXCLUDE_TAX_TRANSACTION, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function isExcludeShipTransaction($storeId = null)
    {
        return $this->scopeConfig->getValue(self::EXCLUDE_SHIP_TRANSACTION, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function isExcludeShippingIncludingTax($storeId = null)
    {
        return $this->scopeConfig->getValue(self::EXCLUDE_SHIP_INCLUDING_TAX, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getSuccessPagePath($storeId = null)
    {
        return $this->scopeConfig->getValue(self::SUCCESS_PATH, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function isExcludeOrderWithZero($storeId = null)
    {
        return $this->scopeConfig->getValue(self::EXCLUDE_ZERO_ORDER, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function isMeasureProductLinks($storeId = null)
    {
        return $this->scopeConfig->getValue(self::MEASURE_PRODUCT_CLICK, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getChildParent($storeId = null)
    {
        return $this->scopeConfig->getValue(self::CHILD_PARENT, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getGTMApiAccountId($storeId = null)
    {
        return $this->scopeConfig->getValue(self::GTM_ACCOUNT_ID, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getGTMApiContainerId($storeId = null)
    {
        return $this->scopeConfig->getValue(self::GTM_CONTAINER_ID, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getGTMApiMeasurementId($storeId = null)
    {
        return $this->scopeConfig->getValue(self::GTM_MEASUREMENT_ID, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getJsonExportPublicId($storeId = null)
    {
        return $this->scopeConfig->getValue(self::JSON_EXPORT_PUBLIC_ID, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function isMoveJsBottom($storeId = null)
    {
        return $this->scopeConfig->getValue(self::IS_MOVE_JS_BOTTOM, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getStoreName($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'general/store_information/name',
            ScopeInterface::SCOPE_STORE, $storeId
        );
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        try {
            return $this->productMetadata->getVersion();
        }catch (\Exception $e){
            $this->logMessage($e->getMessage());
        }
        return null;
    }

    /**
     * @param $product
     * @param int $index
     * @param string $list
     * @param string $lId
     * @param int $iSaddProductClick
     * @param null $categoryName
     * @return bool|string
     */
    public function addProductClick(
        $product,
        $index = 0,
        $list = null,
        $categoryName = null
    ) {
        $productClickBlock = $this->createBlock('Category', 'productClick.phtml');
        $html = '';

        if ($this->isMeasureProductLinks()) {
            if ($productClickBlock && $this->isEnable()) {
                $productClickBlock->setProduct($product);
                $productClickBlock->setIndex($index);
                $productClickBlock->setCategoryName($categoryName);

                if ($list != null) {
                    $currentCategory = $this->getCurrentCategory();
                    if (!empty($currentCategory)) {
                        $list = $this->getGA4Category($currentCategory);
                    } else {
                        $requestPath = $this->_request->getModuleName() .
                            DIRECTORY_SEPARATOR . $this->_request->getControllerName() .
                            DIRECTORY_SEPARATOR . $this->_request->getActionName();
                        switch ($requestPath) {
                            case 'catalogsearch/advanced/result':
                                $list = __('Advanced Search Result');
                                break;
                            case 'catalogsearch/result/index':
                                $list = __('Search Result');
                                break;
                        }
                    }
                }
                $productClickBlock->setList($list);
                // @codingStandardsIgnoreLine
                $html = trim($productClickBlock->toHtml());
            }

            if (!empty($html)) {
                $eventOnClick = ', "eventCallback": function() { document.location = "' .
                    $this->escaper->escapeHtml($product->getUrlModel()->getUrl($product)) . '";return false; }});';
                // @codingStandardsIgnoreLine
                $html = substr(rtrim($html, ");"), 0, -1);
                $eventOnClick = str_replace('"', "'", $eventOnClick);
                $html .= $eventOnClick;
            }
        }

        if (!empty($html)) {
            $html = 'onclick="' . $html . '"';
        }
        return $html;
    }

    /**
     * @param $category
     * @return string
     */
    public function getGA4Category($category)
    {
        $categoryPath = $category->getData('path');

        $categIds = explode('/', $categoryPath);
        $ignoreCategories = 2;
        if (sizeof($categIds) < 3) {
            $ignoreCategories = 1;
        }
        $categoryIds = array_slice($categIds, $ignoreCategories);

        $catNames = $this->getCategoryFromCategoryIds($categoryIds, 1);

        return implode('/', $catNames);
    }

    /**
     * @return mixed
     */
    public function getCurrentCategory()
    {
        return $this->registry->registry('current_category');
    }

    /**
     * @return array
     */
    public function getProductAttribute()
    {
        try {
            $attributes = [];
            $attributeInfo = $this->productAttributes->create();
            foreach ($attributeInfo as $items) {
                array_push($attributes,
                    ['value' => $items->getData('attribute_code'), 'label' => $items->getData('attribute_code')]);
            }
        } catch (\Exception $e) {
            $this->_logger->info($e->getMessage());
        }
        return $attributes;
    }

    /**
     * @param $blockName
     * @param $template
     * @return bool
     */
    protected function createBlock($blockName, $template)
    {
        if ($block = $this->block->createBlock('\Meetanshi\GA4\Block\\' . $blockName)
            ->setTemplate('Meetanshi_GA4::' . $template)
        ) {
            return $block;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getCurrentProduct()
    {
        return $this->registry->registry('current_product');
    }

    /**
     * @param string $msg
     */
    public function logMessage($msg = '')
    {
        $this->_logger->info($msg);
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrencyCode()
    {
        try {
            return $this->storeManager->getStore()->getCurrentCurrencyCode();
        }catch (\Magento\Framework\Exception\NoSuchEntityException $e){}
        return null;
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBaseCurrencyCode()
    {
        return $this->storeManager->getStore()->getBaseCurrencyCode();
    }

    /**
     * @param $product
     * @return mixed
     */
    public function getProductBrand($product)
    {
        return $product->getAttributeText($this->getProductBrandAttribute());
    }

    /**
     * @param $product
     * @return mixed
     */
    public function getGA4ProductId($product)
    {
        $productSku = $product->getData('sku');
        if ($this->getProductIdentifier() == 1) {
            $productSku = $product->getData('product_id');
        }

        return $productSku;
    }

    /**
     * @param $categoryIds
     * @param int $multi
     * @return array|null
     */
    public function getCategoryFromCategoryIds($categoryIds, $multi = 0)
    {
        if ($categoryIds == null || sizeof($categoryIds) <= 0){
            return null;
        }

        $categoryFactory = $this->categoryCollection;
        $categories = $categoryFactory->create()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('entity_id', ['in' => [$categoryIds]]);

        $multiCategoryArray = [];
        $categoryName = null;

        foreach ($categories as $category) {
            $categoryName = $category->getName();
            $multiCategoryArray[] = $categoryName;
        }

        if ($multi) {
            return $multiCategoryArray;
        }
        return $categoryName;
    }

    /**
     * @param $qty
     * @param $product
     * @param string $price
     * @return array
     * @throws \Zend_Log_Exception
     */
    public function addToCartData($qty, $product, $price = '')
    {
        $result = [];

        try {
            $result['event'] = 'add_to_cart';
            $result['ecommerce'] = [];
            $result['ecommerce']['currency'] = $this->getCurrencyCode();
            $result['ecommerce']['items'] = [];
            $productData = [];

            $options = $product->getTypeInstance(true)->getOrderOptions($product);
            $variant = '';
            $info = [];
            $option = [];

            if (isset($options['attributes_info'])) {
                $info = $options['attributes_info'];
            } elseif (isset($options['additional_info'])) {
                $info = $options['additional_info'];
            }elseif (isset($options['options'])){
                foreach ($options['options'] as $otp){
                    $option[] = $otp['value'];
                }
            }

            foreach ($info as $value) {
                $option[] = $value['value'];
                /*if ($variant != null) {
                    break;
                }*/
            }

            if (sizeof($option)){
                foreach ($option as $var){
                    if ($variant == '')
                        $variant = $var;
                    else
                        $variant = $variant . ',' . $var;
                }
            }

            $categoryIds = $product->getCategoryIds();
            $brand = null;

            $multiCategoryArray = $this->getCategoryData($categoryIds);

            $productSku = $product->getSku();
            if ($this->getProductIdentifier() == 1) {
                $productSku = $product->getId();
            }

            $productName = html_entity_decode($product->getName());

            if ($this->getChildParent() == 'parent') {
                if ($this->getParentProduct($product) != null) {
                    $parentProduct = $this->getParentProduct($product);
                    $productSku = $parentProduct->getData('sku');
                    $productName = html_entity_decode($parentProduct->getName());

                    if ($this->getProductIdentifier() == 1) {
                        $productSku = $parentProduct->getData('product_id');
                    }
                }
            }

            $brand = null;
            if ($this->isProductBrand()) {
                $attribute = $this->getProductBrandAttribute();
                if ($attribute != null)
                    $brand = $product->getAttributeText($attribute);
                if ($brand == null) {
                    $brand = $product->getData($attribute);
                }
                $productData['item_brand'] = $brand;
            }

            foreach ($multiCategoryArray as $key => $cat) {
                if ($key == 0) {
                    $productData["item_category"] = $cat;
                } else {
                    $j = $key + 1;
                    $productData["item_category$j"] = $cat;
                }
            }

            if ($price == '')
                $price = (float)number_format($product->getPriceInfo()->getPrice('final_price')->getValue(), 2, '.', '');

            $result['ecommerce']['value'] = $price;

            $productData['item_name'] = html_entity_decode($productName);
            $productData['item_id'] = $productSku;
            $productData['price'] = $price;
            $productData['currency'] = $this->getCurrencyCode();

            if ($this->isProductBrand()) {
                $productData['item_brand'] = $brand;
            }

            $productCategoryIds = $product->getCategoryIds();
            $categoryName = $this->getCategoryFromCategoryIds($product->getCategoryIds());
            $productData['item_list_name'] = $categoryName;
            $productData['item_list_id'] = sizeof($productCategoryIds) ? $productCategoryIds[0] : '';
            $productData['quantity'] = $qty;
            $productData['currency'] = $this->getCurrencyCode();

            if ($this->isEnableVariant()) {
                if ($variant != null) {
                    $productData['item_variant'] = $variant;
                }
            }
            $result['ecommerce']['items'][] = $productData;
        } catch (\Exception $e) {
            $this->_logger->info($e->getMessage());
        }

        return $result;
    }

    /**
     * @param $product
     * @param $quoteItem
     * @param $qty
     * @param $price
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function removeCartData($product, $quoteItem, $qty, $price = '')
    {
        $options = $product->getTypeInstance(true)->getOrderOptions($product);
        $variant = '';
        $info = [];
        $result = [];

        $result['event'] = 'remove_from_cart';
        $result['ecommerce'] = [];
        $result['ecommerce']['currency'] = $this->getCurrencyCode();
        $result['ecommerce']['items'] = [];
        $productData = [];

        if (isset($options['attributes_info'])) {
            $info = $options['attributes_info'];
        } elseif (isset($options['additional_info'])) {
            $info = $options['additional_info'];
        }
        foreach ($info as $value) {
            $variant = $value['value'];
            if ($variant != null) {
                break;
            }
        }

        $categoryIds = $product->getCategoryIds();
        $brand = null;

        $multiCategoryArray = $this->getCategoryData($categoryIds);
        $productName = html_entity_decode($product->getName());

        if (!$this->getProductIdentifier()) {
            $productSku = $product->getSku();
        } else {
            $productSku = $product->getId();
        }

        if ($this->getChildParent() == 'parent') {
            if ($this->getParentProduct($product) != null) {
                $parentProduct = $this->getParentProduct($product);
                $productSku = $parentProduct->getData('sku');
                $productName = html_entity_decode($parentProduct->getName());

                if ($this->getProductIdentifier() == 1) {
                    $productSku = $parentProduct->getData('product_id');
                }
                $productData['item_id'] = $productSku;
            }
        }

        if ($price == '')
            $price = (float)number_format($product->getPriceInfo()->getPrice('final_price')->getValue(), 2, '.', '');

        $result['ecommerce']['value'] = $price;

        $brand = null;
        if ($this->isProductBrand()) {
            $attribute = $this->getProductBrandAttribute();
            if ($attribute != null)
                $brand = $product->getAttributeText($attribute);
            if ($brand == null) {
                $brand = $product->getData($attribute);
            }
            $productData['item_brand'] = $brand;
        }

        foreach ($multiCategoryArray as $key => $cat) {
            if ($key == 0) {
                $productData["item_category"] = $cat;
            } else {
                $j = $key + 1;
                $productData["item_category$j"] = $cat;
            }
        }

        $productData['item_name'] = $productName;
        $productData['item_id'] = $productSku;
        $productData['price'] = $price;
        if ($this->isProductBrand()) {
            $productData['item_brand'] = $brand;
        }

        $productCategoryIds = $product->getCategoryIds();
        $categoryName = $this->getCategoryFromCategoryIds($product->getCategoryIds());
        $productData['item_list_name'] = $categoryName;
        $productData['item_list_id'] = sizeof($productCategoryIds) ? $productCategoryIds[0] : '';
        $productData['quantity'] = $qty;
        $productData['currency'] = $this->getCurrencyCode();

        if ($this->isEnableVariant() && $variant != null) {
            $productData['item_variant'] = $variant;
        }

        $result['ecommerce']['items'][] = $productData;

        return $result;
    }

    /**
     * @param $product
     * @param $wishListBuyRequest
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function addToWishListData($product, $wishListBuyRequest)
    {
        $result = [];

        $result['event'] = 'add_to_wishlist';
        $result['ecommerce'] = [];
        $result['ecommerce']['currency'] = $this->getCurrencyCode();
        $result['ecommerce']['items'] = [];
        $productData = [];

        $categoryIds = $product->getCategoryIds();
        $brand = null;

        $multiCategoryArray = $this->getCategoryData($categoryIds);

        $productName = html_entity_decode($product->getName());

        if (!$this->getProductIdentifier()) {
            $productSku = $product->getSku();
        } else {
            $productSku = $product->getId();
        }

        if ($this->getChildParent() == 'parent') {
            if ($this->getParentProduct($product) != null) {
                $parentProduct = $this->getParentProduct($product);
                $productSku = $parentProduct->getData('sku');
                $productName = html_entity_decode($parentProduct->getName());

                if ($this->getProductIdentifier() == 1) {
                    $productSku = $parentProduct->getData('product_id');
                }
                $productData['item_id'] = $productSku;
            }
        }

        $price = $productData['price'] = number_format((float)$product->getPriceInfo()->getPrice('final_price')->getValue(),
            2, '.', '');

        $brand = null;
        if ($this->isProductBrand()) {
            $attribute = $this->getProductBrandAttribute();
            if ($attribute != null)
                $brand = $product->getAttributeText($attribute);
            if ($brand == null) {
                $brand = $product->getData($attribute);
            }
            $productData['item_brand'] = $brand;
        }

        foreach ($multiCategoryArray as $key => $cat) {
            if ($key == 0) {
                $productData['item_category'] = $cat;
            } else {
                $j = $key + 1;
                $productData['item_category'.$j] = $cat;
            }
        }

        $productData['item_name'] = $productName;
        $productData['item_id'] = $productSku;
        $productData['price'] = $price;
        if ($this->isProductBrand()) {
            $productData['item_brand'] = $brand;
        }

        $productCategoryIds = $product->getCategoryIds();
        $categoryName = $this->getCategoryFromCategoryIds($product->getCategoryIds());
        $productData['item_list_name'] = $categoryName;
        $productData['item_list_id'] = sizeof($productCategoryIds) ? $productCategoryIds[0] : '';

        if ($this->isEnableVariant()) {
            $productData['item_variant'] = $this->getVariantForProduct($product, $wishListBuyRequest);
        }

        $result['ecommerce']['items'][] = $productData;

        return $result;
    }

    /**
     * @param $product
     * @param $buyRequest
     * @return null|string
     */
    public function getVariantForProduct($product, $buyRequest)
    {
        $variant = [];

        if ($product->getTypeId() == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
            $options = $product->getTypeInstance(true)->getSelectedAttributesInfo($product);
            foreach ($options as $option) {
                $variant[] = $option['label'] . ": " . $option['value'];
            }

            if (!$variant && isset($buyRequest['super_attribute'])) {
                $superAttributeLabels = [];
                $superAttributeOptions = [];
                $attributes = $product->getTypeInstance(true)->getConfigurableAttributes($product);
                foreach ($attributes as $attribute) {
                    $superAttributeLabels[$attribute['attribute_id']] = $attribute['label'];
                    foreach ($attribute->getOptions() as $option) {
                        $superAttributeOptions[$attribute['attribute_id']][$option['value_index']] = $option['store_label'];
                    }
                }

                foreach ($buyRequest['super_attribute'] as $key => $value) {
                    $variant[] = $superAttributeLabels[$key] . ": " . $superAttributeOptions[$key][$value];
                }
            }
        }

        if ($variant) {
            return implode(' | ', $variant);
        }
        return null;
    }

    /**
     * @param $product
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function addToCompareData($product)
    {
        $result = [];

        $result['event'] = 'add_to_compare';
        $result['ecommerce'] = [];
        $result['ecommerce']['items'] = [];
        $productData = [];
        $productData['currency'] = $this->getCurrencyCode();

        $productName = html_entity_decode($product->getName());

        if (!$this->getProductIdentifier()) {
            $productSku = $product->getSku();
        } else {
            $productSku = $product->getId();
        }

        if ($this->getChildParent() == 'parent') {
            if ($this->getParentProduct($product) != null) {
                $parentProduct = $this->getParentProduct($product);
                $productSku = $parentProduct->getData('sku');
                $productName = html_entity_decode($parentProduct->getName());

                if ($this->getProductIdentifier() == 1) {
                    $productSku = $parentProduct->getData('product_id');
                }
                $productData['item_id'] = $productSku;
            }
        }

        $price = $productData['price'] = number_format((float)$product->getPriceInfo()->getPrice('final_price')->getValue(),
            2, '.', '');

        $productData['item_name'] = $productName;
        $productData['item_id'] = $productSku;
        $productData['price'] = $price;

        $brand = null;
        if ($this->isProductBrand()) {
            $attribute = $this->getProductBrandAttribute();
            if ($attribute != null)
                $brand = $product->getAttributeText($attribute);
            if ($brand == null) {
                $brand = $product->getData($attribute);
            }
            $productData['item_brand'] = $brand;
        }

        $result['ecommerce']['items'][] = $productData;

        return $result;
    }

    /**
     * @param $product
     * @return \Magento\Catalog\Model\Product|null
     */
    public function getParentProduct($product)
    {
        $parentId = null;
        $configProduct = $this->configurable->getParentIdsByChild($product->getId());
        if (isset($configProduct[0])) {
            $parentId = $configProduct[0];
        }
        if ($parentId != null) {
            $parentProduct = $this->productFactory->create($parentId);
            return $parentProduct;
        }
        return null;
    }

    /**
     * @param $categoryIds
     * @return array
     */
    public function getCategoryData($categoryIds)
    {
        if ($categoryIds == null || sizeof($categoryIds) <= 0){
            return [];
        }

        $multiCategoryArray = [];
        $categoryFactory = $this->categoryCollection;
        $categories = $categoryFactory->create()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('entity_id', ['in' => [$categoryIds]]);

        foreach ($categories as $category) {
            $categoryName = $category->getName();
            $multiCategoryArray[] = $categoryName;
        }

        return $multiCategoryArray;
    }

    /**
     * @param $quoteId
     * @return string
     */
    public function getCheckoutCartData($quoteId)
    {
        $itemStr = $this->getQuoteItems($quoteId, 'cart_view', 1);
        $quote = $this->quoteFactory->create()->load($quoteId);
        $total = number_format((float)$quote->getGrandTotal(), 2, '.', '');
        try {
            $curCode = $this->storeManager->getStore()->getCurrentCurrencyCode();
        }catch (\Exception $e){$this->logMessage($e->getMessage()); $curCode = null;}
        $itemData = $this->jsonData->jsonEncode($itemStr);

        $str = "window.dataLayer.push({ ecommerce: null }); window.dataLayer.push({event: 'view_cart', ecommerce: {currency: '$curCode', value: $total, items: $itemData}});";

        return $str;
    }

    /**
     * @param $listStr
     * @param $prodData
     * @return string
     */
    public function getListData($listStr, $prodData)
    {
        $categoryName = null;
        $categoryIds = $prodData['catids'];
        $categories = [];

        if ($categoryIds != null && sizeof($categoryIds) > 0) {
            $categoryFactory = $this->categoryCollection;
            $categories = $categoryFactory->create()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('entity_id', ['in' => [$categoryIds]]);
        }
        $productName = $prodData['name'];
        $productName = str_replace("'"," ", $productName);
        $productName = str_replace('"'," ", $productName);
        $productSku = $prodData['sku'];
        $productId = $prodData['id'];
        $price = number_format((float)$prodData['price'], 2, '.', '');
        $brand = $prodData['brand'];
        $qty = $prodData['qty'];

        if ($this->getProductIdentifier() == 1) {
            $productSku = $productId;
        }

        foreach ($categories as $category) {
            $categoryName = $category->getName();
            $multiCategoryArray[] = $categoryName;
        }

        if (sizeof($prodData)) {
            if ($listStr == '') {
                $listStr = "{
                        item_name: '$productName',
                        item_id: '$productSku',
                        price: $price,
                        quantity: $qty,
                        item_category: '$categoryName'";

                if ($this->isProductBrand() && $brand != null) {
                    $listStr .= ",\nitem_brand: '$brand'";
                }
                $listStr .= "\n}";
            } else {
                $listStr .= ", {
                        item_name: '$productName',
                        item_id: '$productSku',
                        price: $price,
                        quantity: $qty,
                        item_category: '$categoryName'";

                if ($this->isProductBrand() && $brand != null) {
                    $listStr .= ",\nitem_brand: '$brand'";
                }
                $listStr .= "\n}";
            }
        }

        return $listStr;
    }

    /**
     * @param $orderId
     * @param string $event
     * @param int $refund
     * @param array $refundData
     * @return array|string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPurchaseData($orderId, $event = '', $refund = 0, $refundData = [])
    {
        $orders = $this->orderCollection->create()
            ->addFieldToSelect('*')
            ->addAttributeToFilter('entity_id', $orderId);

        $quoteId = null;
        $total = 0;

        if ($orders->count()) {
            $coupon = $transId = $storeName = $orderTax = $shipAmount = $currencyCode = null;
            foreach ($orders->getData() as $order) {
                $transId = $order['increment_id'];
                //$storeName = $order['store_name'];
                if ($this->getStoreName() != null) {
                    $storeName = $this->getStoreName();
                } else {
                    $storeName = $this->storeManager->getStore()->getName();
                }
                $currencyCode = $order['order_currency_code'];
                $grandTotal = number_format((float)$order['grand_total'], 2, '.', '');
                $subTotal = number_format((float)$order['subtotal'], 2, '.', '');
                $quoteId = $order['quote_id'];
                $coupon = $order['coupon_rule_name'];
                $shipAmount = number_format((float)$order['shipping_incl_tax'], 2, '.', '');
                $orderTax = number_format((float)$order['tax_amount'], 2, '.', '');
                $shipTaxAmount = number_format((float)$order['shipping_tax_amount'], 2, '.', '');

                if ($this->getOrderSuccessTotalCalculation() == 'subtotal') {
                    $total = $subTotal;
                } else {
                    $total = $grandTotal;
                }
                if ($this->isExcludeShippingIncludingTax()) {
                    $shipAmount = $shipAmount - $shipTaxAmount;
                }
            }

            //$shipAmount = (float)number_format($shipAmount, 2);
            //$total = (float)number_format($total, 2);

            if ($this->isExcludeOrderWithZero()) {
                if ($total <= 0) {
                    return '';
                }
            }

            $itemArray = [];
            if ($event == 'refund'){
                if ($refundData != null && sizeof($refundData)) {
                    $total = $refundData['total'];
                    $shipAmount = $refundData['shipping'];
                    $orderTax = $refundData['tax'];
                    $itemArray = $refundData['item'];
                }
            }else{
                $itemArray = $this->getItemsArrayData($quoteId);
            }

            if ($refund){
                $data = [
                    'event' => 'refund',
                    'ecommerce' => [
                        'transaction_id' => $transId,
                        'affiliation' => $storeName,
                        'value' => $total,
                        'tax' => $orderTax,
                        'shipping' => $shipAmount,
                        'currency' => $currencyCode
                    ]
                ];
            }else {
                $data = [
                    'event' => 'purchase',
                    'ecommerce' => [
                        'transaction_id' => $transId,
                        'affiliation' => $storeName,
                        'value' => $total,
                        'tax' => $orderTax,
                        'shipping' => $shipAmount,
                        'currency' => $currencyCode
                    ]
                ];
            }
            if ($coupon != null && $coupon != '') {
                $data['ecommerce']['coupon'] = $coupon;
            }
            $data['ecommerce']['items'] = $itemArray;

            return $data;
        }
        return [];
    }

    /**
     * @param $quoteId
     * @return array
     */
    public function getItemsArrayData($quoteId)
    {
        $quote = $this->quoteFactory->create()->load($quoteId);
        $items = $quote->getAllVisibleItems();
        $isProductBrand = false;
        $multiCategoryArray = null;
        $tax = 0;
        if ($this->isProductBrand()) {
            $isProductBrand = true;
        }
        $brand = null;
        $itemData = null;
        $allItemData = null;

        foreach ($items as $item) {
            $parentItemId = $item->getParentItemId();
            $productName = $item->getName();
            $productSku = $item->getSku();
            //$price = (float)number_format($item->getBasePrice(), 2);
            $price = $productData['price'] = number_format((float)$item->getProduct()->getPriceInfo()->getPrice('final_price')->getValue(),2, '.', '');

            $tax += number_format((float)$item->getTaxAmount(), 2, '.', '');
            $qty = $item->getQty();

            if ($this->getProductIdentifier() == 1) {
                $productSku = $item->getProduct()->getId();
            }
            if ($this->getChildParent() == 'parent') {
                if ($parentItemId != null) {
                    $quoteItemCollection = $this->quoteItemCollectionFactory->create();
                    $quoteItem = $quoteItemCollection
                        ->addFieldToSelect('*')
                        ->addFieldToFilter('item_id', $parentItemId)
                        ->getFirstItem();

                    $productSku = $quoteItem->getData('sku');
                    if ($this->getProductIdentifier() == 1) {
                        $productSku = $quoteItem->getData('product_id');
                    }
                }
            }

            $attribute = $this->getProductBrandAttribute();
            if ($isProductBrand && $attribute != null) {
                $brand = $item->getProduct()->getAttributeText($attribute);
                if ($brand == null) {
                    $brand = $item->getProduct()->getData($attribute);
                }
                if ($brand == null){
                    /** @var $product \Magento\Catalog\Model\Product */
                    $product = $this->productFactory->create()->load($item->getProduct()->getId());
                    $brand = $product->getAttributeText($attribute);
                    if ($brand == null){
                        $brand = $product->getData($attribute);
                    }
                }
            }

            $options = $item->getProduct()->getTypeInstance(true)->getOrderOptions($item->getProduct());
            $info = [];
            $variant = '';

            if (isset($options['attributes_info'])) {
                $info = $options['attributes_info'];
            } elseif (isset($options['additional_info'])) {
                $info = $options['additional_info'];
            }
            foreach ($info as $value) {
                $variant = $value['value'];
                if ($variant != null) {
                    break;
                }
            }
            $categoryIds = $item->getProduct()->getCategoryIds();
            $categories = [];
            $categoryName = null;

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
            $itemData['item_name'] = $productName;
            $itemData['item_id'] = $productSku;
            $itemData['price'] = $price;
            $itemData['quantity'] = $qty;
            $itemData['item_category'] = $categoryName;

            if ($this->isProductBrand() && $brand != null && $brand != " ") {
                $itemData['item_brand'] = $brand;
            }
            if ($this->isEnableVariant() && $variant != null) {
                $itemData['item_variant'] = $variant;
            }

            $allItemData[] = $itemData;
            $itemData = null;
            $multiCategoryArray = [];
        }

        return $allItemData;
    }

    /**
     * @param $quoteId
     * @param $eventName
     * @param int $multiCategory
     * @param string $couponName
     * @param int $couponId
     * @return string|null|array
     */
    public function getQuoteItems($quoteId, $eventName, $multiCategory = 0, $couponName = '', $couponId = 0)
    {
        $quote = $this->quoteFactory->create()->load($quoteId);
        $items = $quote->getAllVisibleItems();
        $itemStr = '';
        $isProductBrand = false;
        $multiCategoryArray = [];
        $tax = 0;
        if ($this->isProductBrand()) {
            $isProductBrand = true;
        }
        $i = 0;
        $brand = null;
        $itemData = null;
        $allItemsData = null;

        foreach ($items as $item) {
            $parentItemId = $item->getParentItemId();
            $productName = $item->getName();
            $productSku = $item->getSku();
            // $price = (float)number_format($item->getBasePrice(), 2);
            $price = $productData['price'] = number_format((float)$item->getProduct()->getPriceInfo()->getPrice('final_price')->getValue(),2, '.', '');
            $tax += number_format((float)$item->getTaxAmount(), 2, '.', '');
            $qty = $item->getQty();

            if ($this->getProductIdentifier() == 1) {
                $productSku = $item->getProduct()->getId();
            }
            if ($this->getChildParent() == 'parent') {
                if ($parentItemId != null) {
                    $quoteItemCollection = $this->quoteItemCollectionFactory->create();
                    $quoteItem = $quoteItemCollection
                        ->addFieldToSelect('*')
                        ->addFieldToFilter('item_id', $parentItemId)
                        ->getFirstItem();

                    $productSku = $quoteItem->getData('sku');
                    if ($this->getProductIdentifier() == 1) {
                        $productSku = $quoteItem->getData('product_id');
                    }
                }
            }
            $attribute = $this->getProductBrandAttribute();
            if ($isProductBrand && $attribute != null) {
                $brand = $item->getProduct()->getAttributeText($attribute);
                if ($brand == null) {
                    $brand = $item->getProduct()->getData($attribute);
                }
                if ($brand == null){
                    /** @var $product \Magento\Catalog\Model\Product */
                    $product = $this->productFactory->create()->load($item->getProduct()->getId());
                    $brand = $product->getAttributeText($attribute);
                    if ($brand == null){
                        $brand = $product->getData($attribute);
                    }
                }
            }

            $options = $item->getProduct()->getTypeInstance(true)->getOrderOptions($item->getProduct());
            $info = [];
            $variant = '';

            if (isset($options['attributes_info'])) {
                $info = $options['attributes_info'];
            } elseif (isset($options['additional_info'])) {
                $info = $options['additional_info'];
            }
            foreach ($info as $value) {
                $variant = $value['value'];
                if ($variant != null) {
                    break;
                }
            }
            $categoryIds = $item->getProduct()->getCategoryIds();
            $categories = [];
            $categoryName = null;

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

            if ($i == 0 && $eventName == 'purchase') {
                $itemStr = "{
                        item_name: '$productName',
                        item_id: '$productSku',
                        price: $price,
                        quantity: $qty,
                        item_category: '$categoryName'";

                if ($this->isProductBrand() && $brand != null) {
                    $itemStr .= ",\nitem_brand: '$brand'";
                }
                if ($this->isEnableVariant() && $variant != null) {
                    $itemStr .= ",\nitem_variant: '$variant'";
                }
                $itemStr .= "\n}";
            } elseif ($eventName == 'purchase') {
                $itemStr .= ", {
                        item_name: '$productName',
                        item_id: '$productSku',
                        price: $price,
                        quantity: $qty,
                        item_category: '$categoryName'";

                if ($this->isProductBrand() && $brand != null) {
                    $itemStr .= ",\nitem_brand: '$brand'";
                }
                if ($this->isEnableVariant() && $variant != null) {
                    $itemStr .= ",\nitem_variant: '$variant'";
                }
                $itemStr .= "\n}";
            }

            if ($eventName == 'checkout') {
                $itemData['item_name'] = $productName;
                $itemData['item_id'] = $productSku;
                $itemData['price'] = $price;

                if ($this->isProductBrand() && $brand != null) {
                    $itemData['item_brand'] = $brand;
                }

                foreach ($multiCategoryArray as $key => $cat) {
                    if ($key == 0) {
                        $itemData['item_category'] = $cat;
                    } else {
                        $j = $key + 1;
                        $itemData['item_category'.$j] = $cat;
                    }
                }

                if ($this->isEnableVariant() && $variant != null) {
                    $itemData['item_variant'] = $variant;
                }
                $itemData['quantity'] = $qty;
            }

            if ($eventName == 'coupon' || $eventName == 'cart_view') {
                $itemData['item_name'] = $productName;
                $itemData['item_id'] = $productSku;
                $itemData['price'] = $price;

                if ($this->isProductBrand() && $brand != null) {
                    $itemData['item_brand'] = $brand;
                }

                foreach ($multiCategoryArray as $key => $cat) {
                    if ($key == 0) {
                        $itemData['item_category'] = $cat;
                    } else {
                        $j = $key + 1;
                        $itemData['item_category'.$j] = $cat;
                    }
                }

                if ($this->isEnableVariant() && $variant != null) {
                    $itemData['item_variant'] = $variant;
                }
                $itemData['quantity'] = $qty;

                if ($eventName == 'coupon') {
                    $itemData['promotion_id'] = $couponId;
                    $itemData['promotion_name'] = $couponName;
                }
            }
            $allItemsData[] = $itemData;
            $itemData = null;
            $multiCategoryArray = [];
        }
        return $allItemsData;
    }

    /**
     * @param $cartId
     * @param $shipName
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getShippingInfo($cartId, $shipName)
    {
        $result = [];
        $result['event'] = 'add_shipping_info';
        $result['ecommerce'] = [];
        $result['ecommerce']['currency'] = $this->getCurrencyCode();
        $result['ecommerce']['shipping_tier'] = $shipName;

        $itemArray = $this->getItemsArrayData($cartId);
        $result['ecommerce']['items'] = $itemArray;
        return $result;
    }

    /**
     * @param $cartId
     * @param $code
     * @param $id
     * @return string
     */
    public function getCouponData($cartId, $code, $id)
    {
        $itemArray = $this->getQuoteItems($cartId, 'coupon', 0, $code, $id);
        $items = $this->jsonData->jsonEncode($itemArray);

        $str = "window.dataLayer.push({ ecommerce: null }); window.dataLayer.push({event: 'select_promotion',ecommerce: {items: $items}});";

        return $str;
    }

    /**
     * @param $cartId
     * @param $paymentName
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPaymentInfo($cartId, $paymentName)
    {
        $result = [];
        $result['event'] = 'add_payment_info';
        $result['ecommerce'] = [];
        $result['ecommerce']['currency'] = $this->getCurrencyCode();
        $result['ecommerce']['payment_type'] = $paymentName;

        $itemArray = $this->getItemsArrayData($cartId);
        $result['ecommerce']['items'] = $itemArray;
        return $result;
    }

    /**
     * @param $quoteId
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCheckoutData($quoteId)
    {
        $itemStr = $this->getQuoteItems($quoteId, 'checkout', 1);
        $itemData = $this->jsonData->jsonEncode($itemStr);

        $currency = $this->getCurrencyCode();
        $str = "window.dataLayer.push({ ecommerce: null }); window.dataLayer.push({event: 'begin_checkout', ecommerce: {currency: $currency,items: $itemData}});";

        return $str;
    }

    /**
     * @param $price
     * @return float|string
     */
    public function getCurrentStorePrice($price)
    {
        try {
            $priceCurrency = $this->priceCurrencyInterFace;
            if ($this->getCurrencyCode() != $this->getBaseCurrencyCode()) {
                return $priceCurrency->convert($price, $this->storeManager->getStore(), $this->getCurrencyCode());
            }
            return number_format((float)$price, 2, '.', '');
        } catch (\Exception $e) {
            $this->_logger->info($e->getMessage());
        }
        return '';
    }
}
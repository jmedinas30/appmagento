<?php

namespace Meetanshi\GA4\Observer;

use Magento\Framework\Event\ObserverInterface;
use Meetanshi\GA4\Helper\Data;
use Magento\Backend\Model\Session;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Catalog\Model\ProductFactory;

class OrderRefund implements ObserverInterface
{
    protected $helper;
    protected $session;
    protected $quoteFactory;
    protected $quoteItemCollectionFactory;
    protected $categoryCollection;
    protected $productFactory;

    public function __construct
    (
        Data $data,
        Session $session,
        CollectionFactory $collectionFactory,
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        \Magento\Quote\Model\ResourceModel\Quote\Item\CollectionFactory $quoteItemCollectionFactory,
        ProductFactory $productFactory
    )
    {
        $this->helper = $data;
        $this->session = $session;
        $this->categoryCollection = $collectionFactory;
        $this->quoteFactory = $quoteFactory;
        $this->quoteItemCollectionFactory = $quoteItemCollectionFactory;
        $this->productFactory = $productFactory;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order\Creditmemo $creditmemo */
        $creditmemo = $observer->getEvent()->getCreditmemo();

        if ($this->helper->isEnable()) {
            if ($creditmemo && $creditmemo->getOrderId()) {
                $data['id'] = $creditmemo->getOrderId();
                $data['event'] = 'refund';
                $data['refund'] = 1;
                $data['total'] = number_format((float)$creditmemo->getGrandTotal(), 2, '.', '');
                $data['shipping'] = number_format((float)$creditmemo->getShippingAmount(), 2, '.', '');
                $data['tax'] = number_format((float)$creditmemo->getTaxAmount(), 2, '.', '');
                $quoteId = $creditmemo->getOrder()->getQuoteId();
                $data['quoteId'] = $quoteId;
                $allItemData = [];

                foreach ($creditmemo->getAllItems() as $item){
                    $itemData = $this->getItemData($item, $quoteId);
                    if ($itemData != null) {
                        $allItemData[] = $itemData;
                    }
                }
                $data['item'] = $allItemData;
            } else {
                $data = [];
            }
            $this->session->setGA4RefundOrder($data);
        }
    }

    public function getItemData($item, $quoteId)
    {
        /** @var \Magento\Sales\Api\Data\CreditmemoItemInterface $item */
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $brand = null;
        $itemData = null;
        $orderItemId = $item->getOrderItemId();

        /** @var \Magento\Sales\Api\OrderItemRepositoryInterface $orderItemRepository */
        $orderItemRepository = $objectManager->get('Magento\Sales\Api\OrderItemRepositoryInterface');
        $itemCollection = $orderItemRepository->get($orderItemId);
        $quoteItemId = null;

        if ($itemCollection != null) {
            $quoteItemId = $itemCollection->getQuoteItemId();
        }

        $quote = $this->quoteFactory->create()->load($quoteId);
        $items = $quote->getAllVisibleItems();
        $isProductBrand = false;
        $multiCategoryArray = null;

        if ($this->helper->isProductBrand()) {
            $isProductBrand = true;
        }
        $brand = null;
        $itemData = null;
        $allItemData = null;

        $productName = $item->getName();
        $productSku = $item->getSku();

        foreach ($items as $itemm) {
            if ($quoteItemId == $itemm->getId()) {
                $parentItemId = $itemm->getParentItemId();
                if ($this->helper->getProductIdentifier() == 1) {
                    $productSku = $itemm->getProduct()->getId();
                }
                if ($this->helper->getChildParent() == 'parent') {
                    if ($parentItemId != null) {
                        $quoteItemCollection = $this->quoteItemCollectionFactory->create();
                        $quoteItem = $quoteItemCollection
                            ->addFieldToSelect('*')
                            ->addFieldToFilter('item_id', $parentItemId)
                            ->getFirstItem();

                        $productSku = $quoteItem->getData('sku');
                        if ($this->helper->getProductIdentifier() == 1) {
                            $productSku = $quoteItem->getData('product_id');
                        }
                    }
                }
                $attribute = $this->helper->getProductBrandAttribute();
                if ($isProductBrand && $attribute != null) {
                    $brand = $itemm->getProduct()->getAttributeText($attribute);
                    if ($brand == null) {
                        $brand = $itemm->getProduct()->getData($attribute);
                    }
                    if ($brand == null){
                        if ($item->getProduct()) {
                            /** @var $product \Magento\Catalog\Model\Product */
                            $product = $this->productFactory->create()->load($item->getProduct()->getId());
                            $brand = $product->getAttributeText($attribute);
                            if ($brand == null) {
                                $brand = $product->getData($attribute);
                            }
                        }
                    }
                }

                $options = $itemm->getProduct()->getTypeInstance(true)->getOrderOptions($itemm->getProduct());
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
                $categoryIds = $itemm->getProduct()->getCategoryIds();
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
                $itemData['price'] = $item->getPrice();
                $itemData['quantity'] = $item->getQty();
                $itemData['item_category'] = $categoryName;

                if ($item->getDiscountAmount()) {
                    $itemData['discount'] = $item->getDiscountAmount();
                }

                $itemData['item_category'] = $categoryName;

                if ($this->helper->isProductBrand() && $brand != null && $brand != " ") {
                    $itemData['item_brand'] = $brand;
                }
                if ($this->helper->isEnableVariant() && $variant != null && $variant != " ") {
                    $itemData['item_variant'] = $variant;
                }
            }
        }
        return $itemData;
    }
}
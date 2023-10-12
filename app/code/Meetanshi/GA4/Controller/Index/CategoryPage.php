<?php

namespace Meetanshi\GA4\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Store\Model\StoreManagerInterface;
use Meetanshi\GA4\Helper\Data;

class CategoryPage extends Action
{
    protected $resultJsonFactory;
    protected $collection;
    protected $storeManager;
    protected $helper;
    protected $checkoutSession;
    protected $coupon;
    protected $registry;
    protected $categoryFactory;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Data $data,
        StoreManagerInterface $storeManager,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\SalesRule\Model\Coupon $coupon,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->helper = $data;
        $this->storeManager = $storeManager;
        $this->checkoutSession = $checkoutSession;
        $this->coupon = $coupon;
        $this->registry = $registry;
        $this->categoryFactory = $categoryFactory;
    }

    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
        $params = $this->getRequest()->getParams();

        $returnResult = $this->resultJsonFactory->create();
        $response['success'] = 0;
        $response['data'] = $response['listData'] = null;
        $categoryId = 0;
        if (isset($params['cat'])) {
            if ($params['cat'] != '') {
                $categoryId = $params['cat'];
            }
        }

        if ($categoryId != 0) {
            //$currentCategory = $this->registry->registry('current_category');
            $categoryFactory = $this->categoryFactory;
            $category = $categoryFactory->create()->load($categoryId);
            $categoryProducts = $category->getProductCollection()->addFieldToFilter('visibility',
                4)->addAttributeToSelect('*');

            $listViewItemData = '';

            $cnt = 0;
            foreach ($categoryProducts as $product) {
                $prodData = null;
                $prodData['name'] = strip_tags($product->getName());
                $prodData['id'] = $product->getId();
                $prodData['sku'] = $product->getSku();
                $prodData['price'] = number_format((float)$product->getFinalPrice(), 2, '.', '');
                $prodData['catids'] = $product->getCategoryIds();
                $prodData['qty'] = $product->getQty();
                $brand = null;
                if ($this->helper->isProductBrand()) {
                    /** @var $product \Magento\Catalog\Model\Product */
                    $attribute = $this->helper->getProductBrandAttribute();
                    if ($attribute != null)
                        $brand = $product->getAttributeText($attribute);
                    if ($brand == null) {
                        $brand = $product->getData($attribute);
                    }
                    $prodData['brand'] = $brand;
                }

                $listViewItemData = $this->helper->getListData($listViewItemData, $prodData);

                $cnt++;
                if ($cnt > 20){
                    break;
                }
            }

            if ($listViewItemData != '') {
                $str = "window.dataLayer.push({ ecommerce: null });
                window.dataLayer.push({event: 'view_item_list',ecommerce: {";

                $str .= "items: [$listViewItemData]
                    }
                   });";

                $response['listData'] = "<script>$str</script>";
            } else {
                $response['listData'] = '';
            }

            if (!$this->helper->isEnable()) {
                $returnResult->setData($response);
                return $returnResult;
            }
        }

        $response['success'] = 1;
        $response['data'] = '';
        $returnResult->setData($response);
        return $returnResult;
    }
}

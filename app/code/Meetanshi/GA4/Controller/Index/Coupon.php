<?php

namespace Meetanshi\GA4\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Store\Model\StoreManagerInterface;
use Meetanshi\GA4\Helper\Data;

class Coupon extends Action
{
    protected $resultJsonFactory;
    protected $collection;
    protected $storeManager;
    protected $helper;
    protected $checkoutSession;
    protected $coupon;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Data $data,
        StoreManagerInterface $storeManager,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\SalesRule\Model\Coupon $coupon
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->helper = $data;
        $this->storeManager = $storeManager;
        $this->checkoutSession = $checkoutSession;
        $this->coupon = $coupon;
    }

    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
        $params = $this->getRequest()->getParams();

        $returnResult = $this->resultJsonFactory->create();
        $response['success'] = 0;
        $response['data'] = $response['cartData'] = null;

        if (!$this->helper->isEnable()) {
            $returnResult->setData($response);
            return $returnResult;
        }
        $couponData = null;
        if ($params != null) {
            $quoteId = $this->checkoutSession->getQuoteId();
            $couponCode = $this->checkoutSession->getQuote()->getCouponCode();
            $ruleId = $this->coupon->loadByCode($couponCode)->getRuleId();
            $couponData = $this->helper->getCouponData($quoteId, $couponCode, $ruleId);
        }

        $response['success'] = 1;
        $response['data'] = $couponData;
        $response['cartData'] = '<script id="coupon-ga4-m">' . $couponData . '</script>';
        $returnResult->setData($response);
        return $returnResult;
    }
}

<?php

namespace Meetanshi\GA4\CustomerData;

use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Framework\DataObject;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Json\Helper\Data;

/**
 * Class GAData
 * @package Meetanshi\GA4\CustomerData
 */
class GAData extends DataObject implements SectionSourceInterface
{
    /**
     * @var Data
     */
    protected $helper;
    /**
     * @var CustomerSession
     */
    protected $customerSession;
    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * Constructor
     * @param Data $helper
     * @param CustomerSession $customerSession
     * @param CheckoutSession $checkoutSession
     * @param array $data
     */
    public function __construct(
        Data $helper,
        CustomerSession $customerSession,
        CheckoutSession $checkoutSession,
        array $data = []
    ) {
        parent::__construct($data);
        $this->helper = $helper;
        $this->customerSession = $customerSession;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * {@inheritdoc}
     */
    public function getSectionData()
    {
        $sectionData = [];

        if ($this->checkoutSession->getShippingData()) {
            $shipData = $this->checkoutSession->getShippingData();
            $sectionData[] = $shipData;
        }
        $this->checkoutSession->setShippingData(null);

        if ($this->checkoutSession->getPaymentData()) {
            $paymentData = $this->checkoutSession->getPaymentData();
            $sectionData[] = $paymentData;
        }
        $this->checkoutSession->getPaymentData(null);

        if ($this->checkoutSession->getGA4AddCartProduct()) {
            $sectionData[] = $this->checkoutSession->getGA4AddCartProduct();
        }
        $this->checkoutSession->setGA4AddCartProduct(null);

        if ($this->customerSession->getGA4AddCompareProduct()) {
            $sectionData[] = $this->customerSession->getGA4AddCompareProduct();
        }
        $this->customerSession->setGA4AddCompareProduct(null);

        if ($this->checkoutSession->getGA4RemoveCartProduct()) {
            $sectionData[] = $this->checkoutSession->getGA4RemoveCartProduct();
        }
        $this->checkoutSession->setGA4RemoveCartProduct(null);

        if ($this->customerSession->getGA4AddWishListProduct()) {
            $sectionData[] = $this->customerSession->getGA4AddWishListProduct();
        }
        $this->customerSession->setGA4AddWishListProduct(null);

        return [
            'datalayer' => $this->helper->jsonEncode($sectionData)
        ];
    }
}

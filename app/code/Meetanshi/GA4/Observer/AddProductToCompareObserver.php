<?php

namespace Meetanshi\GA4\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Meetanshi\GA4\Helper\Data;
use \Magento\Customer\Model\Session;

/**
 * Class AddProductToCompareObserver
 * @package Meetanshi\GA4\Observer
 */
class AddProductToCompareObserver implements ObserverInterface
{
    /**
     * @var Data
     */
    protected $helper;
    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * AddProductToCompareObserver constructor.
     * @param Data $helper
     * @param Session $customerSession
     */
    public function __construct(
        Data $helper,
        Session $customerSession
    ) {
        $this->helper = $helper;
        $this->customerSession = $customerSession;
    }

    /**
     * @param Observer $observer
     * @return $this
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        $product = $observer->getData('product');
        if ($this->helper->isEnable()) {
            if ($product != null || $product->getId()) {
                $this->customerSession->setGA4AddCompareProduct($this->helper->addToCompareData($product));
            }
        }
        return $this;
    }
}

<?php

namespace Meetanshi\GA4\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Meetanshi\GA4\Helper\Data;
use Magento\Customer\Model\Session;

/**
 * Class AddProductToWishListObserver
 * @package Meetanshi\GA4\Observer
 */
class AddProductToWishListObserver implements ObserverInterface
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
     * AddProductToWishListObserver constructor.
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
        if ($this->helper->isEnable()) {
            $product = $observer->getData('product');
            $wishListProduct = $observer->getData('item');

            if ($product->getId() || $product != null) {
                $wishListBuyRequest = $wishListProduct->getBuyRequest()->getData();
                $this->customerSession->setGA4AddWishListProduct($this->helper->addToWishListData($product, $wishListBuyRequest));
            }
        }
        return $this;
    }
}

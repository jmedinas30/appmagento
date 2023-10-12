<?php

namespace Meetanshi\GA4\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Session;
use Meetanshi\GA4\Helper\Data;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Event\Observer;

/**
 * Class QuoteRemoveProductObserver
 * @package Meetanshi\GA4\Observer
 */
class QuoteRemoveProductObserver implements ObserverInterface
{
    /**
     * @var Data
     */
    protected $helper;
    /**
     * @var ProductRepository
     */
    protected $productRepository;
    /**
     * @var Session
     */
    protected $checkoutSession;

    /**
     * QuoteRemoveProductObserver constructor.
     * @param Data $helper
     * @param ProductRepository $productRepository
     * @param Session $checkoutSession
     */
    public function __construct(
        Data $helper,
        ProductRepository $productRepository,
        Session $checkoutSession
    )
    {
        $this->helper = $helper;
        $this->productRepository = $productRepository;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        try {
            if ($this->helper->isEnable()) {
                $quoteItem = $observer->getData('quote_item');
                if ($quoteItem != null) {
                    $productId = $quoteItem->getData('product_id');
                    $productSku = $quoteItem->getData('sku');
                    $qty = $quoteItem->getData('qty');
                    $price = $quoteItem->getData('price');

                    if ($productId != null && $productId) {
                        try {
                            if ($price == null || $price <= 0) {
                                $product1 = $this->productRepository->get($productSku);
                                $price = (float)number_format($product1->getPriceInfo()->getPrice('final_price')->getValue(), 2, '.', '');
                            } else {
                                $price = (float)number_format($price, 2, '.', '');
                            }
                            $product = $this->productRepository->getById($productId);
                        } catch (\Exception $e) {
                            $product = $this->productRepository->getById($productId);
                        }
                        $this->checkoutSession->setGA4RemoveCartProduct($this->helper->removeCartData($product, $quoteItem, $qty, $price));
                    }
                }
            }
        } catch (\Exception $e) {
            $this->helper->logMessage($e->getMessage());
        }
        return $this;
    }
}

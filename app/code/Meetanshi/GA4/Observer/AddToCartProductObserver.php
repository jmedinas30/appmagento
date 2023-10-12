<?php

namespace Meetanshi\GA4\Observer;

use Magento\Framework\Event\ObserverInterface;
use Meetanshi\GA4\Helper\Data as DataHelper;
use Magento\Checkout\Model\Session;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\Event\Observer;

/**
 * Class AddToCartProductObserver
 * @package Meetanshi\GA4\Observer
 */
class AddToCartProductObserver implements ObserverInterface
{
    /**
     * @var DataHelper
     */
    protected $helper;
    /**
     * @var Session
     */
    protected $session;
    /**
     * @var ResolverInterface
     */
    protected $resolver;

    /**
     * AddToCartProductObserver constructor.
     * @param DataHelper $helper
     * @param Session $session
     * @param ResolverInterface $resolver
     */
    public function __construct(
        DataHelper $helper,
        Session $session,
        ResolverInterface $resolver
    ) {
        $this->helper = $helper;
        $this->session = $session;
        $this->resolver = $resolver;
    }

    /**
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        if ($this->helper->isEnable()) {
            try {
                $product = $observer->getData('product');
                $request = $observer->getData('request');
                $params = $request->getParams();
                $version = $this->helper->getVersion();
                $qty = 1;
                $price = '';
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

                if (isset($params['options']) && sizeof($params['options'])) {
                    $items = $this->session->getQuote()->getAllItems();
                    $max = 0;
                    $lastItem = null;
                    foreach ($items as $item) {
                        if ($item->getId() > $max) {
                            $max = $item->getId();
                            $lastItem = $item;
                        }
                    }
                    if ($lastItem) {
                        $lastAddedProductPrice = $lastItem->getPrice();
                        $price = (float)number_format($lastAddedProductPrice, 2, '.', '');
                    }
                }else{
                    /** @var \Magento\Catalog\Model\Product $product */
                    if ($product && $product->getSku()){
                        $productRepository = $objectManager->get('\Magento\Catalog\Model\ProductRepository');
                        $product1 = $productRepository->get($product->getSku());

                        $price = (float)number_format($product1->getPriceInfo()->getPrice('final_price')->getValue(), 2, '.', '');
                    }
                }

                if (isset($params['qty'])) {
                    if ($version != null && version_compare($version, '2.4.6', '>=')) {
                        $quantityProcessor = $objectManager->get('Magento\Checkout\Model\Cart\RequestQuantityProcessor');
                        $filter = new \Magento\Framework\Filter\LocalizedToNormalized(['locale' => $objectManager->get(\Magento\Framework\Locale\ResolverInterface::class)->getLocale()]);
                        $qty = $quantityProcessor->prepareQuantity($params['qty']);
                        $qty = $filter->filter($qty);
                    } else {
                        /*$filter = new \Magento\Framework\Filter\LocalizedToNormalized(['locale' => $this->resolver->getLocale()]);
                        $qty = $filter->filter($params['qty']);*/
                        $qty = $params['qty'];
                    }
                }
                $this->session->setGA4AddCartProduct($this->helper->addToCartData($qty, $product, $price));
            }catch (\Exception $e){
                $this->helper->logMessage($e->getMessage());
            }
        }
        return $this;
    }
}
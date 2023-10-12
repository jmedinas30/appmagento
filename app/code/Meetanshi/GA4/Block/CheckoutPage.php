<?php

namespace Meetanshi\GA4\Block;

use Magento\Framework\View\Element\Template;

/**
 * Class CheckoutPage
 * @package Meetanshi\GA4\Block
 */
class CheckoutPage extends Template
{
    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $cart;
    /**
     * @var \Magento\Checkout\Model\SessionFactory
     */
    protected $checkoutSession;

    /**
     * Checkout constructor.
     * @param Template\Context $context
     * @param \Magento\Checkout\Model\Cart $cart
     * @param \Magento\Checkout\Model\SessionFactory $checkoutSession
     * @param array $data
     */
    public function __construct
    (
        Template\Context $context,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Checkout\Model\SessionFactory $checkoutSession,
        array $data = []
    ) {
        $this->cart = $cart;
        $this->checkoutSession = $checkoutSession;
        parent::__construct($context, $data);
    }

    /**
     * @return int
     */
    public function getQuoteId()
    {
        return (int)$this->checkoutSession->create()->getQuote()->getId();
    }
}

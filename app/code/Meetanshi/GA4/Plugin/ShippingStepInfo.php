<?php

namespace Meetanshi\GA4\Plugin;

use Magento\Checkout\Model\Session;
use Magento\Quote\Api\CartRepositoryInterface;
use Meetanshi\GA4\Helper\Data;
use Magento\Checkout\Model\ShippingInformationManagement;

/**
 * Class ShippingInformation
 * @package Meetanshi\GA4\Plugin
 */
class ShippingStepInfo
{
    /**
     * @var Data
     */
    protected $helper;
    /**
     * @var Session
     */
    protected $checkoutSession;
    /**
     * @var CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @param Data $helper
     * @param Session $checkoutSession
     * @param CartRepositoryInterface $quoteRepository
     */
    public function __construct(
        Data $helper,
        Session $checkoutSession,
        CartRepositoryInterface $quoteRepository
    ) {
        $this->helper = $helper;
        $this->checkoutSession = $checkoutSession;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @param ShippingInformationManagement $subject
     * @param callable $proceed
     * @param $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     * @return \Magento\Checkout\Api\Data\PaymentDetailsInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function aroundSaveAddressInformation(
        ShippingInformationManagement $subject,
        callable $proceed,
        $cartId,
        $addressInformation
    ) {
        if ($this->helper->isEnable()) {
            /** @var \Magento\Quote\Model\Quote $quote */
            $quote = $this->quoteRepository->getActive($cartId);
            $shippingAllData = $quote->getShippingAddress()->getShippingDescription();
            $this->checkoutSession->setShippingData(null);
            $this->checkoutSession->setShippingData($this->helper->getShippingInfo($quote->getId(), $shippingAllData));
        }
        return $proceed($cartId, $addressInformation);
    }
}

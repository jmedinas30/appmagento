<?php

namespace Meetanshi\GA4\Plugin;

use Magento\Checkout\Model\Session;
use Meetanshi\GA4\Helper\Data;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Checkout\Model\PaymentInformationManagement;

/**
 * Class PaymentStepInfo
 * @package Meetanshi\GA4\Plugin
 */
class PaymentStepInfo
{
    /**
     * @var Data
     */
    protected $helper;
    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;
    /**
     * @var Session
     */
    protected $checkoutSession;

    /**
     * @param Data $helper
     * @param Session $checkoutSession
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        Data $helper,
        Session $checkoutSession,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->helper = $helper;
        $this->orderRepository = $orderRepository;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @param PaymentInformationManagement $subject
     * @param $result
     * @return mixed
     */
    public function afterSavePaymentInformationAndPlaceOrder(
        PaymentInformationManagement $subject,
        $result
    ) {
        $orderId = $result;

        if ($this->helper->isEnable()) {
            if ($orderId != null){
                $order = $this->orderRepository->get($orderId);
            }else{
                $order = $this->checkoutSession->getLastRealOrder();
            }

            $paymentAdditionalInfo = $order->getPayment()->getAdditionalInformation();
            if (sizeof($paymentAdditionalInfo) && isset($paymentAdditionalInfo['method_title'])) {
                $paymentTitle = $paymentAdditionalInfo['method_title'];
                $this->checkoutSession->setPaymentData($this->helper->getPaymentInfo($order->getQuoteId(), $paymentTitle));
            }
        }

        return $result;
    }
}

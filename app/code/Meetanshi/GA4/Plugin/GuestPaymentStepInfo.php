<?php

namespace Meetanshi\GA4\Plugin;

use Magento\Checkout\Model\Session;
use Meetanshi\GA4\Helper\Data;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Checkout\Model\GuestPaymentInformationManagement;

/**
 * Class GuestPaymentStepInfo
 * @package Meetanshi\GA4\Plugin
 */
class GuestPaymentStepInfo
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
     * @param OrderRepositoryInterface $orderRepository
     * @param Session $checkoutSession
     */
    public function __construct(
        Data $helper,
        OrderRepositoryInterface $orderRepository,
        Session $checkoutSession
    ) {
        $this->helper = $helper;
        $this->orderRepository = $orderRepository;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @param GuestPaymentInformationManagement $subject
     * @param $result
     * @return mixed
     */
    public function afterSavePaymentInformationAndPlaceOrder(
        GuestPaymentInformationManagement $subject,
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

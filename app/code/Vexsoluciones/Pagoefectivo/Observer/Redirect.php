<?php
namespace Vexsoluciones\Pagoefectivo\Observer;

use Magento\Payment\Observer\AbstractDataAssignObserver;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Framework\Event\ObserverInterface;

class Redirect implements ObserverInterface
{
    

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $orderIds = $observer->getEvent()->getOrderIds();
        $orderId = $orderIds[0];

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $order = $objectManager->create('\Magento\Sales\Model\OrderRepository')->get($orderId);

        $payment = $order->getPayment();


        if($payment->getMethod()=="cuotealo_pay"){ 
            $cipUrl = $payment->getAdditionalInformation('cip_url');
            header("Location: ".$cipUrl);
            die();
        }
    }
}
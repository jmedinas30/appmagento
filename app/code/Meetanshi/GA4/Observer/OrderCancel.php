<?php

namespace Meetanshi\GA4\Observer;

use Magento\Framework\Event\ObserverInterface;
use Meetanshi\GA4\Helper\Data;
use Magento\Backend\Model\Session;

class OrderCancel implements ObserverInterface
{
    protected $helper;
    protected $session;

    public function __construct
    (
        Data $data,
        Session $session
    )
    {
        $this->helper = $data;
        $this->session = $session;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getOrder();

        if ($this->helper->isEnable()) {
            if ($order && $order->getId()) {
                $data['id'] = $order->getId();
                $data['event'] = 'cancel';
                $data['refund'] = 1;
            } else {
                $data = [];
            }
            $this->session->setGA4CancelOrder($data);
        }
    }
}
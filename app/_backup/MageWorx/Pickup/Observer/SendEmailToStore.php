<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Pickup\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use MageWorx\Pickup\Helper\Data;
use MageWorx\Pickup\Model\StoreEmailSender;

class SendEmailToStore implements ObserverInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var StoreEmailSender
     */
    private $storeEmailSender;

    /**
     * SendEmailToStore constructor.
     *
     * @param Data $helper
     * @param \Psr\Log\LoggerInterface $logger
     * @param LocationRepositoryInterface $locationRepository
     */
    public function __construct(
        StoreEmailSender $storeEmailSender,
        Data $helper,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->storeEmailSender   = $storeEmailSender;
        $this->helper             = $helper;
        $this->logger             = $logger;
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(Observer $observer)
    {
        try {
            if ($this->out($observer)) {
                return;
            }

            /** @var \Magento\Sales\Model\Order $order */
            $order = $observer->getOrder();
            $this->storeEmailSender->send($order, true);
        } catch (LocalizedException $exception) {
            $this->logger->error($exception->getMessage());

            return;
        }
    }

    /**
     * @param Observer $observer
     * @return bool
     */
    protected function out($observer)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getOrder();
        if (!$order) {
            return true;
        }

        if ($order->getIsVirtual()) {
            return true;
        }

        if ($order->getShippingMethod() !== 'mageworxpickup_mageworxpickup') {
            return true;
        }

        if (!$this->helper->isEmailToStoreEnabled()) {
            return true;
        }

        return false;
    }
}

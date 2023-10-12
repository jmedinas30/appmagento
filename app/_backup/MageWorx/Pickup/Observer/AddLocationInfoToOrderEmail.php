<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Pickup\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use MageWorx\Locations\Api\LocationRepositoryInterface;
use MageWorx\Locations\Model\Source\Region;
use MageWorx\Locations\Api\Data\LocationInterface;

/**
 * Class AddLocationInfoToOrderEmail
 *
 */
class AddLocationInfoToOrderEmail implements ObserverInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * AddLocationInfoToOrderEmail constructor.
     *
     * @param \Psr\Log\LoggerInterface $logger
     * @param LocationRepositoryInterface $locationRepository
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        LocationRepositoryInterface $locationRepository
    ) {
        $this->logger             = $logger;
        $this->locationRepository = $locationRepository;
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

            /** @var \Magento\Framework\DataObject $transport */
            $transport = $observer->getTransport();
            /** @var \Magento\Sales\Model\Order $order */
            $order      = $transport->getData('order');
            $locationId = $order->getMageworxPickupLocationId();
            /** @var LocationInterface $location */
            $location = $this->locationRepository->getById($locationId);

            $currentData = $transport->getData();
            $transport->setData(array_merge($currentData, $location->getPreparedDataForCustomer()));

            $observer->setTransport($transport);
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
        /** @var \Magento\Framework\DataObject $transport */
        $transport = $observer->getTransport();
        if (!$transport) {
            return true;
        }

        /** @var \Magento\Sales\Model\Order $order */
        $order = $transport->getData('order');
        if (!$order) {
            return true;
        }

        if ($order->getIsVirtual()) {
            return true;
        }

        if ($order->getShippingMethod() !== 'mageworxpickup_mageworxpickup') {
            return true;
        }

        if (!$order->getMageworxPickupLocationId()) {
            return true;
        }

        return false;
    }
}

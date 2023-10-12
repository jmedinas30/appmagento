<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Pickup\Plugin;

use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\OrderRepositoryInterface;
use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\Locations\Api\LocationRepositoryInterface;

class CheckSourceForPickupShipment
{
    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * ChooseLocationSource constructor.
     *
     * @param LocationRepositoryInterface $locationRepository
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        LocationRepositoryInterface $locationRepository,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->locationRepository = $locationRepository;
        $this->orderRepository    = $orderRepository;
    }

    /**
     * @param \Magento\Sales\Model\Order\ShipmentRepository $subject
     * @param \Magento\Sales\Api\Data\ShipmentInterface $entity
     * @return array
     * @throws LocalizedException
     */
    public function beforeSave(
        \Magento\Sales\Model\Order\ShipmentRepository $subject,
        \Magento\Sales\Api\Data\ShipmentInterface $entity
    ) {
        $result = [$entity];
        $order  = $this->orderRepository->get($entity->getOrderId());

        if ($order->getShippingMethod() !== 'mageworxpickup_mageworxpickup') {
            return $result;
        }

        $locationId = $order->getData('mageworx_pickup_location_id');
        if (!$locationId) {
            return $result;
        }

        /** @var \MageWorx\Locations\Api\Data\LocationInterface $location */
        $location = $this->locationRepository->getById($locationId);
        if ($location->getSourceCode() &&
            $location->getAssignType() == LocationInterface::ASSIGN_TYPE_PRODUCTS_FROM_SOURCE
        ) {
            if ($entity->getExtensionAttributes() && $entity->getExtensionAttributes()->getSourceCode()) {
                $sourceCode = $entity->getExtensionAttributes()->getSourceCode();
                if (!$sourceCode) {
                    $entity->getExtensionAttributes()->setSourceCode($location->getSourceCode());
                } elseif ($sourceCode != $location->getSourceCode()) {
                    throw new LocalizedException(
                        __('Source code for Pickup shipping method should be %1', $location->getSourceCode())
                    );
                }
            }
        }

        return $result;
    }
}

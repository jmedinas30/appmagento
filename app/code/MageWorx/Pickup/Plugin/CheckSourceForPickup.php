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

class CheckSourceForPickup
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
     * @param \Magento\Sales\Model\ShipOrder $subject
     * @param int $orderId
     * @param \Magento\Sales\Api\Data\ShipmentItemCreationInterface[] $items
     * @param bool $notify
     * @param bool $appendComment
     * @param \Magento\Sales\Api\Data\ShipmentCommentCreationInterface|null $comment
     * @param \Magento\Sales\Api\Data\ShipmentTrackCreationInterface[] $tracks
     * @param \Magento\Sales\Api\Data\ShipmentPackageCreationInterface[] $packages
     * @param \Magento\Sales\Api\Data\ShipmentCreationArgumentsInterface|null $arguments
     * @return array
     * @throws LocalizedException
     */
    public function beforeExecute(
        \Magento\Sales\Model\ShipOrder $subject,
        $orderId,
        array $items = [],
        $notify = false,
        $appendComment = false,
        \Magento\Sales\Api\Data\ShipmentCommentCreationInterface $comment = null,
        array $tracks = [],
        array $packages = [],
        \Magento\Sales\Api\Data\ShipmentCreationArgumentsInterface $arguments = null
    ) {
        $result = [$orderId, $items, $notify, $appendComment, $comment, $tracks, $packages, $arguments];
        $order  = $this->orderRepository->get($orderId);

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
            $sourceCode = $arguments->getExtensionAttributes()->getSourceCode();
            if (!$sourceCode) {
                $arguments->getExtensionAttributes()->setSourceCode($location->getSourceCode());
            } elseif ($sourceCode != $location->getSourceCode()) {
                throw new LocalizedException(
                    __('Source code for Pickup shipping method should be %1', $location->getSourceCode())
                );
            }
        }

        return $result;
    }
}

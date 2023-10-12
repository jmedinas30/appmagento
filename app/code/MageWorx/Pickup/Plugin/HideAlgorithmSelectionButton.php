<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Pickup\Plugin;

use Magento\Framework\App\RequestInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\Locations\Api\LocationRepositoryInterface;

class HideAlgorithmSelectionButton
{
    /**
     * @var RequestInterface
     */
    protected $request;

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
     * @param RequestInterface $request
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        LocationRepositoryInterface $locationRepository,
        RequestInterface $request,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->locationRepository = $locationRepository;
        $this->request            = $request;
        $this->orderRepository    = $orderRepository;
    }

    /**
     * @param \Magento\InventoryShippingAdminUi\Ui\Component\Control\SourceSelection\AlgorithmSelectionButton $subject
     * @param array $result
     * @return array
     */
    public function afterGetButtonData(
        \Magento\InventoryShippingAdminUi\Ui\Component\Control\SourceSelection\AlgorithmSelectionButton $subject,
        array $result
    ) {
        $orderId = (int)$this->request->getParam('order_id');
        /** @var \Magento\Sales\Model\Order $order */
        try {
            $order = $this->orderRepository->get($orderId);
        } catch (\Exception $e) {
            return $result;
        }

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
            return [];
        }

        return $result;
    }
}

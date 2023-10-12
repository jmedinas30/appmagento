<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Pickup\Plugin;

use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Sales\Api\Data\OrderExtension;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use MageWorx\Locations\Api\LocationRepositoryInterface;

class AddLocationToOrder
{
    /**
     * @var OrderExtensionFactory
     */
    private $orderExtensionFactory;

    /**
     * @var \Magento\Framework\Session\SessionManagerInterface
     */
    protected $sessionManager;

    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * @var \Magento\Framework\Stdlib\CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * AddLocationToOrder constructor.
     *
     * @param CookieManagerInterface $cookieManager
     * @param LocationRepositoryInterface $locationRepository
     * @param OrderExtensionFactory $orderExtensionFactory
     * @param \Magento\Framework\Session\SessionManagerInterface $sessionManager
     */
    public function __construct(
        CookieManagerInterface $cookieManager,
        LocationRepositoryInterface $locationRepository,
        OrderExtensionFactory $orderExtensionFactory,
        \Magento\Framework\Session\SessionManagerInterface $sessionManager
    ) {
        $this->cookieManager         = $cookieManager;
        $this->locationRepository    = $locationRepository;
        $this->orderExtensionFactory = $orderExtensionFactory;
        $this->sessionManager        = $sessionManager;
    }

    /**
     * Set Location Code Data
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $order
     * @return OrderInterface
     */
    public function afterGet(
        OrderRepositoryInterface $subject,
        OrderInterface $order
    ) {
        /** @var \Magento\Sales\Api\Data\OrderExtensionInterface $extensionAttributes */
        $extensionAttributes = $order->getExtensionAttributes();
        if ($extensionAttributes === null) {
            $extensionAttributes = $this->orderExtensionFactory->create();
        }

        $extensionAttributes->setMageworxPickupLocationId($order->getMageworxPickupLocationId());

        $order->setExtensionAttributes($extensionAttributes);

        return $order;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderSearchResultInterface $orderSearchResult
     * @return OrderSearchResultInterface
     */
    public function afterGetList(
        OrderRepositoryInterface $subject,
        OrderSearchResultInterface $orderSearchResult
    ) {
        /** @var OrderInterface $entity */
        foreach ($orderSearchResult->getItems() as $order) {
            $this->afterGet($subject, $order);
        }

        return $orderSearchResult;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $order
     * @return OrderInterface
     */
    public function beforeSave(
        OrderRepositoryInterface $subject,
        OrderInterface $order
    ) {
        $id = $this->sessionManager->getData('mageworx_pickup_location_id');

        if (!$id) {
            $id = $this->getLocationIdFromCookie('mageworx_location_id');
        }

        if ($id && $order->getShippingMethod() == 'mageworxpickup_mageworxpickup') {
            $name = $this->locationRepository->getById($id)->getName();
            $order->setShippingDescription($order->getShippingDescription() . ' ' . $name);
            $order->setMageworxPickupLocationId($id);
            $this->sessionManager->setData('mageworx_pickup_location_id', null);
        }

        return [$order];
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getLocationIdFromCookie($name)
    {
        return $this->cookieManager->getCookie($name);
    }
}

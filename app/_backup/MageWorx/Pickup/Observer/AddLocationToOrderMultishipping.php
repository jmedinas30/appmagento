<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Pickup\Observer;

use Magento\Framework\Stdlib\CookieManagerInterface;
use MageWorx\Locations\Api\LocationRepositoryInterface;

/**
 * Observer class
 */
class AddLocationToOrderMultishipping implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Framework\Stdlib\CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * SavePickupLocationToMultiQuote constructor.
     *
     * @param CookieManagerInterface $cookieManager
     * @param LocationRepositoryInterface $locationRepository
     */
    public function __construct(
        CookieManagerInterface $cookieManager,
        LocationRepositoryInterface $locationRepository
    ) {
        $this->cookieManager      = $cookieManager;
        $this->locationRepository = $locationRepository;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $addressId  = $observer->getAddress()->getId();
        $locationId = $this->getLocationIdFromCookie('shipping_method-' . $addressId);
        $order      = $observer->getOrder();
        if ($locationId && $order->getShippingMethod() == 'mageworxpickup_mageworxpickup') {
            $name = $this->locationRepository->getById($locationId)->getName();
            $order->setShippingDescription($order->getShippingDescription() . ' ' . $name);
            $order->setMageworxPickupLocationId($locationId);
        }

        $observer->setOrder($order);
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

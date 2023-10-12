<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Pickup\Plugin;

use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use MageWorx\Locations\Api\LocationRepositoryInterface;

class CheckPickupLocationForMultishipping
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
     * @param \Magento\Multishipping\Model\Checkout\Type\Multishipping $subject
     * @param array $methods
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeSetShippingMethods(
        \Magento\Multishipping\Model\Checkout\Type\Multishipping $subject,
        $methods
    ) {
        foreach ($methods as $addressId => $method) {
            $locationId = $this->getLocationIdFromCookie('shipping_method-' . $addressId);
            if (!$locationId && $method == 'mageworxpickup_mageworxpickup') {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('The store for pickup is missing. Select the store and try again.')
                );
            }
        }

        return [$methods];
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

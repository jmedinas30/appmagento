<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Pickup\Plugin;

use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Quote\Api\CartRepositoryInterface;

class SavePickupLocationToQuote
{
    const COOKIE_NAME = 'mageworx_location_id';

    /**
     * @var \Magento\Framework\Stdlib\CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * @var \Magento\Framework\Session\SessionManagerInterface
     */
    protected $sessionManager;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * SavePickupLocationToQuote constructor.
     *
     * @param CartRepositoryInterface $quoteRepository
     * @param CookieManagerInterface $cookieManager
     * @param SessionManagerInterface $sessionManager
     */
    public function __construct(
        CartRepositoryInterface $quoteRepository,
        CookieManagerInterface $cookieManager,
        SessionManagerInterface $sessionManager
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->cookieManager   = $cookieManager;
        $this->sessionManager  = $sessionManager;
    }

    /**
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param int $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {
        if ($addressInformation->getShippingMethodCode() == 'mageworxpickup') {
            $locationId = $this->getLocationIdFromCookie();
            if ($locationId) {
                $this->sessionManager->setData('mageworx_pickup_location_id', $locationId);
                /** @var \Magento\Quote\Model\Quote $quote */
                $quote = $this->quoteRepository->getActive($cartId);
                $quote->setMageworxPickupLocationId($locationId);
                $this->quoteRepository->save($quote);
            }
        }

        return [$cartId, $addressInformation];
    }

    /**
     * @return null|string
     */
    public function getLocationIdFromCookie()
    {
        return $this->cookieManager->getCookie(self::COOKIE_NAME);
    }
}

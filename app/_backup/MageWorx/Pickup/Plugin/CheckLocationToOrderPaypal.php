<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Pickup\Plugin;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Quote\Model\Quote as QuoteEntity;
use MageWorx\Locations\Api\LocationRepositoryInterface;

/**
 * Observer class
 */
class CheckLocationToOrderPaypal
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
     * @var \Magento\Framework\App\State
     */
    protected $state;

    /**
     * CheckLocationToOrderPaypal constructor.
     *
     * @param \Magento\Framework\App\State $state
     * @param CookieManagerInterface $cookieManager
     * @param LocationRepositoryInterface $locationRepository
     */
    public function __construct(
        \Magento\Framework\App\State $state,
        CookieManagerInterface $cookieManager,
        LocationRepositoryInterface $locationRepository
    ) {
        $this->state              = $state;
        $this->cookieManager      = $cookieManager;
        $this->locationRepository = $locationRepository;
    }

    /**
     * @param \Magento\Quote\Model\QuoteManagement $quoteManagement
     * @param QuoteEntity $quote
     * @param array $orderData
     * @return array
     * @throws LocalizedException
     */
    public function beforeSubmit(
        \Magento\Quote\Model\QuoteManagement $quoteManagement,
        QuoteEntity $quote,
        $orderData = []
    ) {
        if ($this->state->getAreaCode() == 'adminhtml') {
            return [$quote, $orderData];
        }
        $methodCode = $quote->getShippingAddress()->getShippingMethod();

        if ($methodCode !== 'mageworxpickup_mageworxpickup') {
            return [$quote, $orderData];
        }

        $locationId = $this->getLocationIdFromCookie('mageworx_location_id');

        if (!$locationId) {
            throw new LocalizedException(
                __('Please, choose store for Pickup shipping method')
            );
        }

        $quote->setMageworxPickupLocationId($locationId);
        $name = $this->locationRepository->getById($locationId)->getName();
        $quote->setShippingDescription($quote->getShippingDescription() . ' ' . $name);
        $quote->setMageworxPickupLocationId($locationId);

        return [$quote, $orderData];
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

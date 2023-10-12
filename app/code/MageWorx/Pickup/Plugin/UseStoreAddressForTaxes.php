<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
declare(strict_types=1);

namespace MageWorx\Pickup\Plugin;

use Magento\Quote\Model\Quote\Address as QuoteAddress;
use Magento\Tax\Api\Data\QuoteDetailsInterface;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Directory\Model\RegionFactory;
use Magento\Framework\Exception\LocalizedException;
use MageWorx\Locations\Api\Data\LocationInterface;
use Magento\Framework\Stdlib\CookieManagerInterface;
use MageWorx\Locations\Api\LocationRepositoryInterface;

/**
 * Adding store address for tax calculation
 *
 * @package MageWorx\Pickup\Plugin
 */
class UseStoreAddressForTaxes
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
     * @var RegionFactory
     */
    protected $regionFactory;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    /**
     * UseStoreAddressForTaxes constructor.
     *
     * @param RegionFactory $regionFactory
     * @param \Magento\Framework\App\State $state
     * @param CookieManagerInterface $cookieManager
     * @param LocationRepositoryInterface $locationRepository
     */
    public function __construct(
        RegionFactory $regionFactory,
        \Magento\Framework\App\State $state,
        CookieManagerInterface $cookieManager,
        LocationRepositoryInterface $locationRepository
    ) {
        $this->regionFactory      = $regionFactory;
        $this->state              = $state;
        $this->cookieManager      = $cookieManager;
        $this->locationRepository = $locationRepository;
    }

    /**
     * @param string $name
     * @return string|null
     */
    protected function getLocationIdFromCookie(string $name)
    {
        return $this->cookieManager->getCookie($name);
    }

    /**
     * @param $entity
     * @return bool
     * @throws LocalizedException
     */
    protected function out($entity): bool
    {
        if ($this->state->getAreaCode() == 'adminhtml') {
            return true;
        }

        if ($entity->isVirtual()) {
            return true;
        }

        if ($entity->getShippingAddress()) {
            $methodCode = $entity->getShippingAddress()->getShippingMethod();
        }

        if (empty($methodCode)) {
            $methodCode = $entity->getShippingMethod();
        }

        if ($methodCode !== 'mageworxpickup_mageworxpickup') {
            return true;
        }

        return false;
    }

    /**
     * @param \Magento\Tax\Model\Sales\Total\Quote\CommonTaxCollector $subject
     * @param QuoteDetailsInterface $result
     * @param QuoteDetailsInterface $quoteDetails
     * @param QuoteAddress $address
     * @return QuoteDetailsInterface
     * @throws LocalizedException
     */
    public function afterPopulateAddressData(
        \Magento\Tax\Model\Sales\Total\Quote\CommonTaxCollector $subject,
        QuoteDetailsInterface $result,
        QuoteDetailsInterface $quoteDetails,
        QuoteAddress $address
    ) {
        $locationId = $this->getLocationIdFromCookie('mageworx_location_id');

        if ($this->out($address->getQuote()) || !$locationId) {
            return $result;
        }

        $shippingAddress = $result->getShippingAddress();
        /** @var LocationInterface $location */
        $location = $this->locationRepository->getById($locationId);
        $result->setShippingAddress($this->prepareAddress($shippingAddress, $location));

        return $result;
    }

    /**
     * @param AddressInterface $address
     * @param LocationInterface $location
     * @return AddressInterface
     */
    private function prepareAddress(
        AddressInterface $address,
        LocationInterface $location
    ): AddressInterface {
        $locationRegion = $this->regionFactory->create()->loadByName($location->getRegion(), $location->getCountryId());

        $address->setRegion($address->getRegion()->setRegionId($locationRegion->getRegionId()));
        $address->setCountryId($location->getCountryId());
        $address->setPostcode($location->getPostcode());
        $address->setStreet([$location->getAddress()]);
        $address->setCity($location->getCity());

        return $address;
    }
}

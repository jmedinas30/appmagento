<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Pickup\Model\Carrier;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Rate\Result;

class PickupShipping extends \Magento\Shipping\Model\Carrier\AbstractCarrier
    implements \Magento\Shipping\Model\Carrier\CarrierInterface
{
    /**
     * @var string
     */
    protected $_code = 'mageworxpickup';

    /**
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     */
    protected $rateResultFactory;

    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    protected $rateMethodFactory;

    /**
     * @var \MageWorx\StoreLocator\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $httpRequest;

    /**
     * PickupShipping constructor.
     *
     * @param \MageWorx\StoreLocator\Helper\Data $helper
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Request\Http $httpRequest,
        \MageWorx\StoreLocator\Helper\Data $helper,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        array $data = []
    ) {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
        $this->httpRequest       = $httpRequest;
        $this->helper            = $helper;
        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
    }

    /**
     * @return array
     */
    public function getAllowedMethods()
    {
        return [$this->_code => $this->getPickupTitle()];
    }

    /**
     * @param RateRequest $request
     * @return bool|\Magento\Framework\DataObject|Result|null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->rateResultFactory->create();

        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method = $this->rateMethodFactory->create();

        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod($this->_code);
        $method->setMethodTitle($this->getPickupTitle());

        $amount = $this->getShippingPrice($request);

        $method->setPrice($amount);
        $method->setCost($amount);

        $result->append($method);

        return $result;
    }

    /**
     * @return false|string
     */
    protected function getPickupTitle()
    {
        return $this->getConfigData('name');
    }

    /**
     * @param RateRequest $request
     * @return float
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getShippingPrice(RateRequest $request)
    {
        $configPrice = $this->getConfigData('price');

        $shippingPrice = $this->getFinalPriceWithHandlingFee($configPrice);

        /* Set free shipping by promo rules */
        $items                = $request->getAllItems();
        $freeShippingFound    = false;
        $notFreeShippingFound = false;
        foreach ($items as $item) {
            if ($item->getFreeShipping() || $item->getAddress()->getFreeShipping()) {
                $freeShippingFound = true;
            } else {
                $notFreeShippingFound = true;
            }
        }

        if ($shippingPrice && ($freeShippingFound && !$notFreeShippingFound)) {
            $shippingPrice = 0;
        }

        return $shippingPrice;
    }

    /**
     * @param \Magento\Framework\DataObject $request
     * @return bool|\Magento\Framework\Model\AbstractModel|\Magento\Quote\Model\Quote\Address\RateResult\Error
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function checkAvailableShipCountries(\Magento\Framework\DataObject $request)
    {
        $result = parent::checkAvailableShipCountries($request);

        if ($result instanceof PickupShipping && !$this->isLocationsAvailable()) {

            $showMethod = $this->getConfigData('showmethod');
            if ($showMethod) {
                /** @var \Magento\Quote\Model\Quote\Address\RateResult\Error $error */
                $error = $this->_rateErrorFactory->create();
                $error->setCarrier($this->_code);
                $error->setCarrierTitle($this->getConfigData('title'));
                $errorMsg = $this->getConfigData('specificerrmsg');
                $error->setErrorMessage(
                    $errorMsg ? $errorMsg : __(
                        'Sorry, but we can\'t find store with all product from your cart.'
                    )
                );

                return $error;
            }

            return false;
        }

        return $result;
    }

    /**
     * @return bool
     */
    protected function isLocationsAvailable()
    {
        if ($this->httpRequest->getFullActionName() == 'multishipping_checkout_addressesPost') {
            return true;
        }

        $availableLocations = $this->helper->getLocationsForCurrentQuote();

        return (bool)count($availableLocations);
    }
}

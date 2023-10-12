<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Pickup\Block\Adminhtml\Order\Create;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use MageWorx\Locations\Api\LocationRepositoryInterface;
use MageWorx\Locations\Helper\Data;

class LocationChooser extends \Magento\Sales\Block\Adminhtml\Order\Create\Shipping\Method\Form
{
    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * @var \Magento\Framework\Session\SessionManagerInterface
     */
    protected $sessionManager;

    /**
     * LocationChooser constructor.
     *
     * @param LocationRepositoryInterface $locationRepository
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Model\Session\Quote $sessionQuote
     * @param \Magento\Sales\Model\AdminOrder\Create $orderCreate
     * @param PriceCurrencyInterface $priceCurrency
     * @param \Magento\Tax\Helper\Data $taxData
     * @param \Magento\Framework\Session\SessionManagerInterface $sessionManager
     */
    public function __construct(
        LocationRepositoryInterface $locationRepository,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Model\Session\Quote $sessionQuote,
        \Magento\Sales\Model\AdminOrder\Create $orderCreate,
        PriceCurrencyInterface $priceCurrency,
        \Magento\Tax\Helper\Data $taxData,
        \Magento\Framework\Session\SessionManagerInterface $sessionManager,
        array $data = []
    ) {
        parent::__construct($context, $sessionQuote, $orderCreate, $priceCurrency, $taxData, $data);
        $this->locationRepository = $locationRepository;
        $this->sessionManager     = $sessionManager;
    }

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('order.create.shipping.method.locations');
    }

    /**
     * @return \MageWorx\Locations\Model\ResourceModel\Location\Collection
     */
    public function getLocations()
    {
        $ids = [];
        foreach ($this->getQuote()->getAllItems() as $item) {
            $ids[] = $item->getProductId();
        }

        $availableLocations = $this->locationRepository->getListLocationByProductIds($ids, null, false);

        $locationsIds = [];

        foreach ($availableLocations as $availableLocation) {
            $locationsIds[] = $availableLocation->getId();
        }

        if (array_search($this->getChosenLocationId(), $locationsIds) === false) {
            $this->sessionManager->setData('mageworx_pickup_location_id', null);
        }

        return $availableLocations;
    }

    /**
     * @return string
     */
    public function getChosenLocationId()
    {
        if ($id = $this->getRequest()->getParam('mageworx_pickup_location_id')) {
            $this->sessionManager->setData('mageworx_pickup_location_id', $id);
        }

        if ($this->getShippingMethod() == 'mageworxpickup_mageworxpickup'
            && $id = $this->sessionManager->getData('mageworx_pickup_location_id')) {

            return $id;
        }

        return '';
    }
}

<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
declare(strict_types=1);

namespace MageWorx\StoreLocator\Block\Adminhtml;

use Magento\Framework\View\Element\Template;
use MageWorx\Locations\Helper\Data as HelperLocations;
use MageWorx\StoreLocator\Helper\Data;

class MapPopup extends \MageWorx\StoreLocator\Block\GoogleMap
{
    /**
     * @var HelperLocations
     */
    protected $helperLocations;

    /**
     * @param \Magento\Framework\Locale\Resolver $locale
     * @param HelperLocations $helperLocations
     * @param Data $helper
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Locale\Resolver $locale,
        HelperLocations $helperLocations,
        Data $helper,
        Template\Context $context,
        array $data = []
    ) {
        $this->helperLocations = $helperLocations;
        parent::__construct($locale, $helper, $context, $data);
    }

    /**
     * Prepare layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $result = parent::_prepareLayout();
        $this->setTemplate('MageWorx_StoreLocator::popup.phtml');

        return $result;
    }

    /**
     * @return array
     */
    public function getMapCenter(): array
    {
        $coordinates = [];
        $storeId     = (int)$this->getRequest()->getParam('store', \Magento\Store\Model\Store::DEFAULT_STORE_ID);

        if ($this->helper->getDefaultLatitude($storeId) && $this->helper->getDefaultLongitude($storeId)) {
            $coordinates['lng'] = $this->helper->getDefaultLongitude($storeId);
            $coordinates['lat'] = $this->helper->getDefaultLatitude($storeId);
        } elseif ($this->helper->getDefaultCountry($storeId)
            || $this->helper->getDefaultRegion($storeId)
            || $this->helper->getDefaultCity($storeId)
        ) {
            $coordinates['lng'] = 0;
            $coordinates['lat'] = 0;
        } else {
            $coordinates = $this->helper->getCoordinatesByGeoIp();
        }

        return $coordinates;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMapZoom(): string
    {
        $storeId = (int)$this->getRequest()->getParam('store', \Magento\Store\Model\Store::DEFAULT_STORE_ID);

        if (!$this->helper->getDefaultScale($storeId)) {
            return '13';
        }

        return $this->helper->getDefaultScale($storeId);
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getDefaultScale(): string
    {
        $storeId = (int)$this->getRequest()->getParam('store', \Magento\Store\Model\Store::DEFAULT_STORE_ID);

        if (!$this->helper->getDefaultScale($storeId)) {
            return '13';
        }

        return $this->helper->getDefaultScale($storeId);
    }

    /**
     * @return string
     */
    public function getCurrentPlaceName(): string
    {
        $storeId = (int)$this->getRequest()->getParam('store', \Magento\Store\Model\Store::DEFAULT_STORE_ID);

        return $this->helper->getDefaultPlace($storeId);
    }
}

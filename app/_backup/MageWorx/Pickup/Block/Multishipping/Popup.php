<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Pickup\Block\Multishipping;

use Magento\Framework\View\Element\Template\Context;
use MageWorx\StoreLocator\Helper\Data;
use MageWorx\StoreLocator\Block\Popup as StoreLocatorPopup;

class Popup extends StoreLocatorPopup
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * Popup constructor.
     *
     * @param \Magento\Framework\Registry $coreRegistry
     * @param Data $helper
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Registry $coreRegistry,
        Data $helper,
        Context $context,
        array $data = []
    ) {
        parent::__construct($helper, $context, $data);
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getLocationsHtml()
    {
        $result = '';
        foreach ($this->getAddressData() as $addressId => $productIds) {
            $block = $this->getLayout()->createBlock(Locations::class);
            $block->setLayoutType($this->helper->getCheckoutLayout());
            $block->setProductIds($productIds);
            $result .= '<div class="multi-locations" id="shipping_method-' . $addressId . '">' . $block->toHtml(
            ) . '</div>';
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function getAddressData()
    {
        $data = $this->coreRegistry->registry('products_by_address');

        return $data ? $data : [];
    }
}

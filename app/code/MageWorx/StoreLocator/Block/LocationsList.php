<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreLocator\Block;

use Magento\Framework\View\Element\Template;

class LocationsList extends Template
{
    /**
     * Prepare layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->setTemplate('list.phtml');

        return parent::_prepareLayout();
    }

    /**
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getLocationInfoForListHtml($location)
    {
        $block = $this->getLayout()->createBlock(\MageWorx\StoreLocator\Block\LocationInfo::class)
                      ->setTemplate('MageWorx_StoreLocator::location_info_for_list.phtml');
        $block->setData('location', $location);
        $block->setData('is_checkout', $this->getIsCheckout());

        return $block->toHtml();
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getLocationsFilterForListHtml()
    {
        $block = $this->getLayout()->createBlock(\MageWorx\StoreLocator\Block\Filter::class)
                      ->setTemplate('MageWorx_StoreLocator::filter_for_list.phtml');
        $block->setData('locations', $this->getLocations());

        return $block->toHtml();
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getSearchBoxHtml()
    {
        $block = $this->getLayout()->createBlock(\MageWorx\StoreLocator\Block\Search::class)
                      ->setTemplate('MageWorx_StoreLocator::search.phtml');

        return $block->toHtml();
    }
}

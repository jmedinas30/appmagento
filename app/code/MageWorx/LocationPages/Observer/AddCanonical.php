<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Observer;

use Magento\Framework\View\Page\Config;
use MageWorx\LocationPages\Helper\Data as HelperData;

/**
 * Observer class for canonical URL
 */
class AddCanonical implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Framework\View\Page\Config
     */
    protected $pageConfig;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * AddCanonical constructor.
     *
     * @param Config $pageConfig
     * @param HelperData $helperData
     */
    public function __construct(
        Config $pageConfig,
        HelperData $helperData
    ) {
        $this->pageConfig = $pageConfig;
        $this->helperData = $helperData;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($observer->getFullActionName() == 'mageworx_locationpages_location_view') {

            /** @var \MageWorx\LocationPages\Model\LocationPage $locationPage */
            $locationPage = $this->helperData->getCurrentLocationPage();
            $this->addCanonical($locationPage);
        }

        if ($observer->getFullActionName() == 'mageworx_locationpages_locationList_view') {

            /** @var \MageWorx\LocationPages\Model\LocationList $locationList */
            $locationList = $this->helperData->getCurrentLocationList();
            $this->addCanonical($locationList);
        }
    }

    /**
     * @param \MageWorx\LocationPages\Model\LocationList|\MageWorx\LocationPages\Model\LocationPage $page
     */
    protected function addCanonical($page)
    {
        if ($page) {
            $canonicalUrl = $page->getFullUrl();
            if ($canonicalUrl) {
                $this->pageConfig->addRemotePageAsset(
                    $canonicalUrl,
                    'canonical',
                    ['attributes' => ['rel' => 'canonical']]
                );
            }
        }
    }
}

<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Observer;

use Magento\Framework\View\Page\Config;
use MageWorx\LocationPages\Helper\Data as HelperData;

/**
 * Observer class for robots
 */
class AddRobots implements \Magento\Framework\Event\ObserverInterface
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

            $robots = $this->helperData->getCurrentLocation()->getMetaRobots();
            if (!$robots) {
                $robots = $this->helperData->getDefaultMetaRobots();
            }
            $this->pageConfig->setRobots($robots);
        }
    }
}

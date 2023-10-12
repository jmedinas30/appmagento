<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
declare(strict_types=1);

namespace MageWorx\StoreLocator\Block\LocationInfo;

use Magento\CatalogInventory\Model\Configuration;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Directory\Model\RegionFactory;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Store\Model\StoreManagerInterface;
use MageWorx\StoreLocator\Helper\Data;

class LocationDetail extends \MageWorx\StoreLocator\Block\LocationInfo
{
    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $filterProvider;

    /**
     * @param RegionFactory $regionFactory
     * @param \Magento\Framework\Filesystem\Driver\File $driver
     * @param \MageWorx\Locations\Model\ResourceModel\Location $locationResource
     * @param Configuration $inventoryConfig
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param StoreManagerInterface $storeManager
     * @param Data $helper
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param array $data
     */
    public function __construct(
        RegionFactory $regionFactory,
        \Magento\Framework\Filesystem\Driver\File $driver,
        \MageWorx\Locations\Model\ResourceModel\Location $locationResource,
        Configuration $inventoryConfig,
        \Magento\Framework\Module\Manager $moduleManager,
        StoreManagerInterface $storeManager,
        Data $helper,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        array $data = []
    ) {
        parent::__construct(
            $regionFactory,
            $driver,
            $locationResource,
            $inventoryConfig,
            $moduleManager,
            $storeManager,
            $helper,
            $context,
            $data
        );
        $this->filterProvider = $filterProvider;
    }

    /**
     * Prepare URL rewrite editing layout
     *
     * @return $this
     */
    protected function _prepareLayout(): LocationDetail
    {
        parent::_prepareLayout();
        $this->setTemplate('location_detail.phtml');

        return $this;
    }

    /**
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getWorkingHoursHtml($location): string
    {
        $block = $this->getLayout()->createBlock(\MageWorx\StoreLocator\Block\LocationInfo\WorkingHours::class)
                      ->setTemplate('MageWorx_StoreLocator::location_detail/working_hours.phtml');
        $block->setLocation($location);

        return $block->toHtml();
    }

    /**
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @return string
     * @throws \Exception
     */
    public function getDescriptionHtml(\MageWorx\Locations\Api\Data\LocationInterface $location): string
    {
        if ($location->getDescription() !== null) {
            return $this->filterProvider->getPageFilter()->filter($location->getDescription());
        }

        return '';
    }
}

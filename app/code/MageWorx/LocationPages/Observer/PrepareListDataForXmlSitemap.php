<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use MageWorx\Locations\Api\LocationRepositoryInterface;
use MageWorx\LocationPages\Model\LocationPageFactory;
use MageWorx\LocationPages\Api\LocationListRepositoryInterface;
use MageWorx\LocationPages\Helper\Data;

class PrepareListDataForXmlSitemap implements ObserverInterface
{
    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * @var LocationListRepositoryInterface
     */
    protected $locationListRepository;

    /**
     * @var LocationPageFactory
     */
    protected $locationPageFactory;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * PrepareListDataForXmlSitemap constructor.
     *
     * @param Data $helper
     * @param LocationListRepositoryInterface $locationListRepository
     * @param LocationRepositoryInterface $locationRepository
     * @param LocationPageFactory $locationPageFactory
     */
    public function __construct(
        Data $helper,
        LocationListRepositoryInterface $locationListRepository,
        LocationRepositoryInterface $locationRepository,
        LocationPageFactory $locationPageFactory
    ) {
        $this->helper                 = $helper;
        $this->locationRepository     = $locationRepository;
        $this->locationListRepository = $locationListRepository;
        $this->locationPageFactory    = $locationPageFactory;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        if (!$this->helper->isAddToSitemap()) {
            return;
        }

        if ($storeId = $observer->getData('storeId')) {
            $container                           = $observer->getEvent()->getContainer();
            $generators                          = $container->getGenerators();
            $generatorCode                       = 'location_page';
            $generators[$generatorCode]          = [];
            $generators[$generatorCode]['title'] = __('Location Pages');

            $collection = $this->locationRepository->getListLocationForFront($storeId);
            /** @var \MageWorx\LocationPages\Model\LocationPage $locationPage */
            $locationPage = $this->locationPageFactory->create();

            /** @var \MageWorx\Locations\Api\Data\LocationInterface $item */
            foreach ($collection as $item) {
                $locationPage->setLocation($item);
                $generators[$generatorCode]['items'][] = [
                    'url_key'      => $locationPage->getUrl(),
                    'date_changed' => date('Y-m-d', strtotime($item->getDateModified())),
                ];
            }

            $collection = $this->locationListRepository->getLocationListCollection();
            /** @var \MageWorx\Locations\Api\Data\LocationInterface $item */
            foreach ($collection as $item) {
                $generators[$generatorCode]['items'][] = [
                    'url_key'      => $item->getUrl(),
                    'date_changed' => date('Y-m-d'),
                ];
            }
            $container->setGenerators($generators);
        }
    }
}

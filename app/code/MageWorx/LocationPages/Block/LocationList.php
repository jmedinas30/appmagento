<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Block;

use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use MageWorx\LocationPages\Api\Data\LocationListInterface;
use MageWorx\LocationPages\Api\LocationListRepositoryInterface;

/**
 * Class LocationList
 */
class LocationList extends \Magento\Framework\View\Element\Template
{
    /**
     * Core registry
     *
     * @var \MageWorx\Locations\Model\Registry
     */
    protected $coreRegistry;

    /**
     * Catalog layer
     *
     * @var \Magento\Catalog\Model\Layer
     */
    protected $catalogLayer;

    /**
     * @var \Magento\Catalog\Helper\Category
     */
    protected $categoryHelper;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \MageWorx\LocationPages\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Widget\Model\Template\Filter
     */
    protected $templateProcessor;

    /** @var EventManagerInterface */
    protected $eventManager;

    /**
     * @var LocationListRepositoryInterface
     */
    protected $locationListRepository;

    /**
     * Location constructor.
     *
     * @param LocationListRepositoryInterface $locationListRepository
     * @param EventManagerInterface $eventManager
     * @param \MageWorx\LocationPages\Helper\Data $helper
     * @param \Magento\Widget\Model\Template\Filter $templateProcessor
     * @param \MageWorx\Locations\Model\Registry $coreRegistry
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param \MageWorx\Locations\Model\Registry $registry
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Helper\Category $categoryHelper
     * @param string[] $data
     */
    public function __construct(
        LocationListRepositoryInterface $locationListRepository,
        EventManagerInterface $eventManager,
        \MageWorx\LocationPages\Helper\Data $helper,
        \Magento\Widget\Model\Template\Filter $templateProcessor,
        \MageWorx\Locations\Model\Registry $coreRegistry,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \MageWorx\Locations\Model\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Helper\Category $categoryHelper,
        array $data = []
    ) {
        $this->eventManager           = $eventManager;
        $this->templateProcessor      = $templateProcessor;
        $this->categoryHelper         = $categoryHelper;
        $this->catalogLayer           = $layerResolver->get();
        $this->coreRegistry           = $coreRegistry;
        $this->storeManager           = $storeManager;
        $this->helper                 = $helper;
        $this->locationListRepository = $locationListRepository;
        parent::__construct($context, $data);
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        /** @var LocationListInterface $locationList */
        $locationList = $this->getCurrentLocationList();
        $storeId      = $this->helper->getStoreId();

        if ($locationList) {
            $title = $this->helper->getListMetaTitle($storeId);
            if ($title) {
                $this->pageConfig->getTitle()->set($title);
            } else {
                $this->pageConfig->getTitle()->set($locationList->getName());
            }
            $description = $this->helper->getListMetaDescription($storeId);
            if ($description) {
                $this->pageConfig->setDescription($description);
            }
            $keywords = $this->helper->getListMetaKeywords($storeId);
            if ($keywords) {
                $this->pageConfig->setKeywords($keywords);
            }

            $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
            if ($pageMainTitle) {
                $pageMainTitle->setPageTitle($locationList->getName());
            }

            $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
            if ($breadcrumbs && $this->helper->isAddBreadcrumbs($storeId)) {
                $this->addBreadcrumbs($breadcrumbs);
            }
        }

        return $this;
    }

    /**
     * @return bool|LocationListInterface
     */
    public function getCurrentLocationList()
    {
        return $this->helper->getCurrentLocationList();
    }

    /**
     * @param \Magento\Theme\Block\Html\Breadcrumbs $breadcrumbs
     * @return mixed
     */
    protected function addBreadcrumbs($breadcrumbs)
    {
        $breadcrumbs->addCrumb(
            'home',
            [
                'label' => __('Home'),
                'title' => __('Go to Home Page'),
                'link'  => $this->_storeManager->getStore()->getBaseUrl()
            ]
        );

        $locationList           = $this->getCurrentLocationList();
        $locationListCollection = $this->locationListRepository->getLocationListCollectionByIds(
            explode('/', $locationList->getPath())
        );

        /** @var \MageWorx\LocationPages\Api\Data\LocationListInterface $locationList */
        foreach ($locationListCollection as $item) {
            $breadcrumbs->addCrumb(
                $locationList->getType() . '_' . $this->helper->prepareStringToUrl($item->getName()),
                [
                    'label' => __($item->getName()),
                    'title' => __($item->getName()),
                    'link'  => $item->getFullUrl()
                ]
            );
        }

        $header = $locationList->getName();
        $url    = $locationList->getFullUrl();
        $breadcrumbs->addCrumb(
            'mageworx_location',
            [
                'label' => $header,
                'title' => $header,
                'link'  => $url
            ]
        );

        return $breadcrumbs;
    }

    /**
     * @return \MageWorx\LocationPages\Model\ResourceModel\LocationList\Collection
     */
    public function getChildLocations()
    {
        return $this->locationListRepository->getChildLocationsForList($this->getCurrentLocationList());
    }
}

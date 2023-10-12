<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Controller\Location;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Store\Model\Store;
use Magento\Framework\View\Result\PageFactory;
use MageWorx\LocationPages\Model\LocationPageFactory;
use MageWorx\LocationPages\Api\Data\LocationPageInterface;
use MageWorx\Locations\Api\LocationRepositoryInterface;

class View extends Action
{
    /**
     * Core registry
     *
     * @var \MageWorx\Locations\Model\Registry
     */
    protected $coreRegistry;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Framework\Controller\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * Catalog Layer Resolver
     *
     * @var Resolver
     */
    protected $layerResolver;

    /**
     * @var LocationPageFactory
     */
    protected $locationPageFactory;

    /**
     * @var \MageWorx\LocationPages\Helper\Data
     */
    protected $helper;

    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * View constructor.
     *
     * @param LocationRepositoryInterface $locationRepository
     * @param \MageWorx\LocationPages\Helper\Data $helper
     * @param Context $context
     * @param \MageWorx\Locations\Model\Registry $coreRegistry
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param LocationPageFactory $locationPageFactory
     * @param PageFactory $resultPageFactory
     * @param \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
     * @param Resolver $layerResolver
     */
    public function __construct(
        LocationRepositoryInterface $locationRepository,
        \MageWorx\LocationPages\Helper\Data $helper,
        Context $context,
        \MageWorx\Locations\Model\Registry $coreRegistry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        LocationPageFactory $locationPageFactory,
        PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        Resolver $layerResolver
    ) {
        parent::__construct($context);
        $this->helper               = $helper;
        $this->locationPageFactory  = $locationPageFactory;
        $this->storeManager         = $storeManager;
        $this->coreRegistry         = $coreRegistry;
        $this->resultPageFactory    = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->layerResolver        = $layerResolver;
        $this->locationRepository   = $locationRepository;
    }

    /**
     * @param int $locationId
     * @return \MageWorx\LocationPages\Api\Data\LocationPageInterface
     */
    public function initLocationPage($locationId)
    {
        /** @var \MageWorx\LocationPages\Api\Data\LocationPageInterface $locationPage */
        $locationPage = $this->locationPageFactory->create();
        $locationPage->setLocation($this->locationRepository->getById($locationId));

        $this->coreRegistry->register(LocationPageInterface::CURRENT_LOCATION_PAGE, $locationPage, true);

        return $locationPage;
    }

    /**
     * @return $this|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $locationId = $this->_request->getParam('id');

        $locationPage = $this->initLocationPage($locationId);

        if (!$locationPage->getLocation()->getId()) {
            return $this->resultForwardFactory->create()->forward('noroute');
        }

        if (!$locationPage->getLocation()->getIsActive() || !$this->isAllowedForCurrentStore($locationPage)) {
            return $this->resultForwardFactory->create()->forward('noroute');
        }
        $this->layerResolver->create(Resolver::CATALOG_LAYER_CATEGORY);
        $page = $this->resultPageFactory->create();

        if ($this->helper->getProductLayout()) {
            $page->getConfig()->setPageLayout($this->helper->getProductLayout());
        }

        return $page;
    }

    /**
     * @param \MageWorx\LocationPages\Api\Data\LocationPageInterface $locationPage
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function isAllowedForCurrentStore($locationPage)
    {
        if (array_search(
            $this->storeManager->getStore()->getId(),
            $locationPage->getLocation()->getStoreIds()
        ) !== false
            || array_search(Store::DEFAULT_STORE_ID, $locationPage->getLocation()->getStoreIds()) !== false
        ) {
            return true;
        }

        return false;
    }
}

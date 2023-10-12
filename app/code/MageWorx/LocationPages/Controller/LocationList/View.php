<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Controller\LocationList;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Store\Model\Store;
use Magento\Framework\View\Result\PageFactory;
use MageWorx\LocationPages\Api\LocationListRepositoryInterface;
use MageWorx\LocationPages\Api\Data\LocationListInterface;

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
     * @var LocationListRepositoryInterface
     */
    protected $locationListRepository;

    /**
     * View constructor.
     *
     * @param Context $context
     * @param \MageWorx\Locations\Model\Registry $coreRegistry
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param PageFactory $resultPageFactory
     * @param \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
     * @param LocationListRepositoryInterface $locationListRepository
     */
    public function __construct(
        Context $context,
        \MageWorx\Locations\Model\Registry $coreRegistry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        LocationListRepositoryInterface $locationListRepository
    ) {
        parent::__construct($context);
        $this->locationListRepository = $locationListRepository;
        $this->storeManager           = $storeManager;
        $this->coreRegistry           = $coreRegistry;
        $this->resultPageFactory      = $resultPageFactory;
        $this->resultForwardFactory   = $resultForwardFactory;
    }

    /**
     * @param int $locationListId
     * @return LocationListInterface
     */
    public function initLocationList($locationListId)
    {
        /** @var LocationListInterface $locationPage */
        $locationList = $this->locationListRepository->getById($locationListId);

        $this->coreRegistry->register(LocationListInterface::CURRENT_LOCATION_LIST, $locationList, true);

        return $locationList;
    }

    /**
     * @return $this|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $locationId = $this->_request->getParam('id');

        $locationList = $this->initLocationList($locationId);

        if (!$locationList->getId()) {
            return $this->resultForwardFactory->create()->forward('noroute');
        }

        $page = $this->resultPageFactory->create();

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

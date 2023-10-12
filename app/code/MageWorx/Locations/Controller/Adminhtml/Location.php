<?php
/**
 * Copyright ©  MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use MageWorx\Locations\Api\LocationRepositoryInterface;
use MageWorx\Locations\Model\Registry;
use MageWorx\Locations\Api\Data\LocationInterface;
use Magento\Store\Model\Store;

abstract class Location extends Action
{
    const ADMIN_RESOURCE = 'MageWorx_Locations::locations';

    /**
     * Location repository
     *
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var \Magento\Backend\Model\View\Result\Page|null
     */
    protected $resultPage = null;

    /**
     * Location constructor.
     *
     * @param Registry $registry
     * @param LocationRepositoryInterface $locationRepository
     * @param Context $context
     */
    public function __construct(
        Registry $registry,
        LocationRepositoryInterface $locationRepository,
        Context $context
    ) {
        $this->locationRepository = $locationRepository;
        $this->coreRegistry       = $registry;
        parent::__construct($context);
    }

    /**
     * Set page data
     *
     * @return $this
     */
    public function setPageData()
    {
        $resultPage = $this->getResultPage();
        $resultPage->getConfig()->getTitle()->set(__('MageWorx Stores'));

        return $this;
    }

    /**
     * Instantiate result page object
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page
     */
    public function getResultPage()
    {
        if ($this->resultPage === null) {
            $this->resultPage = $this->resultPageFactory->create();
        }

        return $this->resultPage;
    }

    /**
     * @param int $locationId
     * @return mixed
     */
    public function initLocation($locationId = 0)
    {
        $storeId = $this->getRequest()->getParam('store');

        if ($locationId) {
            $this->coreRegistry->register(LocationInterface::CURRENT_STORE_ID_FOR_LOCATION, $storeId, true);
            $location = $this->locationRepository->getById($locationId);
        } else {
            $location = $this->locationRepository->getEmptyEntity();
        }

        $this->coreRegistry->register(LocationInterface::CURRENT_LOCATION, $location, true);

        return $location;
    }

    /**
     * Init action
     *
     * @return $this
     */
    public function _initAction()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu(
            'MageWorx_Locations::location'
        )->_addBreadcrumb(
            __('Locations'),
            __('Locations')
        );

        return $this;
    }
}

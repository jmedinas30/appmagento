<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Controller\Adminhtml\Location;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use MageWorx\Locations\Controller\Adminhtml\Location;
use MageWorx\Locations\Api\LocationRepositoryInterface;
use MageWorx\Locations\Model\Registry;

class Edit extends Location
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Edit constructor.
     *
     * @param Registry $registry
     * @param LocationRepositoryInterface $locationRepository
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Registry $registry,
        LocationRepositoryInterface $locationRepository,
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct(
            $registry,
            $locationRepository,
            $context
        );
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Edit Location
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $locationId = $this->getRequest()->getParam('entity_id');
        /** @var \MageWorx\Locations\Model\Location $location */
        $location = $this->initLocation($locationId);

        /** @var \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->getResultPage();
        $title      = $location->getId() ? $location->getName() . ' ' . __('Store') : __('New Store');
        $resultPage->getConfig()->getTitle()->set($title);
        if ($locationId && !$location->getId()) {
            $this->messageManager->addErrorMessage(__('The store no longer exists.'));
        }
        $data = $this->_session->getData('mageworx_locations_location', true);

        if (!empty($data)) {
            $location->setData($data);
        }

        $location->getRule()->getConditions()->setFormName('mageworx_locations_location_form');
        $location->getRule()->getConditions()->setJsFormObject(
            $location->getRule()->getConditionsFieldSetId($location->getRule()->getConditions()->getFormName())
        );

        return $resultPage;
    }
}

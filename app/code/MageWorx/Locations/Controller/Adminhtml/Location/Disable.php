<?php
/**
 * Copyright ©  MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Controller\Adminhtml\Location;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use \MageWorx\Locations\Controller\Adminhtml\Location;
use MageWorx\Locations\Api\LocationRepositoryInterface;
use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\Locations\Model\Registry;

class Disable extends Location
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Delete constructor.
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
     * Edit location
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id             = $this->getRequest()->getParam('entity_id');

        if ($id) {
            try {
                /** @var \MageWorx\Locations\Model\Location $location */
                $location = $this->locationRepository->getById($id);
                $location->setIsActive(LocationInterface::INACTIVE);
                $this->locationRepository->save($location);
                $this->messageManager->addSuccessMessage(__('The store has been disabled.'));
                $resultRedirect->setPath('mageworx_locations/*/');

                return $resultRedirect;
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $resultRedirect->setPath('mageworx_locations/*/');

                return $resultRedirect;
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a store to disable.'));
        $resultRedirect->setPath('mageworx_locations/*/');

        return $resultRedirect;
    }
}

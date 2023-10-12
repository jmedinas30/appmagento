<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Controller\Adminhtml\Page;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use MageWorx\LocationPages\Model\LocationPageFactory;
use MageWorx\Locations\Api\LocationRepositoryInterface;

/**
 * Class View
 */
class View extends Action
{
    /**
     * @var LocationPageFactory
     */
    protected $locationPageFactory;

    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * @var \Magento\Framework\Controller\Result\ForwardFactory
     */
    private $resultForwardFactory;

    /**
     * View constructor.
     *
     * @param LocationRepositoryInterface $locationRepository
     * @param LocationPageFactory $LocationPageFactory
     * @param \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
     * @param Context $context
     */
    public function __construct(
        LocationRepositoryInterface $locationRepository,
        LocationPageFactory $LocationPageFactory,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        Context $context
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        $this->locationPageFactory  = $LocationPageFactory;
        $this->locationRepository   = $locationRepository;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $locationId     = $this->getRequest()->getParam('entity_id');
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($locationId) {
            /** @var \MageWorx\LocationPages\Model\LocationPage $locationPage */
            $locationPage = $this->locationPageFactory->create();
            $locationPage->setLocation($this->locationRepository->getById($locationId));
            $url = $locationPage->getFullUrl();
            if ($url) {
                $resultRedirect->setUrl($url);
            } else {
                $this->messageManager->addErrorMessage(__('We can\'t find a page for default store view.'));

                return $this->resultForwardFactory->create()->forward('noroute');
            }
        } else {
            $this->messageManager->addErrorMessage(__('We can\'t find a store.'));

            return $this->resultForwardFactory->create()->forward('noroute');
        }

        return $resultRedirect;
    }
}

<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Controller\Adminhtml\Location;

use Magento\Backend\App\Action\Context;
use MageWorx\Locations\Model\Registry;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\Exception\LocalizedException;
use MageWorx\Locations\Controller\Adminhtml\Location;
use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\Locations\Api\LocationRepositoryInterface;

abstract class MassAction extends Location
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * MassAction constructor.
     *
     * @param Registry $registry
     * @param Context $context
     * @param Filter $filter
     * @param LocationRepositoryInterface $locationRepository
     */
    public function __construct(
        Registry $registry,
        Context $context,
        Filter $filter,
        LocationRepositoryInterface $locationRepository
    ) {
        parent::__construct(
            $registry,
            $locationRepository,
            $context
        );
        $this->filter = $filter;
    }

    /**
     * @param LocationInterface $location
     * @return $this
     */
    abstract protected function executeAction(LocationInterface $location): MassAction;

    /**
     * @return \Magento\Framework\Phrase
     */
    abstract protected function getErrorMessage(): \Magento\Framework\Phrase;

    /**
     * @param int $collectionSize
     * @return \Magento\Framework\Phrase
     */
    abstract protected function getSuccessMessage(int $collectionSize): \Magento\Framework\Phrase;

    /**
     * execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        try {
            $collection     = $this->filter->getCollection($this->locationRepository->getListLocation());
            $collectionSize = $collection->getSize();
            foreach ($collection as $location) {
                /** @var LocationInterface $location */
                $location = $this->locationRepository->getById($location->getId());
                $this->executeAction($location);
            }

            $this->messageManager->addSuccessMessage($this->getSuccessMessage($collectionSize));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, $this->getErrorMessage());
        }
        $redirectResult = $this->resultRedirectFactory->create();
        $redirectResult->setPath('mageworx_locations/*/index');

        return $redirectResult;
    }
}

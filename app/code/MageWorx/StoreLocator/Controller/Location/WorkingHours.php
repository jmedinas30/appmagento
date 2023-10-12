<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreLocator\Controller\Location;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use MageWorx\Locations\Api\LocationRepositoryInterface;

/**
 * Class WorkingHours
 */
class WorkingHours extends Action
{
    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * WorkingHours constructor.
     *
     * @param LocationRepositoryInterface $locationRepository
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param Context $context
     */
    public function __construct(
        LocationRepositoryInterface $locationRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Context $context
    ) {
        $this->locationRepository = $locationRepository;
        $this->storeManager       = $storeManager;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $storeId     = $this->storeManager->getStore()->getId();
        $locationIds = $this->getRequest()->getParam('location_ids');
        $result      = [];
        if (is_array($locationIds)) {
            $locations = $this->locationRepository->getListLocationByIds($locationIds, $storeId);

            foreach ($locations as $location) {
                $result[$location->getId()]['isOpen'] = $location->isOpenNow();
                $result[$location->getId()]['info']   = $location->getWorkingHoursInfo();
            }
        }

        $this->getResponse()->setBody(json_encode($result));
    }
}

<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
declare(strict_types=1);

namespace MageWorx\LocationPages\Controller\Adminhtml\LoadFilterList;

use \Magento\Backend\App\Action\Context;
use MageWorx\LocationPages\Api\LocationListRepositoryInterface;
use \Magento\Framework\Controller\Result\JsonFactory;
use MageWorx\StoreLocator\Helper\Data;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var LocationListRepositoryInterface
     */
    protected $locationListRepository;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    const ADMIN_RESOURCE = 'MageWorx_Locations::settings';

    /**
     * Index constructor.
     *
     * @param Data $helper
     * @param Context $context
     * @param LocationListRepositoryInterface $locationListRepository
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Data $helper,
        Context $context,
        LocationListRepositoryInterface $locationListRepository,
        JsonFactory $resultJsonFactory
    ) {
        $this->helper                 = $helper;
        $this->locationListRepository = $locationListRepository;
        $this->resultJsonFactory      = $resultJsonFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $result     = $this->resultJsonFactory->create();
        try {
            $collection = $this->locationListRepository->getLocationsWithEmptyCoordinates(
                $this->helper->getFilterBy()
            );
            $filters    = [];

            foreach ($collection as $filter) {
                $filters[] = [
                    'id'      => $filter->getId(),
                    'address' => $this->locationListRepository->getAddressByLocationList($filter)
                ];
            }

            if (count($filters)) {
                $data = [
                    'status' => 1,
                    'list' => $filters,
                    'msg' => __('Filter list successfully loaded...')
                ];
            } else {
                $data = [
                    'status' => 1,
                    'list' => $filters,
                    'msg' => __('There are no filter without coordinates.')
                ];
            }
        } catch (\Exception $exception) {
            $data = [
                'status' => 0,
                'msg' => $exception->getMessage()
            ];
        }

        $result->setData($data);

        return $result;
    }
}

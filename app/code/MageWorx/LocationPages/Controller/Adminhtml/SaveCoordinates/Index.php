<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
declare(strict_types=1);

namespace MageWorx\LocationPages\Controller\Adminhtml\SaveCoordinates;

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
        $data   = $this->getRequest()->getParams();
        $result = $this->resultJsonFactory->create();

        if (isset($data['data'])) {
            $coordinates  = $data['data'];
            $locationList = $this->locationListRepository->getById($coordinates['id']);
            $errorIn      = [];
            $successfully = 0;
            try {
                $locationList->setLatitude($coordinates['lat']);
                $locationList->setLongitude($coordinates['lng']);
                $this->locationListRepository->save($locationList);
                $successfully++;
            } catch (\Magento\Framework\Exception\CouldNotSaveException $e) {
                $errorIn[] = $locationList->getName();
            }

            $status = 0;

            if ($successfully) {
                $status = 1;
            }

            $result->setData(
                [
                    'status'       => $status,
                    'successfully' => $successfully,
                    'err'          => count($errorIn),
                    'error_msg'    => __('Could not save coordinates for next places: ') . implode(', ', $errorIn)
                ]
            );

        } else {
            $result->setData(
                [
                    'status'       => 0,
                    'successfully' => 0,
                    'err'          => 1,
                    'error_msg'    => __('Empty data')
                ]
            );
        }

        return $result;
    }
}

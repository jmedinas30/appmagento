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
use Magento\CatalogRule\Model\RuleFactory;
use MageWorx\Locations\Model\Registry;
use Magento\Framework\Exception\LocalizedException;
use MageWorx\Locations\Helper\Data as HelperLocation;
use MageWorx\Locations\Api\Data\LocationInterface;

class Save extends Location
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var  RuleFactory
     */
    protected $ruleFactory;

    /**
     * @var HelperLocation
     */
    protected $helperLocation;

    /**
     * Save constructor.
     *
     * @param HelperLocation $helperLocation
     * @param RuleFactory $ruleFactory
     * @param Registry $registry
     * @param LocationRepositoryInterface $locationRepository
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        HelperLocation $helperLocation,
        RuleFactory $ruleFactory,
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
        $this->ruleFactory       = $ruleFactory;
        $this->helperLocation    = $helperLocation;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $data           = $this->getRequest()->getPost()->toArray();
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!$data) {
            $resultRedirect->setPath('mageworx_locations/*/');

            return $resultRedirect;
        }

        $data = $this->prepareData($data);

        if (!empty($data['entity_id'])) {
            $id = $data['entity_id'];
            /** @var \MageWorx\Locations\Model\Location $location */
            $location = $this->initLocation($data['entity_id']);
        } else {
            $id       = '';
            $location = $this->initLocation();
        }

        $data['rule'] = $this->prepareRule($data, $location);

        $location->setData($data);
        $location->setCurrentStoreId($this->getRequest()->getParam('store'));

        try {
            if (empty($data['entity_id'])) {
                $this->locationRepository->checkLocationByCode($data['code']);
            }

            $this->locationRepository->save($location);
            $this->_getSession()->setData('mageworx_locations_location', false);
            if ($this->getRequest()->getParam('back')) {
                $resultRedirect->setPath(
                    'mageworx_locations/*/edit',
                    [
                        'entity_id' => $location->getId(),
                        '_current'  => true
                    ]
                );

                return $resultRedirect;
            }
            $resultRedirect->setPath('mageworx_locations/*/');
            $name = $location->getName();
            $this->messageManager->addSuccessMessage(__('Store "%1" was successfully saved.', $name));

            return $resultRedirect;
        } catch (LocalizedException $e) {
            $this->messageManager->addExceptionMessage($e, $e->getMessage());
        } catch (\RuntimeException $e) {
            $this->messageManager->addExceptionMessage($e, $e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, $e->getMessage());
        }

        $this->_getSession()->setData('mageworx_locations_location', $this->getRequest()->getPost()->toArray());

        $resultRedirect->setPath(
            'mageworx_locations/*/edit',
            [
                'entity_id' => $id,
                '_current'  => true
            ]
        );

        return $resultRedirect;
    }

    /**
     * @param string[] $data
     * @return string[]
     */
    protected function prepareData($data)
    {
        $data['code'] = str_replace(' ', '_', $data['code']);

        if (!empty($data['image_path'][0]['file'])) {
            $data['image_path'] = $data['image_path'][0]['file'];
        } elseif (!empty($data['image_path'][0]['name'])) {
            $data['image_path'] = $data['image_path'][0]['name'];
        }

        if ($data['working_hours_type'] === LocationInterface::WORKING_24_HOURS_A_DAY) {
            $data['working_hours'] = [];
        } elseif (isset($data['working_hours']) && $data['working_hours_type']) {
            if ($data['working_hours_type'] == 'everyday') {
                $result ['everyday']   = $data['working_hours']['everyday'];
                $data['working_hours'] = $result;
            } else {
                unset($data['working_hours']['everyday']);
            }

            foreach ($data['working_hours'] as $key => $array) {
                foreach ($array as $dataKey => $value) {
                    if (in_array($dataKey, ['from', 'to', 'lunch_from', 'lunch_to'])
                        && isset($value['hour'])
                        && isset($value['minute'])
                        && isset($value['day_part'])
                    ) {
                        $data['working_hours'][$key][$dataKey] =
                            $value['hour'] . ':' . $value['minute'] . ' ' . $value['day_part'];
                    }
                }
            }
        }

        if (isset($data['product_skus']) && is_string($data['product_skus'])) {
            $data['product_skus'] = json_decode($data['product_skus'], true);
        }

        return $data;
    }

    /**
     * @param string[] $data
     * @param Location $location
     * @return \Magento\CatalogRule\Model\Rule
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function prepareRule($data, $location)
    {
        $rule = $this->ruleFactory->create();

        if (isset($data['rule']['conditions'])) {
            $ruleData['conditions'] = $data['rule']['conditions'];
            $rule->loadPost($ruleData);
            $rule->setWebsiteIds($this->helperLocation->getWebsiteIdsFromStoreIds($data['store_ids']));
        } else {
            $rule = $location->getRule();
        }

        return $rule;
    }
}

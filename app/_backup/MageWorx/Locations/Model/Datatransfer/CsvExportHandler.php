<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Model\Datatransfer;

use Magento\Framework\DataObject;
use MageWorx\Locations\Model\Source\WorkingDay;
use MageWorx\Locations\Helper\Data as Helper;
use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\Locations\Api\LocationRepositoryInterface;
use Magento\Store\Model\Store;

class CsvExportHandler
{
    const FILE_NAME = 'export_mageworx_stores.csv';

    /**
     * @var array
     */
    protected $staticAttributeList = [
        'store_ids',
        'working_hours',
        'product_skus'
    ];

    /**
     * @var WorkingDay
     */
    protected $workingDay;

    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory
     */
    protected $attributeCollectionFactory;

    /**
     * @var \Magento\Eav\Model\Entity
     */
    protected $entity;

    /**
     * CsvExportHandler constructor.
     *
     * @param \Magento\Eav\Model\Entity $entity
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory $attributeCollectionFactory
     * @param Helper $helper
     * @param WorkingDay $workingDay
     * @param LocationRepositoryInterface $locationRepository
     * @param string[] $data
     */
    public function __construct(
        \Magento\Eav\Model\Entity $entity,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory $attributeCollectionFactory,
        Helper $helper,
        WorkingDay $workingDay,
        LocationRepositoryInterface $locationRepository,
        $data = []
    ) {

        $this->entity                     = $entity;
        $this->attributeCollectionFactory = $attributeCollectionFactory;
        $this->helper                     = $helper;
        $this->workingDay                 = $workingDay;
        $this->locationRepository         = $locationRepository;
        $this->ignoreAttribute            = $data;
    }

    /**
     * @param int $onlyDafault
     * @return string
     */
    public function export($onlyDafault = 1)
    {
        /** start csv content and set template */
        $attributeCodes = $this->prepareHeadersData();

        $headers  = new DataObject(
            $attributeCodes
        );
        $template = '"{{' . implode('}}","{{', $attributeCodes) . '}}"';
        $content  = $headers->toString($template);

        $content .= "\n";
        $data    = [];

        $storeIds = $onlyDafault ? [Store::DEFAULT_STORE_ID] : $this->helper->getAllStoreIds();

        foreach ($storeIds as $storeId) {
            $this->helper->setCurrentStoreIdForLocation($storeId);
            $collection = $this->locationRepository->getListLocation();

            foreach ($collection as $location) {
                $location->setStoreCode($this->helper->getStoreCodeById($storeId));
                $this->prepareData($location);

                $data[$location->getId()][$storeId] = $location->toString($template);
            }
        }

        foreach ($data as $locationData) {
            foreach ($locationData as $dataByStores) {
                $content .= $dataByStores . "\n";
            }
        }

        return $content;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return self::FILE_NAME;
    }

    /**
     * @return string[]
     */
    protected function prepareHeadersData()
    {
        /** @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection $attributeCollection */
        $attributeList = $this->attributeCollectionFactory->create();
        $attributeList->addFieldToFilter(
            'entity_type_id',
            $this->entity->setType(LocationInterface::ENTITY)->getTypeId()
        )->setOrder('attribute_id', 'ASC');

        $attributeCodes = [];

        $attributeCodes['code']        = 'code';
        $attributeCodes['store_code']  = 'store_code';
        $attributeCodes['store_codes'] = 'store_codes';

        foreach ($attributeList as $item) {

            $code = $item->getAttributeCode();
            if ($code == 'code') {
                continue;
            }

            if (array_search($code, $this->ignoreAttribute) !== false) { //skip unnecessary data
                continue;
            }

            $attributeCodes[$code] = $code;

            if ($item->getBackendType() == 'static') {
                $this->staticAttributeList[] = $code;
            }
        }

        $attributeCodes['working_hours_everyday'] = 'working_hours_everyday';
        foreach ($this->workingDay->toOptionArray() as $day) {
            $name                  = 'working_hours_' . $day['value'];
            $attributeCodes[$name] = $name;
        }

        $attributeCodes['product_skus'] = 'product_skus';

        return $attributeCodes;
    }

    /**
     * @param LocationInterface $location
     */
    protected function prepareData($location)
    {
        foreach ($location->getData() as $code => $value) {
            if ($this->helper->getCurrentStoreIdForLocation() != Store::DEFAULT_STORE_ID
                && (array_search($code, $this->staticAttributeList) !== false)
            ) {
                $location->setData($code, '');
                continue;
            }
            if (is_array($value)) {
                if ($code == 'working_hours') {
                    foreach ($value as $day => $item) {
                        if ($item['off']) {
                            $preparedValue = 'off';
                        } else {
                            $preparedValue = $item['from'] . ' - ' . $item['to'];

                            if ($item['has_lunch_time']) {
                                $preparedValue .= '||' . $item['lunch_from'] . ' - ' . $item['lunch_to'];
                            }
                        }

                        $location->setData('working_hours_' . $day, $preparedValue);
                    }
                    continue;
                }
                if ($code == 'store_ids') {
                    $codes = [];
                    foreach ($value as $storeId) {
                        $codes[] = $this->helper->getStoreCodeById($storeId);
                    }
                    $location->setData('store_codes', implode(LocationInterface::IMPORT_ARRAY_SEPARATOR, $codes));
                    continue;
                }
                $location->setData($code, implode(LocationInterface::IMPORT_ARRAY_SEPARATOR, $value));
                continue;
            }
            if ($code == 'assign_type' && $value == LocationInterface::ASSIGN_TYPE_CONDITION) {
                $location->setData($code, LocationInterface::ASSIGN_TYPE_SPECIFIC_PRODUCTS);
            }
        }
    }
}

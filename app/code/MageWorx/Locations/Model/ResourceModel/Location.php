<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Model\ResourceModel;

use Magento\Eav\Model\Entity\Context;
use Magento\Framework\DataObject;
use MageWorx\Locations\Api\Data\LocationInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Profiler;
use MageWorx\Locations\Model\Source\WorkingDay;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;

/**
 * {@inheritdoc}
 */
class Location extends \Magento\Eav\Model\Entity\AbstractEntity
{

    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'mageworx_locations_location_collection';
    protected $_eventObject = 'location_collection';
    protected $_read;
    protected $_write;
    protected $_mainTable;

    /** @var EventManagerInterface */
    protected $eventManager;

    /** @var \Magento\Framework\App\State */
    protected $state;

    /** @var  \MageWorx\Locations\Helper\Data */
    protected $helper;

    /**
     * @var Json $serializer
     */
    protected $serializer;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var WorkingDay
     */
    protected $workingDays;

    /**
     * Location constructor.
     *
     * @param WorkingDay $workingDays
     * @param \MageWorx\Locations\Helper\Data $helper
     * @param StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\State $state
     * @param EventManagerInterface $eventManager
     * @param Json $serializer
     * @param Context $context
     * @param string[] $data
     */
    public function __construct(
        WorkingDay $workingDays,
        \MageWorx\Locations\Helper\Data $helper,
        StoreManagerInterface $storeManager,
        \Magento\Framework\App\State $state,
        EventManagerInterface $eventManager,
        Json $serializer,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->workingDays  = $workingDays;
        $this->helper       = $helper;
        $this->state        = $state;
        $this->storeManager = $storeManager;
        $this->serializer   = $serializer;
        $this->eventManager = $eventManager;
    }

    /**
     * @return $this|void
     */
    protected function _construct()
    {
        $this->_read      = LocationInterface::ENTITY . '_read';
        $this->_write     = LocationInterface::ENTITY . '_write';
        $this->_mainTable = LocationInterface::ENTITY_TABLE;

        return $this;
    }

    /**
     * @return \Magento\Eav\Model\Entity\Type
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getEntityType()
    {
        if (empty($this->_type)) {
            $this->setType(LocationInterface::ENTITY);
        }

        return parent::getEntityType();
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return $this
     */
    protected function _afterSave(\Magento\Framework\DataObject $object)
    {
        $this->saveStoreRelation($object);
        $this->saveProductRelation($object);
        $this->saveWorkingHours($object);

        $this->eventManager->dispatch(
            'mageworx_location_after_save',
            ['location' => $object]
        );

        return parent::_afterSave($object);
    }

    /**
     * @param \Magento\Framework\DataObject $object
     */
    protected function saveWorkingHours($object)
    {
        if ($object->getData(LocationInterface::WORKING_HOURS_TYPE) == LocationInterface::WORKING_24_HOURS_A_DAY) {
            $this->deleteWorkingHours($object);

            return;
        }

        $workingHours = $object->getData('working_hours');

        if (!$workingHours) {
            return;
        }

        if (!is_array($workingHours)) {
            $workingHours = explode(',', $workingHours);
        }

        $this->deleteWorkingHours($object);

        $relation = [];
        if ($object->getWorkingHoursType() == LocationInterface::WORKING_EVERYDAY) {
            if (isset($workingHours[LocationInterface::WORKING_EVERYDAY])) {
                $relation[] = [
                    'entity_id'      => $object->getId(),
                    'day'            => LocationInterface::WORKING_EVERYDAY,
                    'from'           => $workingHours[LocationInterface::WORKING_EVERYDAY]['from'],
                    'to'             => $workingHours[LocationInterface::WORKING_EVERYDAY]['to'],
                    'is_day_off'     => 0,
                    'lunch_from'     => $workingHours[LocationInterface::WORKING_EVERYDAY]['lunch_from'],
                    'lunch_to'       => $workingHours[LocationInterface::WORKING_EVERYDAY]['lunch_to'],
                    'has_lunch_time' => $workingHours[LocationInterface::WORKING_EVERYDAY]['has_lunch_time']
                ];
            }
        } elseif ($object->getWorkingHoursType() == LocationInterface::WORKING_PER_DAY_OF_WEEK) {
            foreach ($this->workingDays->toArray() as $day => $label) {
                if (isset($workingHours[$day])) {
                    $relation[] = [
                        'entity_id'      => $object->getId(),
                        'day'            => $day,
                        'from'           => $workingHours[$day]['from'],
                        'to'             => $workingHours[$day]['to'],
                        'is_day_off'     => $workingHours[$day]['off'],
                        'lunch_from'     => $workingHours[$day]['lunch_from'],
                        'lunch_to'       => $workingHours[$day]['lunch_to'],
                        'has_lunch_time' => $workingHours[$day]['has_lunch_time']
                    ];
                } else {
                    $relation[] = [
                        'entity_id'      => $object->getId(),
                        'day'            => $day,
                        'from'           => "",
                        'to'             => "",
                        'is_day_off'     => 1,
                        'lunch_from'     => "",
                        'lunch_to'       => "",
                        'has_lunch_time' => 0
                    ];
                }
            }
        }

        if (!empty($relation)) {
            $this->getConnection()->insertMultiple(
                $this->getTable(LocationInterface::ENTITY_WORKING_HOURS_TABLE),
                $relation
            );
        }
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     */
    protected function deleteWorkingHours(\Magento\Framework\Model\AbstractModel $object)
    {
        $where = 'entity_id = "' . $object->getId() . '"';
        $this->getConnection()->delete(
            $this->getTable(LocationInterface::ENTITY_WORKING_HOURS_TABLE),
            $where
        );
    }

    /**
     * @param \Magento\Framework\DataObject $object
     */
    protected function saveStoreRelation($object)
    {
        $this->deleteStoreRelation($object);

        $selectedStoreIds = $object->getStoreIds();

        if (is_string($selectedStoreIds)) {
            $selectedStoreIds = explode(',', $selectedStoreIds);
        }

        if (!empty($selectedStoreIds)) {
            $relation = [];

            if (in_array(Store::DEFAULT_STORE_ID, $selectedStoreIds)) {
                $relation[] = [
                    'entity_id' => $object->getId(),
                    'store_id'  => Store::DEFAULT_STORE_ID
                ];
            } else {
                foreach ($selectedStoreIds as $storeId) {
                    $relation[] = [
                        'entity_id' => $object->getId(),
                        'store_id'  => $storeId
                    ];
                }
            }

            $this->getConnection()->insertMultiple(
                $this->getTable(LocationInterface::ENTITY_STORE_RELATION_TABLE),
                $relation
            );
        }
    }

    /**
     * @param \Magento\Framework\DataObject $object
     */
    public function saveProductRelation($object)
    {
        $this->deleteProductRelation($object);
        if ($object->getData('assign_type') == LocationInterface::ASSIGN_TYPE_CONDITION) {
            if ($rule = $object->getRule()) {
                $productIds         = $rule->getMatchingProductIds();
                $selectedProductSku = $this->getProductSkus($productIds);
            } else {
                $selectedProductSku = [];
            }
        } elseif ($object->getData('assign_type') == LocationInterface::ASSIGN_TYPE_SPECIFIC_PRODUCTS) {
            $selectedProductSku = $object->getProductSkus();
        }

        if (!empty($selectedProductSku)) {
            $relation = [];
            foreach ($selectedProductSku as $sku) {
                if (isset($sku['sku'])) {
                    $sku = $sku['sku'];
                }
                $relation[] = [
                    'location_code' => $object->getCode(),
                    'product_sku'   => $sku
                ];
            }

            $this->getConnection()->insertMultiple(
                $this->getTable(LocationInterface::ENTITY_PRODUCT_RELATION_TABLE),
                $relation
            );
        }
    }

    /**
     * @param string[] $productIds
     * @return string[]
     */
    protected function getProductSkus($productIds)
    {
        foreach ($productIds as $productId => $value) {
            if (is_array($value) && isset($value[1])) {
                if ($value[1] === false) {
                    unset($productIds[$productId]);
                } else {
                    $productIds[$productId] = $productId;
                }
            } else {
                $productIds[$productId] = $productId;
            }
        }

        $connection = $this->getConnection();
        $select     = $connection->select()
                                 ->from($this->getTable('catalog_product_entity'), 'sku')
                                 ->where('entity_id IN (?)', $productIds);

        return $connection->fetchAll($select);
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return $this
     */
    protected function _afterLoad(\Magento\Framework\DataObject $object)
    {
        $this->loadStoreRelation($object);
        $this->loadProductRelation($object);
        $this->loadWorkingHours($object);
        $this->loadLocationPageFullUrl($object);

        return parent::_afterLoad($object);
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function loadLocationPageFullUrl($object)
    {
        if ($id = $object->getId()) {
            $connection = $this->getConnection();
            $select     = $connection->select()->from(
                [
                    'url_rewrite' =>
                        $this->getTable('url_rewrite')
                ],
                ['request_path']
            )->where(
                'url_rewrite.entity_type = ?',
                LocationInterface::ENTITY
            )->where(
                'url_rewrite.entity_id = ?',
                $object->getId()
            )->where(
                'url_rewrite.target_path = ?',
                'location_pages/location/view/id/' . $object->getId()
            )->where('url_rewrite.store_id = ?', $this->helper->getStoreId());

            $url      = $connection->fetchOne($select);
            $storeUrl = $this->helper->getStoreUrl();

            if ($url) {
                $object->setPageFullUrl($storeUrl . $url);
            }
        }
    }

    /**
     * @param \Magento\Framework\DataObject $object
     */
    protected function loadWorkingHours($object)
    {
        if ($id = $object->getId()) {
            $connection     = $this->getConnection();
            $select         = $connection->select()->from(
                [
                    'relation' =>
                        $this->getTable(LocationInterface::ENTITY_WORKING_HOURS_TABLE)
                ]
            )->where('relation.entity_id = ?', $id);
            $relationResult = $connection->fetchAll($select);
            if ($relationResult) {
                $data = [];
                foreach ($relationResult as $result) {
                    $data[$result['day']]['from']           = $result['from'];
                    $data[$result['day']]['to']             = $result['to'];
                    $data[$result['day']]['off']            = $result['is_day_off'];
                    $data[$result['day']]['lunch_from']     = $result['lunch_from'];
                    $data[$result['day']]['lunch_to']       = $result['lunch_to'];
                    $data[$result['day']]['has_lunch_time'] = $result['has_lunch_time'];
                }
                $object->setData('working_hours', $data);
            }
        }
    }

    /**
     * @param \Magento\Framework\DataObject $object
     */
    protected function loadStoreRelation($object)
    {
        if ($id = $object->getId()) {
            $object->setCurrentStoreId($this->getCurrentStoreId());

            $connection     = $this->getConnection();
            $select         = $connection->select()->from(
                [
                    'relation' =>
                        $this->getTable(LocationInterface::ENTITY_STORE_RELATION_TABLE)
                ]
            )->where('relation.entity_id = ?', $id);
            $relationResult = $connection->fetchAll($select);
            if ($relationResult) {
                $data = [];
                foreach ($relationResult as $storeData) {
                    $data[] = (int)$storeData['store_id'];

                }
                $object->setStoreIds($data);
            }
        }
    }

    /**
     * @param \Magento\Framework\DataObject $object
     */
    protected function loadProductRelation($object)
    {
        if ($code = $object->getCode()) {
            $connection     = $this->getConnection();
            $select         = $connection->select()->from(
                [
                    'relation' =>
                        $this->getTable(LocationInterface::ENTITY_PRODUCT_RELATION_TABLE)
                ]
            )->where('relation.location_code = ?', $code);
            $relationResult = $connection->fetchAll($select);
            if ($relationResult) {
                $data = [];
                foreach ($relationResult as $productData) {
                    $data[] = $productData['product_sku'];

                }
                $object->setData('product_skus', $data);
            }
        }
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     */
    protected function deleteStoreRelation(\Magento\Framework\Model\AbstractModel $object)
    {
        $where = 'entity_id = "' . $object->getId() . '"';
        $this->getConnection()->delete(
            $this->getTable(LocationInterface::ENTITY_STORE_RELATION_TABLE),
            $where
        );
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     */
    protected function deleteProductRelation(\Magento\Framework\Model\AbstractModel $object)
    {
        $where = 'location_code = "' . $object->getCode() . '"';
        $this->getConnection()->delete(
            $this->getTable(LocationInterface::ENTITY_PRODUCT_RELATION_TABLE),
            $where
        );
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _beforeSave(\Magento\Framework\DataObject $object)
    {
        if ($object->getId()) {
            $code = $object->getOrigData(LocationInterface::CODE);
            $object->setData(LocationInterface::CODE, $code);
        }

        $rule = $object->getData('rule');
        if ($rule && $rule->getConditions()) {
            $object->setConditionsSerialized($this->serializer->serialize($rule->getConditions()->asArray()));
        }

        $website = $object->getData(LocationInterface::WEBSITE_URL);
        if ($website) {
            $website =  str_replace(['http://', 'https://'], '', $website);
        }

        $object->setData(LocationInterface::WEBSITE_URL, $website);

        if ($object->getCurrentStoreId()) {
            $object = $this->saveDataForStores($object);
            $object->setHasDataChanges(false);
        }

        $this->eventManager->dispatch(
            'mageworx_location_before_save',
            ['location' => $object]
        );

        return parent::_beforeSave($object);
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return mixed
     */
    public function saveDataForStores($object)
    {
        foreach ($this->getAttributesByTable() as $table => $tableData) {
            $attrIds = [];
            $data    = [];
            foreach ($tableData as $attr) {
                $attrCode = $attr->getAttributeCode();
                if ($object->getData($attrCode) === '') {
                    continue;
                }
                if ($object->getData($attrCode) !== $object->getOrigData($attrCode)) {
                    $attrIds[] = $attr->getId();
                    $data[]    = [
                        'attribute_id' => $attr->getId(),
                        'store_id'     => $object->getCurrentStoreId(),
                        'entity_id'    => $object->getId(),
                        'value'        => $object->getData($attrCode)
                    ];
                }
            }

            $this->getConnection()->delete(
                $this->getTable($table),
                [
                    'entity_id = ?'       => $object->getId(),
                    'store_id = ?'        => $object->getCurrentStoreId(),
                    'attribute_id IN (?)' => $attrIds
                ]
            );

            if (!empty($data)) {
                $this->getConnection()->insertMultiple(
                    $this->getTable($table),
                    $data
                );
            }
        }

        return $object;
    }

    /**
     * @param string $code
     * @return string
     */
    public function getIdByLocationCode($code)
    {
        $connection = $this->getConnection();
        $select     = $connection->select()->from($this->getTable(LocationInterface::ENTITY_TABLE), 'entity_id')
                                 ->where('code = :locationCode');

        $bind = [':locationCode' => (string)$code];

        return $connection->fetchOne($select, $bind);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $location
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function _loadModelAttributes($location)
    {
        if (!$location->getId()) {
            return $this;
        }

        Profiler::start('load_model_attributes');

        $sqls = [];
        foreach (array_keys($this->getAttributesByTable()) as $table) {
            $attribute        = current($this->_attributesByTable[$table]);
            $eavType          = $attribute->getBackendType();
            $sql              = $this->_getLoadAttributesSelect($location, $table);
            $sqls[$eavType][] = $sql->columns('*');
        }
        $selectGroups = $this->_resourceHelper->getLoadAttributesSelectGroups($sqls);
        foreach ($selectGroups as $sqls) {
            if (!empty($sqls)) {
                if (is_array($sqls)) {
                    $sql = $this->_prepareLoadSelect($sqls);
                } else {
                    $sql = $sqls;
                }

                $values = $this->getConnection()->fetchAll($sql);
                $values = $this->prepareAttributeValues($values);
                foreach ($values as $valueRow) {
                    $this->_setAttributeValue($location, $valueRow);
                }
            }
        }

        Profiler::stop('load_model_attributes');

        return $this;
    }

    /**
     * @param string[] $values
     * @return string[]
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function prepareAttributeValues($values)
    {
        $valuesByStores = [];
        $result         = [];
        foreach ($values as $key => $valueRow) {
            $valuesByStores[$valueRow['attribute_id']][$valueRow['store_id']] = $valueRow;
        }

        if ($this->state->getAreaCode() == 'frontend' || $this->state->getAreaCode() == 'webapi_rest') {
            $storeId = $this->storeManager->getStore()->getId();
            foreach ($valuesByStores as $valueRows) {
                if (isset($valueRows[$storeId])) {
                    $result[] = $valueRows[$storeId];
                } else {
                    if (isset($valueRows[Store::DEFAULT_STORE_ID])) {
                        $result[] = $valueRows[Store::DEFAULT_STORE_ID];
                    }
                }
            }
        } else {
            $currentStoreId = $this->getCurrentStoreId();
            foreach ($valuesByStores as $valueRows) {
                if (isset($valueRows[$currentStoreId])) {
                    $result[] = $valueRows[$currentStoreId];
                }
            }
        }

        return $result;
    }

    /**
     * @return int|null
     */
    protected function getCurrentStoreId()
    {
        return $this->helper->getCurrentStoreIdForLocation();
    }

    /**
     * @param string[] $updateData
     * @return $this
     */
    public function updatePathInfo($updateData)
    {
        $connection = $this->getConnection();

        $conditions = [];

        foreach ($updateData as $id => $qty) {

            $case = $connection->quoteInto('?', $id);

            $result = $connection->quoteInto('?', $qty);

            $conditions[$case] = $result;

        }

        $value = $connection->getCaseSql('entity_id', $conditions, 'location_page_path');

        $where = ['entity_id IN (?)' => array_keys($updateData)];

        $connection->update(
            $this->getTable(LocationInterface::ENTITY_TABLE),
            ['location_page_path' => $value],
            $where
        );

        return $this;
    }

    /**
     * @param DataObject $object
     * @return $this
     */
    protected function _afterDelete(DataObject $object)
    {
        $this->eventManager->dispatch(
            'mageworx_location_after_delete',
            ['location' => $object]
        );

        return parent::_afterDelete($object);
    }

    /**
     * @return array
     */
    public function getCustomAttributes()
    {
        $connection = $this->getConnection();
        $select     = $connection->select()->distinct()->from(
            $this->getTable('eav_attribute'),
            ['attribute_code', 'frontend_label']
        )->joinLeft(
        ['entity_table' => $this->getTable('eav_entity_type')],
        $this->getTable('eav_attribute') . '.entity_type_id = entity_table.entity_type_id',
        []
        )->joinInner(
            ['entity_attribute_int_table' => $this->getTable('mageworx_location_entity_int')],
            $this->getTable('eav_attribute') . '.attribute_id = entity_attribute_int_table.attribute_id',
            []
        )->where("`" . $this->getTable('eav_attribute') . "`.`backend_type` = 'int'"
        )->where("`entity_attribute_int_table`.`value` = 1"
        )->where("`entity_table`.`entity_type_code` = ?", 'mageworx_location');

        return $connection->fetchAll($select);
    }
}

<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Model\ResourceModel\Location;

use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use MageWorx\Locations\Api\Data\LocationInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Magento\CatalogInventory\Model\Configuration;

/**
 * {@inheritdoc}
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /** @var \Magento\Framework\App\State */
    protected $state;

    /**
     * @var \MageWorx\Locations\Model\MsiResolver
     */
    protected $msiResolver;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /** @var  \MageWorx\Locations\Helper\Data */
    protected $helper;

    /**
     * @var \MageWorx\StoreLocator\Helper\Data
     */
    protected $helperLocator;

    /** @var Configuration */
    protected $inventoryConfig;

    protected $attributesForSearch = [
        'small_city',
        'city',
        'country_id',
        'region',
        'postcode'
    ];

    /**
     * @param Configuration $inventoryConfig
     * @param \MageWorx\Locations\Model\MsiResolver $msiResolver
     * @param \MageWorx\Locations\Helper\Data $helper
     * @param \MageWorx\StoreLocator\Helper\Data $helperLocator
     * @param StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\State $state
     * @param \Magento\Framework\Data\Collection\EntityFactory $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Eav\Model\Config $eavConfig
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param \Magento\Eav\Model\EntityFactory $eavEntityFactory
     * @param \Magento\Eav\Model\ResourceModel\Helper $resourceHelper
     * @param \Magento\Framework\Validator\UniversalFactory $universalFactory
     * @param \Magento\Framework\DB\Adapter\AdapterInterface|null $connection
     */
    public function __construct(
        Configuration $inventoryConfig,
        \MageWorx\Locations\Model\MsiResolver $msiResolver,
        \MageWorx\Locations\Helper\Data $helper,
        \MageWorx\StoreLocator\Helper\Data $helperLocator,
        StoreManagerInterface $storeManager,
        \Magento\Framework\App\State $state,
        \Magento\Framework\Data\Collection\EntityFactory $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Eav\Model\EntityFactory $eavEntityFactory,
        \Magento\Eav\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\Validator\UniversalFactory $universalFactory,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $eavConfig,
            $resource,
            $eavEntityFactory,
            $resourceHelper,
            $universalFactory,
            $connection
        );
        $this->state           = $state;
        $this->storeManager    = $storeManager;
        $this->helper          = $helper;
        $this->helperLocator   = $helperLocator;
        $this->msiResolver     = $msiResolver;
        $this->inventoryConfig = $inventoryConfig;
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \MageWorx\Locations\Model\Location::class,
            \MageWorx\Locations\Model\ResourceModel\Location::class
        );
        $this->_setIdFieldName($this->_idFieldName);
    }

    /**
     * @param array|bool $filters
     * @return $this
     */
    public function setOrderByOrderField($filters = false)
    {
        if ($filters && $this->canApplyRadiusFilter($filters)) {
            $this->getSelect()->order('distance ASC');
        } else {
            $this->getSelect()->order('e.order ASC');
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function _afterLoad()
    {
        $this->loadStoreRelation();
        $this->loadProductRelation();
        $this->loadWorkingHours();
        $this->loadLocationPageFullUrl();

        return parent::_afterLoad();
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function loadLocationPageFullUrl()
    {
        $ids        = $this->getColumnValues('entity_id');
        $connection = $this->getConnection();
        $select     = $connection->select()->from(
            [
                'url_rewrite' =>
                    $this->getTable('url_rewrite')
            ]
        )->where('entity_type = "' . LocationInterface::ENTITY . '" AND url_rewrite.entity_id IN (?)', $ids);

        $urls = $connection->fetchAll($select);
        if ($urls) {
            $data = [];
            foreach ($urls as $resultData) {
                $id                        = $resultData['entity_id'];
                $data[$id]['request_path'] = $resultData['request_path'];
            }
        }
        $storeUrl = $this->helper->getStoreUrl();
        foreach ($this as $item) {
            $linkedId = $item->getData('entity_id');
            if (!empty($data[$linkedId])) {
                $item->setPageFullUrl($storeUrl . $data[$linkedId]['request_path']);
            }
        }
    }

    /**
     * load user relation
     */
    protected function loadStoreRelation()
    {
        $ids = $this->getColumnValues('entity_id');
        if (count($ids)) {
            $data       = [];
            $connection = $this->getConnection();
            $select     = $connection->select()->from(
                [
                    'relation_store' =>
                        $this->getTable(LocationInterface::ENTITY_STORE_RELATION_TABLE)
                ]
            )->where('relation_store.entity_id IN (?)', $ids);

            $relationResult = $connection->fetchAll($select);
            if ($relationResult) {
                $data = [];
                foreach ($relationResult as $storeData) {
                    $id                       = $storeData['entity_id'];
                    $data[$id]['store_ids'][] = $storeData['store_id'];
                }
            }
            foreach ($this as $item) {
                $linkedId = $item->getData('entity_id');
                if (!empty($data[$linkedId])) {
                    $item->setStoreIds($data[$linkedId]['store_ids']);
                } else {
                    $item->setStoreIds([]);
                }
            }
        }
    }

    /**
     * load user relation
     */
    protected function loadWorkingHours()
    {
        $ids = $this->getColumnValues('entity_id');
        if (count($ids)) {
            $data       = [];
            $connection = $this->getConnection();
            $select     = $connection->select()->from(
                [
                    'relation_store' =>
                        $this->getTable(LocationInterface::ENTITY_WORKING_HOURS_TABLE)
                ]
            )->where('relation_store.entity_id IN (?)', $ids);

            $relationResult = $connection->fetchAll($select);

            if ($relationResult) {
                foreach ($relationResult as $storeData) {
                    $id                                             = $storeData['entity_id'];
                    $data[$id][$storeData['day']]['from']           = $storeData['from'];
                    $data[$id][$storeData['day']]['to']             = $storeData['to'];
                    $data[$id][$storeData['day']]['off']            = $storeData['is_day_off'];
                    $data[$id][$storeData['day']]['lunch_from']     = $storeData['lunch_from'];
                    $data[$id][$storeData['day']]['lunch_to']       = $storeData['lunch_to'];
                    $data[$id][$storeData['day']]['has_lunch_time'] = $storeData['has_lunch_time'];
                }
            }

            foreach ($this as $item) {
                $linkedId = $item->getData('entity_id');
                if (!empty($data[$linkedId])) {
                    $item->setWorkingHours($data[$linkedId]);
                } else {
                    $item->setWorkingHours([]);
                }
            }
        }
    }

    /**
     * load user relation
     */
    protected function loadProductRelation()
    {
        $codes = $this->getColumnValues('code');
        if (count($codes)) {
            $data       = [];
            $connection = $this->getConnection();
            $select     = $connection->select()->from(
                [
                    'relation_product' =>
                        $this->getTable(LocationInterface::ENTITY_PRODUCT_RELATION_TABLE)
                ]
            )->where('relation_product.location_code IN (?)', $codes);

            $relationResult = $connection->fetchAll($select);
            if ($relationResult) {
                $data = [];
                foreach ($relationResult as $storeData) {
                    $code                          = $storeData['location_code'];
                    $data[$code]['product_skus'][] = $storeData['product_sku'];
                }
            }

            if (!empty($data)) {
                foreach ($this as $item) {
                    $linkedId = $item->getCode();
                    if (!empty($data[$linkedId])) {
                        $item->setData('product_skus', $data[$linkedId]['product_skus']);
                    }
                }
            }
        }
    }

    /**
     * @param array $ids
     * @param bool $addOutOfStockItems
     * @param string $sku
     * @return $this
     */
    public function addProductIdsFilter($ids, $addOutOfStockItems = true, $sku = ''): Collection
    {
        $ids = is_array($ids) ? $ids : [$ids];

        $connection = $this->getConnection();
        if ($sku === '') {
            $sku        = $connection->select()->from(
                [
                    'products' =>
                        $this->getTable('catalog_product_entity')
                ],
                ['sku']
            )->where('products.entity_id IN (?)', $ids);
        } else {
            $sku = [$sku];
        }

        $this->joinProductRelation();
        $cond = '((e.assign_type = "specific_products" OR e.assign_type = "condition") ' .
            'AND relation_product.product_sku IN (?)) ';

        $having = 'COUNT(DISTINCT relation_product.product_sku) = ' . count($ids);

        // join MSI tables only if MSI enabled
        if ($this->msiResolver->isMsiEnabled()) {
            $this->joinSourceItems();
            $inStockCond = $this->getInStockCondition($addOutOfStockItems);
            $cond        .= 'OR (e.assign_type = "products_from_source" AND source_item.sku  IN (?)' . $inStockCond . ') ';
            $having      .= ' OR COUNT(DISTINCT source_item.sku) = ' . count($ids);
        }

        $cond   .= 'OR e.assign_type = "all_products"';
        $having .= ' OR e.assign_type = "all_products"';

        $this->getSelect()->where($cond, $sku);

        if (count($ids) > 1) {
            $this->getSelect()->having($having);
        }

        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->getSelect()->limit($limit);

        return $this;
    }

    /**
     * @param int|array $productId
     * @param bool $addOutOfStockItems
     * @param int $storeId
     * @return int
     */
    public function getLocationCountForProduct(
        $productId,
        $addOutOfStockItems = true,
        $storeId = Store::DEFAULT_STORE_ID
    ) {
        $connection = $this->getConnection();

        $productId = is_array($productId) ? $productId : [$productId];

        $sku = $connection->select()->from(
            [
                'products' =>
                    $this->getTable('catalog_product_entity')
            ],
            ['sku']
        )->where('products.entity_id IN (?)', $productId);

        $select = $connection->select()->from(
            ['e' => $this->getTable(LocationInterface::ENTITY_TABLE)],
            ['e.entity_id']
        )->joinLeft(
            ['relation_product' => $this->getTable(LocationInterface::ENTITY_PRODUCT_RELATION_TABLE)],
            'e.code = relation_product.location_code',
            []
        )->joinLeft(
            ['relation_store' => $this->getTable(LocationInterface::ENTITY_STORE_RELATION_TABLE)],
            'e.entity_id = relation_store.entity_id',
            []
        );

        $select->where('relation_store.store_id IN (?)', [$storeId, Store::DEFAULT_STORE_ID]);

        $cond = '((e.assign_type = "specific_products" OR e.assign_type = "condition") ' .
            'AND relation_product.product_sku IN (?)) ';

        // join MSI tables only if MSI enabled
        if ($this->msiResolver->isMsiEnabled()) {
            $inStockCond = $this->getInStockCondition($addOutOfStockItems);
            $select->joinLeft(
                ['source_item' => $this->getTable('inventory_source_item')],
                'e.source_code = source_item.source_code',
                []
            );
            $cond .= 'OR (e.assign_type = "products_from_source" AND source_item.sku  IN (?)' . $inStockCond . ') ';
        }

        $cond .= 'OR e.assign_type = "all_products"';

        $select->group('e.entity_id')
               ->where($cond, $sku)
               ->where(
                   'e.is_active = ?',
                   LocationInterface::ACTIVE
               );

        $rows = $connection->fetchCol($select);

        return count(array_unique($rows));
    }

    /**
     * @param bool $addOutOfStockItems
     * @return string
     */
    protected function getInStockCondition($addOutOfStockItems)
    {
        $inStockCond = '';

        if (!$addOutOfStockItems) {
            $inStockCond = ' AND source_item.status = 1';

            if ($this->inventoryConfig->getManageStock()) {
                $inStockCond = ' AND source_item.quantity > 0';
            }
        }

        return $inStockCond;
    }

    /**
     * Join product relation table
     */
    protected function joinProductRelation()
    {
        if (!$this->getFlag('is_product_relation_table_joined')) {
            $this->setFlag('is_product_relation_table_joined', true);
            $this->getSelect()->joinLeft(
                ['relation_product' => $this->getTable(LocationInterface::ENTITY_PRODUCT_RELATION_TABLE)],
                'e.code = relation_product.location_code',
                []
            )->group('e.code');
        }
    }

    /**
     * Join source items table
     */
    protected function joinSourceItems()
    {
        if (!$this->getFlag('is_source_items_table_joined')) {
            $this->setFlag('is_source_items_table_joined', true);
            $this->getSelect()->joinLeft(
                ['source_item' => $this->getTable('inventory_source_item')],
                'e.source_code = source_item.source_code',
                []
            )->group('e.code');
        }
    }

    /**
     * @param int $storeId
     * @return $this
     */
    public function addStoreFilter($storeId)
    {
        if ($storeId != Store::DEFAULT_STORE_ID) {
            $this->joinStoreRelation();
            $this->getSelect()->where('relation_store.store_id IN (?)', [$storeId, Store::DEFAULT_STORE_ID]);
        }

        return $this;
    }

    /**
     * Join store relation table
     */
    protected function joinStoreRelation()
    {
        if (!$this->getFlag('is_store_relation_table_joined')) {
            $this->setFlag('is_store_relation_table_joined', true);
            $this->getSelect()->joinLeft(
                ['relation_store' => $this->getTable(LocationInterface::ENTITY_STORE_RELATION_TABLE)],
                'e.' . 'entity_id' . ' = relation_store.' . 'entity_id',
                []
            )->group('e.entity_id');
        }
    }

    /**
     * @param string $attribute
     * @param string[]|null $condition
     * @return $this
     */
    public function addFieldToFilter($attribute, $condition = null)
    {
        if ($attribute == 'store_id') {
            return $this->addStoreFilter($condition);
        }

        if ($attribute == 'country') {
            $condition = $this->helper->prepareCountryCode($condition);
            $attribute = 'country_id';
        }

        if ($attribute == 'location_page_path') {
            $this->getSelect()->where('location_page_path LIKE ?', '%' . $condition . '/%');

            return $this;
        }

        return parent::addFieldToFilter($attribute, $condition);
    }

    /**
     * @return string[]
     */
    public function getAllCodes()
    {
        $connection = $this->getConnection();
        $select     = $connection->select()->from($this->getMainTable(), 'code');

        return $connection->fetchAll($select);
    }

    /**
     * @return $this
     */
    public function setLocationCode()
    {
        return $this;
    }

    /**
     * Load attributes into loaded entities
     *
     * @param bool $printQuery
     * @param bool $logQuery
     * @return $this
     * @throws LocalizedException
     * @throws \Exception
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function _loadAttributes($print = false, $log = false)
    {
        if (empty($this->_items) || empty($this->_itemsById) || empty($this->_selectAttributes)) {
            return $this;
        }

        $entity = $this->getEntity();

        $tableAttrs     = [];
        $attributeTypes = [];
        foreach ($this->_selectAttributes as $attributeCode => $attributeId) {
            if (!$attributeId) {
                continue;
            }
            $attribute = $this->_eavConfig->getAttribute($entity->getType(), $attributeCode);
            if ($attribute && !$attribute->isStatic()) {
                $tableAttrs[$attribute->getBackendTable()][] = $attributeId;
                if (!isset($attributeTypes[$attribute->getBackendTable()])) {
                    $attributeTypes[$attribute->getBackendTable()] = $attribute->getBackendType();
                }
            }
        }

        $sqls = [];
        foreach ($tableAttrs as $table => $attributes) {
            $sql                             = $this->_getLoadAttributesSelect($table, $attributes);
            $sqls[$attributeTypes[$table]][] = $this->_addLoadAttributesSelectValues(
                $sql,
                $table,
                $attributeTypes[$table]
            );
        }
        $sqlGroups = $this->_resourceHelper->getLoadAttributesSelectGroups($sqls);
        foreach ($sqlGroups as $sqls) {
            if (!empty($sqls)) {
                try {
                    if (is_array($sqls)) {
                        $sql = implode(' UNION ALL ', $sqls);
                    } else {
                        $sql = $sqls;
                    }
                    $values = $this->getConnection()->fetchAll($sql);
                    $values = $this->prepareAttributeValues($values);
                } catch (\Exception $e) {
                    $this->printLogQuery(true, true, $sql);
                    throw $e;
                }

                foreach ($values as $value) {
                    $this->_setItemAttributeValue($value);
                }
            }
        }

        return $this;
    }

    /**
     * @param string $table
     * @param string[] $ids
     * @return \Magento\Framework\DB\Select
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function _getLoadAttributesSelect($table, $ids = [])
    {
        if (empty($ids)) {
            $ids = $this->_selectAttributes;
        }
        $entity    = $this->getEntity();
        $linkField = $entity->getLinkField();
        $sql       = $this->getConnection()->select()
                          ->from(
                              ['e' => $this->getEntity()->getEntityTable()],
                              ['entity_id']
                          )->join(
                ['t_d' => $table],
                "e.{$linkField} = t_d.{$linkField}",
                ['t_d.attribute_id', 't_d.store_id']
            )->where(
                " e.entity_id IN (?)",
                array_keys($this->_itemsById)
            )->where(
                't_d.attribute_id IN (?)',
                $ids
            );
        $storeId   = $this->storeManager->getStore()->getId();

        if ($this->state->getAreaCode() == 'frontend' || $this->state->getAreaCode() == 'webapi_rest') {
            $sql->where(
                't_d.store_id IN (?)',
                [Store::DEFAULT_STORE_ID, $storeId]
            );
        } else {
            $sql->where(
                't_d.store_id IN (?)',
                $this->getCurrentStoreId()
            );
        }

        if ($entity->getEntityTable() == \Magento\Eav\Model\Entity::DEFAULT_ENTITY_TABLE && $entity->getTypeId()) {
            $sql->where(
                'entity_type_id =?',
                $entity->getTypeId()
            );
        }

        return $sql;
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
            $valuesByStores[$valueRow['attribute_id']][$valueRow['entity_id']][$valueRow['store_id']][] = $valueRow;
        }

        if ($this->state->getAreaCode() == 'frontend' || $this->state->getAreaCode() == 'webapi_rest') {
            $storeId = $this->storeManager->getStore()->getId();
            foreach ($valuesByStores as $attributeRows) {
                foreach ($attributeRows as $entityRows) {
                    if (!empty($entityRows[$storeId][0]['value'])) {
                        $result = array_merge($result, $entityRows[$storeId]);
                    } else {
                        if (isset($entityRows[Store::DEFAULT_STORE_ID])) {
                            $result = array_merge($result, $entityRows[Store::DEFAULT_STORE_ID]);
                        }
                    }
                }
            }
        } else {
            $storeInBackend = $this->getCurrentStoreId();
            foreach ($valuesByStores as $attributeRows) {
                foreach ($attributeRows as $valueRows) {
                    if (isset($valueRows[$storeInBackend])) {
                        $result = array_merge($result, $valueRows[$storeInBackend]);
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @return int
     */
    protected function getCurrentStoreId()
    {
        return $this->helper->getCurrentStoreIdForLocation();
    }

    /**
     * $filters param could have 'search_text' for search by text
     *      OR
     * 'radius', 'current_coordinates' and 'unit for search by radius
     *
     * 'current_coordinates' should be in next format
     * $filters['current_coordinates'] = [
     *           'lat' => 10.0000123,
     *           'lng' => 10.1234567
     *           ]
     *
     * 'unit' can be 'km' or 'miles'
     *
     * @param array $filters
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function addSearchFilters(array $filters)
    {
        if (!isset($filters['autocomplete']) || !isset($filters['radius'])) {
            return $this;
        }

        if ($this->canApplyRadiusFilter($filters)) {
            $this->addRadiusFilter($filters);
        } elseif ($this->canApplySearchTextFilter($filters)) {
            $this->addSearchTextFilter($filters);
        }

        return $this;
    }

    /**
     * @param array $filters
     * @return bool
     */
    protected function canApplyRadiusFilter($filters)
    {
        if ($filters['autocomplete']['lat']
            && ($filters['autocomplete']['lng'])
            && isset($filters['unit'])
            && isset($filters['skip_radius'])
            && !$filters['skip_radius']
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param array $filters
     * @return bool
     */
    protected function canApplySearchTextFilter($filters)
    {
        if (!empty($filters['autocomplete']['small_city'])
            || !empty($filters['autocomplete']['city'])
            || !empty($filters['autocomplete']['region'])
            || !empty($filters['autocomplete']['postcode'])
            || !empty($filters['autocomplete']['country_id'])
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param array $filters
     * @return $this
     */
    protected function addRadiusFilter($filters)
    {
        if ($filters['radius']) {
            $radius = ($filters['unit'] == 'km') ? $filters['radius'] / 1.609344 : $filters['radius'];
            $this->getSelect()->having('distance <= ? ', $radius);
        }

        return $this;
    }

    public function addDistanceField($filters)
    {
        if ($this->canApplyRadiusFilter($filters)) {
            $this->getSelect()->columns(
                [
                    'distance' => $this->getDistanceSql($filters['autocomplete'])
                ]
            );
        }
    }

    /**
     * @param array $coordinates
     * @return string
     */
    protected function getDistanceSql($coordinates)
    {
        return new \Zend_Db_Expr(
            '(((acos(sin((' . $coordinates['lat'] . '*pi()/180)) *' .
            'sin((`latitude`*pi()/180))+cos((' . $coordinates['lat'] . '*pi()/180)) *' .
            'cos((`latitude`*pi()/180)) * cos(((' . $coordinates['lng'] . ' - ' .
            '`longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344)'
        );
    }

    /**
     * @param array $filters
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function addSearchTextFilter($filters)
    {
        foreach ($this->attributesForSearch as $attr) {
            if ($filters['autocomplete'][$attr]) {
                continue;
            }

            $word = $filters['autocomplete'][$attr];
            if ($word == '') {
                continue;
            }

            if ($attr == 'small_city') {
                $attr = 'city';
            }

            $cond = ['like' => '%' . $word . '%'];

            $this->addAttributeToFilter($attr, $cond);
        }

        return $this;
    }

    /**
     * @param string $sku
     * @return array
     */
    public function getLocationCodesFromRelationTableBySku($sku)
    {
        $connection = $this->getConnection();
        $select     = $connection->select()->from(
            $this->getTable(LocationInterface::ENTITY_PRODUCT_RELATION_TABLE),
            'location_code'
        )->where('product_sku = ?', $sku);

        return $connection->fetchCol($select);
    }

    /**
     * @param string $sku
     */
    public function deleteSkuFromRelationTable($sku)
    {
        $connection = $this->getConnection();
        $connection->delete(
            $this->getTable(LocationInterface::ENTITY_PRODUCT_RELATION_TABLE),
            [
                'product_sku = ?' => $sku
            ]
        );
    }

    /**
     * @param array $codes
     * @param string $sku
     */
    public function addSkuToRelationTable($codes, $sku)
    {
        $relation = [];

        foreach ($codes as $code) {
            $row['location_code'] = $code;
            $row['product_sku']   = $sku;
            $relation[]           = $row;
        }

        if (!empty($relation)) {
            $this->getConnection()->insertMultiple(
                $this->getTable(LocationInterface::ENTITY_PRODUCT_RELATION_TABLE),
                $relation
            );
        }
    }
}

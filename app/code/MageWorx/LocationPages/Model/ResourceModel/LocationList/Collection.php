<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Model\ResourceModel\LocationList;

use MageWorx\LocationPages\Api\Data\LocationListInterface;
use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\LocationPages\Helper\Data as Helper;
use Magento\Store\Model\Store;

/**
 * {@inheritdoc}
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute
     */
    protected $eavAttribute;

    /**
     * Collection constructor.
     *
     * @param Helper $helper
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute $eavAttribute
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Framework\DB\Adapter\AdapterInterface|null $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb|null $resource
     */
    public function __construct(
        Helper $helper,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute $eavAttribute,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        $this->helper       = $helper;
        $this->storeManager = $storeManager;
        $this->eavAttribute = $eavAttribute;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }

    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \MageWorx\LocationPages\Model\LocationList::class,
            \MageWorx\LocationPages\Model\ResourceModel\LocationList::class
        );
        $this->_setIdFieldName($this->_idFieldName);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _afterLoad()
    {
        foreach ($this->_items as $item) {
            $item->setUrlPath($this->prepareUrlPath($item));
        }

        return parent::_afterLoad();
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return string
     */
    protected function prepareUrlPath($object)
    {
        $path = '';
        if ($object->getPath()) {
            $connection = $this->getConnection();
            $select     = $connection->select()->from(
                $this->getTable(LocationListInterface::LOCATION_LIST_TABLE),
                ['id', 'name']
            )->where('id IN (?)', explode('/', $object->getPath()));

            $parentData = $connection->fetchAssoc($select);

            foreach (explode('/', $object->getPath()) as $id) {
                foreach ($parentData as $row) {
                    if ($row['id'] == $id) {
                        $path .= $this->helper->prepareStringToUrl($row['name']) . '/';
                    }
                }
            }
        }

        return $path;
    }

    /**
     * @param string $type
     * @param int $parentId
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function loadLocationCount($type, $parentId)
    {
        $ids = $this->getColumnValues('name');

        if ($type == 'city') {
            $type        = 'value';
            $connection  = $this->getConnection();
            $attributeId = $this->eavAttribute->getIdByCode(LocationInterface::ENTITY, 'city');
            $select      = $connection->select()
                                      ->from(
                                          ['e' => $this->getTable(LocationInterface::ENTITY_TABLE . '_varchar')],
                                          [
                                              $type   => $type,
                                              'total' => new \Zend_Db_Expr('COUNT(*)')
                                          ]
                                      )
                                      ->where($type . ' IN (?)', $ids)
                                      ->where('e.attribute_id' . ' = ?', $attributeId)
                                      ->group($type);
        } else {
            if ($type == 'country_id') {
                foreach ($ids as $key => $value) {
                    $ids[$key] = $this->helper->prepareCountryCode($value);
                }
            }
            $connection = $this->getConnection();

            $select = $connection->select()
                                 ->from(
                                     ['e' => $this->getTable(LocationInterface::ENTITY_TABLE)],
                                     [
                                         $type   => $type,
                                         'total' => new \Zend_Db_Expr('COUNT(*)')
                                     ]
                                 )
                                 ->where('e.' . $type . ' IN (?)', $ids)
                                 ->group($type);
        }

        $select->joinLeft(
            ['locations' => $this->getTable(LocationInterface::ENTITY_TABLE)],
            'e.entity_id' . ' = locations.' . 'entity_id',
            ''
        )->where('locations.location_page_path LIKE ?', '%' . $parentId . '/%')
               ->where('locations.is_active = ?', LocationInterface::ACTIVE);

        if ($this->storeManager->getStore()->getId() != Store::DEFAULT_STORE_ID) {
            $storeIds = [$this->storeManager->getStore()->getId(), Store::DEFAULT_STORE_ID];
            $select->joinLeft(
                ['relation_stores' => $this->getTable(LocationInterface::ENTITY_STORE_RELATION_TABLE)],
                'e.entity_id' . ' = relation_stores.' . 'entity_id',
                ''
            )->where('relation_stores.store_id' . ' IN (?)', $storeIds);
        }

        $result = $connection->fetchAll($select);
        if ($result) {
            $data = [];
            foreach ($result as $resultData) {
                $id                 = $resultData[$type];
                $data[$id]['total'] = $resultData['total'];
            }
        }

        foreach ($this as $id => $item) {
            $linkedId = $item->getData('name');

            if ($type == 'country_id') {
                $linkedId = $this->helper->prepareCountryCode($linkedId);
            }

            if (!empty($data[$linkedId])) {
                if ($data[$linkedId]['total'] == 0 &&
                    $this->storeManager->getStore()->getId() != Store::DEFAULT_STORE_ID
                ) {
                    $this->removeItemByKey($id);
                }
                $item->setLocationCount($data[$linkedId]['total']);
            } else {
                if ($this->storeManager->getStore()->getId() != Store::DEFAULT_STORE_ID) {
                    $this->removeItemByKey($id);
                }
            }
        }
    }

    /**
     * @param string $type
     * @param string $name
     * @return \Magento\Framework\DataObject
     */
    public function getByName(string $type, string $name)
    {
        $this->addFieldToFilter('type', $type);
        $this->addFieldToFilter('name', $name);

        return $this->getFirstItem();
    }
}

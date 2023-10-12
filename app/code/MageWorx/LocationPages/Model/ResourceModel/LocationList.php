<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Model\ResourceModel;

use Magento\Store\Model\Store;
use MageWorx\LocationPages\Api\Data\LocationListInterface;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Model\ResourceModel\Db\Context;
use MageWorx\LocationPages\Helper\Data as Helper;
use MageWorx\Locations\Api\Data\LocationInterface;

/**
 * {@inheritdoc}
 */
class LocationList extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'mageworx_mageworx_locationpage_locationlist_collection';
    protected $_eventObject = 'locationlist_collection';

    /**
     *
     * @var string
     */
    protected $urlRewriteTable = 'url_rewrite';

    /**
     * @var string
     */
    protected $entityType = 'mageworx_locationlist';

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * LocationList constructor.
     *
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param Helper $helper
     * @param Context $context
     * @param null $connectionName
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Helper $helper,
        Context $context,
        $connectionName = null
    ) {
        $this->helper       = $helper;
        $this->storeManager = $storeManager;
        parent::__construct($context, $connectionName);
    }

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(LocationListInterface::LOCATION_LIST_TABLE, 'id');
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        $object->setUrlPath($this->prepareUrlPath($object));

        return parent::_afterLoad($object);
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
            )
                                     ->where('id IN (?)', explode('/', $object->getPath()));

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
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->saveUrlRewrite($object);

        return parent::_afterSave($object);
    }

    /**
     * @param \MageWorx\LocationPages\Model\LocationList $object
     * @throws StateException
     */
    public function saveUrlRewrite($object)
    {
        $data = [];
        $object->setUrlPath($this->prepareUrlPath($object));
        $url = $object->getUrl();
        $id  = (int)$object->getId();

        if ($this->helper->isSingleStoreMode()) {
            $storeIds = [$this->helper->getStoreIdForSingleStoreMode()];
        } else {
            $storeIds = $this->helper->getAllStoreIds();
        }

        foreach ($storeIds as $storeId) {
            if ((int)$storeId === Store::DEFAULT_STORE_ID && !$this->helper->isSingleStoreMode()) {
                continue;
            }

            $row        = [];
            $targetPath = 'location_pages/locationList/view/id/' . $id;
            $oldUrl = $this->getOldUrlKey($storeId, $id, $this->entityType, $targetPath);

            if ($oldUrl && $oldUrl != $url && $this->helper->isCreateRedirects()) {
                $row['entity_type']      = $this->entityType;
                $row['entity_id']        = (int)$id;
                $row['request_path']     = $oldUrl;
                $row['target_path']      = $url;
                $row['store_id']         = (int)$storeId;
                $row['description']      = 'Added by MageWorx_LocationPages';
                $row['is_autogenerated'] = true;
                $row['redirect_type']    = 302;
                $data[]                  = $row;
            }

            $row['entity_type']      = $this->entityType;
            $row['entity_id']        = (int)$id;
            $row['request_path']     = $url;
            $row['target_path']      = $targetPath;
            $row['store_id']         = (int)$storeId;
            $row['description']      = 'Added by MageWorx_LocationPages';
            $row['redirect_type']    = 0;
            $row['is_autogenerated'] = true;
            $data[]                  = $row;
        }

        if (!empty($data)) {
            $where = 'entity_type = "' . $this->entityType . '" AND entity_id = ' . (int)$id;
            $this->getConnection()->delete($this->getTable($this->urlRewriteTable), $where);
            if (!$this->checkUrlKeyDuplicate($url, $storeId, $id)) {
                $this->getConnection()->insertMultiple($this->getTable($this->urlRewriteTable), $data);
            }
        }
    }

    /**
     * @param \MageWorx\LocationPages\Model\LocationPage $locationPage
     * @throws StateException
     */
    public function saveUrlRewriteLocationPage($locationPage)
    {
        $data             = [];
        $selectedStoreIds = $locationPage->getLocation()->getData('store_ids');
        $selectedStoreIds = is_array($selectedStoreIds) ? $selectedStoreIds : explode(',', $selectedStoreIds);
        $allStores        = array_search(Store::DEFAULT_STORE_ID, $selectedStoreIds);

        $url = $locationPage->getUrl();
        $id  = (int)$locationPage->getLocation()->getId();

        if ($this->helper->isSingleStoreMode()) {
            $storeIds = [$this->helper->getStoreIdForSingleStoreMode()];
        } else {
            $storeIds = $this->helper->getAllStoreIds();
        }

        foreach ($storeIds as $storeId) {
            if ((int)$storeId === Store::DEFAULT_STORE_ID && !$this->helper->isSingleStoreMode()) {
                continue;
            }

            $row        = [];
            $targetPath = 'location_pages/location/view/id/' . $id;
            if (in_array($storeId, $selectedStoreIds) || $allStores !== false) {
                $oldUrl = $this->getOldUrlKey(
                    $storeId,
                    $id,
                    \MageWorx\Locations\Api\Data\LocationInterface::ENTITY,
                    $targetPath
                );

                if ($oldUrl && $oldUrl != $url && $this->helper->isCreateRedirects()) {
                    $row['entity_type']      = \MageWorx\Locations\Api\Data\LocationInterface::ENTITY;
                    $row['entity_id']        = (int)$id;
                    $row['request_path']     = $oldUrl;
                    $row['target_path']      = $url;
                    $row['store_id']         = (int)$storeId;
                    $row['description']      = 'Added by MageWorx_LocationPages';
                    $row['is_autogenerated'] = true;
                    $row['redirect_type']    = 302;
                    $data[]                  = $row;
                }

                $row['entity_type']      = \MageWorx\Locations\Api\Data\LocationInterface::ENTITY;
                $row['entity_id']        = (int)$id;
                $row['request_path']     = $url;
                $row['target_path']      = $targetPath;
                $row['store_id']         = (int)$storeId;
                $row['redirect_type']    = 0;
                $row['description']      = 'Added by MageWorx_LocationPages';
                $row['is_autogenerated'] = true;
                $data[]                  = $row;
            }
        }

        if (!empty($data)) {
            $where = 'entity_type = "' . \MageWorx\Locations\Api\Data\LocationInterface::ENTITY .
                '" AND entity_id = ' . (int)$id;
            $this->getConnection()->delete($this->getTable($this->urlRewriteTable), $where);
            if (!$this->checkUrlKeyDuplicate($url, $storeId, $id)) {
                $this->getConnection()->insertMultiple($this->getTable($this->urlRewriteTable), $data);
            }
        }
    }

    /**
     * @param int $storeId
     * @param int $id
     * @param string $type
     * @return string
     */
    protected function getOldUrlKey($storeId, $id, $type, $targetPath)
    {
        $connection = $this->getConnection();

        $tableName = $this->getTable($this->urlRewriteTable);
        $sql       = $connection->select()->from(
            ['url_rewrite' => $tableName],
            ['request_path', 'store_id']
        )->where('entity_type = ?', $type)
                                ->where('entity_id = ?', $id)
                                ->where('target_path = ?', $targetPath)
                                ->where('store_id = ?', $storeId);

        return $connection->fetchOne($sql);
    }

    /**
     * @param string $urlKey
     * @param int $storeId
     * @param int $id
     * @throws StateException
     */
    protected function checkUrlKeyDuplicate($urlKey, $storeId, $id)
    {
        $connection = $this->getConnection();

        $tableName = $this->getTable($this->urlRewriteTable);
        $sql       = $connection->select()
                                ->from(
                                    ['url_rewrite' => $tableName],
                                    ['request_path', 'store_id']
                                )
                                ->where('request_path = ?', $urlKey)
                                ->where('entity_id != ?', $id)
                                ->where('store_id = ?', $storeId);

        $urlKeyDuplicates = $connection->fetchAssoc($sql);

        if (!empty($urlKeyDuplicates)) {
            return true;
        }

        return false;
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterDelete(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->deleteUrlRewrites($object);

        return parent::_beforeDelete($object);
    }

    /**
     * @param string $type
     * @param string $name
     * @param string|null $parentId
     * @return string
     */
    public function getIdByTypeName($type, $name, $parentId = null)
    {
        $connection = $this->getConnection();
        $select     = $connection->select()
                                 ->from($this->getTable(LocationListInterface::LOCATION_LIST_TABLE), 'id')
                                 ->where('type = :type AND name = :name');

        if ($parentId) {
            $select->where('parent_id = ?', $parentId);
        }

        $bind = [':type' => (string)$type, ':name' => (string)$name];

        return $connection->fetchOne($select, $bind);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @param string $type
     */
    public function deleteUrlRewrites(\Magento\Framework\Model\AbstractModel $object, $type = '')
    {
        $type       = $type ?: $this->entityType;
        $connection = $this->getConnection();
        $connection->delete(
            $this->getTable($this->urlRewriteTable),
            [
                'entity_type = ?' => $type,
                "entity_id = ?"   => (int)$object->getId()
            ]
        );
    }
}
<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Model\ResourceModel\Location\Grid;

use Magento\CatalogInventory\Model\Configuration;
use MageWorx\Locations\Model\ResourceModel\Location\Collection as LocationCollection;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\Collection\EntityFactory;
use Psr\Log\LoggerInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;

class Collection extends LocationCollection implements SearchResultInterface
{
    /**
     * Aggregations
     *
     * @var \Magento\Framework\Search\AggregationInterface
     */
    protected $aggregations;

    /**
     * Collection constructor.
     *
     * @param Configuration $inventoryConfig
     * @param \MageWorx\Locations\Model\MsiResolver $msiResolver
     * @param \MageWorx\Locations\Helper\Data $helper
     * @param StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\State $state
     * @param \Magento\Eav\Model\Config $eavConfig
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param \Magento\Eav\Model\EntityFactory $eavEntityFactory
     * @param \Magento\Eav\Model\ResourceModel\Helper $resourceHelper
     * @param \Magento\Framework\Validator\UniversalFactory $universalFactory
     * @param EntityFactory $entityFactory
     * @param LoggerInterface $logger
     * @param FetchStrategyInterface $fetchStrategy
     * @param ManagerInterface $eventManager
     * @param string $mainTable
     * @param string $resourceModel
     * @param string $model
     * @param null $connection
     */
    public function __construct(
        Configuration $inventoryConfig,
        \MageWorx\Locations\Model\MsiResolver $msiResolver,
        \MageWorx\Locations\Helper\Data $helper,
        \MageWorx\StoreLocator\Helper\Data $helperLocator,
        StoreManagerInterface $storeManager,
        \Magento\Framework\App\State $state,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Eav\Model\EntityFactory $eavEntityFactory,
        \Magento\Eav\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\Validator\UniversalFactory $universalFactory,
        EntityFactory $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        $mainTable,
        $resourceModel,
        $model = \Magento\Framework\View\Element\UiComponent\DataProvider\Document::class,
        $connection = null
    ) {
        parent::__construct(
            $inventoryConfig,
            $msiResolver,
            $helper,
            $helperLocator,
            $storeManager,
            $state,
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
        $this->_init($model, $resourceModel);
    }

    /**
     * @return \Magento\Framework\Search\AggregationInterface
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /**
     * @param \Magento\Framework\Search\AggregationInterface $aggregations
     * @return $this
     */
    public function setAggregations($aggregations)
    {
        $this->aggregations = $aggregations;
    }

    /**
     * Retrieve all ids for collection
     * Backward compatibility with EAV collection
     *
     * @param int $limit
     * @param int $offset
     * @return string[]
     */
    public function getAllIds($limit = null, $offset = null)
    {
        return $this->getConnection()->fetchCol($this->_getAllIdsSelect($limit, $offset), $this->_bindParams);
    }

    /**
     * Get search criteria.
     *
     * @return \Magento\Framework\Api\SearchCriteriaInterface|null
     */
    public function getSearchCriteria()
    {
        return null;
    }

    /**
     * Set search criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return $this
     */
    public function setSearchCriteria(SearchCriteriaInterface $searchCriteria = null)
    {
        return $this;
    }

    /**
     * Get total count.
     *
     * @return int
     */
    public function getTotalCount()
    {
        return $this->getSize();
    }

    /**
     * Set total count.
     *
     * @param int $totalCount
     * @return $this
     */
    public function setTotalCount($totalCount)
    {
        return $this;
    }

    /**
     * Set items list.
     *
     * @param \Magento\Framework\Api\ExtensibleDataInterface[] $items
     * @return $this
     */
    public function setItems(array $items = null)
    {
        return $this;
    }

    /**
     * @param null $limit
     * @param null $offset
     * @return \Magento\Framework\DB\Select
     */
    protected function _getAllIdsSelect($limit = null, $offset = null)
    {
        $idsSelect = clone $this->getSelect();
        $idsSelect->reset(\Magento\Framework\DB\Select::ORDER)
                  ->reset(\Magento\Framework\DB\Select::LIMIT_COUNT)
                  ->reset(\Magento\Framework\DB\Select::LIMIT_OFFSET)
                  ->reset(\Magento\Framework\DB\Select::COLUMNS)
                  ->columns('entity_id')
                  ->limit($limit, $offset);

        return $idsSelect;
    }

    /**
     * @return $this|\Magento\Framework\Api\Search\DocumentInterface[]|\Magento\Framework\DataObject[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getItems()
    {
        $this->addAttributeToSelect('*');

        return parent::getItems();
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addItem(\Magento\Framework\DataObject $object)
    {
        $object->setIdFieldName($this->_idFieldName);

        return parent::addItem($object);
    }
}

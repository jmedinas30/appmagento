<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Model\Layer\Location;

use Magento\Framework\Search\EngineResolverInterface;
use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\Locations\Model\ResourceModel\Catalog\Product\Collection as LocationProductCollection;
use MageWorx\StoreLocator\Helper\Data;
use MageWorx\Locations\Helper\VersionResolver;

class ItemCollectionProvider
{
    /**
     * @var Data
     */
    private $helper;

    /**
     * @var VersionResolver
     */
    private $versionResolver;

    /**
     * @var LocationProductCollection
     */
    private $locationProductCollection;

    /**
     * @var EngineResolverInterface
     */
    private $engineResolver;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var array
     */
    private $collections = [
        'mysql'          => 'MageWorx\LocationPages\Model\ResourceModel\Product\CollectionFactory',
        'elasticsearch5' => 'elasticsearchCategoryCollectionFactory',
        'elasticsearch6' => 'elasticsearchCategoryCollectionFactory',
        'elasticsearch7' => 'elasticsearchCategoryCollectionFactory',
        'default'        => 'elasticsearchCategoryCollectionFactory',
        'elastic'        => 'MageWorx\LocationPages\Model\ResourceModel\Product\CollectionFactory'
    ];

    /**
     * ItemCollectionProvider constructor.
     *
     * @param VersionResolver $versionResolver
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param Data $helper
     * @param LocationProductCollection $locationProductCollection
     * @param EngineResolverInterface $engineResolver
     */
    public function __construct(
        VersionResolver $versionResolver,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        Data $helper,
        LocationProductCollection $locationProductCollection,
        EngineResolverInterface $engineResolver
    ) {
        $this->versionResolver           = $versionResolver;
        $this->objectManager             = $objectManager;
        $this->helper                    = $helper;
        $this->locationProductCollection = $locationProductCollection;
        $this->engineResolver            = $engineResolver;
    }


    /**
     * @param LocationInterface $location
     * @return \MageWorx\LocationPages\Model\ResourceModel\Product\Collection
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCollection(LocationInterface $location)
    {
        if (!isset($this->collections[$this->engineResolver->getCurrentSearchEngine()])) {
            throw new \DomainException('Undefined search engine ' . $this->engineResolver->getCurrentSearchEngine());
        }

        //Check Magento CE > 2.3.3
        if ($this->versionResolver->checkModuleVersion('Magento_Elasticsearch', '100.3.2')) {
            $factory = $this->objectManager->create(
                $this->collections[$this->engineResolver->getCurrentSearchEngine()]
            );
        } else {
            $factory = $this->objectManager->create($this->collections['mysql']);
        }

        $collection = $factory->create();

        if ($location->getAssignType() == LocationInterface::ASSIGN_TYPE_ALL) {
            return $collection;
        }

        if ($location->getAssignType() == LocationInterface::ASSIGN_TYPE_PRODUCTS_FROM_SOURCE) {
            $ids = $this->locationProductCollection->getProductIdsBySourceCode(
                $location->getSourceCode(),
                $this->helper->getDisplayStockStatus()
            );

            if (empty($ids)) {
                $collection->addFieldToFilter('entity_id', 0);
            } else {
                $collection->addFieldToFilter('entity_id', $ids);
            }
        } else {
            $skus = $location->getProductSkus();
            if (empty($skus)) {
                $collection->addFieldToFilter('sku', '0');
            } else {
                $collection->addFieldToFilter('sku', $skus);
            }
        }

        return $collection;
    }
}

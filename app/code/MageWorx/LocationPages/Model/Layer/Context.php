<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Model\Layer;

use \MageWorx\LocationPages\Model\Layer\Location\ItemCollectionProvider;
use \Magento\Catalog\Model\Layer\Category\StateKey;
use \MageWorx\LocationPages\Model\Layer\Location\CollectionFilter;

class Context implements \Magento\Catalog\Model\Layer\ContextInterface
{
    /**
     * @var ItemCollectionProvider
     */
    protected $collectionProvider;

    /**
     * @var CollectionFilter
     */
    protected $collectionFilter;

    /**
     * @var StateKey
     */
    protected $stateKey;

    /**
     * Context constructor.
     *
     * @param ItemCollectionProvider $collectionProvider
     * @param StateKey $stateKey
     * @param CollectionFilter $collectionFilter
     */
    public function __construct(
        ItemCollectionProvider $collectionProvider,
        StateKey $stateKey,
        CollectionFilter $collectionFilter
    ) {
        $this->collectionProvider = $collectionProvider;
        $this->stateKey           = $stateKey;
        $this->collectionFilter   = $collectionFilter;
    }

    /**
     * @return CollectionFilter
     */
    public function getCollectionFilter()
    {
        return $this->collectionFilter;
    }

    /**
     * @return ItemCollectionProvider
     */
    public function getCollectionProvider()
    {
        return $this->collectionProvider;
    }

    /**
     * @return StateKey
     */
    public function getStateKey()
    {
        return $this->stateKey;
    }
}

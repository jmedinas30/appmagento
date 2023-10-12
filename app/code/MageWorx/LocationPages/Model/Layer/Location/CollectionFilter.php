<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Model\Layer\Location;

use MageWorx\Locations\Api\Data\LocationInterface;
use \Magento\Catalog\Model\Product\Visibility;
use \Magento\Catalog\Model\Config;

class CollectionFilter
{

    /**
     * Catalog config
     *
     * @var Config
     */
    protected $catalogConfig;
    /**
     * Product visibility
     *
     * @var Visibility
     */
    protected $productVisibility;

    /**
     * CollectionFilter constructor.
     *
     * @param Visibility $productVisibility
     * @param Config $catalogConfig
     */
    public function __construct(
        Visibility $productVisibility,
        Config $catalogConfig
    ) {
        $this->productVisibility = $productVisibility;
        $this->catalogConfig     = $catalogConfig;
    }

    /**
     * @param \MageWorx\LocationPages\Model\ResourceModel\Product\Collection $collection
     * @param LocationInterface $location
     */
    public function filter(
        $collection,
        LocationInterface $location
    ) {
        $collection
            ->addAttributeToSelect($this->catalogConfig->getProductAttributes())
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->setVisibility($this->productVisibility->getVisibleInCatalogIds());
    }
}

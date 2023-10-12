<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Plugin;

use MageWorx\Locations\Model\ResourceModel\Location\Collection;

class SaveNewProductSkuPlugin
{
    /**
     * @var Collection
     */
    protected $locationResource;

    /**
     * @var array
     */
    protected $productLocationCodes = [];
    /**
     * SaveNewProductSkuPlugin constructor.
     *
     * @param Collection $locationResource
     */
    public function __construct(Collection$locationResource)
    {
        $this->locationResource = $locationResource;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return mixed
     */
    public function beforeBeforeSave(\Magento\Catalog\Model\Product $product)
    {
        $origData = $product->getOrigData();

        if (!isset($origData['sku'])) {
            return [];
        }

        if ($origData['sku'] === $product->getSku()) {
            return [];
        }

        $this->productLocationCodes = $this->locationResource->getLocationCodesFromRelationTableBySku($origData['sku']);
        $this->locationResource->deleteSkuFromRelationTable($origData['sku']);
        return [];
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @param \Magento\Framework\Model\AbstractModel $result
     * @return mixed
     */
    public function afterAfterSave(\Magento\Catalog\Model\Product $product, $result)
    {
        if (empty($this->productLocationCodes)) {
            return $result;
        }

        $this->locationResource->addSkuToRelationTable($this->productLocationCodes, $product->getSku());
        return $result;
    }
}

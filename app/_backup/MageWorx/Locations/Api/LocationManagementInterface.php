<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Api;

interface LocationManagementInterface
{
    /**
     * Add Products to Location
     *
     * @param string $productSkus
     * @param string $code
     * @return \MageWorx\Locations\Api\Data\LocationInterface
     */
    public function addProductsToLocation($productSkus, $code);

    /**
     * Delete Products from Location
     *
     * @param string $productSkus
     * @param string $code
     * @return \MageWorx\Locations\Api\Data\LocationInterface
     */
    public function deleteProductsFromLocation($productSkus, $code);

    /**
     * Delete All Old Products and Add New Products To Location
     *
     * @param string $productSkus
     * @param string $code
     * @return \MageWorx\Locations\Api\Data\LocationInterface
     */
    public function updateProductInLocation($productSkus, $code);
}

<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreLocator\Controller\Location;

/**
 * Class UpdateFindAStore
 */
class UpdateFindAStore extends UpdateLocatorOnProductPage
{
    /**
     * @return \Magento\Framework\View\Element\BlockInterface
     */
    protected function getBlock()
    {
        return $this->_view->getLayout()
                           ->createBlock(\MageWorx\StoreLocator\Block\Catalog\Product\FindAStore::class);
    }
}

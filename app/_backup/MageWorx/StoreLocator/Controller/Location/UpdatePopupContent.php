<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreLocator\Controller\Location;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Type;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\GroupedProduct\Model\Product\Type\Grouped;
use MageWorx\StoreLocator\Helper\Data;

/**
 * Class UpdatePopupContent
 */
class UpdatePopupContent extends UpdateLocatorOnProductPage
{
    /**
     * @return \Magento\Framework\View\Element\BlockInterface
     */
    protected function getBlock()
    {
        return $this->_view->getLayout()
                           ->createBlock(\MageWorx\StoreLocator\Block\Popup::class)
                           ->setIsPopupUpdate(true);
    }
}

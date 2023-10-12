<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Block\Adminhtml\Location\Edit\Products\Grid;

use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\Locations\Model\ResourceModel\Catalog\Product\Collection;

/**
 * Class Renderer
 */
class Renderer extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Text
{
    /**
     * @var Collection
     */
    protected $productCollection;

    /**
     * @var \MageWorx\Locations\Model\Registry
     */
    protected $registry;

    /**
     * @var \MageWorx\Locations\Model\MsiResolver
     */
    protected $msiResolver;

    /**
     * @var array
     */
    protected $qty;

    /**
     * Renderer constructor.
     *
     * @param Collection $productCollection
     * @param \MageWorx\Locations\Model\Registry $registry
     * @param \Magento\Backend\Block\Context $context
     * @param array $data
     */
    public function __construct(
        \MageWorx\Locations\Model\MsiResolver $msiResolver,
        Collection $productCollection,
        \MageWorx\Locations\Model\Registry $registry,
        \Magento\Backend\Block\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->registry          = $registry;
        $this->productCollection = $productCollection;
        $this->msiResolver       = $msiResolver;
    }

    /**
     * @param \Magento\Framework\DataObject $row
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        parent::render($row);
        $qty = $this->getProductsQty();
        if (isset($qty[$row->getSku()])) {
            return $qty[$row->getSku()];
        }

        return '0.00';
    }

    /**
     * @return string[]
     */
    protected function getProductsQty()
    {
        if (!$this->qty) {
            $location = $this->registry->registry(LocationInterface::CURRENT_LOCATION);
            if ($location && $this->msiResolver->isMsiEnabled()) {
                $this->qty = $this->productCollection->getItemsQty($location->getSourceCode());
            } else {
                $this->qty = 0;
            }
        }

        return $this->qty;
    }
}

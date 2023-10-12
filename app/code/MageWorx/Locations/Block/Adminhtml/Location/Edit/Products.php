<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Block\Adminhtml\Location\Edit;

use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\Locations\Model\ResourceModel\Catalog\Product\Collection;

class Products extends \Magento\Catalog\Block\Adminhtml\Category\AssignProducts
{
    /**
     * @var Collection
     */
    protected $productCollection;

    /**
     * Block template
     *
     * @var string
     */
    protected $_template = 'MageWorx_Locations::products_grid.phtml';

    /**
     * @var \MageWorx\Locations\Model\Registry
     */
    protected $locationRegistry;

    /**
     * Products constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \MageWorx\Locations\Model\Registry $registry
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param Collection $productCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \MageWorx\Locations\Model\Registry $registry,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        Collection $productCollection,
        array $data = []
    ) {
        parent::__construct($context, $coreRegistry, $jsonEncoder, $data);
        $this->locationRegistry  = $registry;
        $this->productCollection = $productCollection;
    }

    /**
     * Retrieve instance of grid block
     *
     * @return \Magento\Framework\View\Element\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBlockGrid()
    {
        if (null === $this->blockGrid) {
            $this->blockGrid = $this->getLayout()->createBlock(
                Products\Grid::class,
                'location.product.grid'
            );
        }

        return $this->blockGrid;
    }

    /**
     * @return string
     */
    public function getProductsJson()
    {
        /** @var LocationInterface $location */
        $location = $this->locationRegistry->registry(LocationInterface::CURRENT_LOCATION);
        if ($location && $location->getSourceCode()) {
            $collection = $this->productCollection->getProductIdsBySourceCode($location->getSourceCode());
        } else {
            $productSkus = $location->getProductSkus();
            $collection  = $this->productCollection->getProductIdsBySkus($productSkus);
        }

        if (!empty($collection)) {
            return $this->jsonEncoder->encode(array_flip($collection));
        }

        return '{}';
    }
}

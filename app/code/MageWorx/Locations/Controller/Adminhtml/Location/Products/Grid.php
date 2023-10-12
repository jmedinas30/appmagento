<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Controller\Adminhtml\Location\Products;

use MageWorx\Locations\Controller\Adminhtml\Location;
use Magento\Backend\App\Action\Context;
use MageWorx\Locations\Api\LocationRepositoryInterface;
use MageWorx\Locations\Model\Registry;

class Grid extends Location
{
    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $layoutFactory;

    /**
     * Grid constructor.
     *
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     * @param Registry $registry
     * @param LocationRepositoryInterface $locationRepository
     * @param Context $context
     */
    public function __construct(
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        Registry $registry,
        LocationRepositoryInterface $locationRepository,
        Context $context
    ) {
        parent::__construct(
            $registry,
            $locationRepository,
            $context
        );
        $this->resultRawFactory = $resultRawFactory;
        $this->layoutFactory    = $layoutFactory;
    }

    /**
     * Grid Action
     * Display list of products related to current category
     *
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $location = $this->initLocation($this->getRequest()->getParam('entity_id'));
        if (!$location) {
            /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();

            return $resultRedirect->setPath('*/*/', ['_current' => true, 'id' => null]);
        }
        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();

        return $resultRaw->setContents(
            $this->layoutFactory->create()->createBlock(
                \MageWorx\Locations\Block\Adminhtml\Location\Edit\Products\Grid::class,
                'location.product.grid'
            )->toHtml()
        );
    }
}

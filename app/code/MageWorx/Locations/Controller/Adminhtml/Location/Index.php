<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Controller\Adminhtml\Location;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use MageWorx\Locations\Controller\Adminhtml\Location;
use MageWorx\Locations\Api\LocationRepositoryInterface;
use MageWorx\Locations\Model\Registry;

class Index extends Location
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Index constructor.
     *
     * @param Registry $registry
     * @param LocationRepositoryInterface $locationRepository
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Registry $registry,
        LocationRepositoryInterface $locationRepository,
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct(
            $registry,
            $locationRepository,
            $context
        );
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $this->setPageData();

        return $this->getResultPage();
    }
}

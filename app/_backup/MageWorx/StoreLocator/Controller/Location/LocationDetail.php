<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreLocator\Controller\Location;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use MageWorx\Locations\Api\LocationRepositoryInterface;
use MageWorx\StoreLocator\Model\Source\Layout;

/**
 * Class LocationDetail
 */
class LocationDetail extends Action
{
    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * @var  \MageWorx\StoreLocator\Helper\Data
     */
    protected $helper;

    /**
     * LocationDetail constructor.
     *
     * @param LocationRepositoryInterface $locationRepository
     * @param \MageWorx\StoreLocator\Helper\Data $helper
     * @param Context $context
     */
    public function __construct(
        LocationRepositoryInterface $locationRepository,
        \MageWorx\StoreLocator\Helper\Data $helper,
        Context $context
    ) {
        $this->locationRepository = $locationRepository;
        $this->helper             = $helper;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     * @throws LocalizedException
     */
    public function execute()
    {
        $id          = $this->getRequest()->getParam('id');
        $currentPage = $this->getRequest()->getParam('current_page');
        if ($id) {
            $location = $this->locationRepository->getById($id);

            $this->_view->loadLayout();

            switch ($currentPage) {
                case 'cms_page_view':
                    $currentLayout = $this->helper->getLocationsPageLayout();
                    break;
                case 'catalog_product_view':
                    $currentLayout = $this->helper->getPopupLayout();
                    break;
                case 'checkout_index_index':
                default:
                    $currentLayout = $this->helper->getCheckoutLayout();
                    break;
            }

            if ($currentLayout == Layout::FILTER_LEFT_MAP || $currentLayout == Layout::FILTER_ON_MAP) {
                $template = 'MageWorx_StoreLocator::location_detail.phtml';
            } else {
                $template = 'MageWorx_StoreLocator::location_detail_for_list.phtml';
            }
            $block = $this->_view->getLayout()->createBlock(\MageWorx\StoreLocator\Block\LocationDetail::class)
                                 ->setTemplate($template);
            $block->setData('location', $location);
            $block->setData('is_checkout', $currentPage == 'checkout_index_index');

            $this->_view->renderLayout();

            $this->getResponse()->setHeader('Content-Type', 'text/html', true);
            $this->getResponse()->setContent($block->toHtml());
        }
    }
}

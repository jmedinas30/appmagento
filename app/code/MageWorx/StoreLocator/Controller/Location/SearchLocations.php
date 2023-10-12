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
 * Class SearchLocations
 */
class SearchLocations extends Action
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
     * Customer session
     *
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var mixed
     */
    protected $locations;

    /**
     * SearchLocations constructor.
     *
     * @param \Magento\Customer\Model\Session $customerSession
     * @param LocationRepositoryInterface $locationRepository
     * @param \MageWorx\StoreLocator\Helper\Data $helper
     * @param Context $context
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        LocationRepositoryInterface $locationRepository,
        \MageWorx\StoreLocator\Helper\Data $helper,
        Context $context
    ) {
        $this->customerSession    = $customerSession;
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
        $this->_view->loadLayout();
        $blockHtml = $this->prepareBlock();
        $this->_view->renderLayout();

        $this->getResponse()->setHeader('Content-Type', 'text/html', true);
        $this->getResponse()->setContent($blockHtml);
    }

    /**
     * @return \MageWorx\StoreLocator\Block\Filter
     */
    private function prepareFilterBlock()
    {
        $block = $this->_view->getLayout()->createBlock(\MageWorx\StoreLocator\Block\Filter::class)
                             ->setTemplate('MageWorx_StoreLocator::filter_for_list.phtml');
        $block->setData('locations', $this->locations);

        return $block;
    }

    /**
     * @return string
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function prepareBlock()
    {
        $currentPage   = $this->getRequest()->getParam('current_page');
        $currentLayout = $this->getLayout($currentPage);

        $this->loadLocations($currentPage);

        if ($currentLayout == Layout::FILTER_LEFT_MAP || $currentLayout == Layout::FILTER_ON_MAP) {
            $template = 'MageWorx_StoreLocator::filter.phtml';
            $block    = $this->_view->getLayout()->createBlock(\MageWorx\StoreLocator\Block\Filter::class);
        } else {
            $template = 'MageWorx_StoreLocator::list.phtml';
            $block    = $this->_view->getLayout()->createBlock(\MageWorx\StoreLocator\Block\LocationsList::class);
        }

        $block->setTemplate($template)
              ->setPlace($this->getRequest()->getParam('search_text'))
              ->setLocations($this->locations);

        $block->setData('is_checkout', strpos($currentPage, 'checkout') !== false);

        $html = $block->toHtml();
        if ($currentLayout !== Layout::FILTER_LEFT_MAP && $currentLayout !== Layout::FILTER_ON_MAP) {
            $filterBlock = $this->prepareFilterBlock();
            $html        .= '|||' . $filterBlock->toHtml();
        }

        return $html;
    }

    /**
     * @param string currentPage
     * @return \MageWorx\Locations\Model\ResourceModel\Location\Collection|string[]
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function loadLocations($currentPage)
    {
        $filters = $this->getFilters($currentPage);

        switch ($currentPage) {
            case 'mageworx_store_locator_location_updatemainpage':
            case 'cms_page_view':
                $locations = $this->locationRepository->getListLocationForFront(
                    $this->helper->getStoreId(),
                    $filters
                );
                break;
            case 'mageworx_store_locator_location_updatepopupcontent':
            case 'catalog_product_view':
                $product = $this->getRequest()->getParam('current_products');
                if ($product) {
                    $locations = $this->locationRepository->getListLocationByProductIds(
                        $product,
                        null,
                        $this->helper->getDisplayStockStatus(),
                        $filters
                    );
                } else {
                    $locations = $this->locationRepository->getListLocationForFront(
                        $this->helper->getStoreId(),
                        $filters
                    );
                }
                break;
            case 'checkout_index_index':
            default:
                $locations = $this->helper->getLocationsForCurrentQuote($filters);
                break;
        }

        $this->locations = $locations;
    }

    /**
     * @param string $currentPage
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLayout($currentPage)
    {
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

        return $currentLayout;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getFilters($currentPage)
    {
        $filters = [];

        $radius                  = $this->getRequest()->getParam('radius');
        $filters['radius']       = $radius;
        $filters['autocomplete'] = $this->getRequest()->getParam('autocomplete');
        $filters['unit']         = $this->helper->getRadiusUnit();
        if ((empty($filters['autocomplete']['lat']) || $filters['autocomplete']['lat'] == '0')
            && (empty($filters['autocomplete']['lng']) || $filters['autocomplete']['lng'] == '0')) {
            if (
                $this->helper->getDefaultMapView() == \MageWorx\Locations\Model\Source\DefaultMapView::DEFAULT_LOCATION
            ) {
                $filters[$this->helper->getFilterBy()] = $this->helper->getDefaultPlace();
                $filters['autocomplete']                   = $this->helper->getDefaultPlaceCoordinates();
            } else {
                $geoIpCoord              = $this->helper->getCoordinatesByGeoIp();
                $filters['autocomplete'] = $geoIpCoord;
            }
        }
        if (empty($filters['autocomplete']['lat']) && empty($filters['autocomplete']['lng'])) {
            $filters[$this->helper->getFilterBy()] = $this->helper->getDefaultPlace();
            $filters['autocomplete']                   = $this->helper->getDefaultPlaceCoordinates();
        }

        $filters['city'] = $this->getRequest()->getParam('autocomplete')['city'] ?? '';
        $filters['skip_radius'] = $this->helper->skipRadiusFilter($currentPage);
        $this->customerSession->setData(LocationRepositoryInterface::LOCATOR_COORDINATES, $filters);

        return $filters;
    }
}

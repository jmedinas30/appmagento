<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
declare(strict_types=1);

namespace MageWorx\LocationPages\Block\Adminhtml\Config;

use MageWorx\LocationPages\Api\LocationListRepositoryInterface;
use MageWorx\StoreLocator\Helper\Data;

/**
 * SearchSuiteSphinx system block to display Run button in module settings
 */
class GetCoordinatesForFilter extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var LocationListRepositoryInterface
     */
    protected $locationListRepository;

    /**
     * GetCoordinatesForFilter constructor.
     *
     * @param Data $helper
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        Data $helper,
        LocationListRepositoryInterface $locationListRepository,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        $this->helper = $helper;
        $this->locationListRepository = $locationListRepository;
        parent::__construct($context, $data);
    }

    /**
     * Set template
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('MageWorx_LocationPages::get-coordinates-btn.phtml');
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return (string)$this->helper->getMapApiKey();
    }

    /**
     * Add generate button to config field
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element): string
    {
        return $this->_toHtml();
    }

    /**
     * Return generate button html
     *
     * @param string $sku
     * @return string
     */
    public function getButtonHtml(): string
    {
        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            [
                'id' => 'get_coordinates_config_btn',
                'label' => __('Load Coordinates'),
                'onclick' => 'javascript:loadPlaces(this, "'
                    . $this->_urlBuilder->getUrl('mageworx_locationpages/loadFilterList/index/') .
                    '", "'
                    . $this->_urlBuilder->getUrl('mageworx_locationpages/saveCoordinates/index/') .
                    '"); return false;'
            ]
        );

        return $button->toHtml();
    }

    /**
     * @return int
     */
    public function getEmptyCoordinatesCount(): int
    {
        return (int)$this->locationListRepository->getEmptyCoordinatesCount($this->helper->getFilterBy());
    }
}

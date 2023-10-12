<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
declare(strict_types=1);

namespace MageWorx\StoreLocator\Block\Adminhtml;

use MageWorx\StoreLocator\Helper\Data;

class DefaultMapCenter extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * GetCoordinatesForFilter constructor.
     *
     * @param Data $helper
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        Data $helper,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    /**
     * Set template
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('MageWorx_StoreLocator::get-coordinates-btn.phtml');
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
                'label' => __('Open map')

            ]
        );

        return $button->toHtml();
    }

    /**
     * @return string
     */
    public function getSelectedLocation(): string
    {
        $storeId = (int)$this->getRequest()->getParam('store', \Magento\Store\Model\Store::DEFAULT_STORE_ID);
        if ($this->helper->getDefaultCity($storeId)
            && $this->helper->getDefaultRegion($storeId)
            && $this->helper->getDefaultCountry($storeId)
        ) {
            return $this->helper->getDefaultCity($storeId) . ', ' . $this->helper->getDefaultRegion($storeId)
                . ', ' . $this->helper->getDefaultCountry($storeId);
        }

        return '';
    }

    /**
     * {@inheritdoc}
     */
    protected function _isInheritCheckboxRequired(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    protected function _renderInheritCheckbox(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return '';
    }
}

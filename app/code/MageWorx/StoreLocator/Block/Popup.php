<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreLocator\Block;

use Magento\Framework\View\Element\Template;
use MageWorx\StoreLocator\Helper\Data;

class Popup extends Template
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * MainPage constructor.
     *
     * @param Data $helper
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Data $helper,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper = $helper;
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        if (!$this->getTemplate()) {
            $this->setTemplate('MageWorx_StoreLocator::locations/popup.phtml');
        }

        return parent::_prepareLayout();
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getLocationsHtml()
    {
        $block = $this->getLayout()->createBlock(\MageWorx\StoreLocator\Block\Locations::class);
        $block->setLayoutType($this->helper->getPopupLayout());

        if ($this->getProduct()) {
            $block->setProduct($this->getProduct());
        }

        $block->setIsPopupUpdate($this->getIsPopupUpdate());

        return $block->toHtml();
    }
}

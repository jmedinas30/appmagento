<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreLocator\Block\Header;

use Magento\Framework\View\Element\Template;
use MageWorx\StoreLocator\Helper\Data;
use Magento\Store\Model\StoreManagerInterface;

class Link extends \Magento\Framework\View\Element\Html\Link
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Link constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param Data $helper
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Data $helper,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->storeManager = $storeManager;
        $this->helper       = $helper;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->helper->getLinkTitle();
    }

    /**
     * @return string
     */
    public function getHref()
    {
        return $this->getUrl($this->helper->getLinkUrl());
    }

    /**
     * Render block HTML.
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (false != $this->getTemplate()) {
            return parent::_toHtml();
        }

        return '<li><a href="' . $this->getHref() . '">' . $this->escapeHtml($this->getLabel()) . '</a></li>';
    }
}

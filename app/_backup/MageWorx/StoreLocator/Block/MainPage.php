<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreLocator\Block;

use Magento\Framework\View\Element\Template;
use MageWorx\StoreLocator\Helper\Data;

class MainPage extends Template
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
     * Prepare layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        if ($this->helper->isModuleOutputEnabled()) {
            $this->setTemplate('MageWorx_StoreLocator::locations/main_page.phtml');
        }

        return parent::_prepareLayout();
    }
}

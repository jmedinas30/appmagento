<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Block\Location\ProductList;

use Magento\Catalog\Helper\Product\ProductList;
use Magento\Catalog\Model\Product\ProductList\Toolbar as ToolbarModel;

class Toolbar extends \Magento\Catalog\Block\Product\ProductList\Toolbar
{
    /**
     * @var \MageWorx\LocationPages\Helper\Data
     */
    protected $helper;

    /**
     * Toolbar constructor.
     *
     * @param \MageWorx\LocationPages\Helper\Data $helper
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\Session $catalogSession
     * @param \Magento\Catalog\Model\Config $catalogConfig
     * @param ToolbarModel $toolbarModel
     * @param \Magento\Framework\Url\EncoderInterface $urlEncoder
     * @param ProductList $productListHelper
     * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
     * @param string[] $data
     */
    public function __construct(
        \MageWorx\LocationPages\Helper\Data $helper,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Session $catalogSession,
        \Magento\Catalog\Model\Config $catalogConfig,
        ToolbarModel $toolbarModel,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        ProductList $productListHelper,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct(
            $context,
            $catalogSession,
            $catalogConfig,
            $toolbarModel,
            $urlEncoder,
            $productListHelper,
            $postDataHelper,
            $data
        );
    }

    /**
     * Retrieve current View mode
     *
     * @return string
     */
    public function getCurrentMode()
    {
        $mode = $this->_getData('_current_grid_mode');
        if ($mode) {
            return $mode;
        }
        $defaultMode = $this->helper->getProductDisplayMode();
        $mode        = $this->_toolbarModel->getMode();
        if (!$mode) {
            $mode = $defaultMode;
        }

        $this->setData('_current_grid_mode', $mode);

        return $mode;
    }

    /**
     * Retrieve widget options in json format
     *
     * @param string[] $customOptions Optional parameter for passing custom selectors from template
     * @return string
     */
    public function getWidgetOptionsJson(array $customOptions = [])
    {
        $options = [
            'modeDefault'  => $this->helper->getProductDisplayMode(),
            'orderDefault' => $this->helper->getProductDefaultSort()
        ];

        return parent::getWidgetOptionsJson(array_replace_recursive($options, $customOptions));
    }

    /**
     * Get order field
     *
     * @return null|string
     */
    protected function getOrderField()
    {
        if ($this->_orderField === null) {
            $this->_orderField = $this->helper->getProductDefaultSort();
        }

        return $this->_orderField;
    }
}

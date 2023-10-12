<?php

namespace Meetanshi\GA4\Block;

/**
 * Class Category
 * @package Meetanshi\GA4\Block
 */
class Category extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Meetanshi\GA4\Helper\Data
     */
    protected $helper;

    /**
     * Category constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Meetanshi\GA4\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Meetanshi\GA4\Helper\Data $helper,
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function isEnable()
    {
        return $this->helper->isEnable();
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrencyCode()
    {
        return $this->_storeManager->getStore()->getCurrentCurrencyCode();
    }
}

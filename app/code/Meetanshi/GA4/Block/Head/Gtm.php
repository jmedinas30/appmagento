<?php

namespace Meetanshi\GA4\Block\Head;

use Magento\Framework\View\Element\Template\Context;
use Meetanshi\GA4\Helper\Data;
use Magento\Framework\View\Element\Template;

/**
 * Class Gtm
 * @package Meetanshi\GA4\Block\Head
 */
class Gtm extends Template
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * Gtm constructor.
     * @param Context $context
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $helper,
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
     */
    public function getGTMJSCode()
    {
        return $this->helper->getGAJSCode();
    }

    /**
     * @return mixed
     */
    public function getNonJSCode()
    {
        return $this->helper->getGANonJSCode();
    }
}

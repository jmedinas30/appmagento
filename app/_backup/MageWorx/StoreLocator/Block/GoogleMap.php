<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreLocator\Block;

use Magento\Framework\View\Element\Template;
use MageWorx\StoreLocator\Helper\Data;

class GoogleMap extends Template
{
    /**
     * @var Data
     */
    protected $helper;
    /**
     *
     * @var \Magento\Framework\Locale\Resolver
     */
    private $locale;

    /**
     * MainPage constructor.
     *
     * @param \Magento\Framework\Locale\Resolver $locale
     * @param Data $helper
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Locale\Resolver $locale,
        Data $helper,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->locale = $locale;
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
            $this->setTemplate('MageWorx_StoreLocator::locations/script.phtml');
        }

        return parent::_prepareLayout();
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->helper->getMapApiKey();
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        $locale = explode('_', $this->locale->getLocale());

        return $locale[0] ?? 'en';
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        $locale = explode('_', $this->locale->getLocale());

        return $locale[1] ?? 'US';
    }

    /**
     * @return bool|null
     */
    public function isShowMap()
    {
        return $this->helper->isShowMap();
    }

    /**
     * @return bool
     */
    public function initializeByLoad()
    {
        if ($this->getInitializeByLoad() === false) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getScriptUrlWithParams()
    {
        $url = 'https://maps.googleapis.com/maps/api/js?key=';
        $url .= $this->getApiKey();
        $url .= '&libraries=places&';
        if ($this->initializeByLoad()) {
            $url .= 'callback=initialize&';
        }
        $url .= 'language=' . $this->getLanguage() . '&';
        $url .= 'region=' . $this->getRegion();

        return $url;
    }
}

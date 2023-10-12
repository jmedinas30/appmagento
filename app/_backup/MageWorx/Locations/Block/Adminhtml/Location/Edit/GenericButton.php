<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Block\Adminhtml\Location\Edit;

use MageWorx\Locations\Helper\Data;

class GenericButton
{
    /**
     * Url Builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * Helper
     *
     * @var Data
     */
    protected $helper;

    /**
     * GenericButton constructor.
     *
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param Data $helper
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        Data $helper
    ) {
        $this->urlBuilder = $context->getUrlBuilder();
        $this->helper     = $helper;
    }

    /**
     * Return the current location id.
     *
     * @return int|null
     */
    public function getLocationId()
    {
        /** @var \MageWorx\Locations\Api\Data\LocationInterface $location */
        $location = $this->helper->getCurrentLocation();

        return $location ? $location->getId() : null;
    }

    /**
     * Generate url
     *
     * @param string $route
     * @param array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->urlBuilder->getUrl($route, $params);
    }

    /**
     * @param string $name
     * @return string
     */
    public function canRender($name)
    {
        return $name;
    }
}

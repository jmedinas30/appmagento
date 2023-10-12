<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Model;

use MageWorx\LocationPages\Api\Data\LocationPageInterface;
use MageWorx\Locations\Api\Data\LocationInterface;

class LocationPage extends \Magento\Framework\Model\AbstractModel implements LocationPageInterface
{
    /**
     * @var \MageWorx\Locations\Api\Data\LocationInterface
     */
    protected $location;

    /**
     * @var \MageWorx\LocationPages\Helper\Data
     */
    protected $helper;

    /**
     * LocationPage constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \MageWorx\LocationPages\Helper\Data $helper
     * @param \Magento\Framework\Registry $registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param string[] $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \MageWorx\LocationPages\Helper\Data $helper,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->helper = $helper;
    }

    /**
     * @param LocationInterface $location
     * @return $this
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get page location
     *
     * @return LocationInterface
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return string
     */
    public function getUrlPath()
    {
        $path = $this->helper->getBasePath() . '/';

        $pathParts = $this->prepareLocationPath($this->helper->getUrlPathParts());

        foreach ($pathParts as $key => $value) {
            $str = $this->helper->prepareStringToUrl($value);
            if ($str) {
                $path .= $str . '/';
            }
        }

        return $path;
    }

    /**
     * @return string
     */
    public function getUrlKey()
    {
        $path = '';

        $pathParts = $this->prepareLocationPath($this->helper->getUrlKeyParts());

        foreach ($pathParts as $key => $value) {
            $str = $this->helper->prepareStringToUrl($value);
            if ($str) {
                $path .= $str . '-';
            }
        }

        return rtrim($path, '-');
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getFullUrl()
    {
        return $this->getLocation()->getPageFullUrl();
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->getUrlPath() . $this->getUrlKey();
    }

    /**
     * @param string[] $pathParts
     * @return string[]
     */
    public function prepareLocationPath($pathParts)
    {
        $result = [];

        foreach ($pathParts as $key) {
            if ($key == 'country') {
                $result[$key] = $this->getLocation()->getCountry();
            } else {
                $result[$key] = $this->getLocation()->getData($key);
            }

        }

        return $result;
    }
}

<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Model;

use MageWorx\LocationPages\Api\Data\LocationListInterface;
use Magento\Framework\Filter\TranslitUrl;

class LocationList extends \Magento\Framework\Model\AbstractModel implements LocationListInterface
{
    /**
     * @var \MageWorx\LocationPages\Helper\Data
     */
    protected $helper;

    /**
     * @var TranslitUrl
     */
    protected $translitUrl;

    /**
     * LocationList constructor.
     *
     * @param TranslitUrl $translitUrl
     * @param \Magento\Framework\Model\Context $context
     * @param \MageWorx\LocationPages\Helper\Data $helper
     * @param \Magento\Framework\Registry $registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param string[] $data
     */
    public function __construct(
        TranslitUrl $translitUrl,
        \Magento\Framework\Model\Context $context,
        \MageWorx\LocationPages\Helper\Data $helper,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->helper      = $helper;
        $this->translitUrl = $translitUrl;
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\MageWorx\LocationPages\Model\ResourceModel\LocationList::class);
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setName($value)
    {
        $this->setData(LocationListInterface::NAME, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setType($value)
    {
        $this->setData(LocationListInterface::TYPE, $value);

        return $this;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setParentId($value)
    {
        $this->setData(LocationListInterface::PARENT_ID, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setPath($value)
    {
        $this->setData(LocationListInterface::PATH, $value);

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getData(LocationListInterface::NAME);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->getData(LocationListInterface::TYPE);
    }

    /**
     * @return int
     */
    public function getParentId()
    {
        return $this->getData(LocationListInterface::PARENT_ID);
    }

    /**
     * @return int
     */
    public function getPath()
    {
        return trim((string)$this->getData(LocationListInterface::PATH), '/');
    }

    /**
     * @return string
     */
    public function getUrlKey()
    {
        return $this->helper->prepareStringToUrl($this->getName());
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getFullUrl()
    {
        return $this->helper->getStoreUrl() . $this->getUrl();
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->getUrlPath() . $this->getUrlKey();
    }
}

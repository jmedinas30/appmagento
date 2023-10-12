<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
declare(strict_types=1);

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
    public function setName(string $value): LocationListInterface
    {
        $this->setData(LocationListInterface::NAME, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setType(string $value): LocationListInterface
    {
        $this->setData(LocationListInterface::TYPE, $value);

        return $this;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setParentId(int $value): LocationListInterface
    {
        $this->setData(LocationListInterface::PARENT_ID, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setPath(string $value): LocationListInterface
    {
        $this->setData(LocationListInterface::PATH, $value);

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->getData(LocationListInterface::NAME);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->getData(LocationListInterface::TYPE);
    }

    /**
     * @return int
     */
    public function getParentId(): int
    {
        return (int)$this->getData(LocationListInterface::PARENT_ID);
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return trim((string)$this->getData(LocationListInterface::PATH), '/');
    }

    /**
     * @return string
     */
    public function getUrlKey(): string
    {
        return $this->helper->prepareStringToUrl($this->getName());
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getFullUrl(): string
    {
        return $this->helper->getStoreUrl() . $this->getUrl();
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->getUrlPath() . $this->getUrlKey();
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setLatitude($value): LocationListInterface
    {
        $this->setData(LocationListInterface::LATITUDE, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setLongitude($value): LocationListInterface
    {
        $this->setData(LocationListInterface::LONGITUDE, $value);

        return $this;
    }

    /**
     * @return string
     */
    public function getLatitude(): string
    {
        return (string)$this->getData(LocationListInterface::LATITUDE);
    }

    /**
     * @return string
     */
    public function getLongitude(): string
    {
        return (string)$this->getData(LocationListInterface::LONGITUDE);
    }
}

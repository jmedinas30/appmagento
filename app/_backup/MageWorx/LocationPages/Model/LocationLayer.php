<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Model;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory as AttributeCollectionFactory;

class LocationLayer extends \Magento\Catalog\Model\Layer
{
    /**
     * @var \MageWorx\LocationPages\Helper\Data
     */
    protected $helper;

    /**
     * LocationLayer constructor.
     *
     * @param \MageWorx\LocationPages\Helper\Data $helper
     * @param Layer\Context $context
     * @param \Magento\Catalog\Model\Layer\StateFactory $layerStateFactory
     * @param AttributeCollectionFactory $attributeCollectionFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product $catalogProduct
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Registry $registry
     * @param CategoryRepositoryInterface $categoryRepository
     * @param string[] $data
     */
    public function __construct(
        \MageWorx\LocationPages\Helper\Data $helper,
        \MageWorx\LocationPages\Model\Layer\Context $context,
        \Magento\Catalog\Model\Layer\StateFactory $layerStateFactory,
        AttributeCollectionFactory $attributeCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product $catalogProduct,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $registry,
        CategoryRepositoryInterface $categoryRepository,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $layerStateFactory,
            $attributeCollectionFactory,
            $catalogProduct,
            $storeManager,
            $registry,
            $categoryRepository,
            $data
        );
        $this->helper = $helper;
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getProductCollection()
    {
        if (isset($this->_productCollections[$this->getCurrentLocation()->getId()])) {
            $collection = $this->_productCollections[$this->getCurrentLocation()->getId()];
        } else {
            $collection = $this->collectionProvider->getCollection($this->getCurrentLocation());
            $this->prepareProductCollection($collection);
            $this->_productCollections[$this->getCurrentLocation()->getId()] = $collection;
        }

        return $collection;
    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @return $this|\Magento\Catalog\Model\Layer
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function prepareProductCollection($collection)
    {
        $this->collectionFilter->filter($collection, $this->getCurrentLocation());

        return $this;
    }

    /**
     * @return \Magento\Catalog\Api\Data\CategoryInterface|mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrentLocation()
    {
        $locationPage = $this->getData('current_location');
        if ($locationPage === null) {
            /** @var \MageWorx\LocationPages\Model\LocationPage $locationPage */
            $locationPage = $this->helper->getCurrentLocation();
            if ($locationPage) {
                $this->setData('current_location', $locationPage);
            } else {
                $locationPage = $this->categoryRepository->get($this->getCurrentStore()->getRootCategoryId());
                $this->setData('current_location', $locationPage);
            }
        }

        return $locationPage;
    }
}

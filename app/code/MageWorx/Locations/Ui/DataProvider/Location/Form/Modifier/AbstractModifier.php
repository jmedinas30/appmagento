<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Ui\DataProvider\Location\Form\Modifier;

use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use MageWorx\Locations\Model\Registry;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\Locations\Api\LocationRepositoryInterface;
use MageWorx\Locations\Helper\Data;
use Magento\Store\Model\Store;

/**
 * Class AbstractModifier
 *
 */
abstract class AbstractModifier implements ModifierInterface
{
    const FORM_NAME           = 'mageworx_locations_location_form';
    const DATA_SOURCE_DEFAULT = 'general';
    const DATA_SCOPE_LOCATION = 'data.location';

    /**
     * Container fieldset prefix
     */
    const CONTAINER_PREFIX = 'container_';

    /**
     * Meta config path
     */
    const META_CONFIG_PATH = '/arguments/data/config';

    /**
     * @var ArrayManager
     */
    protected $arrayManager;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * @var LocationInterface
     */
    protected $defaultLocation;

    /**
     * AbstractModifier constructor.
     *
     * @param LocationRepositoryInterface $locationRepository
     * @param Data $helper
     * @param ArrayManager $arrayManager
     * @param UrlInterface $urlBuilder
     * @param Registry $coreRegistry
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        LocationRepositoryInterface $locationRepository,
        Data $helper,
        ArrayManager $arrayManager,
        UrlInterface $urlBuilder,
        Registry $coreRegistry,
        StoreManagerInterface $storeManager
    ) {
        $this->locationRepository = $locationRepository;
        $this->helper             = $helper;
        $this->arrayManager       = $arrayManager;
        $this->urlBuilder         = $urlBuilder;
        $this->registry           = $coreRegistry;
        $this->storeManager       = $storeManager;
    }

    /**
     * Get current location
     *
     * @return LocationInterface|null
     */
    protected function getLocation()
    {
        /** @var LocationInterface $location */
        $location = $this->helper->getCurrentLocation();

        return $location;
    }

    /**
     * Get current location
     *
     * @return LocationInterface
     */
    protected function getDefaultLocation()
    {
        if ($this->defaultLocation) {
            return $this->defaultLocation;
        }

        $this->helper->setCurrentStoreIdForLocation(Store::DEFAULT_STORE_ID);
        /** @var LocationInterface $location */
        $this->defaultLocation = $this->locationRepository->getById($this->helper->getCurrentLocation()->getId());
        $this->helper->setCurrentStoreIdForLocation(null);

        return $this->defaultLocation;
    }
}

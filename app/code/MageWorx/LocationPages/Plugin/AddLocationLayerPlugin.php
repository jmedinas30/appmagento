<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Plugin;

class AddLocationLayerPlugin
{
    const CATALOG_LAYER_LOCATION = \MageWorx\LocationPages\Model\LocationLayer::class;

    /**
     * Catalog view layer models list
     *
     * @var array
     */
    protected $layersPool;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;
    /**
     * @var \MageWorx\LocationPages\Model\LocationLayer
     */
    protected $layer = null;

    /**
     * LayerResolver constructor.
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\App\Request\Http $request
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\Request\Http $request
    ) {
        $this->objectManager = $objectManager;
        $this->request       = $request;
    }

    /**
     * Create Catalog Layer by specified type
     *
     * @param string $layerType
     * @return void
     */
    public function aroundCreate(\Magento\Catalog\Model\Layer\Resolver $subject, callable $proceed, $layerType)
    {
        if ($this->request->getFullActionName() == 'mageworx_locationpages_location_view') {
            if (isset($this->layer)) {
                throw new \RuntimeException('Catalog Layer has been already created');
            }

            $this->layer = $this->objectManager->create(self::CATALOG_LAYER_LOCATION);
        } else {
            $proceed($layerType);
        }
    }

    /**
     * Get current Catalog Layer
     *
     * @return \Magento\Catalog\Model\Layer | \MageWorx\LocationPages\Model\LocationLayer
     */
    public function aroundGet(\Magento\Catalog\Model\Layer\Resolver $subject, callable $proceed)
    {
        if ($this->request->getFullActionName() == 'mageworx_locationpages_location_view') {
            if (!isset($this->layer)) {
                $this->layer = $this->objectManager->create(self::CATALOG_LAYER_LOCATION);
            }

            return $this->layer;
        }

        return $proceed();
    }
}

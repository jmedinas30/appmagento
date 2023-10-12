<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreLocator\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use MageWorx\StoreLocator\Model\Source\Scale;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class ChangeDefaultScaleFiled implements DataPatchInterface
{
    /**
     * @var \MageWorx\StoreLocator\Helper\Data
     */
    protected $helper;

    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * @param \MageWorx\StoreLocator\Helper\Data $helper
     * @param ConfigInterface $config
     */
    public function __construct(
        \MageWorx\StoreLocator\Helper\Data $helper,
        ConfigInterface $config
    ) {
        $this->helper = $helper;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $scale = $this->helper->getDefaultScale();
        if ($scale) {
            switch ($scale) {
                case Scale::WORLD:
                    $zoomLevel = '3';
                    $scale =  Scale::COUNTRY;
                    break;
                case Scale::COUNTRY:
                    $zoomLevel = '5';
                    break;
                case Scale::REGION:
                    $zoomLevel = '8';
                    break;
                case Scale::CITY:
                    $zoomLevel = '11';
                    break;
                case Scale::STORE:
                    $zoomLevel = '18';
                    $scale =  Scale::CITY;
                    break;
                default:
                    $zoomLevel = $scale;
                    break;
            }

            $this->setDefaultScale($zoomLevel);
            $this->setDefaultFilterBy($scale);
        }
    }

    /**
     * @param string $zoomLevel
     * @return $this
     */
    public function setDefaultScale(string $zoomLevel)
    {
        $this->config->saveConfig(
            \MageWorx\StoreLocator\Helper\Data::XML_PATH_DEFAULT_SCALE,
            $zoomLevel,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            0
        );

        return $this;
    }

    /**
     * @param string $scale
     * @return $this
     */
    public function setDefaultFilterBy(string $scale)
    {
        $this->config->saveConfig(
            \MageWorx\StoreLocator\Helper\Data::XML_PATH_FILTER_BY,
            $scale,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            0
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}

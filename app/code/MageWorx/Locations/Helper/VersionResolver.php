<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
declare(strict_types=1);

namespace MageWorx\Locations\Helper;

use Magento\Framework\Serialize\Serializer\Json as JsonHelper;

/**
 * Resolver magento version
 *
 */
class VersionResolver extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\App\ProductMetadataInterface $productMetadata
     */
    protected $productMetadata;

    /**
     * @var string|int
     */
    protected $moduleVersion;

    /**
     * @var \Magento\Framework\Component\ComponentRegistrarInterface
     */
    protected $componentRegistrar;

    /**
     * @var \Magento\Framework\Filesystem\Directory\ReadFactory
     */
    protected $readFactory;

    /**
     * @var JsonHelper
     */
    protected $jsonHelper;

    /**
     * MagentoVersion constructor.
     *
     * @param \Magento\Framework\App\ProductMetadataInterface $productMetadata
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\Component\ComponentRegistrarInterface $componentRegistrar
     * @param \Magento\Framework\Filesystem\Directory\ReadFactory $readFactory
     * @param JsonHelper $jsonHelper
     */
    public function __construct(
        \Magento\Framework\App\ProductMetadataInterface $productMetadata,
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Component\ComponentRegistrarInterface $componentRegistrar,
        \Magento\Framework\Filesystem\Directory\ReadFactory $readFactory,
        JsonHelper $jsonHelper
    ) {
        $this->productMetadata    = $productMetadata;
        $this->componentRegistrar = $componentRegistrar;
        $this->readFactory        = $readFactory;
        $this->jsonHelper         = $jsonHelper;
        parent::__construct($context);
    }

    /**
     * @deprecated - see https://github.com/magento/magento2/issues/24025
     * @return string
     */
    public function getVersion() : ?string
    {
        return $this->productMetadata->getVersion();
    }

    /**
     * Check module version according to conditions
     *
     * @param string $fromVersion
     * @param string $toVersion
     * @param string $fromOperator
     * @param string $toOperator
     * @param string $moduleName
     * @return bool|mixed
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function checkModuleVersion(
        $moduleName,
        $fromVersion,
        $toVersion = '',
        $fromOperator = '>=',
        $toOperator = '<'
    ) : ?bool {
        if (empty($this->moduleVersion[$moduleName])) {
            $this->moduleVersion[$moduleName] = $this->getModuleVersion($moduleName);
        }

        $fromCondition = version_compare($this->moduleVersion[$moduleName], $fromVersion, $fromOperator);
        if ($toVersion === '') {
            return $fromCondition;
        }

        return $fromCondition && version_compare($this->moduleVersion[$moduleName], $toVersion, $toOperator);
    }

    /**
     * @param string $moduleName
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getModuleVersion($moduleName) : ?string
    {
        $path             = $this->componentRegistrar->getPath(
            \Magento\Framework\Component\ComponentRegistrar::MODULE,
            $moduleName
        );
        if (!$path) {
            return '0';
        }
        $directoryRead    = $this->readFactory->create($path);
        $composerJsonData = $directoryRead->readFile('composer.json');
        $data             = $this->jsonHelper->unserialize($composerJsonData);

        if ($data && is_array($data)) {
            return $data['version'] ?? '0';
        }
        return $data->version ?? '0';
    }
}

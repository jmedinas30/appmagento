<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MageWorx\GoogleApi\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use MageWorx\GoogleApi\Helper\Data as GoogleApiHelper;

class ChangeConfigurationSettingsPaths implements DataPatchInterface, PatchVersionInterface
{
    /**
     * XML config path api key
     */
    const OLD_XML_PATH_MAP_API_KEY = 'mageworx_locations/map/map_api_key';

    /**
     * XML config path for autocomplete countries
     */
    const OLD_XML_PATH_AUTOCOMPLETE_RESTRICT_COUNTRIES = 'mageworx_locations/map/autocomplete_restrict_countries';

    /**
     * @var ModuleDataSetupInterface
     */
    protected $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->update(
            $this->moduleDataSetup->getTable('core_config_data'),
            ['path' => GoogleApiHelper::XML_PATH_MAP_API_KEY],
            ['path = ?' => self::OLD_XML_PATH_MAP_API_KEY]
        );

        $this->moduleDataSetup->getConnection()->update(
            $this->moduleDataSetup->getTable('core_config_data'),
            ['path' => GoogleApiHelper::XML_PATH_AUTOCOMPLETE_RESTRICT_COUNTRIES],
            ['path = ?' => self::OLD_XML_PATH_AUTOCOMPLETE_RESTRICT_COUNTRIES]
        );
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

    public static function getVersion()
    {
        return '1.0.0';
    }
}

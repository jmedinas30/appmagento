<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\GoogleApi\Setup;

use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Store\Model\ScopeInterface;
use MageWorx\GoogleApi\Helper\Data as GoogleApiHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;


class InstallData implements InstallDataInterface
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
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $setup->getConnection()->update(
            $setup->getTable('core_config_data'),
            ['path' => GoogleApiHelper::XML_PATH_MAP_API_KEY],
            ['path = ?' => self::OLD_XML_PATH_MAP_API_KEY]
        );

        $setup->getConnection()->update(
            $setup->getTable('core_config_data'),
            ['path' => GoogleApiHelper::XML_PATH_AUTOCOMPLETE_RESTRICT_COUNTRIES],
            ['path = ?' => self::OLD_XML_PATH_AUTOCOMPLETE_RESTRICT_COUNTRIES]
        );

        $setup->endSetup();
    }
}

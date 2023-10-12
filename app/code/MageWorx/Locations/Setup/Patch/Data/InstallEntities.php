<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\Locations\Setup\LocationSetupFactory;

class InstallEntities implements DataPatchInterface, PatchVersionInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    protected $moduleDataSetup;

    /**
     * @var LocationSetupFactory
     */
    protected $locationSetupFactory;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        LocationSetupFactory $locationSetupFactory
    ) {
        $this->moduleDataSetup      = $moduleDataSetup;
        $this->locationSetupFactory = $locationSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $locationSetup = $this->locationSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $locationSetup->installEntities();

        $locationSetup->addAttribute(
            LocationInterface::ENTITY,
            'meta_title',
            ['type' => 'varchar']
        );
        $locationSetup->addAttribute(
            LocationInterface::ENTITY,
            'meta_description',
            ['type' => 'varchar']
        );
        $locationSetup->addAttribute(
            LocationInterface::ENTITY,
            'meta_keywords',
            ['type' => 'varchar']
        );
        $locationSetup->addAttribute(
            LocationInterface::ENTITY,
            'meta_robots',
            ['type' => 'varchar']
        );
        $locationSetup->addAttribute(
            LocationInterface::ENTITY,
            'name',
            ['type' => 'varchar']
        );
        $locationSetup->addAttribute(
            LocationInterface::ENTITY,
            'description',
            ['type' => 'varchar']
        );
        $locationSetup->addAttribute(
            LocationInterface::ENTITY,
            'city',
            ['type' => 'varchar']
        );
        $locationSetup->addAttribute(
            LocationInterface::ENTITY,
            'address',
            ['type' => 'varchar']
        );
        $locationSetup->addAttribute(
            LocationInterface::ENTITY,
            'source_code',
            ['type' => 'varchar']
        );

        $locationSetup->updateAttribute(
            LocationInterface::ENTITY,
            'source_code',
            'backend_type',
            'static'
        );

        $locationSetup->addAttribute(
            LocationInterface::ENTITY,
            LocationInterface::TIMEZONE,
            [
                'type'     => 'static',
                'label'    => 'Timezone',
                'input'    => 'text',
                'required' => false,
                'default'  => \MageWorx\Locations\Model\Source\Timezone::USE_DEFAULT,
            ]
        );
        $locationSetup->addAttribute(
            LocationInterface::ENTITY,
            LocationInterface::IS_PICKUP_AVAILABLE,
            [
                'type'     => 'static',
                'source'   => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                'label'    => 'Is Pickup Available',
                'input'    => 'select',
                'required' => false,
                'default'  => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::VALUE_YES
            ]
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

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

class AddIsWorkingHoursEnabledAttribute implements DataPatchInterface
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

        if (!$locationSetup->getAttributeId(
            LocationInterface::ENTITY,
            LocationInterface::IS_WORKING_HOURS_ENABLED
        )) {
            $locationSetup->addAttribute(
                LocationInterface::ENTITY,
                LocationInterface::IS_WORKING_HOURS_ENABLED,
                [
                    'type'     => 'static',
                    'source'   => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                    'label'    => 'Is Working Hours Enabled',
                    'input'    => 'select',
                    'required' => false,
                    'default'  => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::VALUE_YES
                ]
            );
        }
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

<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\Locations\Setup\LocationSetupFactory;

class AddExtraAttributes implements DataPatchInterface
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
            LocationInterface::GAS_STATION
        )) {
            $locationSetup->addAttribute(
                LocationInterface::ENTITY,
                LocationInterface::GAS_STATION,
                [
                    'type'     => 'int',
                    'note'     => 'Location extra attribute',
                    'source'   => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                    'label'    => 'Gas station',
                    'input'    => 'select',
                    'required' => false,
                    'default'  => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::VALUE_NO
                ]
            );
        }

        if (!$locationSetup->getAttributeId(
            LocationInterface::ENTITY,
            LocationInterface::PHARMACY
        )) {
            $locationSetup->addAttribute(
                LocationInterface::ENTITY,
                LocationInterface::PHARMACY,
                [
                    'type'     => 'int',
                    'note'     => 'Location extra attribute',
                    'source'   => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                    'label'    => 'Pharmacy',
                    'input'    => 'select',
                    'required' => false,
                    'default'  => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::VALUE_NO
                ]
            );
        }

        if (!$locationSetup->getAttributeId(
            LocationInterface::ENTITY,
            LocationInterface::TIRE_CENTER
        )) {
            $locationSetup->addAttribute(
                LocationInterface::ENTITY,
                LocationInterface::TIRE_CENTER,
                [
                    'type'     => 'int',
                    'note'     => 'Location extra attribute',
                    'source'   => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                    'label'    => 'Tire center',
                    'input'    => 'select',
                    'required' => false,
                    'default'  => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::VALUE_NO
                ]
            );
        }

        if (!$locationSetup->getAttributeId(
            LocationInterface::ENTITY,
            LocationInterface::FOOD_COURT
        )) {
            $locationSetup->addAttribute(
                LocationInterface::ENTITY,
                LocationInterface::FOOD_COURT,
                [
                    'type'     => 'int',
                    'note'     => 'Location extra attribute',
                    'source'   => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                    'label'    => 'Food court',
                    'input'    => 'select',
                    'required' => false,
                    'default'  => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::VALUE_NO
                ]
            );
        }

        if (!$locationSetup->getAttributeId(
            LocationInterface::ENTITY,
            LocationInterface::CURBSIDE_PICKUP
        )) {
            $locationSetup->addAttribute(
                LocationInterface::ENTITY,
                LocationInterface::CURBSIDE_PICKUP,
                [
                    'type'     => 'int',
                    'note'     => 'Location extra attribute',
                    'source'   => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                    'label'    => 'Curbside pickup',
                    'input'    => 'select',
                    'required' => false,
                    'default'  => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::VALUE_NO
                ]
            );
        }

        if (!$locationSetup->getAttributeId(
            LocationInterface::ENTITY,
            LocationInterface::PARKING
        )) {
            $locationSetup->addAttribute(
                LocationInterface::ENTITY,
                LocationInterface::PARKING,
                [
                    'type'     => 'int',
                    'note'     => 'Location extra attribute',
                    'source'   => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                    'label'    => 'Parking',
                    'input'    => 'select',
                    'required' => false,
                    'default'  => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::VALUE_NO
                ]
            );
        }

        if (!$locationSetup->getAttributeId(
            LocationInterface::ENTITY,
            LocationInterface::AMAZON_RETURNS
        )) {
            $locationSetup->addAttribute(
                LocationInterface::ENTITY,
                LocationInterface::AMAZON_RETURNS,
                [
                    'type'     => 'int',
                    'note'     => 'Location extra attribute',
                    'source'   => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                    'label'    => 'Amazon returns',
                    'input'    => 'select',
                    'required' => false,
                    'default'  => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::VALUE_NO
                ]
            );
        }

        if (!$locationSetup->getAttributeId(
            LocationInterface::ENTITY,
            LocationInterface::DELIVERY_PICKUP
        )) {
            $locationSetup->addAttribute(
                LocationInterface::ENTITY,
                LocationInterface::DELIVERY_PICKUP,
                [
                    'type'     => 'int',
                    'note'     => 'Location extra attribute',
                    'source'   => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                    'label'    => 'Delivery & pickup',
                    'input'    => 'select',
                    'required' => false,
                    'default'  => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::VALUE_NO
                ]
            );
        }

        if (!$locationSetup->getAttributeId(
            LocationInterface::ENTITY,
            LocationInterface::OPTICAL_DEPARTMENT
        )) {
            $locationSetup->addAttribute(
                LocationInterface::ENTITY,
                LocationInterface::OPTICAL_DEPARTMENT,
                [
                    'type'     => 'int',
                    'note'     => 'Location extra attribute',
                    'source'   => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                    'label'    => 'Optical department',
                    'input'    => 'select',
                    'required' => false,
                    'default'  => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::VALUE_NO
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

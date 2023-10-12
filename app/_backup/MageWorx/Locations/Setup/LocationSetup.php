<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Setup;

use Magento\Eav\Setup\EavSetup;
use MageWorx\Locations\Api\Data\LocationInterface;

class LocationSetup extends EavSetup
{

    /**
     * @return string[]
     */
    public function getDefaultEntities()
    {
        $locationEntity = LocationInterface::ENTITY;

        $entities = [
            $locationEntity => [
                'entity_model' => \MageWorx\Locations\Model\ResourceModel\Location::class,
                'table'        => LocationInterface::ENTITY_TABLE,
                'attributes'   => [
                    'code'                  => [
                        'type'       => 'static',
                        'unique'     => true,
                        'label'      => 'Location Code',
                        'input'      => 'text',
                        'sort_order' => 2,
                    ],
                    'is_active'             => [
                        'type'       => 'static',
                        'label'      => 'Is Active',
                        'input'      => 'select',
                        'source'     => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                        'sort_order' => 3,
                    ],
                    'order'                 => [
                        'type'       => 'static',
                        'label'      => 'Sort Order',
                        'input'      => 'select',
                        'required'   => false,
                        'source'     => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                        'sort_order' => 4,
                    ],
                    'image_path'            => [
                        'type'       => 'static',
                        'label'      => 'Image Path',
                        'input'      => 'image',
                        'backend'    => \Magento\Catalog\Model\Category\Attribute\Backend\Image::class,
                        'required'   => false,
                        'sort_order' => 6,
                    ],
                    'working_hours_type'    => [
                        'type'       => 'static',
                        'label'      => 'Working Hours Type',
                        'input'      => 'select',
                        'required'   => false,
                        'sort_order' => 7,
                    ],
                    'date_created'          => [
                        'type'       => 'static',
                        'input'      => 'date',
                        'sort_order' => 8,
                        'visible'    => false,
                    ],
                    'date_modified'         => [
                        'type'       => 'static',
                        'input'      => 'date',
                        'sort_order' => 9,
                        'visible'    => false,
                    ],
                    'country_id'            => [
                        'type'       => 'static',
                        'label'      => 'Country Id',
                        'input'      => 'text',
                        'sort_order' => 10,
                    ],
                    'region'                => [
                        'type'       => 'static',
                        'label'      => 'Region',
                        'input'      => 'text',
                        'required'   => false,
                        'sort_order' => 11,

                    ],
                    'postcode'              => [
                        'type'       => 'static',
                        'label'      => 'Postcode',
                        'input'      => 'text',
                        'required'   => false,
                        'sort_order' => 15,
                    ],
                    'email'                 => [
                        'type'       => 'static',
                        'label'      => 'Email',
                        'input'      => 'text',
                        'required'   => false,
                        'sort_order' => 16,
                    ],
                    'phone_number'          => [
                        'type'       => 'static',
                        'label'      => 'Phone Number',
                        'input'      => 'text',
                        'required'   => false,
                        'sort_order' => 17,
                    ],
                    'website'               => [
                        'type'       => 'static',
                        'label'      => 'Website',
                        'input'      => 'text',
                        'required'   => false,
                        'sort_order' => 18,
                    ],
                    'latitude'              => [
                        'type'       => 'static',
                        'label'      => 'Latitude',
                        'input'      => 'text',
                        'sort_order' => 19,
                    ],
                    'longitude'             => [
                        'type'       => 'static',
                        'label'      => 'Longitude',
                        'input'      => 'text',
                        'sort_order' => 20,
                    ],
                    'conditions_serialized' => [
                        'type'       => 'static',
                        'label'      => 'Conditions Serialized',
                        'required'   => false,
                        'input'      => 'text',
                        'sort_order' => 21,
                    ],
                    'assign_type'           => [
                        'type'       => 'static',
                        'label'      => 'Assign Type',
                        'input'      => 'text',
                        'required'   => false,
                        'sort_order' => 22,
                    ],
                    'facebook'              => [
                        'type'       => 'static',
                        'label'      => 'Facebook',
                        'input'      => 'text',
                        'required'   => false,
                        'sort_order' => 23,
                    ],
                    'skype'                 => [
                        'type'       => 'static',
                        'label'      => 'Skype',
                        'input'      => 'text',
                        'required'   => false,
                        'sort_order' => 24,
                    ],
                    'instagram'             => [
                        'type'       => 'static',
                        'label'      => 'Instagram',
                        'input'      => 'text',
                        'required'   => false,
                        'sort_order' => 25,
                    ],
                    'whatsapp'              => [
                        'type'       => 'static',
                        'label'      => 'WhatsApp',
                        'input'      => 'text',
                        'required'   => false,
                        'sort_order' => 26,
                    ],
                    'apply_by_cron'         => [
                        'type'       => 'static',
                        'label'      => 'Apply By Crone',
                        'input'      => 'select',
                        'source'     => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                        'sort_order' => 27,
                    ],
                    'store_id'              => [
                        'type'       => 'static',
                        'label'      => 'Store Ids',
                        'input'      => 'text',
                        'required'   => false,
                        'sort_order' => 28,
                    ],
                    'location_page_path'    => [
                        'type'       => 'static',
                        'label'      => 'Location Page Path',
                        'input'      => 'text',
                        'sort_order' => 29,
                    ],
                    'source_code'           => [
                        'type'       => 'static',
                        'label'      => 'Source Code',
                        'input'      => 'text',
                        'sort_order' => 30,
                    ],
                ],
            ],
        ];

        return $entities;
    }
}

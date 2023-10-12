<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Ui\DataProvider\Location\Form\Modifier;

use Magento\Framework\App\RequestInterface;
use MageWorx\Locations\Model\Registry;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\System\Store;
use MageWorx\Locations\Helper\Data;
use MageWorx\Locations\Model\Source\Timezone as TimezoneOptions;
use MageWorx\Locations\Model\Source\Country;
use MageWorx\Locations\Model\Source\AssignType;
use MageWorx\Locations\Model\MsiResolver\Sources;
use MageWorx\Locations\Model\Source\Region;
use MageWorx\Locations\Model\Source\WorkingDay;
use MageWorx\Locations\Model\Source\WorkingHoursType;
use MageWorx\Locations\Model\Source\MetaRobots;
use MageWorx\Locations\Api\LocationRepositoryInterface;
use MageWorx\Locations\Api\Data\LocationInterface;

/**
 * Class LocationModifier
 *
 */
class LocationModifier extends AbstractModifier
{
    /**
     * @var array
     */
    protected $meta = [];

    /**
     * @var Store
     */
    protected $store;

    /**
     * @var Country
     */
    protected $country;

    /**
     * @var Region
     */
    protected $region;

    /**
     * @var WorkingHoursType
     */
    protected $workingHoursType;

    /**
     * @var WorkingDay
     */
    protected $workingDays;

    /**
     * @var AssignType
     */
    protected $assignType;

    /**
     * @var Sources
     */
    protected $sources;

    /**
     * @var MetaRobots
     */
    protected $metaRobots;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var TimezoneOptions
     */
    protected $timezoneOptions;

    /**
     * LocationModifier constructor.
     *
     * @param LocationRepositoryInterface $locationRepository
     * @param MetaRobots $metaRobots
     * @param WorkingHoursType $workingHoursType
     * @param WorkingDay $workingDays
     * @param Country $country
     * @param Region $region
     * @param AssignType $assignType
     * @param Sources $sources
     * @param Data $helper
     * @param Store $store
     * @param ArrayManager $arrayManager
     * @param UrlInterface $urlBuilder
     * @param Registry $coreRegistry
     * @param StoreManagerInterface $storeManager
     * @param RequestInterface $request
     * @param TimezoneOptions $timezoneOptions
     */
    public function __construct(
        LocationRepositoryInterface $locationRepository,
        MetaRobots $metaRobots,
        WorkingHoursType $workingHoursType,
        WorkingDay $workingDays,
        Country $country,
        Region $region,
        AssignType $assignType,
        Sources $sources,
        Data $helper,
        Store $store,
        ArrayManager $arrayManager,
        UrlInterface $urlBuilder,
        Registry $coreRegistry,
        StoreManagerInterface $storeManager,
        RequestInterface $request,
        TimezoneOptions $timezoneOptions
    ) {
        parent::__construct(
            $locationRepository,
            $helper,
            $arrayManager,
            $urlBuilder,
            $coreRegistry,
            $storeManager
        );
        $this->country          = $country;
        $this->region           = $region;
        $this->assignType       = $assignType;
        $this->sources          = $sources;
        $this->request          = $request;
        $this->store            = $store;
        $this->workingHoursType = $workingHoursType;
        $this->workingDays      = $workingDays;
        $this->metaRobots       = $metaRobots;
        $this->timezoneOptions  = $timezoneOptions;
    }

    /**
     * @param array $data
     * @return array
     */
    public function modifyData(array $data)
    {
        $actionParameters = [];
        $currentStoreId   = $this->helper->getCurrentStoreIdForLocation();
        if ($currentStoreId) {
            $actionParameters['store'] = $currentStoreId;
        };

        $submitUrl = $this->urlBuilder->getUrl('mageworx_locations/location/save', $actionParameters);
        $data      = array_replace_recursive(
            $data,
            [
                'config' => [
                    'submit_url' => $submitUrl,
                ]
            ]
        );

        return $data;
    }

    /**
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta)
    {
        $disableForStoreEdit = $this->getLocation()->getCurrentStoreId() ? true : false;
        $disableStoreChooser = $this->storeManager->isSingleStoreMode() ? true : false;
        $disableCodeEdit     = $this->getLocation()->getId() ? true : false;
        $countryId           = $this->getLocation()->getCountryId() ? $this->getLocation()->getCountryId() : 'US';
        $regions             = $this->region->toOptionArray(true, $countryId);

        if ($disableForStoreEdit) {
            $meta = array_merge_recursive($meta, $this->getMetaDisableForStoreEdit());
            $meta = array_merge_recursive($meta, $this->getDefaultValues());

        }

        $metaValues['general'] = [
            'children' => [
                'store_ids' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'options'  => $this->store->getStoreValuesForForm(false, true),
                                'disabled' => $disableStoreChooser
                            ],
                        ],
                    ],
                ],
                'code'      => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'disabled' => $disableCodeEdit
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $metaValues['address_tab'] = [
            'children' => [
                'timezone'           => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'options' => $this->timezoneOptions->toOptionArray()
                            ],
                        ],
                    ],
                ],
                'country_id'         => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'options' => $this->country->toOptionArray()
                            ],
                        ],
                    ],
                ],
                'region'             => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'options' => $regions
                            ],
                        ],
                    ],
                ],
                'working_hours_type' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'options' => $this->workingHoursType->toOptionArray(),
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $assignType             = $this->assignType->toOptionArray();
        $disableProductTransfer = $this->getLocation()->getId() ? false : true;

        $metaValues['products'] = [
            'children' => [
                'assign_type'       => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'options' => $assignType,
                            ],
                        ],
                    ],
                ],
                'transfer_products' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'disabled' => $disableProductTransfer
                            ],
                        ],
                    ],
                ],
            ],
        ];

        // add Sources list if MSI enabled
        if ($sourcesInstance = $this->sources->getInstance()) {
            $sources = $sourcesInstance->toOptionArray();

            $metaValues['products']['children']['source_code'] = [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'options' => $sources,
                        ],
                    ],
                ],
            ];
        }

        $metaValues['page'] = [
            'children' => [
                'meta_robots' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'options' => $this->metaRobots->toOptionArrayWithDefault(),
                            ],
                        ],
                    ],
                ],
            ],
        ];

        return array_merge_recursive($meta, $metaValues);
    }

    /**
     * @return array
     */
    protected function getMetaDisableForStoreEdit()
    {
        $metaDisableForStoreEdit = [];

        $disabledForStores = [
            'general'     => ['is_active', LocationInterface::IS_PICKUP_AVAILABLE, 'order', 'image_path', 'store_ids'],
            'address_tab' => [
                LocationInterface::TIMEZONE,
                'country_id',
                'region',
                'postcode',
                'phone_number',
                'email',
                'website',
                'facebook',
                'skype',
                'instagram',
                'whatsapp',
                'is_working_hours_enabled',
                'working_hours_type',
                'working_hours[everyday][has_lunch_time]',
                'working_hours[everyday]',
                'working_hours[sunday][off]',
                'working_hours[sunday][has_lunch_time]',
                'working_hours[sunday]',
                'working_hours[monday][off]',
                'working_hours[monday][has_lunch_time]',
                'working_hours[monday]',
                'working_hours[tuesday][off]',
                'working_hours[tuesday][has_lunch_time]',
                'working_hours[tuesday]',
                'working_hours[wednesday][off]',
                'working_hours[wednesday][has_lunch_time]',
                'working_hours[wednesday]',
                'working_hours[thursday][off]',
                'working_hours[thursday][has_lunch_time]',
                'working_hours[thursday]',
                'working_hours[friday][off]',
                'working_hours[friday][has_lunch_time]',
                'working_hours[friday]',
                'working_hours[saturday][off]',
                'working_hours[saturday][has_lunch_time]',
                'working_hours[saturday]'
            ]
        ];

        foreach ($disabledForStores as $fieldset => $fields) {
            $result = [];
            foreach ($fields as $field) {
                $result[$field] = [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'disabled' => true
                            ],
                        ],
                    ],
                ];
            }
            $metaDisableForStoreEdit[$fieldset] = ['children' => $result];
        }

        $metaDisableForStoreEdit['products'] = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'disabled' => true
                    ],
                ],
            ],
        ];

        $requiredEntry['general'] = [
            'children' => [
                'name' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'validation' => [
                                    'required-entry' => false
                                ]
                            ]
                        ],
                    ],
                ],
            ],
        ];

        $requiredEntry['address_tab'] = [
            'children' => [
                'city'    => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'validation' => [
                                    'required-entry' => false
                                ]
                            ],
                        ],
                    ],
                ],
                'address' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'validation' => [
                                    'required-entry' => false
                                ]
                            ],
                        ],
                    ],
                ],
            ],
        ];

        return array_merge_recursive($requiredEntry, $metaDisableForStoreEdit);
    }

    /**
     * @return array
     */
    protected function getDefaultValues()
    {
        $defaultValuesFor = [
            'general'     => ['name', 'description'],
            'address_tab' => ['city', 'address'],
            'page'        => ['meta_title', 'meta_description', 'meta_keywords', 'meta_robots']
        ];

        $defaultValues = [];

        foreach ($defaultValuesFor as $fieldset => $fields) {
            $result = [];
            foreach ($fields as $field) {
                $result[$field] = [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'notice' => 'Default value: ' . $this->getDefaultLocation()->getData($field)
                            ],
                        ],
                    ],
                ];
            }
            $defaultValues[$fieldset] = ['children' => $result];
        }

        return $defaultValues;
    }
}

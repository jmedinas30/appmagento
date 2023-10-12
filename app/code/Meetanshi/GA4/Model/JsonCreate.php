<?php

namespace Meetanshi\GA4\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Meetanshi\GA4\Helper\Data;
use Magento\Framework\Model\AbstractModel;

/**
 * Class JsonCreate
 * @package Meetanshi\GA4\Model
 */
class JsonCreate extends AbstractModel
{
    /**
     * @var Filesystem
     */
    protected $filesystem;
    /**
     * @var string
     */
    protected $jsonFileName;
    /**
     * @var Data
     */
    protected $helper;

    /**
     * JsonCreate constructor.
     * @param Context $context
     * @param Registry $registry
     * @param Filesystem $filesystem
     * @param Data $helper
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Filesystem $filesystem,
        Data $helper
    )
    {
        $this->filesystem = $filesystem;
        $this->helper = $helper;
        $this->jsonFileName = 'ga4Json' . DIRECTORY_SEPARATOR . 'GA4Import.json';

        parent::__construct($context, $registry);
    }

    /**
     * @param $accountId
     * @param $containerId
     * @param $uaTrackingId
     * @param $publicId
     * @return bool
     */
    public function generateJsonData(
        $accountId,
        $containerId,
        $uaTrackingId,
        $publicId
    )
    {
        $fingerprint = time();

        try {
            $jsonArray = [
                "path" => "accounts/$accountId/containers/$containerId/versions/0",
                "accountId" => "$accountId",
                "containerId" => "$containerId",
                "containerVersionId" => "0",
                "container" => [
                    "path" => "accounts/$accountId/containers/$containerId",
                    "accountId" => "$accountId",
                    "containerId" => "$containerId",
                    "name" => "Meetanshi_GoogleTagManager_JsonExport",
                    "publicId" => "$publicId",
                    "usageContext" => [
                        "WEB"
                    ],
                    "fingerprint" => $fingerprint,
                    "tagManagerUrl" => "https://tagmanager.google.com/#/container/accounts/$accountId/containers/$containerId/workspaces?apiLink=container"
                ],
                "builtInVariable" => [
                    [
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "type" => "PAGE_URL",
                        "name" => "Page URL"
                    ],
                    [
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "type" => "PAGE_HOSTNAME",
                        "name" => "Page Hostname"
                    ],
                    [
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "type" => "PAGE_PATH",
                        "name" => "Page Path"
                    ],
                    [
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "type" => "REFERRER",
                        "name" => "Referrer"
                    ],
                    [
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "type" => "EVENT",
                        "name" => "Event"
                    ]
                ],
                "variable" => [
                    [
                        "name" => "ME - MEASUREMENT ID",
                        "type" => "c",
                        "parameter" => [
                            [
                                "type" => "TEMPLATE",
                                "key" => "value",
                                "value" => "$uaTrackingId"
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "variableId" => 1,
                        "fingerprint" => $fingerprint,
                        "formatValue" => new \stdClass()
                    ],
                    [
                        "name" => "ME - GA4 - Page Type",
                        "type" => "v",
                        "parameter" => [
                            [
                                "type" => "INTEGER",
                                "key" => "dataLayerVersion",
                                "value" => "2"
                            ],
                            [
                                "type" => "BOOLEAN",
                                "key" => "setDefaultValue",
                                "value" => "false"
                            ],
                            [
                                "type" => "TEMPLATE",
                                "key" => "name",
                                "value" => "pageType"
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "variableId" => 2,
                        "fingerprint" => $fingerprint,
                        "formatValue" => new \stdClass()
                    ],
                    [
                        "name" => "ME - GA4 - ecommerce.items",
                        "type" => "v",
                        "parameter" => [
                            [
                                "type" => "INTEGER",
                                "key" => "dataLayerVersion",
                                "value" => "2"
                            ],
                            [
                                "type" => "BOOLEAN",
                                "key" => "setDefaultValue",
                                "value" => "false"
                            ],
                            [
                                "type" => "TEMPLATE",
                                "key" => "name",
                                "value" => "ecommerce.items"
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "variableId" => 3,
                        "fingerprint" => $fingerprint,
                        "formatValue" => new \stdClass()
                    ],
                    [
                        "name" => "ME - GA4 - customerId",
                        "type" => "v",
                        "parameter" => [
                            [
                                "type" => "INTEGER",
                                "key" => "dataLayerVersion",
                                "value" => "2"
                            ],
                            [
                                "type" => "BOOLEAN",
                                "key" => "setDefaultValue",
                                "value" => "false"
                            ],
                            [
                                "type" => "TEMPLATE",
                                "key" => "name",
                                "value" => "customerId"
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "variableId" => 6,
                        "fingerprint" => $fingerprint,
                        "formatValue" => new \stdClass()
                    ],
                    [
                        "name" => "ME - GA4 - customerGroup",
                        "type" => "v",
                        "parameter" => [
                            [
                                "type" => "INTEGER",
                                "key" => "dataLayerVersion",
                                "value" => "2"
                            ],
                            [
                                "type" => "BOOLEAN",
                                "key" => "setDefaultValue",
                                "value" => "false"
                            ],
                            [
                                "type" => "TEMPLATE",
                                "key" => "name",
                                "value" => "customerGroup"
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "variableId" => 7,
                        "fingerprint" => $fingerprint,
                        "formatValue" => new \stdClass()
                    ],
                    [
                        "name" => "ME - GA4 - transaction_id",
                        "type" => "v",
                        "parameter" => [
                            [
                                "type" => "INTEGER",
                                "key" => "dataLayerVersion",
                                "value" => "2"
                            ],
                            [
                                "type" => "BOOLEAN",
                                "key" => "setDefaultValue",
                                "value" => "false"
                            ],
                            [
                                "type" => "TEMPLATE",
                                "key" => "name",
                                "value" => "ecommerce.transaction_id"
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "variableId" => 8,
                        "fingerprint" => $fingerprint,
                        "formatValue" => new \stdClass()
                    ],
                    [
                        "name" => "ME - GA4 - coupon",
                        "type" => "v",
                        "parameter" => [
                            [
                                "type" => "INTEGER",
                                "key" => "dataLayerVersion",
                                "value" => "2"
                            ],
                            [
                                "type" => "BOOLEAN",
                                "key" => "setDefaultValue",
                                "value" => "false"
                            ],
                            [
                                "type" => "TEMPLATE",
                                "key" => "name",
                                "value" => "ecommerce.coupon"
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "variableId" => 9,
                        "fingerprint" => $fingerprint,
                        "formatValue" => new \stdClass()
                    ],
                    [
                        "name" => "ME - GA4 - tax",
                        "type" => "v",
                        "parameter" => [
                            [
                                "type" => "INTEGER",
                                "key" => "dataLayerVersion",
                                "value" => "2"
                            ],
                            [
                                "type" => "BOOLEAN",
                                "key" => "setDefaultValue",
                                "value" => "false"
                            ],
                            [
                                "type" => "TEMPLATE",
                                "key" => "name",
                                "value" => "ecommerce.tax"
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "variableId" => 10,
                        "fingerprint" => $fingerprint,
                        "formatValue" => new \stdClass()
                    ],
                    [
                        "name" => "ME - GA4 - shipping",
                        "type" => "v",
                        "parameter" => [
                            [
                                "type" => "INTEGER",
                                "key" => "dataLayerVersion",
                                "value" => "2"
                            ],
                            [
                                "type" => "BOOLEAN",
                                "key" => "setDefaultValue",
                                "value" => "false"
                            ],
                            [
                                "type" => "TEMPLATE",
                                "key" => "name",
                                "value" => "ecommerce.shipping"
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "variableId" => 11,
                        "fingerprint" => $fingerprint,
                        "formatValue" => new \stdClass()
                    ],
                    [
                        "name" => "ME - GA4 - Shipping Type",
                        "type" => "v",
                        "parameter" => [
                            [
                                "type" => "INTEGER",
                                "key" => "dataLayerVersion",
                                "value" => "2"
                            ],
                            [
                                "type" => "BOOLEAN",
                                "key" => "setDefaultValue",
                                "value" => "false"
                            ],
                            [
                                "type" => "TEMPLATE",
                                "key" => "name",
                                "value" => "ecommerce.shipping_tier"
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "variableId" => 12,
                        "fingerprint" => $fingerprint,
                        "formatValue" => new \stdClass()
                    ],
                    [
                        "name" => "ME - GA4 - Payment Type",
                        "type" => "v",
                        "parameter" => [
                            [
                                "type" => "INTEGER",
                                "key" => "dataLayerVersion",
                                "value" => "2"
                            ],
                            [
                                "type" => "BOOLEAN",
                                "key" => "setDefaultValue",
                                "value" => "false"
                            ],
                            [
                                "type" => "TEMPLATE",
                                "key" => "name",
                                "value" => "ecommerce.payment_type"
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "variableId" => 13,
                        "fingerprint" => $fingerprint,
                        "formatValue" => new \stdClass()
                    ],
                    [
                        "name" => "ME - GA4 - currency",
                        "type" => "v",
                        "parameter" => [
                            [
                                "type" => "INTEGER",
                                "key" => "dataLayerVersion",
                                "value" => "2"
                            ],
                            [
                                "type" => "BOOLEAN",
                                "key" => "setDefaultValue",
                                "value" => "false"
                            ],
                            [
                                "type" => "TEMPLATE",
                                "key" => "name",
                                "value" => "ecommerce.currency"
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "variableId" => 14,
                        "fingerprint" => $fingerprint,
                        "formatValue" => new \stdClass()
                    ],
                    [
                        "name" => "ME - GA4 - affiliation",
                        "type" => "v",
                        "parameter" => [
                            [
                                "type" => "INTEGER",
                                "key" => "dataLayerVersion",
                                "value" => "2"
                            ],
                            [
                                "type" => "BOOLEAN",
                                "key" => "setDefaultValue",
                                "value" => "false"
                            ],
                            [
                                "type" => "TEMPLATE",
                                "key" => "name",
                                "value" => "ecommerce.affiliation"
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "variableId" => 15,
                        "fingerprint" => $fingerprint,
                        "formatValue" => new \stdClass()
                    ],
                    [
                        "name" => "ME - GA4 - Purchase Value",
                        "type" => "v",
                        "parameter" => [
                            [
                                "type" => "INTEGER",
                                "key" => "dataLayerVersion",
                                "value" => "2"
                            ],
                            [
                                "type" => "BOOLEAN",
                                "key" => "setDefaultValue",
                                "value" => "false"
                            ],
                            [
                                "type" => "TEMPLATE",
                                "key" => "name",
                                "value" => "ecommerce.value"
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "variableId" => 17,
                        "fingerprint" => $fingerprint,
                        "formatValue" => new \stdClass()
                    ]
                ],
                "trigger" => [
                    [
                        "name" => "ME - GA4 - gtm.dom",
                        "type" => "CUSTOM_EVENT",
                        "customEventFilter" => [
                            [
                                "type" => "EQUALS",
                                "parameter" => [
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg0",
                                        "value" => "{{_event}}"
                                    ],
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg1",
                                        "value" => "gtm.dom"
                                    ]
                                ]
                            ]
                        ],
                        "filter" => [
                            [
                                "type" => "EQUALS",
                                "parameter" => [
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg0",
                                        "value" => "{{Event}}"
                                    ],
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg1",
                                        "value" => "gtm.dom"
                                    ]
                                ]
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "triggerId" => 1,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - select_item",
                        "type" => "CUSTOM_EVENT",
                        "customEventFilter" => [
                            [
                                "type" => "EQUALS",
                                "parameter" => [
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg0",
                                        "value" => "{{_event}}"
                                    ],
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg1",
                                        "value" => "select_item"
                                    ]
                                ]
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "triggerId" => 2,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - add_to_cart",
                        "type" => "CUSTOM_EVENT",
                        "customEventFilter" => [
                            [
                                "type" => "EQUALS",
                                "parameter" => [
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg0",
                                        "value" => "{{_event}}"
                                    ],
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg1",
                                        "value" => "add_to_cart"
                                    ]
                                ]
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "triggerId" => 3,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - remove_from_cart",
                        "type" => "CUSTOM_EVENT",
                        "customEventFilter" => [
                            [
                                "type" => "EQUALS",
                                "parameter" => [
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg0",
                                        "value" => "{{_event}}"
                                    ],
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg1",
                                        "value" => "remove_from_cart"
                                    ]
                                ]
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "triggerId" => 4,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - select_promotion",
                        "type" => "CUSTOM_EVENT",
                        "customEventFilter" => [
                            [
                                "type" => "EQUALS",
                                "parameter" => [
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg0",
                                        "value" => "{{_event}}"
                                    ],
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg1",
                                        "value" => "select_promotion"
                                    ]
                                ]
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "triggerId" => 5,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - begin_checkout",
                        "type" => "CUSTOM_EVENT",
                        "customEventFilter" => [
                            [
                                "type" => "EQUALS",
                                "parameter" => [
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg0",
                                        "value" => "{{_event}}"
                                    ],
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg1",
                                        "value" => "begin_checkout"
                                    ]
                                ]
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "triggerId" => 6,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - view_item_list",
                        "type" => "CUSTOM_EVENT",
                        "customEventFilter" => [
                            [
                                "type" => "EQUALS",
                                "parameter" => [
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg0",
                                        "value" => "{{_event}}"
                                    ],
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg1",
                                        "value" => "view_item_list"
                                    ]
                                ]
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "triggerId" => 7,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - view_item",
                        "type" => "CUSTOM_EVENT",
                        "customEventFilter" => [
                            [
                                "type" => "EQUALS",
                                "parameter" => [
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg0",
                                        "value" => "{{_event}}"
                                    ],
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg1",
                                        "value" => "view_item"
                                    ]
                                ]
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "triggerId" => 8,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - view_promotion",
                        "type" => "CUSTOM_EVENT",
                        "customEventFilter" => [
                            [
                                "type" => "EQUALS",
                                "parameter" => [
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg0",
                                        "value" => "{{_event}}"
                                    ],
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg1",
                                        "value" => "view_promotion"
                                    ]
                                ]
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "triggerId" => 9,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - purchase",
                        "type" => "CUSTOM_EVENT",
                        "customEventFilter" => [
                            [
                                "type" => "EQUALS",
                                "parameter" => [
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg0",
                                        "value" => "{{_event}}"
                                    ],
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg1",
                                        "value" => "purchase"
                                    ]
                                ]
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "triggerId" => 10,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - shipping info",
                        "type" => "CUSTOM_EVENT",
                        "customEventFilter" => [
                            [
                                "type" => "EQUALS",
                                "parameter" => [
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg0",
                                        "value" => "{{_event}}"
                                    ],
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg1",
                                        "value" => "add_shipping_info"
                                    ]
                                ]
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "triggerId" => 11,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - add_payment_info info",
                        "type" => "CUSTOM_EVENT",
                        "customEventFilter" => [
                            [
                                "type" => "EQUALS",
                                "parameter" => [
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg0",
                                        "value" => "{{_event}}"
                                    ],
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg1",
                                        "value" => "add_payment_info"
                                    ]
                                ]
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "triggerId" => 12,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - view_cart",
                        "type" => "CUSTOM_EVENT",
                        "customEventFilter" => [
                            [
                                "type" => "EQUALS",
                                "parameter" => [
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg0",
                                        "value" => "{{_event}}"
                                    ],
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg1",
                                        "value" => "view_cart"
                                    ]
                                ]
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "triggerId" => 13,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - add_to_wishlist",
                        "type" => "CUSTOM_EVENT",
                        "customEventFilter" => [
                            [
                                "type" => "EQUALS",
                                "parameter" => [
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg0",
                                        "value" => "{{_event}}"
                                    ],
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg1",
                                        "value" => "add_to_wishlist"
                                    ]
                                ]
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "triggerId" => 14,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - add_to_compare",
                        "type" => "CUSTOM_EVENT",
                        "customEventFilter" => [
                            [
                                "type" => "EQUALS",
                                "parameter" => [
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg0",
                                        "value" => "{{_event}}"
                                    ],
                                    [
                                        "type" => "TEMPLATE",
                                        "key" => "arg1",
                                        "value" => "add_to_compare"
                                    ]
                                ]
                            ]
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "triggerId" => 15,
                        "fingerprint" => $fingerprint
                    ]
                ],
                "tag" => [
                    [
                        "name" => "ME - GA4 Debug",
                        "firingTriggerId" => [
                            "2147479553"
                        ],
                        "tagFiringOption" => "ONCE_PER_EVENT",
                        "type" => "gaawc",
                        "parameter" => [
                            [
                                "type" => "LIST",
                                "key" => "fieldsToSet",
                                "list" => [
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "debug_mode"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "true"
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "type" => "BOOLEAN",
                                "key" => "sendPageView",
                                "value" => "true"
                            ],
                            [
                                "type" => "TEMPLATE",
                                "key" => "measurementId",
                                "value" => "{{ME - MEASUREMENT ID}}"
                            ]
                        ],
                        "monitoringMetadata" => [
                            "type" => "MAP"
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "tagId" => 1,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4",
                        "firingTriggerId" => [
                            "2147479553"
                        ],
                        "tagFiringOption" => "ONCE_PER_EVENT",
                        "type" => "gaawc",
                        "parameter" => [
                            [
                                "type" => "BOOLEAN",
                                "key" => "sendPageView",
                                "value" => "true"
                            ],
                            [
                                "type" => "TEMPLATE",
                                "key" => "measurementId",
                                "value" => "{{ME - MEASUREMENT ID}}"
                            ]
                        ],
                        "monitoringMetadata" => [
                            "type" => "MAP"
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "tagId" => 2,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - item list views/impressions",
                        "firingTriggerId" => [
                            7
                        ],
                        "tagFiringOption" => "ONCE_PER_EVENT",
                        "type" => "gaawe",
                        "parameter" => [
                            [
                                "type" => "LIST",
                                "key" => "userProperties",
                                "list" => [
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "customerGroup"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - customerGroup}}"
                                            ]
                                        ]
                                    ],
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "customerId"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - customerId}}"
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "type" => "TEMPLATE",
                                "key" => "eventName",
                                "value" => "view_item_list"
                            ],
                            [
                                "type" => "LIST",
                                "key" => "eventParameters",
                                "list" => [
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "items"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - ecommerce.items}}"
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "type" => "TAG_REFERENCE",
                                "key" => "measurementId",
                                "value" => "ME - GA4"
                            ]
                        ],
                        "monitoringMetadata" => [
                            "type" => "MAP"
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "tagId" => 3,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - product/item list clicks",
                        "firingTriggerId" => [
                            2
                        ],
                        "tagFiringOption" => "ONCE_PER_EVENT",
                        "type" => "gaawe",
                        "parameter" => [
                            [
                                "type" => "LIST",
                                "key" => "userProperties",
                                "list" => [
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "customerGroup"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - customerGroup}}"
                                            ]
                                        ]
                                    ],
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "customerId"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - customerId}}"
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "type" => "TEMPLATE",
                                "key" => "eventName",
                                "value" => "select_item"
                            ],
                            [
                                "type" => "LIST",
                                "key" => "eventParameters",
                                "list" => [
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "items"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - ecommerce.items}}"
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "type" => "TAG_REFERENCE",
                                "key" => "measurementId",
                                "value" => "ME - GA4"
                            ]
                        ],
                        "monitoringMetadata" => [
                            "type" => "MAP"
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "tagId" => 4,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - add to cart",
                        "firingTriggerId" => [
                            3
                        ],
                        "tagFiringOption" => "ONCE_PER_EVENT",
                        "type" => "gaawe",
                        "parameter" => [
                            [
                                "type" => "LIST",
                                "key" => "userProperties",
                                "list" => [
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "customerGroup"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - customerGroup}}"
                                            ]
                                        ]
                                    ],
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "customerId"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - customerId}}"
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "type" => "TEMPLATE",
                                "key" => "eventName",
                                "value" => "add_to_cart"
                            ],
                            [
                                "type" => "LIST",
                                "key" => "eventParameters",
                                "list" => [
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "items"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - ecommerce.items}}"
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "type" => "TAG_REFERENCE",
                                "key" => "measurementId",
                                "value" => "ME - GA4"
                            ]
                        ],
                        "monitoringMetadata" => [
                            "type" => "MAP"
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "tagId" => 5,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - remove from cart",
                        "firingTriggerId" => [
                            4
                        ],
                        "tagFiringOption" => "ONCE_PER_EVENT",
                        "type" => "gaawe",
                        "parameter" => [
                            [
                                "type" => "LIST",
                                "key" => "userProperties",
                                "list" => [
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "customerGroup"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - customerGroup}}"
                                            ]
                                        ]
                                    ],
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "customerId"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - customerId}}"
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "type" => "TEMPLATE",
                                "key" => "eventName",
                                "value" => "remove_from_cart"
                            ],
                            [
                                "type" => "LIST",
                                "key" => "eventParameters",
                                "list" => [
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "items"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - ecommerce.items}}"
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "type" => "TAG_REFERENCE",
                                "key" => "measurementId",
                                "value" => "ME - GA4"
                            ]
                        ],
                        "monitoringMetadata" => [
                            "type" => "MAP"
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "tagId" => 6,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - item views/impressions",
                        "firingTriggerId" => [
                            8
                        ],
                        "tagFiringOption" => "ONCE_PER_EVENT",
                        "type" => "gaawe",
                        "parameter" => [
                            [
                                "type" => "LIST",
                                "key" => "userProperties",
                                "list" => [
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "customerGroup"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - customerGroup}}"
                                            ]
                                        ]
                                    ],
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "customerId"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - customerId}}"
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "type" => "TEMPLATE",
                                "key" => "eventName",
                                "value" => "view_item"
                            ],
                            [
                                "type" => "LIST",
                                "key" => "eventParameters",
                                "list" => [
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "items"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - ecommerce.items}}"
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "type" => "TAG_REFERENCE",
                                "key" => "measurementId",
                                "value" => "ME - GA4"
                            ]
                        ],
                        "monitoringMetadata" => [
                            "type" => "MAP"
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "tagId" => 7,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - View Promotion",
                        "firingTriggerId" => [
                            9
                        ],
                        "tagFiringOption" => "ONCE_PER_EVENT",
                        "type" => "gaawe",
                        "parameter" => [
                            [
                                "type" => "TEMPLATE",
                                "key" => "eventName",
                                "value" => "view_promotion"
                            ],
                            [
                                "type" => "LIST",
                                "key" => "eventParameters",
                                "list" => [
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "items"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - ecommerce.items}}"
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "type" => "TAG_REFERENCE",
                                "key" => "measurementId",
                                "value" => "ME - GA4"
                            ]
                        ],
                        "monitoringMetadata" => [
                            "type" => "MAP"
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "tagId" => 8,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - Click Promotion",
                        "firingTriggerId" => [
                            5
                        ],
                        "tagFiringOption" => "ONCE_PER_EVENT",
                        "type" => "gaawe",
                        "parameter" => [
                            [
                                "type" => "TEMPLATE",
                                "key" => "eventName",
                                "value" => "select_promotion"
                            ],
                            [
                                "type" => "LIST",
                                "key" => "eventParameters",
                                "list" => [
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "items"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - ecommerce.items}}"
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "type" => "TAG_REFERENCE",
                                "key" => "measurementId",
                                "value" => "ME - GA4"
                            ]
                        ],
                        "monitoringMetadata" => [
                            "type" => "MAP"
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "tagId" => 9,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - Begin Checkout",
                        "firingTriggerId" => [
                            6
                        ],
                        "tagFiringOption" => "ONCE_PER_EVENT",
                        "type" => "gaawe",
                        "parameter" => [
                            [
                                "type" => "LIST",
                                "key" => "userProperties",
                                "list" => [
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "customerGroup"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - customerGroup}}"
                                            ]
                                        ]
                                    ],
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "customerId"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - customerId}}"
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "type" => "TEMPLATE",
                                "key" => "eventName",
                                "value" => "begin_checkout"
                            ],
                            [
                                "type" => "LIST",
                                "key" => "eventParameters",
                                "list" => [
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "items"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - ecommerce.items}}"
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "type" => "TAG_REFERENCE",
                                "key" => "measurementId",
                                "value" => "ME - GA4"
                            ]
                        ],
                        "monitoringMetadata" => [
                            "type" => "MAP"
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "tagId" => 10,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - Purchase",
                        "firingTriggerId" => [
                            10
                        ],
                        "tagFiringOption" => "ONCE_PER_EVENT",
                        "type" => "gaawe",
                        "parameter" => [
                            [
                                "type" => "LIST",
                                "key" => "userProperties",
                                "list" => [
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "customerGroup"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - customerGroup}}"
                                            ]
                                        ]
                                    ],
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "customerId"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - customerId}}"
                                            ]
                                        ]
                                    ],
                                ]
                            ],
                            [
                                "type" => "TEMPLATE",
                                "key" => "eventName",
                                "value" => "purchase"
                            ],
                            [
                                "type" => "LIST",
                                "key" => "eventParameters",
                                "list" => [
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "items"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - ecommerce.items}}"
                                            ]
                                        ]
                                    ],
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "transaction_id"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - transaction_id}}"
                                            ]
                                        ]
                                    ],
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "affiliation"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - affiliation}}"
                                            ]
                                        ]
                                    ],
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "tax"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - tax}}"
                                            ]
                                        ]
                                    ],
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "shipping"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - shipping}}"
                                            ]
                                        ]
                                    ],
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "currency"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - currency}}"
                                            ]
                                        ]
                                    ],
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "coupon"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - coupon}}"
                                            ]
                                        ]
                                    ],
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "value"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - Purchase Value}}"
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "type" => "TAG_REFERENCE",
                                "key" => "measurementId",
                                "value" => "ME - GA4"
                            ]
                        ],
                        "monitoringMetadata" => [
                            "type" => "MAP"
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "tagId" => 11,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - Shipping Info",
                        "firingTriggerId" => [
                            11
                        ],
                        "tagFiringOption" => "ONCE_PER_EVENT",
                        "type" => "gaawe",
                        "parameter" => [
                            [
                                "type" => "TEMPLATE",
                                "key" => "eventName",
                                "value" => "add_shipping_info"
                            ],
                            [
                                "type" => "LIST",
                                "key" => "eventParameters",
                                "list" => [
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "items"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - ecommerce.items}}"
                                            ]
                                        ]
                                    ],
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "shipping_tier"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - Shipping Type}}"
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "type" => "TAG_REFERENCE",
                                "key" => "measurementId",
                                "value" => "ME - GA4"
                            ]
                        ],
                        "monitoringMetadata" => [
                            "type" => "MAP"
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "tagId" => 12,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - Payment Info",
                        "firingTriggerId" => [
                            12
                        ],
                        "tagFiringOption" => "ONCE_PER_EVENT",
                        "type" => "gaawe",
                        "parameter" => [
                            [
                                "type" => "TEMPLATE",
                                "key" => "eventName",
                                "value" => "add_payment_info"
                            ],
                            [
                                "type" => "LIST",
                                "key" => "eventParameters",
                                "list" => [
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "items"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - ecommerce.items}}"
                                            ]
                                        ]
                                    ],
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "payment_type"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - Payment Type}}"
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "type" => "TAG_REFERENCE",
                                "key" => "measurementId",
                                "value" => "ME - GA4"
                            ]
                        ],
                        "monitoringMetadata" => [
                            "type" => "MAP"
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "tagId" => 13,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - View Cart",
                        "firingTriggerId" => [
                            13
                        ],
                        "tagFiringOption" => "ONCE_PER_EVENT",
                        "type" => "gaawe",
                        "parameter" => [
                            [
                                "type" => "TEMPLATE",
                                "key" => "eventName",
                                "value" => "view_cart"
                            ],
                            [
                                "type" => "LIST",
                                "key" => "eventParameters",
                                "list" => [
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "currency"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - currency}}"
                                            ]
                                        ]
                                    ],
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "value"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - Purchase Value}}"
                                            ]
                                        ]
                                    ],
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "items"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - ecommerce.items}}"
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "type" => "TAG_REFERENCE",
                                "key" => "measurementId",
                                "value" => "ME - GA4"
                            ]
                        ],
                        "monitoringMetadata" => [
                            "type" => "MAP"
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "tagId" => 14,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - WishList",
                        "firingTriggerId" => [
                            14
                        ],
                        "tagFiringOption" => "ONCE_PER_EVENT",
                        "type" => "gaawe",
                        "parameter" => [
                            [
                                "type" => "TEMPLATE",
                                "key" => "eventName",
                                "value" => "add_to_wishlist"
                            ],
                            [
                                "type" => "LIST",
                                "key" => "eventParameters",
                                "list" => [
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "items"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - ecommerce.items}}"
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "type" => "TAG_REFERENCE",
                                "key" => "measurementId",
                                "value" => "ME - GA4"
                            ]
                        ],
                        "monitoringMetadata" => [
                            "type" => "MAP"
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "tagId" => 15,
                        "fingerprint" => $fingerprint
                    ],
                    [
                        "name" => "ME - GA4 - CompareList",
                        "firingTriggerId" => [
                            15
                        ],
                        "tagFiringOption" => "ONCE_PER_EVENT",
                        "type" => "gaawe",
                        "parameter" => [
                            [
                                "type" => "TEMPLATE",
                                "key" => "eventName",
                                "value" => "add_to_compare"
                            ],
                            [
                                "type" => "LIST",
                                "key" => "eventParameters",
                                "list" => [
                                    [
                                        "type" => "MAP",
                                        "map" => [
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "name",
                                                "value" => "items"
                                            ],
                                            [
                                                "type" => "TEMPLATE",
                                                "key" => "value",
                                                "value" => "{{ME - GA4 - ecommerce.items}}"
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "type" => "TAG_REFERENCE",
                                "key" => "measurementId",
                                "value" => "ME - GA4"
                            ]
                        ],
                        "monitoringMetadata" => [
                            "type" => "MAP"
                        ],
                        "accountId" => "$accountId",
                        "containerId" => "$containerId",
                        "tagId" => 16,
                        "fingerprint" => $fingerprint
                    ]
                ],
                "fingerprint" => $fingerprint
            ];

            $jsonOptions = [
                "exportFormatVersion" => 2,
                "exportTime" => date("Y-m-d h:i:s"),
                "containerVersion" => $jsonArray,
                "fingerprint" => $fingerprint,
                "tagManagerUrl" => "https://tagmanager.google.com/#/versions/accounts/$accountId/containers/$containerId/versions/0?apiLink=version"
            ];

            $jsonExportDir = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);
            $jsonExportDir->writeFile($this->jsonFileName, "");
            $jsonExportDir->writeFile($this->jsonFileName, json_encode($jsonOptions, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        } catch (\Exception $e) {
            $this->helper->logMessage($e->getMessage());
        }
        return true;
    }

    /**
     * @return string
     */
    public function getJsonContent()
    {
        try {
            $jsonExportDir = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
            return $jsonExportDir->readFile($this->jsonFileName);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
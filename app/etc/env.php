<?php
return [
    'backend' => [
        'frontName' => 'a6c823a07f_admin'
    ],
    'crypt' => [
        'key' => 'QuELsRU3OpBeWSjaMEfyJT3RDBSS7HJU'
    ],
    'db' => [
        'table_prefix' => '',
        'connection' => [
            'default' => [
                'host' => 'localhost',
                'dbname' => 'ac908f41_M243',
                'username' => 'ac908f41_M243',
                'password' => 'SecureInfersDodgeDemote',
                'active' => '1',
                'driver_options' => [
                    1014 => false
                ],
                'model' => 'mysql4',
                'engine' => 'innodb',
                'initStatements' => 'SET NAMES utf8;'
            ]
        ]
    ],
    'resource' => [
        'default_setup' => [
            'connection' => 'default'
        ]
    ],
    'x-frame-options' => 'SAMEORIGIN',
    'MAGE_MODE' => 'developer',
    'session' => [
        'save' => 'redis',
        'redis' => [
            'host' => '/var/run/redis-multi-ac908f41.redis/redis.sock',
            'port' => '0',
            'password' => '',
            'timeout' => '6.5',
            'persistent_identifier' => '',
            'database' => '2',
            'compression_threshold' => '2048',
            'compression_library' => 'gzip',
            'log_level' => '4',
            'max_concurrency' => '25',
            'break_after_frontend' => '30',
            'break_after_adminhtml' => '30',
            'first_lifetime' => '600',
            'bot_first_lifetime' => '60',
            'bot_lifetime' => '7200',
            'disable_locking' => '0',
            'min_lifetime' => '60',
            'max_lifetime' => '2592000'
        ]
    ],
    'cache' => [
        'frontend' => [
            'default' => [
                'backend' => 'Cm_Cache_Backend_Redis',
                'backend_options' => [
                    'server' => '/var/run/redis-multi-ac908f41.redis/redis.sock',
                    'database' => '1',
                    'port' => '0',
                    'password' => ''
                ]
            ],
            'page_cache' => [
                'backend' => 'Cm_Cache_Backend_Redis',
                'backend_options' => [
                    'server' => '/var/run/redis-multi-ac908f41.redis/redis.sock',
                    'database' => '0',
                    'port' => '0',
                    'password' => '',
                    'compress_data' => '0'
                ]
            ]
        ],
        'allow_parallel_generation' => false
    ],
    'cache_types' => [
        'config' => 1,
        'layout' => 1,
        'block_html' => 1,
        'collections' => 1,
        'reflection' => 1,
        'db_ddl' => 1,
        'compiled_config' => 1,
        'eav' => 1,
        'customer_notification' => 1,
        'config_integration' => 1,
        'config_integration_api' => 1,
        'google_product' => 1,
        'full_page' => 1,
        'config_webservice' => 1,
        'translate' => 1,
        'vertex' => 1
    ],
    'lock' => [
        'provider' => 'db',
        'config' => [
            'prefix' => ''
        ]
    ],
    'downloadable_domains' => [
        'phantom.pe'
    ],
    'directories' => [
        'document_root_is_pub' => true
    ],
    'install' => [
        'date' => 'Mon, 04 Sep 2023 11:46:44 -0400'
    ]
];

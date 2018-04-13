<?php
/**
 * config_data
 *
 * 数据存储相关配置
 *
 * @package config
 */
return [
    'common_master' => [
        'sType' => 'mysql',
        'sDsn' => 'mysql:host=127.0.0.1;dbname=common_db;port=3255',
        'sUserName' => 'username',
        'sUserPassword' => 'userpassword',
        'aInitSql' => [
            'set names utf8'
        ]
    ],
    'common_slave' => [
        'sType' => 'mysql',
        'sDsn' => 'mysql:host=127.0.0.1;dbname=common_db;port=3255',
        'sUserName' => 'username',
        'sUserPassword' => 'userpassword',
        'aInitSql' => [
            'set names utf8'
        ]
    ],
    'ormcache' => [
        'sType' => 'memcached',
        'aServerList' => [
            [
                '127.0.0.1',
                11211,
                1
            ]
        ]
    ]
];
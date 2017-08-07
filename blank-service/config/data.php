<?php
/**
 * config_data
 *
 * 数据存储相关配置
 *
 * @package config
 */
return [
    'spider_db' => [
        'sType' => 'mysql',
        'sDSN' => 'mysql:host=127.0.0.1;dbname=spider_db;port=3255',
        'sUserName' => 'username',
        'sUserPassword' => 'userpassword',
        'aInitSQL' => [
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
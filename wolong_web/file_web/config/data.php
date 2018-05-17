<?php
/**
 * config_data
 *
 * 数据存储相关配置
 *
 * @package config
 */
return [
    'dfs_master' => [
        'sType' => 'mysql',
        'sDsn' => 'mysql:host=127.0.0.1;dbname=test_db;port=3306',
        'sUserName' => 'username',
        'sUserPassword' => 'userpassword',
        'aInitSql' => [
            'set names utf8'
        ]
    ],
    'dfs_slave' => [
        'sType' => 'mysql',
        'sDsn' => 'mysql:host=127.0.0.1;dbname=test_db;port=3306',
        'sUserName' => 'username',
        'sUserPassword' => 'userpassword',
        'aInitSql' => [
            'set names utf8'
        ]
    ]
];
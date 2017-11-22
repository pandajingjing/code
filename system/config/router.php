<?php
/**
 * config_router
 *
 * 路由相关配置
 *
 * @package config
 */
return [
    'aRouteList' => [
        '\\panda\\controller\\sys\\robot' => [
            '/^\/robot\.txt$/i'
        ],
        '\\panda\\controller\\sys\\phpinfo' => [
            '/^\/phpinfo\/$/i'
        ],
        '\\panda\\controller\\sys\\rpc' => [
            '/^\/rpc\/$/i'
        ],
        '\\app\\controller\\test' => [
            '/^\/(\w+)-(\w+)-(\w+)/i',
            [
                's1',
                's2',
                's3'
            ],
            '/{s1}-{s2}-{s3}'
        ]
    ]
];
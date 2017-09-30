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
        '\\panda\\controller\\sys\\Robot' => [
            '/^\/robot\.txt$/i'
        ],
        '\\panda\\controller\\sys\\PhpInfo' => [
            '/^\/phpinfo\/$/i'
        ],
        '\\panda\\controller\\sys\\Rpc' => [
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
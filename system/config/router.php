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
        'controller_sys_crossdomain' => [
            '/^\/crossdomain\.xml$/i'
        ],
        'controller_sys_robot' => [
            '/^\/robot\.txt$/i'
        ],
        'controller_sys_phpinfo' => [
            '/^\/phpinfo\/$/i'
        ],
        'controller_home_404' => [
            '/^\/favicon\.ico$/i'
        ],
//         '\app\controller\home\home' => [
//             '/^\/$/i'
//         ],
        'controller_app_test' => [
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
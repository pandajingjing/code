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
        'controller_view_preg2' => [
            '/^\/view\/([a-z0-9]{40})\.(.*)/i',
            [
                'sFileKey',
                'sExt'
            ]
        ],
        'controller_view_preg4' => [
            '/^\/view\/([a-z0-9]{40})\/(\d*)x(\d*)\.(jpg|gif|png|bmp)/i',
            [
                'sFileKey',
                'iWidth',
                'iHeight',
                'sExt'
            ]
        ],
        'controller_view_preg51' => [
            '/^\/view\/([a-z0-9]{40})\/(\d*)x(\d*)\_([a-z]+)\.(jpg|gif|png|bmp)/i',
            [
                'sFileKey',
                'iWidth',
                'iHeight',
                'sOpt',
                'sExt'
            ]
        ],
        'controller_view_preg3' => [
            '/^\/view\/([a-z]{1,10})\/([a-z0-9]{40})\.(.*)/i',
            [
                'sBiz',
                'sFileKey',
                'sExt'
            ]
        ],
        'controller_view_preg52' => [
            '/^\/view\/([a-z]{1,10})\/([a-z0-9]{40})\/(\d*)x(\d*)\.(jpg|gif|png|bmp)/i',
            [
                'sBiz',
                'sFileKey',
                'iWidth',
                'iHeight',
                'sExt'
            ]
        ],
        'controller_view_preg6' => [
            '/^\/view\/([a-z]{1,10})\/([a-z0-9]{40})\/(\d*)x(\d*)\_([a-z]+)\.(jpg|gif|png|bmp)/i',
            [
                'sBiz',
                'sFileKey',
                'iWidth',
                'iHeight',
                'sOpt',
                'sExt'
            ]
        ],
        'controller_crossdomain' => [
            '/^\/crossdomain\.xml$/i'
        ],
        'controller_upload' => [
            '/^\/upload\/([a-z]{1,10})\/$/i',
            [
                'sBiz'
            ]
        ]
    ]
];
<?php
/**
 * doc
 *
 * @namespace blank_service\bll
 * @package blank_service_bll
 */
namespace blank_service\bll;

use panda\lib\sys\bll;

/**
 * doc
 */
class doc extends bll
{

    /**
     * 返回文章的章节
     *
     * @return array
     */
    function getChapters()
    {
        return [
            [
                'iIndex' => 1,
                'sTitle' => '服务器及系统环境的相关配置.'
            ],
            [
                'iIndex' => 2,
                'sTitle' => '应用环境的安装.'
            ],
            [
                'iIndex' => 3,
                'sTitle' => '环境各目录说明.'
            ],
            [
                'iIndex' => 4,
                'sTitle' => '获取,新增和编辑引导系统.'
            ],
            [
                'iIndex' => 5,
                'sTitle' => '新增子域名开发步骤.'
            ],
            [
                'iIndex' => 6,
                'sTitle' => '关于IDE配置,个人使用ZendStudio.'
            ],
            [
                'iIndex' => 7,
                'sTitle' => '框架路由和自动加载的规则.'
            ],
            [
                'iIndex' => 8,
                'sTitle' => '新增业务开发步骤.'
            ]
        ];
    }
}
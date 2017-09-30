<?php

/**
 * controller_sys_phpinfo
 *
 * phpinfo controller
 *
 * @package controller_sys
 */
namespace panda\controller\sys;

use panda\lib\controller\Web;

/**
 * controller_sys_phpinfo
 *
 * phpinfo controller
 */
class PhpInfo extends Web
{

    /**
     * 控制器入口函数
     *
     * @return string
     */
    function doRequest()
    {
        $this->addHeader('Content-Type:text/html; charset=utf-8');
        if (PANDA_ENV_NAME == PANDA_ENV_RELEASE) {
            return '\\app\\controller\\home\\NotFound';
        } else {
            return 'app_phpinfo';
        }
    }
}
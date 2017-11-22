<?php
/**
 * phpinfo
 *
 * phpinfo controller
 *
 * @namespace panda\controller\sys
 * @package controller_sys
 */
namespace panda\controller\sys;

use panda\lib\controller\web;

/**
 * phpinfo
 *
 * phpinfo controller
 */
class phpinfo extends web
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
            return '\\app\\controller\\home\\miss';
        } else {
            return 'app_phpinfo';
        }
    }
}
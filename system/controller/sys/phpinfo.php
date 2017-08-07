<?php

/**
 * controller_sys_phpinfo
 *
 * phpinfo controller
 *
 * @package controller_sys
 */

/**
 * controller_sys_phpinfo
 *
 * phpinfo controller
 */
class controller_sys_phpinfo extends lib_controller_web
{

    /**
     * 控制器入口函数
     *
     * @return string|lib_sys_controller
     */
    function doRequest()
    {
        $this->addHeader('Content-Type:text/html; charset=utf-8');
        if (PANDA_ENV_NAME == PANDA_ENV_RELEASE) {
            return 'controller_home_404';
        } else {
            return 'app_phpinfo';
        }
    }
}
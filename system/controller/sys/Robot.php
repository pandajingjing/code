<?php

/**
 * Robot
 *
 * robot controller
 *
 * @package controller_sys
 */
namespace panda\controller\sys;

use panda\lib\controller\Web;

/**
 * Robot
 *
 * robot controller
 */
class Robot extends Web
{

    /**
     * 控制器入口函数
     *
     * @return string|lib_sys_controller
     */
    function doRequest()
    {
        $this->addHeader('Content-Type:text/plain');
        return 'app_robot';
    }
}
<?php
/**
 * robot
 *
 * @namespace panda\controller\sys
 */
namespace panda\controller\sys;

use panda\lib\controller\web;

/**
 * robot
 */
class robot extends web
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
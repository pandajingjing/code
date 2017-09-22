<?php

/**
 * controller_sys_robot
 *
 * robot controller
 *
 * @package controller_sys
 */
namespace panda\controller;
/**
 * controller_sys_robot
 *
 * robot controller
 */
class Robot extends lib_controller_web
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
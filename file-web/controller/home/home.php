<?php

/**
 * controller_home_home
 *
 * 首页控制器
 *
 * @package controller_home
 */

/**
 * controller_home_home
 *
 * 首页控制器
 */
class controller_home_home extends lib_controller_service
{

    /**
     * 控制器入口函数
     *
     * @return string
     */
    function doRequest()
    {
        return $this->returnRow([
            'time' => $this->getVisitTime(),
            'info' => 'this is distribute file storage system.'
        ]);
    }
}
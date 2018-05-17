<?php

/**
 * controller_home_404
 *
 * 404控制器
 *
 * @package controller_home
 */

/**
 * controller_home_home
 *
 * 404控制器
 */
class controller_home_404 extends lib_controller_service
{

    /**
     * 控制器入口函数
     *
     * @return string
     */
    function doRequest()
    {
        $this->addHeader('HTTP/1.1 404 Not Found');
        util_error::initError();
        util_error::addBizError('Page', util_error::TYPE_NOT_FOUND, $this->getParam('sURL', 'router'));
        return $this->returnErrors([
            util_error::getErrors()
        ]);
    }
}
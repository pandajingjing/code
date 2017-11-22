<?php
/**
 * service
 *
 * 内部服务控制器基类
 * @namespace panda\lib\controller
 * @package lib_controller
 */
namespace panda\lib\controller;

use panda\lib\traits\Response;

/**
 * service
 *
 * 内部服务控制器基类
 */
abstract class service extends http
{
    use Response;

    /**
     * 在控制器结束时执行（调度使用）
     *
     * @return void
     */
    function afterRequest()
    {
        $this->addHeader('Content-type: application/json;charset=utf-8');
        parent::afterRequest();
    }
}
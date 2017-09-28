<?php

/**
 * Service
 *
 * 内部服务控制器基类
 *
 * @package lib_sys
 */
namespace panda\lib\controller;

use panda\lib\traits\Response;

/**
 * Service
 *
 * 内部服务控制器基类
 */
abstract class Service extends Http
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
<?php
/**
 * api
 *
 * 外部接口控制器基类
 * @namespace panda\lib\controller
 */
namespace panda\lib\controller;

use panda\lib\traits\response;

/**
 * api
 *
 * 外部接口控制器基类
 */
abstract class api extends service
{
    use response;

    /**
     * 接口字段定义,用于校验文档
     *
     * @var array
     */
    protected $aFields = [];

    /**
     * 在控制器开始时执行（调度使用）
     *
     * 进行数据合法性校验
     *
     * @return void
     */
    function beforeRequest()
    {
        parent::beforeRequest();
        // 检测接口数据
        $this->verify();
    }

    /**
     * 接口数据合法性校验
     */
    protected function verify()
    {}
}
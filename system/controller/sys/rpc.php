<?php
/**
 * rpc
 *
 * rpc controller
 *
 * @namespace panda\controller\sys
 * @package controller_sys
 */
namespace panda\controller\sys;

use panda\lib\controller\http;

/**
 * rpc
 *
 * rpc controller
 */
class rpc extends http
{

    /**
     * 控制器入口函数
     *
     * @return string|lib_sys_controller
     */
    function doRequest()
    {
        return 'service_json';
    }

    /**
     * 在控制器开始时执行（调度使用）
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
     * 校验数据合法性
     *
     * @return void
     */
    protected function verify()
    {
        $sClassName = $this->getParam('class_name', 'get');
        $sFuncName = $this->getParam('func_name', 'get');
        $sParam = $this->getParam('param', 'get');
        $iReqTime = $this->getParam('time', 'get', 'int');
    }
}
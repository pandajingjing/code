<?php

/**
 * controller_sys_rpc
 *
 * rpc controller
 *
 * @package controller_sys
 */
namespace panda\controller;
/**
 * controller_sys_rpc
 *
 * rpc controller
 */
class Rpc extends lib_controller_http
{

    /**
     * 控制器入口函数
     *
     * @return string|lib_sys_controller
     */
    function doRequest()
    {
        if (util_error::isError()) {
            $this->setData('mJData', util_sys_response::returnErrors(util_error::getErrors()));
        } else {
            $this->setData('mJData', util_sys_response::returnOne('this is a rpc request.'));
        }
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
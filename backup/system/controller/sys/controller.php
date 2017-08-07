<?php

/**
 * Controller_Sys_Controller
 * @author jxu
 * @package system_controller_sys
 */
/**
 * Controller_Sys_Controller
 *
 * @author jxu
 */
abstract class Controller_Sys_Controller
{

    /**
     * 构造函数
     */
    function __construct()
    {}

    /**
     * 在控制器开始时执行（调度使用）
     */
    function beforeRequest()
    {}

    /**
     * 在控制器结束时执行（调度使用）
     */
    function afterRequest()
    {}

    /**
     * 控制器入口函数
     */
    abstract function doRequest();

    /**
     * 获取参数
     *
     * @param string $p_sKey            
     * @param string $p_sMethod            
     * @param string $p_sType            
     * @return mix
     */
    protected function getParam($p_sKey, $p_sMethod, $p_sType = '')
    {
        $mValue = Lib_Sys_Var::getInstance()->getParam($p_sKey, $p_sMethod);
        $mValue = Util_Sys_String::trimString(Util_Sys_String::delSlash($mValue));
        if ('' == $p_sType) {
            return $mValue;
        } else {
            if (Util_Sys_String::chkDataType($mValue, $p_sType)) {
                return $mValue;
            } else {
                return null;
            }
        }
    }

    /**
     * 获取请求时间
     *
     * @param boolean $p_bFloat            
     * @return float/int
     */
    protected function getVisitTime($p_bFloat = false)
    {
        return Lib_Sys_Var::getInstance()->getVisitTime($p_bFloat);
    }

    /**
     * 获取当前时间
     *
     * @param boolean $p_bFloat            
     * @return float/int
     */
    protected function getRealTime($p_bFloat = false)
    {
        return Lib_Sys_Var::getInstance()->getVisitTime($p_bFloat);
    }
}
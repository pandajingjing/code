<?php

/**
 * Controller_Sys_Web
 * @author jxu
 * @package system_controller_sys
 */
/**
 * Controller_Sys_Web
 *
 * @author jxu
 */
abstract class Controller_Sys_Web extends Controller_Sys_Controller
{

    /**
     * 内部变量
     *
     * @var array
     */
    protected $_aPri = array(
        'aPageData' => array(),
        'aHeader' => array()
    );

    /**
     * 构造函数
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 在控制器开始时执行（调度使用）
     */
    function beforeRequest()
    {
        parent::beforeRequest();
        // do something
    }

    /**
     * 在控制器结束时执行（调度使用）
     */
    function afterRequest()
    {
        Util_Sys_Cookie::sendCookies();
        // 发送头部信息
        foreach ($this->_aPri['aHeader'] as $aHeader) {
            header($aHeader[0], $aHeader[1], $aHeader[2]);
        }
        parent::afterRequest();
    }

    /**
     * 添加头部信息
     *
     * @param string $p_sValue            
     * @param boolean $p_bReplace            
     * @param int $p_iCode            
     */
    protected function addHeader($p_sValue, $p_bReplace = true, $p_iCode = null)
    {
        $this->_aPri['aHeader'][] = array(
            $p_sValue,
            $p_bReplace,
            $p_iCode
        );
    }

    /**
     * 写Cookie
     *
     * @param string $p_sName            
     * @param string $p_sValue            
     * @param int $p_iLifeTime            
     * @param string $p_sPath            
     */
    protected function setCookie($p_sName, $p_sValue, $p_iLifeTime, $p_sPath = '/')
    {
        Util_Sys_Cookie::setCookie($p_sName, $p_sValue, 0 == $p_iLifeTime ? 0 : $p_iLifeTime + Lib_Sys_Var::getInstance()->getVisitTime(), $p_sPath);
    }

    /**
     * 服务器页面跳转
     *
     * @param string $p_sURL            
     * @param boolean $p_bIsTemp            
     */
    protected function redirectURL($p_sURL, $p_bIsTemp = true)
    {
        $this->addHeader('Location:' . $p_sURL, true, $p_bIsTemp ? 302 : 301);
        $this->afterRequest();
        exit();
    }

    /**
     * 设置Page数据
     *
     * @param string $p_sKey            
     * @param mixed $p_mValue            
     */
    protected function setData($p_sKey, $p_mValue)
    {
        $this->_aPri['aPageData'][$p_sKey] = $p_mValue;
    }

    /**
     * 获取路径
     *
     * @param string $p_sAlias            
     * @param array $p_aData            
     * @return string
     */
    protected function getURL($p_sAlias, $p_aData = array())
    {
        $sDomain = get_config('sWebDomain', 'domain');
        $sPattern = get_config($p_sAlias, 'route');
        return $sDomain . $sPattern;
    }

    /**
     * 获取Page数据（调度使用）
     *
     * @return array
     */
    function getDatas()
    {
        return $this->_aPri['aPageData'];
    }
}
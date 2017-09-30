<?php

/**
 * Web
 *
 * 网页控制器基类
 * @namespace panda\lib\controller
 * @package lib_sys
 */
namespace panda\lib\controller;

use panda\util\sys\Cookie;
use panda\lib\sys\Variable;
use panda\lib\sys\Router;

/**
 * Web
 *
 * 控制器基类
 */
abstract class Web extends Http
{

    /**
     * 在控制器结束时执行（调度使用）
     *
     * @return void
     */
    function afterRequest()
    {
        Cookie::sendCookies();
        parent::afterRequest();
    }

    /**
     * 服务器页面跳转
     *
     * @param string $p_sURL            
     * @param boolean $p_bIsTemp            
     * @return void
     */
    protected function redirectURL($p_sURL, $p_bIsTemp = true)
    {
        $this->addHeader('Location:' . $p_sURL, true, $p_bIsTemp ? 302 : 301);
        $this->afterRequest();
        exit();
    }

    /**
     * 获取当前域名的路径
     *
     * @param string $p_sControllerName            
     * @param array $p_aRouterParam            
     * @return string
     */
    protected function createInURL($p_sControllerName, $p_aRouterParam = [])
    {
        return Variable::getInstance()->getConfig('sSelfSchemeDomain', 'domain') . Router::getInstance()->createUri($p_sControllerName, $p_aRouterParam);
    }

    /**
     * 获取其他域名的路径
     *
     * @param string $p_sDomainKey            
     * @param string $p_sAlias            
     * @param array $p_aRouterParam            
     * @return string
     */
    protected function createOutURL($p_sDomainKey, $p_sAlias, $p_aRouterParam = [])
    {
        return Variable::getInstance()->getConfig($p_sDomainKey, 'domain') . Router::getInstance()->createOutUri($p_sDomainKey, $p_sAlias, $p_aRouterParam);
    }

    /**
     * 设置cookie
     *
     * @param string $p_sName            
     * @param string $p_sValue            
     * @param int $p_iLifeTime            
     * @param string $p_sPath            
     * @return void
     */
    protected function setCookie($p_sName, $p_sValue, $p_iLifeTime, $p_sPath = '/')
    {
        $iExpireTime = 0 == $p_iLifeTime ? 0 : $this->getVisitTime() + $p_iLifeTime;
        $sDomain = Variable::getInstance()->getConfig('sCookieDomain', 'domain');
        Cookie::setCookie($p_sName, $p_sValue, $iExpireTime, $p_sPath, $sDomain);
    }
}
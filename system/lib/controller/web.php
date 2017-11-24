<?php
/**
 * web
 *
 * 网页控制器基类
 * @namespace panda\lib\controller
 * @package lib_controller
 */
namespace panda\lib\controller;

use panda\util\sys\cookie;
use panda\lib\sys\variable;
use panda\lib\sys\router;

/**
 * web
 *
 * 控制器基类
 */
abstract class web extends http
{

    /**
     * 在控制器结束时执行（调度使用）
     *
     * @return void
     */
    function afterRequest()
    {
        cookie::sendCookies();
        parent::afterRequest();
    }

    /**
     * 服务器页面跳转
     *
     * @param string $p_sUrl            
     * @param boolean $p_bIsTemp            
     * @return void
     */
    protected function redirectUrl($p_sUrl, $p_bIsTemp = true)
    {
        $this->addHeader('Location:' . $p_sUrl, true, $p_bIsTemp ? 302 : 301);
        $this->afterRequest();
        exit();
    }

    /**
     * 获取当前域名的路径
     *
     * @param string $p_sControllerName            
     * @param array $p_aRouterParam            
     * @param string $p_sAnchor            
     * @return string
     */
    protected function createInUrl($p_sControllerName, $p_aRouterParam = [], $p_sAnchor = '')
    {
        return variable::getInstance()->getConfig('sSelfSchemeDomain', 'domain') . router::getInstance()->createUri($p_sControllerName, $p_aRouterParam, $p_sAnchor);
    }

    /**
     * 获取其他域名的路径
     *
     * @param string $p_sDomainKey            
     * @param string $p_sAlias            
     * @param array $p_aRouterParam            
     * @param string $p_sAnchor            
     * @return string
     */
    protected function createOutUrl($p_sDomainKey, $p_sAlias, $p_aRouterParam = [], $p_sAnchor = '')
    {
        return variable::getInstance()->getConfig($p_sDomainKey, 'domain') . router::getInstance()->createOutUri($p_sDomainKey, $p_sAlias, $p_aRouterParam, $p_sAnchor);
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
        $sDomain = variable::getInstance()->getConfig('sCookieDomain', 'domain');
        cookie::setCookie($p_sName, $p_sValue, $iExpireTime, $p_sPath, $sDomain);
    }
}
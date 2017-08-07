<?php

/**
 * Lib_Sys_Route
 * @author jxu
 * @package system_lib_sys
 */

/**
 * 系统路由
 *
 * @author jxu
 *        
 */
class Lib_Sys_Route
{

    /**
     * 实例自身
     *
     * @var object
     */
    private static $_oInstance = null;

    /**
     * 路由参数
     *
     * @var array
     */
    private $_aRouteParams = array();

    /**
     * 控制器
     *
     * @var string
     */
    private $_sControllerName = '';

    /**
     * 操作名
     *
     * @var string
     */
    private $_sControllerAction = '';

    /**
     * 获取实例
     *
     * @return object
     */
    static function getInstance()
    {
        if (! self::$_oInstance instanceof self) {
            self::$_oInstance = new self();
        }
        return self::$_oInstance;
    }

    /**
     * 实例化
     */
    protected function __construct()
    {}

    /**
     * 克隆
     */
    protected function __clone()
    {}

    /**
     * 获取路由控制器
     *
     * @param string $p_sDispatchParam            
     */
    function parseWebRoute($p_sDispatchParam)
    {
        $aDispatchParams = parse_url($p_sDispatchParam);
        $aRoutes = get_config('web', 'route');
        $aTmpParams = array();
        foreach ($aRoutes as $sPattern => $aRoute) {
            if (preg_match($sPattern, $aDispatchParams['path'], $aTmpParams)) {
                break;
            }
        }
        $this->_sControllerName = $aRoute[0];
        if (isset($aRoute[1])) {
            $aParams = array();
            $iIndex = 0;
            foreach ($aRoute[1] as $sKey) {
                $aParams[$sKey] = $aTmpParams[++ $iIndex];
            }
            $this->_aRouteParams = $aParams;
        }
//         print_r($aTmpParams);
//         echo '<br />';
//         print_r($aRoute);
//         echo '<br />';
//         print_r($aParams);
    }

    /**
     * 获取路由参数
     *
     * @return array
     */
    function getRouteParam()
    {
        return $this->_aRouteParams;
    }

    /**
     * 获取控制器名称
     *
     * @return string
     */
    function getControllerName()
    {
        return $this->_sControllerName;
    }
}
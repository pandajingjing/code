<?php

/**
 * Lib_Sys_Var
 * @author jxu
 * @package system_lib_sys
 */

/**
 * 系统变量
 *
 * @author jxu
 *        
 */
class Lib_Sys_Var
{

    /**
     * 实例自身
     *
     * @var object
     */
    private static $_oInstance = null;

    /**
     * 获取的cookie数据
     *
     * @var array
     */
    private $_aGetCookies = array();

    /**
     * web服务器参数
     *
     * @var array
     */
    private $_aWebServerParam = array();

    /**
     * cmd服务器参数
     *
     * @var array
     */
    private $_aCmdServerParam = array();

    /**
     * 路由参数
     *
     * @var array
     */
    private $_aRouterParam = array();

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
     * 设置路由参数
     *
     * @param array $p_aParams            
     */
    function setRouterParam($p_aParams)
    {
        $this->_aRouterParam = $p_aParams;
    }

    /**
     * 获取请求时间
     *
     * @param boolean $p_bFloat            
     * @return float/int
     */
    function getVisitTime($p_bFloat = false)
    {
        if ($p_bFloat) {
            return $this->getParam('REQUEST_TIME_FLOAT', 'webserver');
        } else {
            return $this->getParam('REQUEST_TIME', 'webserver');
        }
    }

    /**
     * 获取当前时间
     *
     * @param boolean $p_bFloat            
     * @return float/int
     */
    function getRealTime($p_bFloat = false)
    {
        if ($p_bFloat) {
            return microtime(true);
        } else {
            return time();
        }
    }

    /**
     * 获取某个变量
     *
     * @param string $p_sKey            
     * @param string $p_sType            
     * @return mix
     */
    function getParam($p_sKey, $p_sType)
    {
        $aTmp = $this->getParams($p_sType);
        return isset($aTmp[$p_sKey]) ? $aTmp[$p_sKey] : null;
    }

    /**
     * 获取各种变量
     *
     * @param string $p_sType            
     * @return mix
     */
    function getParams($p_sType)
    {
        $mParam = null;
        $p_sType = strtolower($p_sType);
        switch ($p_sType) {
            case 'post':
                $mParam = $_POST;
                break;
            case 'get':
                $mParam = $_GET;
                break;
            case 'cookie':
                if (empty($this->_aGetCookies)) {
                    $this->_aGetCookies = Util_Sys_Cookie::getCookies();
                }
                $mParam = $this->_aGetCookies;
                break;
            case 'router':
                $mParam = $this->_aRouterParam;
                break;
            case 'webserver':
                if (empty($this->_aWebServerParam)) {
                    $this->_aWebServerParam = $this->_getWebServerParam();
                }
                return $this->_aWebServerParam;
                break;
            case 'cmdserver':
                if (empty($this->_aCmdServerParam)) {
                    $this->_aCmdServerParam = $this->_getCmdServerParam();
                }
                return $this->_aCmdServerParam;
                break;
            case 'file':
                return $_FILES;
                break;
            default:
                $mParam = array_merge($this->_aGetCookies, $_GET, $_POST);
                break;
        }
        return $mParam;
    }

    /**
     * 获取web服务器变量
     *
     * @return array
     */
    private function _getWebServerParam()
    {
        $aServer = array();
        $sIP = null;
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $sIP = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aIPs = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $sIP = array_shift($aIPs);
        } else {
            $sIP = $_SERVER['REMOTE_ADDR'];
        }
        $aServer['CLIENTIP'] = $sIP;
        $aServer['REQUEST_TIME'] = $_SERVER['REQUEST_TIME'];
        $aServer['REQUEST_TIME_FLOAT'] = $_SERVER['REQUEST_TIME_FLOAT'];
        $aServer['HTTP_USER_AGENT'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $aServer['DISPATCH_PARAM'] = $_SERVER['REQUEST_URI'];
        $aServer['HTTP_REFERER'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
        return $aServer;
    }

    /**
     * 获取cmd服务器变量
     *
     * @return array
     */
    private function _getCmdServerParam()
    {
        $aCmd = array();
        $aCmd['DISPATCH_PARAM'] = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : '';
        $aCmd['REQUEST_TIME'] = $_SERVER['REQUEST_TIME'];
        $aCmd['REQUEST_TIME_FLOAT'] = $_SERVER['REQUEST_TIME_FLOAT'];
        if ($_SERVER['argc'] > 2) {
            $aCmd['REQUEST_ARGV'] = array();
            for ($i = 2; $i < $_SERVER['argc']; ++ $i) {
                if (isset($_SERVER['argv'][$i]) and isset($_SERVER['argv'][$i + 1])) {
                    if ('-' == substr($_SERVER['argv'][$i], 0, 1)) {
                        $aCmd['REQUEST_ARGV'][strtoupper(substr($_SERVER['argv'][$i], 1))] = $_SERVER['argv'][++ $i];
                    }
                }
            }
        }
        return $aCmd;
    }
}

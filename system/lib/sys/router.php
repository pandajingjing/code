<?php
/**
 * router
 *
 * 系统路由类
 * @namespace panda\lib\sys
 */
namespace panda\lib\sys;

/**
 * router
 *
 * 系统路由类
 */
class router
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
    private $_aRouterParam = [];

    /**
     * 控制器
     *
     * @var string
     */
    private $_sControllerName = '';

    /**
     * 字符串参数分隔符
     *
     * @var string
     */
    private $_sParamSeperator = '.';

    /**
     * 默认首页控制器名称
     *
     * @var string
     */
    private $_sDefaultControllerName = '';

    /**
     * 默认404页面控制器名称
     *
     * @var string
     */
    private $_s404ControllerName = '';

    /**
     * 默认应用的命名空间
     *
     * @var string
     */
    private $_sDefaultAppNamespace = '';

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
     * 实例化函数
     *
     * @return void
     */
    private function __construct()
    {
        $this->_sDefaultControllerName = variable::getInstance()->getConfig('sDefaultControllerName', 'system');
        $this->_s404ControllerName = variable::getInstance()->getConfig('s404ControllerName', 'system');
        $this->_sDefaultAppNamespace = variable::getInstance()->getConfig('sDefaultAppNamespace', 'system');
    }

    /**
     * 克隆函数
     *
     * @return void
     */
    private function __clone()
    {}

    /**
     * 设置参数分隔符
     *
     * @param string $p_sSeperator            
     * @return void
     */
    function setParamSeperator($p_sSeperator)
    {
        $this->_sParamSeperator = $p_sSeperator;
    }

    /**
     * 解析路由规则
     *
     * @param string $p_sDispatchParam            
     * @return void
     */
    function parseCmd($p_sDispatchParam)
    {
        $aDispatchParams = parse_url($p_sDispatchParam);
        $sPath = $aDispatchParams['path'];
        $sControllerName = '';
        $aRouteParam = [];
        
        $aTmp = explode('/', $sPath);
        $sParam = array_pop($aTmp);
        if (1 == count($aTmp)) {
            $sControllerName = $this->_sDefaultControllerName;
        } else {
            $aTmp[0] = 'controller';
            $iTmp = count($aTmp);
            $sControllerName = '\\' . $this->_sDefaultAppNamespace . '\\' . join('\\', $aTmp);
        }
        $aRouteParam = $this->_parseParam($sParam);
        
        if (class_exists($sControllerName)) { // 默认路由规则
            $oRelClass = new \ReflectionClass($sControllerName);
            if ($oRelClass->isInstantiable()) {
                $this->_sControllerName = $sControllerName;
                $this->_aRouterParam = $aRouteParam;
            } else {
                $this->_sControllerName = $this->_s404ControllerName;
                $this->_aRouterParam['sUrl'] = $sPath;
            }
        } else {
            $this->_sControllerName = $this->_s404ControllerName;
            $this->_aRouterParam['sUrl'] = $sPath;
        }
    }

    /**
     * 解析路由规则
     *
     * @param string $p_sDispatchParam            
     * @return void
     */
    function parseUri($p_sDispatchParam)
    {
        $aDispatchParams = parse_url($p_sDispatchParam);
        $sPath = $aDispatchParams['path'];
        $sControllerName = '';
        $aRouteParam = [];
        // 自定义路由规则
        $aRoutes = variable::getInstance()->getConfig('aRouteList', 'router');
        $aTmpParams = [];
        $bFound = false;
        // debug($aRoutes);
        // debug($aDispatchParams);
        foreach ($aRoutes as $sCtrlName => $aConfig) {
            if (preg_match($aConfig[0], $sPath, $aTmpParams)) {
                $bFound = true;
                $sControllerName = $sCtrlName;
                break;
            }
        }
        // debug($bFound);
        if ($bFound) {
            if (isset($aConfig[1])) {
                $aParam = [];
                $iIndex = 0;
                foreach ($aConfig[1] as $sKey) {
                    $aParam[$sKey] = $aTmpParams[++ $iIndex];
                }
                $aRouteParam = $aParam;
            }
        } else {
            $aTmp = explode('/', $sPath);
            $sParam = array_pop($aTmp);
            if (1 == count($aTmp)) {
                $sControllerName = $this->_sDefaultControllerName;
            } else {
                $aTmp[0] = 'controller';
                $iTmp = count($aTmp);
                $sControllerName = '\\' . $this->_sDefaultAppNamespace . '\\' . join('\\', $aTmp);
            }
            $aRouteParam = $this->_parseParam($sParam);
        }
        // debug($sControllerName);
        if (class_exists($sControllerName)) { // 默认路由规则
            $oRelClass = new \ReflectionClass($sControllerName);
            if ($oRelClass->isInstantiable()) {
                $this->_sControllerName = $sControllerName;
                $this->_aRouterParam = $aRouteParam;
            } else {
                $this->_sControllerName = $this->_s404ControllerName;
                $this->_aRouterParam['sUrl'] = $sPath;
            }
        } else {
            $this->_sControllerName = $this->_s404ControllerName;
            $this->_aRouterParam['sUrl'] = $sPath;
        }
    }

    /**
     * 生成Uri
     *
     * @param string $p_sControllerName            
     * @param array $p_aRouterParam            
     * @param string $p_sAnchor            
     * @throws Exception
     * @return string
     */
    function createUri($p_sControllerName, $p_aRouterParam = [], $p_sAnchor = '')
    {
        $sUrl = '';
        // 自定义路由规则
        $aRoutes = Variable::getInstance()->getConfig('aRouteList', 'router');
        if (isset($aRoutes[$p_sControllerName])) {
            $aSearchKeyss = $aReplaceValss = [];
            $aNormalParam = $p_aRouterParam;
            foreach ($aRoutes[$p_sControllerName][1] as $sKey) {
                $aSearchKeys[] = '{' . $sKey . '}';
                $aReplaceVals[] = $p_aRouterParam[$sKey];
                unset($aNormalParam[$sKey]);
            }
            if (empty($aNormalParam)) {
                $sUrl = str_replace($aSearchKeys, $aReplaceVals, $aRoutes[$p_sControllerName][2]);
            } else {
                $sUrl = str_replace($aSearchKeys, $aReplaceVals, $aRoutes[$p_sControllerName][2]) . '?' . http_build_query($aNormalParam);
            }
        } else {
            if (class_exists($p_sControllerName)) { // 默认路由规则
                if ($this->_sDefaultControllerName == $p_sControllerName) {
                    $aUrlParam = [
                        '',
                        ''
                    ];
                } else {
                    $aUrlParam = explode('\\', $p_sControllerName);
                }
                $sParam = $this->_createParam($p_aRouterParam);
                $aUrlParam[] = $sParam;
                array_shift($aUrlParam);
                array_shift($aUrlParam);
                $aUrlParam[0] = '';
                // debug($aUrlParam);
                $sUrl = join('/', $aUrlParam);
                if ('' == $sUrl) {
                    $sUrl = '/';
                }
                // debug($sUrl);
            } else {
                throw new \Exception(__CLASS__ . ': can not found controller(' . $p_sControllerName . ').');
            }
        }
        if ('' != $p_sAnchor) {
            $sUrl .= '#' . $p_sAnchor;
        }
        return $sUrl;
    }

    /**
     * 生成外站URL
     *
     * @param string $p_sDomainKey            
     * @param string $p_sAlias            
     * @param array $p_aRouterParam            
     * @param string $p_sAnchor            
     * @throws Exception
     * @return string
     */
    function createOutUrl($p_sDomainKey, $p_sAlias, $p_aRouterParam = [], $p_sAnchor = '')
    {
        $aDomainUriList = variable::getInstance()->getConfig($p_sDomainKey, 'uri');
        if (isset($aDomainUriList[$p_sAlias])) {
            $aSearchKeys = $aReplaceVals = [];
            $aNormalParam = $p_aRouterParam;
            foreach ($aDomainUriList[$p_sAlias][1] as $sKey) {
                $aSearchKeys[] = '{' . $sKey . '}';
                $aReplaceVals[] = $p_aRouterParam[$sKey];
                unset($aNormalParam[$sKey]);
            }
            if (empty($aNormalParam)) {
                $sUrl = str_replace($aSearchKeys, $aReplaceVals, $aDomainUriList[$p_sAlias][0]);
            } else {
                $sUrl = str_replace($aSearchKeys, $aReplaceVals, $aDomainUriList[$p_sAlias][0]) . '?' . http_build_query($aNormalParam);
            }
        } else {
            throw new \Exception(__CLASS__ . ': can not found alias(' . $p_sAlias . ') in domain(' . $p_sDomainKey . ').');
        }
        return $sUrl;
    }

    /**
     * 根据URL获取参数
     *
     * @param string $p_sParam            
     * @return array
     */
    private function _parseParam($p_sParam)
    {
        $aParam = [];
        $aTmp = explode($this->_sParamSeperator, $p_sParam);
        for ($iIndex = 0;;) {
            if (isset($aTmp[$iIndex]) and isset($aTmp[$iIndex + 1])) {
                $aParam[$aTmp[$iIndex]] = $aTmp[++ $iIndex];
                ++ $iIndex;
            } else {
                break;
            }
        }
        return $aParam;
    }

    /**
     * 根据参数得到URL
     *
     * @param array $p_aParam            
     * @return string
     */
    private function _createParam($p_aParam)
    {
        ksort($p_aParam);
        $sParam = '';
        foreach ($p_aParam as $sKey => $sValue) {
            $sParam .= $this->_sParamSeperator . urlencode($sKey) . $this->_sParamSeperator . urlencode($sValue);
        }
        if (isset($sParam[0])) {
            $sParam = substr($sParam, 1);
        }
        return $sParam;
    }

    /**
     * 获取路由参数
     *
     * @return array
     */
    function getRouterParam()
    {
        return $this->_aRouterParam;
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
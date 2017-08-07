<?php
/**
 * system basic function
 * @package system
 */
/**
 * 配置
 *
 * @var array
 */
$G_CONFIG = array();

/**
 * 自动加载函数
 *
 * @param string $p_sClassName            
 */
function __autoload($p_sClassName)
{
    global $G_PHP_DIR;
    $aTmp = explode('_', $p_sClassName);
    $sSubPath = strtolower(join(DIRECTORY_SEPARATOR, $aTmp));
    foreach ($G_PHP_DIR as $sLoadDir) {
        $sLoadFilePath = $sLoadDir . DIRECTORY_SEPARATOR . $sSubPath . '.php';
        if (file_exists($sLoadFilePath)) {
            include $sLoadFilePath;
            break;
        }
    }
}

/**
 * 加载配置信息
 *
 * @param string $p_sKey            
 * @param string $p_sClass            
 */
function get_config($p_sKey, $p_sClass = 'common')
{
    global $G_CONFIG_DIR, $G_CONFIG;
    if (! isset($G_CONFIG[$p_sKey])) {
        foreach ($G_CONFIG_DIR as $sConfigDir) {
            $sConfigFilePath = $sConfigDir . DIRECTORY_SEPARATOR . $p_sClass . '.php';
            if (file_exists($sConfigFilePath)) {
                include $sConfigFilePath;
            }
        }
    }
    if (isset($G_CONFIG[$p_sClass][$p_sKey])) {
        return $G_CONFIG[$p_sClass][$p_sKey];
    } else {
        throw new Exception('Miss Config Key ' . $p_sKey . ' in class ' . $p_sClass . '.', 0);
    }
}

/**
 * 初始化环境变量
 */
function init()
{
    date_default_timezone_set(get_config('timezone', 'system'));
    mb_internal_encoding('utf8');
    // register_shutdown_function(get_config('shutdown_handle', 'system'));
    // set_exception_handler(get_config('exception_handle', 'system'));
    // set_error_handler(get_config('error_handle', 'system'));
}

/**
 * 入口函数
 */
function bin()
{
    init();
    
    $oVar = Lib_Sys_Var::getInstance();
    $oRoute = Lib_Sys_Route::getInstance();
    
    $oRoute->parseWebRoute($oVar->getParam('DISPATCH_PARAM', 'webserver'));
    $oVar->setRouterParam($oRoute->getRouteParam());
    $sControllerName = $oRoute->getControllerName();
    
    while (true) {
        $oRelClass = new ReflectionClass($sControllerName);
        $oRelInstance = $oRelClass->newInstance();
        $oRelMethod = $oRelClass->getMethod('beforeRequest');
        $oRelMethod->invoke($oRelInstance);
        $oRelMethod = $oRelClass->getMethod('doRequest');
        $mPage = $oRelMethod->invoke($oRelInstance);
        $oRelMethod = $oRelClass->getMethod('afterRequest');
        $oRelMethod->invoke($oRelInstance);
        if (strstr($mPage, 'Controller') === false) { // 判断是否返回的是另外一个controller
            $sPagePath = $mPage;
            break;
        } else {
            $sControllerName = $mPage;
        }
    }
    
    $oRelMethod = $oRelClass->getMethod('getDatas');
    $aDatas = $oRelMethod->invoke($oRelInstance);
    
    $oTpl = Lib_Sys_Template::getInstance();
    $oTpl->render($sPagePath, $aDatas);
}
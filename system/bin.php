<?php
/**
 * bin
 * 框架入口文件,定义了自动加载函数,入口函数和简单的调试函数
 * @namespace panda
 */
use panda\lib\sys\debugger;
use panda\lib\sys\variable;
use panda\lib\sys\router;
use panda\lib\sys\template;

/**
 * 入口函数
 * 框架引导文件会调用此函数进入框架,完成所有流程
 *
 * @return void
 */
function bin()
{
    include __DIR__ . '/lib/sys/loader.php';
    \panda\lib\sys\loader::register();
    
    ob_start('ob_gzhandler');
    error_reporting(E_ALL);
    $oDebugger = debugger::getInstance();
    $oDebugger->startDebug('Proccess');
    
    $oVar = variable::getInstance();
    date_default_timezone_set($oVar->getConfig('sTimeZone', 'system'));
    mb_internal_encoding('utf8');
    register_shutdown_function($oVar->getConfig('sShutdownHandle', 'system'));
    set_exception_handler($oVar->getConfig('sExceptionHandle', 'system'));
    set_error_handler($oVar->getConfig('sErrorHandle', 'system'));
    
    $oDebugger->startDebug('Parse Route');
    $oRouter = router::getInstance();
    $oRouter->parseUri($oVar->getParam('DISPATCH_PARAM', 'server'));
    $sControllerName = $oRouter->getControllerName();
    $oDebugger->showMsg('router find controller: ' . $sControllerName);
    $oVar->setRouterParam($oRouter->getRouterParam());
    $oDebugger->stopDebug('Parse Route');
    
    while (true) {
        $oDebugger->startDebug('Handle Controller: ' . $sControllerName);
        $oRelClass = new \ReflectionClass($sControllerName);
        $oRelInstance = $oRelClass->newInstance();
        $oRelMethod = $oRelClass->getMethod('beforeRequest');
        $oRelMethod->invoke($oRelInstance);
        $oRelMethod = $oRelClass->getMethod('doRequest');
        $mReturn = $oRelMethod->invoke($oRelInstance);
        $oDebugger->showMsg('controller return: ' . $mReturn);
        $oRelMethod = $oRelClass->getMethod('afterRequest');
        $oRelMethod->invoke($oRelInstance);
        $oDebugger->stopDebug('Handle Controller: ' . $sControllerName);
        if (class_exists($mReturn)) { // 判断是否返回的是另外一个controller
            $sControllerName = $mReturn;
        } else {
            $sPagePath = $mReturn;
            break;
        }
    }
    
    $oDebugger->startDebug('Render Page: ' . $mReturn);
    $oRelMethod = $oRelClass->getMethod('getAllPageData');
    $aPageData = $oRelMethod->invoke($oRelInstance);
    
    $oTpl = template::getInstance();
    $oTpl->setPageData($aPageData);
    $oTpl->render($sPagePath);
    $oDebugger->stopDebug('Render Page: ' . $mReturn);
    
    $oDebugger->stopDebug('Proccess');
}

/**
 * 入口函数
 *
 * 框架引导文件会调用此函数进入框架,完成所有流程
 *
 * @return void
 */
function bin_cmd()
{
    include __DIR__ . '/lib/sys/loader.php';
    \panda\lib\sys\loader::register();
    
    error_reporting(E_ALL);
    
    $oDebugger = debugger::getInstance();
    $oDebugger->startDebug('Proccess');
    
    $oVar = variable::getInstance();
    date_default_timezone_set($oVar->getConfig('sTimeZone', 'system'));
    mb_internal_encoding('utf8');
    register_shutdown_function($oVar->getConfig('sShutdownHandle', 'system'));
    set_exception_handler($oVar->getConfig('sExceptionHandle', 'system'));
    set_error_handler($oVar->getConfig('sErrorHandle', 'system'));
    
    $oDebugger->startDebug('Parse Route');
    $oRouter = router::getInstance();
    $oRouter->parseCmd($oVar->getParam('DISPATCH_PARAM', 'server'));
    $sControllerName = $oRouter->getControllerName();
    $oDebugger->showMsg('router find controller: ' . $sControllerName);
    $oVar->setRouterParam($oRouter->getRouterParam());
    $oDebugger->stopDebug('Parse Route');
    
    while (true) {
        $oDebugger->startDebug('Handle Controller: ' . $sControllerName);
        $oRelClass = new ReflectionClass($sControllerName);
        $oRelInstance = $oRelClass->newInstance();
        $oRelMethod = $oRelClass->getMethod('beforeRequest');
        $oRelMethod->invoke($oRelInstance);
        $oRelMethod = $oRelClass->getMethod('doRequest');
        $mReturn = $oRelMethod->invoke($oRelInstance);
        $oRelMethod = $oRelClass->getMethod('afterRequest');
        $oRelMethod->invoke($oRelInstance);
        $oDebugger->stopDebug('Handle Controller: ' . $sControllerName);
        if (class_exists($mReturn)) { // 判断是否返回的是另外一个controller
            $sControllerName = $mReturn;
        } else {
            $sPagePath = $mReturn;
            break;
        }
    }
    
    $oDebugger->stopDebug('Proccess');
}

/**
 * 调试函数
 *
 * 支持任意个参数,根据不同类型输出显示.配合bootstrap样式更佳.
 *
 * @return void
 */
function debug()
{
    $iCnt = func_num_args();
    $aParams = func_get_args();
    
    if (0 == $iCnt) {
        $aTmp = debug_backtrace();
        $aTmp = $aTmp[0];
        debug($aTmp['file'] . '::' . $aTmp['line']);
    } elseif (1 == $iCnt) {
        $mParam = $aParams[0];
        switch (true) {
            case is_string($mParam):
                echo '<p class="text-success">string(' . mb_strlen($mParam) . '):' . htmlspecialchars($mParam) . '</p>';
                break;
            case is_float($mParam):
                echo '<p class="text-info">float:' . $mParam . '</p>';
                break;
            case is_int($mParam):
                echo '<p class="text-info">int:' . $mParam . '</p>';
                break;
            case is_null($mParam):
                echo '<p class="text-danger">null</p>';
                break;
            case is_bool($mParam):
                echo '<p class="text-warning">' . ($mParam ? 'true' : 'false') . '</p>';
                break;
            case is_array($mParam):
                echo '<pre>' . var_export($mParam, true) . '</pre>';
                break;
            case is_object($mParam):
                echo '<pre>' . var_export($mParam, true) . '</pre>';
                break;
        }
    } else {
        foreach ($aParams as $mParam) {
            debug($mParam);
        }
    }
}
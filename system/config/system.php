<?php
/**
 * config_system
 *
 * 系统相关配置
 *
 * @package config
 */
return [
    'sTimeZone' => 'Asia/Shanghai',
    'sErrorHandle' => '\panda\util\sys\handle::handleError',
    'sExceptionHandle' => '\panda\util\sys\handle::handleException',
    'sShutdownHandle' => '\panda\util\sys\handle::handleShutdown',
    'sDefaultControllerName' => '\\app\\controller\\home\\home',
    's404ControllerName' => '\\app\\controller\\home\\miss',
    'sDefaultAppNamespace' => 'app'
];
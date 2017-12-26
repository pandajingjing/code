<?php
/**
 * controller
 *
 * 控制器基类
 * @namespace panda\lib\sys
 */
namespace panda\lib\sys;

use panda\util\strings;

/**
 * controller
 *
 * 控制器基类
 */
abstract class controller
{

    /**
     * 内部变量
     *
     * 存放控制器数据,页面数据和响应头数据
     *
     * @var array
     */
    protected $aPri = [
        'aControllerData' => [],
        'aPageData' => [],
        'aHeaders' => []
    ];

    /**
     * 构造函数
     *
     * @return void
     */
    function __construct()
    {
        // parent::__construct();
    }

    /**
     * 在控制器开始时执行（调度使用）
     *
     * @return void
     */
    function beforeRequest()
    {
        // parent::beforeRequest();
        // do something
        $this->addLog('controller parameter', json_encode(debugger::getInstance()->getAllParam(), JSON_NUMERIC_CHECK | JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE), 'parameter');
    }

    /**
     * 在控制器结束时执行（调度使用）
     *
     * @return void
     */
    function afterRequest()
    {
        // do something
        // parent::afterRequest();
    }

    /**
     * 控制器入口函数
     *
     * @return string|controller
     */
    abstract function doRequest();

    /**
     * 设置控制器数据
     *
     * @param string $p_sKey            
     * @param mix $p_mValue            
     */
    protected function setControllerData($p_sKey, $p_mValue)
    {
        $this->aPri['aControllerData'][$p_sKey] = $p_mValue;
    }

    /**
     * 获取控制器数据
     *
     * @param string $p_sKey            
     * @return mixed
     */
    protected function getControllerData($p_sKey)
    {
        return $this->aPri['aControllerData'][$p_sKey];
    }

    /**
     * 获取参数
     *
     * 获取不同方法的参数,可以进行类别校验,发过检验不通过,则返回<var>$p_mDefault</var>
     *
     * @param string $p_sKey            
     * @param string $p_sMethod            
     * @param string $p_sType            
     * @param mix $p_mDefault            
     * @return mix
     */
    protected function getParam($p_sKey, $p_sMethod, $p_sType = '', $p_mDefault = null)
    {
        $mValue = variable::getInstance()->getParam($p_sKey, $p_sMethod);
        if ('' == $p_sType) {
            return $mValue;
        } else {
            if (strings::chkStrType($mValue, $p_sType)) {
                return $mValue;
            } else {
                return $p_mDefault;
            }
        }
    }

    /**
     * 获取相关方法的所有参数
     *
     * @param string $p_sMethod            
     * @return array
     */
    protected function getAllParam($p_sMethod)
    {
        return variable::getInstance()->getAllParam($p_sMethod);
    }

    /**
     * 获取请求时间
     *
     * @param boolean $p_bFloat            
     * @return float|int
     */
    protected function getVisitTime($p_bFloat = false)
    {
        return variable::getInstance()->getVisitTime($p_bFloat);
    }

    /**
     * 获取当前时间
     *
     * @param boolean $p_bFloat            
     * @return float|int
     */
    protected function getRealTime($p_bFloat = false)
    {
        return variable::getInstance()->getRealTime($p_bFloat);
    }

    /**
     * 添加日志
     *
     * @param string $p_sTitle            
     * @param string $p_sContent            
     * @param string $p_sClass            
     * @return void
     */
    protected function addLog($p_sTitle, $p_sContent, $p_sClass = 'common')
    {
        logger::getInstance()->addLog($p_sTitle, $p_sContent, $p_sClass);
    }

    /**
     * 开始模块调试
     *
     * @param string $p_sModule            
     * @return void
     */
    protected function startDebug($p_sModule)
    {
        debugger::getInstance()->startDebug($p_sModule);
    }

    /**
     * 发送调试信息
     *
     * @param string $p_sMsg            
     * @param boolean $p_bIsHtml            
     * @return void
     */
    protected function showDebugMsg($p_sMsg, $p_bIsHtml = false)
    {
        debugger::getInstance()->showMsg($p_sMsg, $p_bIsHtml);
    }

    /**
     * 结束模块调试
     *
     * @param string $p_sModule            
     * @return void
     */
    protected function stopDebug($p_sModule)
    {
        debugger::getInstance()->stopDebug($p_sModule);
    }
}
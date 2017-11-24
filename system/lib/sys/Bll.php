<?php
/**
 * bll
 *
 * 业务服务基类
 * @namespace panda\lib\sys
 */
namespace panda\lib\sys;

use panda\lib\traits\response;

/**
 * bll
 *
 * 业务服务基类
 */
class bll
{
    use response;

    /**
     * 构造函数
     *
     * @return void
     */
    function __construct()
    {}

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
     * 筛选数据
     *
     * 筛选<var>$p_aAllDatas</var>中是否有<var>$p_mValue</var>,如果存在则返回,否则返回<var>$p_mDefault</var>
     *
     * @param array $p_aAllDatas            
     * @param string $p_sColumn            
     * @param mix $p_mValue            
     * @param mix $p_mDefault            
     * @return mix
     */
    protected function filterData($p_aAllDatas, $p_sColumn, $p_mValue, $p_mDefault = null)
    {
        foreach ($p_aAllDatas as $aData) {
            if ($p_mValue == $aData[$p_sColumn]) {
                return $p_mValue;
            }
        }
        return $p_mDefault;
    }

    /**
     * 获取配置信息
     *
     * @param string $p_sKey            
     * @param string $p_sClass            
     * @return mix
     */
    protected function getConfig($p_sKey, $p_sClass = 'common')
    {
        return variable::getInstance()->getConfig($p_sKey, $p_sClass);
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
     * @param boolean $p_bIsHTML            
     * @return void
     */
    protected function showDebugMsg($p_sMsg, $p_bIsHTML = false)
    {
        debugger::getInstance()->showMsg($p_sMsg, $p_bIsHTML);
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
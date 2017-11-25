<?php
/**
 * http
 *
 * http协议控制器基类
 * @namespace panda\lib\controller
 */
namespace panda\lib\controller;

use panda\lib\sys\controller;

/**
 * http
 *
 * http协议控制器基类
 */
abstract class http extends controller
{

    /**
     * 在控制器结束时执行（调度使用）
     *
     * @return void
     */
    function afterRequest()
    {
        // 发送头部信息
        foreach ($this->_aPri['aHeaders'] as $aHeader) {
            header($aHeader[0], $aHeader[1], $aHeader[2]);
        }
        $this->addLog('page get data', json_encode($this->getAllPageData(), JSON_NUMERIC_CHECK | JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE), 'parameter');
        parent::afterRequest();
    }

    /**
     * 添加头部信息
     *
     * @param string $p_sValue            
     * @param boolean $p_bReplace            
     * @param int $p_iCode            
     * @return void
     */
    protected function addHeader($p_sValue, $p_bReplace = true, $p_iCode = null)
    {
        $this->_aPri['aHeaders'][] = array(
            $p_sValue,
            $p_bReplace,
            $p_iCode
        );
    }

    /**
     * 设置页面数据
     *
     * @param string $p_sKey            
     * @param mixed $p_mValue            
     * @return void
     */
    protected function setPageData($p_sKey, $p_mValue)
    {
        $this->_aPri['aPageData'][$p_sKey] = $p_mValue;
    }

    /**
     * 获取页面数据
     *
     * @param string $p_sKey            
     * @return mix
     */
    protected function getPageData($p_sKey)
    {
        return $this->_aPri['aPageData'][$p_sKey];
    }

    /**
     * 获取整个页面的数据（调度使用）
     *
     * @return array
     */
    function getAllPageData()
    {
        return $this->_aPri['aPageData'];
    }
}
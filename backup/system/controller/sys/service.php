<?php

/**
 * Controller_Sys_Service
 * @author jxu
 * @package system_controller_sys
 */
/**
 * Controller_Sys_Service
 *
 * @author jxu
 */
abstract class Controller_Sys_Service extends Controller_Sys_Web
{

    protected $_aFields = array();

    public function beforeRequest()
    {
        parent::beforeRequest();
        // 检测接口数据
        $this->verify();
    }

    protected function verify()
    {
        foreach ($this->_aFields as $sFiled => $aSet) {}
    }

    public function afterRequest()
    {
        $this->addHeader('Content-type: application/json;charset=UTF-8');
        parent::afterRequest();
    }

    /**
     * 设置详情接口返回数据
     *
     * @param mix $p_aData            
     * @return string
     */
    protected function setDetail($p_mData)
    {
        $this->setData('mData', $p_mData);
        return 'detail';
    }

    /**
     * 设置分页列表接口返回数据
     *
     * @param int $p_iTotal            
     * @param int $p_iPageSize            
     * @param int $p_iCurPage            
     * @param array $p_aDatas            
     * @return string
     */
    protected function setPageList($p_iTotal, $p_iPageSize, $p_iCurPage, $p_aDatas)
    {
        $this->setData('iTotal', $p_iTotal);
        $this->setData('iPageSize', $p_iPageSize);
        $this->setData('iCurPage', $p_iCurPage);
        $this->setData('aDatas', $p_aDatas);
        return 'pagelist';
    }

    /**
     * 设置列表接口返回数据
     *
     * @param int $p_iTotal            
     * @param array $p_aDatas            
     * @return string
     */
    protected function setList($p_iTotal, $p_aDatas)
    {
        $this->setData('iTotal', $p_iTotal);
        $this->setData('aDatas', $p_aDatas);
        return 'list';
    }
}
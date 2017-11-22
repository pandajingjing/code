<?php

/**
 * Controller_Weight_List
 * @author jxu
 * @package bodybuild-service_controller_weight
 */
/**
 * Controller_Weight_List
 *
 * @author jxu
 */
class Controller_Weight_List extends Controller_Sys_Service
{

    function doRequest()
    {
        $aSearchKey = $this->_parseSearchKey($this->getParam('sSearchKey', 'router'));
        return $this->setDetail('this is weight data list service.');
    }

    /**
     * 解析查询条件
     * @param unknown $p_sSearchKey
     * @return multitype:
     */
    private function _parseSearchKey($p_sSearchKey)
    {
        return array();
    }
}
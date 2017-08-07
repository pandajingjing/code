<?php

/**
 * bclient_file_base
 * @author jxu
 * @package bclient_file
 */

/**
 * bclient_file_base
 *
 * @author jxu
 */
class bclient_file_base extends lib_sys_bclient
{

    /**
     * 检查使用方是否是允许的域名
     *
     * @param string $p_sFromURL            
     * @param string $p_sAgent            
     * @return array
     */
    static function chkAllowedDomain($p_sFromURL, $p_sAgent)
    {
        return parent::_call(__CLASS__, __FUNCTION__, func_get_args());
    }

    /**
     * 返回跨域配置
     *
     * @param boolean $p_bPreg            
     * @return array
     */
    static function getCrossDomain($p_bPreg = true)
    {
        return parent::_call(__CLASS__, __FUNCTION__, func_get_args());
    }
}
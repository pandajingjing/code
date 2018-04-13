<?php
/**
 * home controller
 * @package app-file-view_controller_home
 */
load_lib('/app/controller');

/**
 * home controller
 *
 * @author jxu
 * @package app-file-view_controller_home
 */
class home_homecontroller extends app_controller
{

    function doRequest()
    {
        return '/home/home';
    }

    /**
     * 获取访问该控制器的路径
     */
    static function getURL($p_sAction = '', $p_aParam = array(), $p_bSecure = false, $p_sPre = '')
    {
        return parent::getURL($p_sPre, $p_sAction, $p_aParam, $p_bSecure);
    }
}
<?php
/**
 * home controller
 * @package app-file-upd_cmd_home
 */
load_lib('/cmd/controller');

/**
 * home controller
 *
 * @author jxu
 * @package app-file-upd_cmd_home
 */
class home_homecontroller extends cmd_controller
{

    /**
     * 入口方法
     */
    function doRequest()
    {
        $aParam = $this->getParam('REQUEST_ARGV', 'server');
        $this->stdOut('app-file-upd cmd home');
        if (is_array($aParam)) {
            foreach ($aParam as $sKey => $sValue) {
                $this->stdOut('param: ' . $sKey);
                $this->stdOut('value: ' . $sValue);
            }
        }
    }
}
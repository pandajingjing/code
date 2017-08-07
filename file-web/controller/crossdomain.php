<?php

/**
 * controller_crossdomain
 *
 * crossdomain controller
 *
 * @package controller
 */

/**
 * controller_crossdomain
 *
 * crossdomain controller
 */
class controller_crossdomain extends lib_controller_web
{

    /**
     * 控制器入口函数
     *
     * @return string|lib_sys_controller
     */
    function doRequest()
    {
        $aResult = bclient_file_save::getCrossDomain(false);
        if ($aResult['iStatus'] == 1) {
            $this->setData('aCrossDomainList', $aResult['aList']);
        } else {
            $this->setData('aCrossDomainList', []);
        }
        $this->addHeader('Content-Type:text/xml');
        return 'app_crossdomain';
    }
}
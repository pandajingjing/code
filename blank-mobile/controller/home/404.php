<?php

/**
 * controller_home_404
 * @author jxu
 * @package blank-mobile_controller_home
 */
/**
 * controller_home_404
 *
 * @author jxu
 */
class controller_home_404 extends lib_controller_web
{

    function doRequest()
    {
        $this->addHeader('HTTP/1.1 404 Not Found');
        return 'mobile_404';
    }
}
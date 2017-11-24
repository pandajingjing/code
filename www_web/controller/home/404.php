<?php

/**
 * controller_home_404
 * @author jxu
 * @package www_web_controller_home
 */
/**
 * controller_home_404
 *
 * @author jxu
 */
class controller_home_404 extends controller_base
{

    function doRequest()
    {
        $this->addHeader('HTTP/1.1 404 Not Found');
        return 'home_404';
    }
}
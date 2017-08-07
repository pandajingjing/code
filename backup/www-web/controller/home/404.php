<?php

/**
 * Controller_Home_404
 * @author jxu
 * @package www-web_controller_home
 */
/**
 * Controller_Home_404
 *
 * @author jxu
 */
class Controller_Home_404 extends Controller_Sys_Web
{

    function doRequest()
    {
        $this->addHeader('HTTP/1.1 404 Not Found');
        return 'Home_404';
    }
}
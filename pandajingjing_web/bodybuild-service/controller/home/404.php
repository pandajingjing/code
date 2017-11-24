<?php

/**
 * Controller_Home_404
 * @author jxu
 * @package bodybuild-service_controller_home
 */
/**
 * Controller_Home_404
 *
 * @author jxu
 */
class Controller_Home_404 extends Controller_Sys_Service
{

    function doRequest()
    {
        $this->addHeader('HTTP/1.1 404 Not Found');
        return $this->setDetail('ah oh...it\'s a 404');
    }
}
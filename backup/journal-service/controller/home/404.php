<?php

/**
 * Controller_Home_404
 * @author jxu
 * @package journal-service_controller_home
 */
/**
 * Controller_Home_404
 *
 * @author jxu
 */
class Controller_Home_404 extends Controller_Sys_Service
{

    function afterRequest()
    {
        $this->addHeader('HTTP/1.0 404 Not Found');
        parent::afterRequest();
    }

    function doIndex()
    {
        $sURL = $this->getParam('sURL', 'router');
        return $this->setErrInfo(404, $sURL . ' Is Not Found');
    }
}
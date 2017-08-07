<?php

/**
 * controller_home_404
 * @author jxu
 * @package blank-service_controller_home
 */
/**
 * controller_home_404
 *
 * @author jxu
 */
class controller_home_404 extends lib_controller_service
{

    function doRequest()
    {
        $this->addHeader('HTTP/1.1 404 Not Found');
        return $this->returnErrors([
            'service is not found'
        ]);
    }
}
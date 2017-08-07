<?php

/**
 * controller_home_home
 * @author jxu
 * @package blank-service_controller_home
 */
/**
 * controller_home_home
 *
 * @author jxu
 */
class controller_home_home extends lib_controller_service
{

    function doRequest()
    {
        return $this->returnSuccess([
            'this is home page'
        ]);
    }
}
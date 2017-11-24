<?php

/**
 * Controller_Home_Home
 * @author jxu
 * @package bodybuild-service_controller_home
 */
/**
 * Controller_Home_Home
 *
 * @author jxu
 */
class Controller_Home_Home extends Controller_Sys_Service
{

    function doRequest()
    {
        return $this->setDetail('this is bodybuild data service.');
    }
}
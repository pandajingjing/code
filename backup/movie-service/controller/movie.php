<?php

/**
 * Controller_Movie
 * @author jxu
 * @package movie-service_controller
 */
/**
 * Controller_Home_404
 *
 * @author jxu
 */
class Controller_Movie extends Controller_Sys_Service
{

    function doIndex()
    {
        throw new Exception();
        echo $this->getParam('searchkey', 'router');
        return '';
    }
}
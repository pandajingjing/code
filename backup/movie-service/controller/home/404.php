<?php

/**
 * Controller_Home_404
 * @author jxu
 * @package movie-service_controller_home
 */
/**
 * Controller_Home_404
 *
 * @author jxu
 */
class Controller_Home_404 extends Controller_Sys_Service
{

    function doIndex()
    {
        $this->setData('iErrCode', Util_Error::ERRCODE_404);
        $this->setData('sErrMsg', Util_Error::getErrMsg(Util_Error::ERRCODE_404));
        return 'error';
    }
}
<?php
namespace app\controller\home;

use panda\lib\controller\Service;

/**
 * controller_home_home
 *
 * @author jxu
 * @package blank-service_controller_home
 */
/**
 * controller_home_home
 *
 * @author jxu
 */
class Home extends Service
{

    function doRequest()
    {
        return $this->returnInfo('this is home page');
    }
}
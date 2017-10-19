<?php
namespace app\controller\home;

use panda\lib\controller\Service;

/**
 * controller_home_404
 *
 * @author jxu
 * @package blank-service_controller_home
 */
/**
 * controller_home_404
 *
 * @author jxu
 */
class Miss extends Service
{

    function doRequest()
    {
        $this->addHeader('HTTP/1.1 404 Not Found');
        return $this->returnError('service is not found');
    }
}
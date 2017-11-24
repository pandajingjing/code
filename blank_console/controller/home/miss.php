<?php
namespace app\controller\home;

use panda\lib\controller\Cmd;

/**
 * controller_home_404
 *
 * @author jxu
 * @package blank_service_controller_home
 */
/**
 * controller_home_404
 *
 * @author jxu
 */
class Miss extends Cmd
{

    function doRequest()
    {
        $this->stdOut('Controller Not Found');
    }
}
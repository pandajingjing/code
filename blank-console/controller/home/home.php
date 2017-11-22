<?php
namespace app\controller\home;

use panda\lib\controller\Cmd;

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
class Home extends Cmd
{

    function doRequest()
    {
        $this->stdOut('This is Home Cmd');
    }
}
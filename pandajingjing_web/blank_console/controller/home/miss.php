<?php
/**
 * miss
 * 
 * @namespace app\controller\home
 */
namespace app\controller\home;

use panda\lib\controller\cmd;

/**
 * miss
 */
class miss extends cmd
{

    function doRequest()
    {
        $this->stdOut('Controller Not Found');
    }
}
<?php
/**
 * home
 *
 * @namespace app\controller\home
 */
namespace app\controller\home;

use panda\lib\controller\cmd;

/**
 * home
 */
class home extends cmd
{

    function doRequest()
    {
        $this->stdOut('This is Home Cmd');
    }
}
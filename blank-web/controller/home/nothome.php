<?php

/**
 * Nothome
 * @author jxu
 * @package blank-web_controller_home
 */
namespace app\controller\home;

use panda\lib\controller\Web;

/**
 * Nothome
 *
 * @author jxu
 */
class Nothome extends Web
{

    function doRequest()
    {
        return 'web_home';
    }
}
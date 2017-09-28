<?php

/**
 * Home
 * @author jxu
 * @package blank-web_controller_home
 */
namespace app\controller\home;

use panda\lib\controller\Web;

/**
 * Home
 *
 * @author jxu
 */
class Home extends Web
{

    function doRequest()
    {
        return 'home_home';
    }
}
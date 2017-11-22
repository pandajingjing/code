<?php
/**
 * controller_home_home
 * @author jxu
 * @package blank-mobile_controller_home
 */
namespace app\controller\home;

use panda\lib\controller\Web;

/**
 * controller_home_home
 *
 * @author jxu
 */
class Home extends Web
{

    function doRequest()
    {
        return 'mobile_home';
    }
}
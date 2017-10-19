<?php
namespace app\controller\home;

use panda\lib\controller\Web;

/**
 * controller_home_404
 *
 * @author jxu
 * @package blank-mobile_controller_home
 */
/**
 * controller_home_404
 *
 * @author jxu
 */
class Miss extends Web
{

    function doRequest()
    {
        $this->addHeader('HTTP/1.1 404 Not Found');
        return 'mobile_404';
    }
}
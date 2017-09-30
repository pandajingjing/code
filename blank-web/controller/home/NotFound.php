<?php

/**
 * NotFound
 * @namespace app\controller\home\NotFound
 * @package blank-web_controller_home
 */
namespace app\controller\home;

use panda\lib\controller\Web;

/**
 * NotFound
 *
 * @author jxu
 */
class NotFound extends Web
{

    function doRequest()
    {
        $this->addHeader('HTTP/1.1 404 Not Found');
        return 'web_404';
    }
}
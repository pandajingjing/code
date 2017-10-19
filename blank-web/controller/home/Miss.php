<?php

/**
 * Miss
 * @namespace app\controller\home\NotFound
 * @package blank-web_controller_home
 */
namespace app\controller\home;

use app\controller\Base;

/**
 * Miss
 *
 * @author jxu
 */
class Miss extends Base
{

    function doRequest()
    {
        $this->addHeader('HTTP/1.1 404 Not Found');
        return 'home_404';
    }
}
<?php
/**
 * miss
 * 
 * @namespace app\controller\home
 */
namespace app\controller\home;

use app\controller\base;

/**
 * miss
 */
class miss extends base
{

    function doRequest()
    {
        $this->addHeader('HTTP/1.1 404 Not Found');
        return '/home/404';
    }
}
<?php
/**
 * miss
 *
 * @namespace app\controller\home
 */
namespace app\controller\home;

use app\controller\base;

/**
 * home
 */
class miss extends base
{

    function doRequest()
    {
        return '/home/404';
    }
}
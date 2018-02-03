<?php
/**
 * miss
 *
 * @namespace app\controller\home
 */
namespace app\controller\home;

use panda\lib\controller\web;

/**
 * home
 */
class miss extends web
{

    function doRequest()
    {
        return 'web_404';
    }
}
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
        $this->setPageData('sHomeUrl', $this->createInUrl('\\app\\controller\\home\\home'));
        return '/home/404';
    }
}
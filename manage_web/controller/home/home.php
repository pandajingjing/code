<?php
/**
 * home
 *
 * @namespace app\controller\home
 */
namespace app\controller\home;

use app\controller\loginbase;

/**
 * home
 */
class home extends loginbase
{

    function doRequest()
    {
        return '/home/home';
    }
}
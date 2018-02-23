<?php
/**
 * listing
 *
 * @namespace app\controller\member
 */
namespace app\controller\member;

use app\controller\loginbase;

/**
 * listing
 */
class listing extends loginbase
{

    function doRequest()
    {
        return '/member/listing';
    }
}
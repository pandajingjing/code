<?php
/**
 * listing
 * 
 * @namespace app\controller\photo
 */
namespace app\controller\photo;

use app\controller\base;

/**
 * listing
 */
class listing extends base
{

    function doRequest()
    {
        return '/photo/listing';
    }
}
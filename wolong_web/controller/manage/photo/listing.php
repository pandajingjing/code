<?php
/**
 * listing
 * 
 * @namespace app\controller\manage\photo
 */
namespace app\controller\manage\photo;

use app\controller\manage\base;

/**
 * listing
 */
class listing extends base
{

    function doRequest()
    {
        return '/manage/photo/listing';
    }
}
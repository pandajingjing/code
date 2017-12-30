<?php
/**
 * listing
 * 
 * @namespace app\controller\manage\video
 */
namespace app\controller\manage\video;

use app\controller\manage\base;

/**
 * listing
 */
class listing extends base
{

    function doRequest()
    {
        return '/manage/video/listing';
    }
}
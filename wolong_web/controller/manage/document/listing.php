<?php
/**
 * listing
 * 
 * @namespace app\controller\manage\document
 */
namespace app\controller\manage\document;

use app\controller\manage\base;

/**
 * listing
 */
class listing extends base
{

    function doRequest()
    {
        return '/manage/document/listing';
    }
}
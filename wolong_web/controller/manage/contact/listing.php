<?php
/**
 * listing
 * 
 * @namespace app\controller\manage\contact
 */
namespace app\controller\manage\contact;

use app\controller\manage\base;

/**
 * listing
 */
class listing extends base
{

    function doRequest()
    {
        return '/manage/contact/listing';
    }
}
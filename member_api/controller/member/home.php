<?php
/**
 * home
 *
 * @namespace app\controller\member
 */
namespace app\controller\member;

use panda\lib\controller\api;

/**
 * home
 */
class home extends api
{

    function doRequest()
    {
        return $this->setInfData($this->returnRow([
            'aaa' => 'bbb'
        ]));
    }
}
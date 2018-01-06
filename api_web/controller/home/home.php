<?php
/**
 * home
 *
 * @namespace app\controller\home
 */
namespace app\controller\home;

use panda\lib\controller\api;

/**
 * home
 */
class home extends api
{

    function doRequest()
    {
        return $this->setInfData($this->returnRow([
            'iTime' => $this->getRealTime()
        ]));
    }
}
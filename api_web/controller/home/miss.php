<?php
/**
 * home
 *
 * @namespace app\controller\home
 */
namespace app\controller\home;

use panda\lib\controller\api;
use panda\util\error;

/**
 * home
 */
class miss extends api
{

    function doRequest()
    {
        $this->addHeader('HTTP/1.1 404 Not Found');
        return $this->setInfData($this->returnLogicError('url', error::TYPE_NOT_FOUND, '', $this->getparam('INTEGRATED_URL', 'server')));
    }
}
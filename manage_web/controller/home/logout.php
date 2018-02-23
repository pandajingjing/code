<?php
/**
 * logout
 *
 * @namespace app\controller\home
 */
namespace app\controller\home;

use member_service\bll\session;
use panda\util\strings;
use app\controller\loginbase;

/**
 * logout
 */
class logout extends loginbase
{

    function doRequest()
    {
        $oBllSession = $this->getControllerData(parent::DKEY_SESSION);
        $oBllSession->clear(session::KEY_MEMBER_ID);
        $sEncodeBackUrl = $this->getParam('back_url', 'router');
        $sBackUrl = base64_decode($sEncodeBackUrl);
        // debug($sBackUrl);
        if (strings::chkStrType($sBackUrl, strings::TYPE_URL)) {
            $this->redirectUrl($sBackUrl);
        } else {
            $this->redirectUrl($this->createInUrl('\\app\\controller\\home\\home'));
        }
        return '/home/logout';
    }
}
<?php
/**
 * login
 *
 * @namespace app\controller\home
 */
namespace app\controller\home;

use app\controller\base;
use member_service\bll\member;
use member_service\bll\session;
use panda\util\strings;

/**
 * login
 */
class login extends base
{

    private $_aFormField = [
        'username' => [
            'sMapping' => 'sUserName',
            'mDefault' => ''
        ],
        'userpwd' => [
            'sMapping' => 'sUserPwd',
            'mDefault' => ''
        ],
        'remember' => [
            'sMapping' => 'bRemember',
            'mDefault' => true
        ]
    ];

    function doRequest()
    {
        // 外界参数
        $sNextAction = $this->getParam('next_act', 'post');
        // 本页参数
        // 代码参数
        $aFormData = $aFormStatus = [];
        if ('login' == $sNextAction) {
            foreach ($this->_aFormField as $sFormField => $aFieldSet) {
                $aFormData[$aFieldSet['sMapping']] = $this->getParam($sFormField, 'post');
                $aFormStatus[$aFieldSet['sMapping']] = true;
            }
            $oBllMember = new member();
            $aResult = $oBllMember->chkManageLogin($aFormData['sUserName'], $aFormData['sUserPwd']);
            if ($aResult['iStatus'] == 1) {
                $oBllSession = $this->getControllerData(parent::DKEY_SESSION);
                $oBllSession->set(session::KEY_MEMBER_ID, $aResult['aData']['iAutoId']);
                if ($aFormData['bRemember'] == 'on') {
                    $oBllSession->set(session::KEY_MEMBER_NICKNAME, $aResult['aData']['sNickName']);
                }
                $sEncodeBackUrl = $this->getParam('back_url', 'router');
                $sBackUrl = base64_decode($sEncodeBackUrl);
                // debug($aFormData,$aResult,$sBackUrl);
                if (strings::chkStrType($sBackUrl, strings::TYPE_URL)) {
                    $this->redirectUrl($sBackUrl);
                } else {
                    $this->redirectUrl($this->createInUrl('\\app\\controller\\home\\home'));
                }
            } else {
                $aFormStatus = array_merge($aFormStatus, self::getFormError($aResult));
            }
        } else {
            $oBllSession = $this->getControllerData(parent::DKEY_SESSION);
            $sRememberNickName = $oBllSession->get(session::KEY_MEMBER_NICKNAME);
            if ($sRememberNickName !== null) {
                $this->_aFormField['username']['mDefault'] = $sRememberNickName;
            }
            foreach ($this->_aFormField as $sFormField => $aFieldSet) {
                $aFormData[$aFieldSet['sMapping']] = $aFieldSet['mDefault'];
                $aFormStatus[$aFieldSet['sMapping']] = false;
            }
        }
        $this->setPageData('aFormData', $aFormData);
        $this->setPageData('aFormStatus', $aFormStatus);
        return '/home/login';
    }
}
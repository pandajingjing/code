<?php
/**
 * login
 *
 * @namespace app\controller\home
 */
namespace app\controller\home;

use app\controller\base;
use member_service\bll\member;
use common_service\bll\session;
use panda\util\strings;

/**
 * login
 */
class login extends base
{

    /**
     * 表单字段
     *
     * @var array
     */
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
        /* 开始获取外部数据 */
        /* 获取外部数据结束 */
        
        /* 开始生成当前控制器所需的变量 */
        /* 生成当前控制器所需的变量结束 */
        
        /* 开始初始化业务逻辑代码所需的变量 */
        /* 初始化业务逻辑代码所需的变量结束 */
        
        /* 控制器逻辑代码开始 */
        /* 控制器逻辑代码结束 */
        
        /* 开始设置外部数据 */
        /* 设置外部数据结束 */
        
        /* 开始设置当前控制器所生成的变量 */
        /* 设置当前控制器所生成的变量结束 */
        
        /* 开始设置业务逻辑代码所生成的变量 */
        /* 设置业务逻辑代码所生成的变量结束 */
        
        // 外界参数
        $sNextAction = $this->getParam('next_act', 'post');
        $sEncodeBackUrl = $this->getParam('back_url', 'router');
        // 本页参数
        // 页面url
        $aPageUrl = [
            'sReg' => $this->createInUrl('\\app\\controller\\home\\reg', [
                'back_url' => $sEncodeBackUrl
            ])
        ];
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
                // debug($aFormData,$aResult,$sBackUrl);
                $sBackUrl = base64_decode($sEncodeBackUrl);
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
        // 外界参数
        // 本页参数
        $this->setPageData('aPageUrl', $aPageUrl);
        // 代码参数
        $this->setPageData('aFormData', $aFormData);
        $this->setPageData('aFormStatus', $aFormStatus);
        return '/home/login';
    }
}
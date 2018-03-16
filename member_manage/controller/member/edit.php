<?php
/**
 * edit
 *
 * @namespace app\controller\member
 */
namespace app\controller\member;

use app\controller\loginbase;
use member_service\bll\member;
use panda\util\strings;

/**
 * edit
 */
class edit extends loginbase
{

    /**
     * 表单映射关系
     *
     * @var array
     */
    private $_aFormField = [
        'nickname' => [
            'sMapping' => 'sNickName',
            'mDefault' => ''
        ],
        'realname' => [
            'sMapping' => 'sRealName',
            'mDefault' => ''
        ],
        'channel' => [
            'sMapping' => 'eChannel',
            'mDefault' => member::CHANNEL_SHOP
        ],
        'mobile' => [
            'sMapping' => 'sMobile',
            'mDefault' => ''
        ],
        'wechat' => [
            'sMapping' => 'sWeChat',
            'mDefault' => ''
        ],
        'regtime' => [
            'sMapping' => 'iRegistrationTime',
            'mDefault' => ''
        ]
    ];

    function doRequest()
    {
        // 外界参数
        $iMemberId = $this->getParam('id', 'router', strings::TYPE_INT, 0);
        $sNextAction = $this->getParam('next_act', 'post');
        // 本页参数
        $iOperatorId = $this->getControllerData(parent::DKEY_OPERATOR_ID);
        $sListingUrl = $this->createInUrl('\\app\\controller\\member\\listing');
        $oBllMember = new member();
        $aChannels = $oBllMember->getChannels();
        $aSelectChannels = [];
        foreach ($aChannels as $sVal) {
            $aSelectChannels[] = [
                'sVal' => $sVal,
                'sLang' => '/member/channel_' . $sVal
            ];
        }
        // 代码参数
        $aFormData = $aFormStatus = [];
        // 业务逻辑
        if ('save_member' == $sNextAction) {
            foreach ($this->_aFormField as $sFormField => $aFieldSet) {
                $aFormData[$aFieldSet['sMapping']] = $this->getParam($sFormField, 'post');
                $aFormStatus[$aFieldSet['sMapping']] = true;
            }
            $aFormData['iAddTime'] = $this->getVisitTime();
            $aResult = $oBllMember->editMember($aFormData, $iMemberId, $iOperatorId);
            if ($aResult['iStatus'] == 1) {} else {
                if ($aFormData['iRegistrationTime'] != '') {
                    $aFormData['iRegistrationTime'] = strtotime($aFormData['iRegistrationTime']);
                }
                $aFormStatus = array_merge($aFormStatus, self::getFormError($aResult));
            }
        } else {
            $this->_aFormField['regtime']['mDefault'] = $this->getVisitTime();
            foreach ($this->_aFormField as $sFormField => $aFieldSet) {
                $aFormData[$aFieldSet['sMapping']] = $aFieldSet['mDefault'];
                $aFormStatus[$aFieldSet['sMapping']] = false;
            }
        }
        // 外界参数
        $this->setPageData('iMemberId', $iMemberId);
        // 本页参数
        $this->setPageData('sListingUrl', $sListingUrl);
        $this->setPageData('aSelectChannels', $aSelectChannels);
        // 代码参数
        $this->setPageData('aFormData', $aFormData);
        $this->setPageData('aFormStatus', $aFormStatus);
        return '/member/edit';
    }
}
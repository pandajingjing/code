<?php
/**
 * edit
 *
 * @namespace app\controller\member
 */
namespace app\controller\member;

use app\controller\loginbase;
use member_service\bll\member;

/**
 * edit
 */
class edit extends loginbase
{

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
        $iMemberId = $this->getParam('id', 'router');
        $sNextAction = $this->getParam('next_act', 'post');
        // 本页参数
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
        if ('save_member' == $sNextAction) {
            foreach ($this->_aFormField as $sFormField => $aFieldSet) {
                $aFormData[$aFieldSet['sMapping']] = $this->getParam($sFormField, 'post');
                $aFormStatus[$aFieldSet['sMapping']] = true;
            }
            $aResult = $oBllMember->editMember($aFormData);
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
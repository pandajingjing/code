<?php
/**
 * member
 *
 * @namespace member_service\bll
 */
namespace member_service\bll;

use panda\lib\sys\bll;
use panda\util\strings;
use panda\util\error;

/**
 * member
 */
class member extends bll
{

    /**
     * 系统管理登录数据的验证规则
     *
     * @var array
     */
    protected $aManageLoginRule = [
        'sUserName' => [
            'bRequire' => true, // 不能为空
            'aLength' => [
                1, // 最短
                50
            ], // 最长
            'eType' => strings::TYPE_NORMAL
        ], // 类型
        'sUserPwd' => [
            'bRequire' => true, // 不能为空
            'aLength' => [
                1, // 最短
                50
            ], // 最长
            'eType' => strings::TYPE_NORMAL
        ]
    ];

    /**
     * 属性字段,可以通过保存方法直接修改
     *
     * @var array
     */
    protected $aManagePropertyFields = [
        'sUserName',
        'sUserPwd'
    ];

    /**
     * 用户渠道-微信
     *
     * @var string
     */
    const CHANNEL_WECHAT = 'wechat';

    /**
     * 用户渠道-实体店
     *
     * @var string
     */
    const CHANNEL_SHOP = 'shop';

    /**
     * 用户渠道-团购app
     *
     * @var string
     */
    const CHANNEL_GROUPAPP = 'groupapp';

    /**
     * 用户渠道列表
     *
     * @var array
     */
    const aCHANNELS = [
        self::CHANNEL_WECHAT,
        self::CHANNEL_SHOP,
        self::CHANNEL_GROUPAPP
    ];

    /**
     * 获取渠道列表
     *
     * @return array
     */
    function getChannels()
    {
        return self::aCHANNELS;
    }

    /**
     * 系统管理用户登录
     *
     * @param string $p_sUserName            
     * @param string $p_sUserPwd            
     * @return array
     */
    function chkManageLogin($p_sUserName, $p_sUserPwd)
    {
        $aResult = self::validData([
            'sUserName' => $p_sUserName,
            'sUserPwd' => $p_sUserPwd
        ], false, $this->aManageLoginRule, $this->aManagePropertyFields);
        if ($aResult['iStatus'] == 0) {
            return $aResult;
        }
        if ($p_sUserName == 'agnes') {
            if ($p_sUserPwd == 'xyaim0511') {
                if (true) { // 判断是否能够登陆系统管理，待扩展
                    return $this->returnRow([
                        'iAutoId' => 1,
                        'sNickName' => $p_sUserName
                    ]);
                } else {
                    return self::returnLogicError('sUserName', error::TYPE_INVALID, '', $p_sUserName);
                }
            } else {
                return self::returnLogicError('sUserPwd', error::TYPE_INVALID, '', '');
            }
        } else {
            return self::returnLogicError('sUserName', error::TYPE_NOT_FOUND, '', $p_sUserName);
        }
    }

    /**
     * 用户信息的验证规则
     *
     * @var array
     */
    protected $aMemberInfoRule = [
        'sNickName' => [
            'bRequire' => true, // 不能为空
            'aLength' => [
                1, // 最短
                50
            ], // 最长
            'eType' => strings::TYPE_NORMAL
        ], // 类型
        'sRealName' => [
            'bRequire' => true, // 不能为空
            'aLength' => [
                1, // 最短
                50
            ], // 最长
            'eType' => strings::TYPE_NORMAL
        ],
        'iRegistrationTime' => [
            'bRequire' => true,
            'eType' => strings::TYPE_DATETIME
        ],
        'eChannel' => [
            'bRequire' => true, // 不能为空
            'eType' => strings::TYPE_ENUM,
            'aRange' => self::aCHANNELS,
            'bMulti' => false
        ],
        'sMobile' => [
            'bRequire' => true,
            'eType' => strings::TYPE_CELLPHONE
        ],
        'sWeChat' => [
            'bRequire' => false,
            'eType' => strings::TYPE_NORMAL,
            'aLength' => [
                0,
                50
            ]
        ]
    ];

    /**
     * 用户信息属性字段,可以通过保存方法直接修改
     *
     * @var array
     */
    protected $aMemberInfoPropertyFields = [
        'sNickName',
        'sRealName',
        'iRegistrationTime',
        'eChannel',
        'sMobile',
        'sWeChat'
    ];

    /**
     * 编辑用户信息
     *
     * @param array $p_aData            
     * @param int $p_iMemberId            
     * @return array
     */
    function editMember($p_aData, $p_iMemberId = 0)
    {
        $aResult = self::validData($p_aData, $p_iMemberId > 0 ? true : false, $this->aMemberInfoRule, $this->aMemberInfoPropertyFields);
        if ($aResult['iStatus'] == 0) {
            return $aResult;
        }
        $p_aData['iRegistrationTime'] = strtotime($p_aData['iRegistrationTime']);
        // return self::returnInfo('sss');
        return self::returnLogicError('eChannel', error::TYPE_LENGTH_LONG, '', '');
    }
}
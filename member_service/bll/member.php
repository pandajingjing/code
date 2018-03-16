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
use member_service\orm\member as orm_member;

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
        ],
        'iAddTime' => [
            'bRequire' => true,
            'eType' => strings::TYPE_INT,
            'bUnsigned' => true
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
        'sWeChat',
        'iAddTime'
    ];

    /**
     * 编辑用户信息
     *
     * @param array $p_aData            
     * @param int $p_iMemberId            
     * @param int $p_iOperatorId            
     * @return array
     */
    function editMember($p_aData, $p_iMemberId, $p_iOperatorId)
    {
        // 验证和过滤数据
        $aResult = self::validData($p_aData, $p_iMemberId > 0 ? true : false, $this->aMemberInfoRule, $this->aMemberInfoPropertyFields);
        if ($aResult['iStatus'] == 0) {
            return $aResult;
        }
        $aSaveData = $aResult['aData'];
        // 如果没有任何需要保存的数据,返回保存成功
        if (empty($aSaveData)) {
            return self::returnPrimary($p_iMemberId);
        }
        // 实例化orm
        $oMember = new orm_member();
        if ($p_iMemberId == 0) {
            // 初始化数据
            $oMember->sNickName = $aSaveData['sNickName'];
            $oMember->sRealName = $aSaveData['sRealName'];
            $oMember->eChannel = $aSaveData['eChannel'];
            $oMember->sMobile = $aSaveData['sMobile'];
            $oMember->sWeChat = $aSaveData['sWeChat'];
            $oMember->iRegistrationTime = strtotime($aSaveData['iRegistrationTime']);
            $oMember->iAddTime = $aSaveData['iAddTime'];
            // 人物关系
            $oMember->iCreatorId = $p_iOperatorId;
            // 默认值
            $oMember->iPlatformScore = 0;
            // 流程初始状态
            // 业务逻辑判断,@todo 各种不能重复的判断
            // 保存数据
            try {
                $mResult = $oMember->addData();
                if ($mResult != false) {
                    return self::returnPrimary($mResult);
                } else {
                    return self::returnSystemError();
                }
            } catch (\Exception $oEx) {
                $this->addBllExLog(get_class($this), __FUNCTION__, $oEx);
                return self::returnSystemError();
            }
        } else {
            $oMember->iAutoId = $p_iMemberId;
            $mMember = $oMember->getDetail();
            // 未找到数据
            if ($mMember == null) {
                return self::returnLogicError('iAutoId', error::TYPE_NOT_FOUND, '', $p_iMemberId);
            }
            
            // 实例化model
            // @todo gogogo
            if ($mPrimary == '') {} else {
                $oData = TestData::get($mPrimary);
                
                // 记录老数据
                $aOldData = $oData->toArray();
                if (! isset($aSaveData['edit_time'])) {
                    $oData->edit_time = time();
                }
            }
            // 业务逻辑判断
            if ($oData->creator_id != $iOperatorID) { // 不是创建者不允许编辑,或许还有别的逻辑
                return $this->_returnLogicError('operator_id', ErrorCollector::TYPE_INVALID, '', $iOperatorID);
            }
            // 保存数据
            try {
                $oData->save($aSaveData);
            } catch (\Exception $oEx) {
                $this->_logError(get_class($this), __FUNCTION__, $oEx->getMessage());
                return $this->_returnSystemError();
            }
            $oLog = new OprLog();
            if ($mPrimary > 0) {
                $oLog->saveEdit(OprLog::LOGNAME_TEST, $oData->id, $oData->toArray(), $aOldData, $iOperatorID);
            } else {
                $oLog->save(OprLog::LOGNAME_TEST, OprLog::ACTION_ADD, $oData->id, $iOperatorID);
            }
            return $this->_returnPrimary($oData->id);
            
            return self::returnLogicError('eChannel', error::TYPE_LENGTH_LONG, '', '');
        }
    }
}
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
     * 登录数据的验证规则
     *
     * @var array
     */
    protected $aLoginRule = [
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
    protected $aPropertyFields = [
        'sUserName',
        'sUserPwd'
    ];

    /**
     * 用户登录
     *
     * @param string $p_sUserName            
     * @param string $p_sUserPwd            
     * @return array
     */
    function chkLogin($p_sUserName, $p_sUserPwd)
    {
        $aResult = self::validData([
            'sUserName' => $p_sUserName,
            'sUserPwd' => $p_sUserPwd
        ], false, $this->aLoginRule, $this->aPropertyFields);
        if ($aResult['iStatus'] == 0) {
            return $aResult;
        }
        if ($p_sUserName == 'agnes') {
            if ($p_sUserPwd == 'xyaim0511') {
                return $this->returnRow([
                    'iAutoId' => 1,
                    'sNickName' => $p_sUserName
                ]);
            } else {
                return self::returnLogicError('sUserPwd', error::TYPE_INVALID, '', '');
            }
        } else {
            return self::returnLogicError('sUserName', error::TYPE_NOT_FOUND, '', $p_sUserName);
        }
    }
}
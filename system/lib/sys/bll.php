<?php
/**
 * bll
 *
 * 业务服务基类
 * @namespace panda\lib\sys
 */
namespace panda\lib\sys;

use panda\lib\traits\response;
use panda\util\strings;
use panda\util\error;

/**
 * bll
 *
 * 业务服务基类
 */
class bll
{
    use response;

    /**
     * 数据校验规则
     *
     * @var array
     */
    protected $aValidRule = [];

    /**
     * 属性字段,可以通过保存方法直接修改
     *
     * @var array
     */
    protected $aPropertyFields = [];

    /**
     * 流程字段,不可以通过保存方法直接修改,需要使用具体的方法修改
     *
     * @var array
     */
    protected $aFlowFields = [];

    /**
     * 构造函数
     *
     * @return void
     */
    function __construct()
    {}

    /**
     * 添加日志
     *
     * @param string $p_sTitle            
     * @param string $p_sContent            
     * @param string $p_sClass            
     * @return void
     */
    protected function addLog($p_sTitle, $p_sContent, $p_sClass = 'common')
    {
        logger::getInstance()->addLog($p_sTitle, $p_sContent, $p_sClass);
    }

    /**
     * 筛选数据
     *
     * 筛选<var>$p_aAllDatas</var>中是否有<var>$p_mValue</var>,如果存在则返回,否则返回<var>$p_mDefault</var>
     *
     * @param array $p_aAllDatas            
     * @param string $p_sColumn            
     * @param mix $p_mValue            
     * @param mix $p_mDefault            
     * @return mix
     */
    protected function filterData($p_aAllDatas, $p_sColumn, $p_mValue, $p_mDefault = null)
    {
        foreach ($p_aAllDatas as $aData) {
            if ($p_mValue == $aData[$p_sColumn]) {
                return $p_mValue;
            }
        }
        return $p_mDefault;
    }

    /**
     * 获取配置信息
     *
     * @param string $p_sKey            
     * @param string $p_sClass            
     * @return mix
     */
    protected function getConfig($p_sKey, $p_sClass = 'common')
    {
        return variable::getInstance()->getConfig($p_sKey, $p_sClass);
    }

    /**
     * 开始模块调试
     *
     * @param string $p_sModule            
     * @return void
     */
    protected function startDebug($p_sModule)
    {
        debugger::getInstance()->startDebug($p_sModule);
    }

    /**
     * 发送调试信息
     *
     * @param string $p_sMsg            
     * @param boolean $p_bIsHtml            
     * @return void
     */
    protected function showDebugMsg($p_sMsg, $p_bIsHtml = false)
    {
        debugger::getInstance()->showMsg($p_sMsg, $p_bIsHtml);
    }

    /**
     * 结束模块调试
     *
     * @param string $p_sModule            
     * @return void
     */
    protected function stopDebug($p_sModule)
    {
        debugger::getInstance()->stopDebug($p_sModule);
    }

    /**
     * 验证数据类型
     *
     * @param array $aRule            
     * @param string $sField            
     * @param mix $mValue            
     * @return true|false
     */
    protected static function validType($aRule, $sField, $mValue)
    {
        if (strings::chkStrType($mValue, $aRule['eType'])) {
            if ($aRule['eType'] == strings::TYPE_ENUM) {
                if ($aRule['multi']) {
                    $aDiff = array_diff($mValue, $aRule['aRange']);
                    if (empty($aDiff)) {
                        return true;
                    } else {
                        error::addFieldError($sField, error::TYPE_INVALID, '', $aDiff);
                        return false;
                    }
                } else {
                    if (in_array($mValue, $aRule['aRange'])) {
                        return true;
                    } else {
                        error::addFieldError($sField, error::TYPE_INVALID, '', $mValue);
                        return false;
                    }
                }
            } else {
                return true;
            }
        } else {
            error::addFieldError($sField, error::TYPE_FORMAT_ERROR, $aRule['eType'], $mValue);
            return false;
        }
    }

    /**
     * 验证长度
     *
     * @param array $aRule            
     * @param string $sField            
     * @param mix $mValue            
     * @return true|false
     */
    protected static function validLength($aRule, $sField, $mValue)
    {
        if (isset($aRule['aLength'])) {
            if (strings::chkStrLength($mValue, $aRule['aLength'][0])) {
                if (strings::chkStrLength($mValue, 0, $aRule['aLength'][1])) {
                    return true;
                } else {
                    error::addFieldError($sField, error::TYPE_LENGTH_LONG, $aRule['aLength'][1], $mValue);
                    return false;
                }
            } else {
                error::addFieldError($sField, error::TYPE_LENGTH_SHORT, $aRule['aLength'][0], $mValue);
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * 验证和过滤用户输入的数据
     *
     * @param array $aData            
     * @param boolean $bIsNew            
     * @param array $aValidRule            
     * @param array $aPropertyFields            
     * @return array
     */
    protected static function validData($aData, $bIsNew, $aValidRule, $aPropertyFields)
    {
        // 字段校验
        error::initError();
        $aSaveData = [];
        foreach ($aValidRule as $sField => $aRule) {
            if (isset($aData[$sField])) {
                $mValue = $aData[$sField];
                if ($aRule['bRequire']) {
                    if ($mValue === '') {
                        error::addFieldError($sField, error::TYPE_EMPTY, 'require', '');
                    } else {
                        if (self::validType($aRule, $sField, $mValue)) {
                            if (self::validLength($aRule, $sField, $mValue)) {
                                $aSaveData[$sField] = $mValue;
                            }
                        }
                    }
                } else {
                    if (self::validType($aRule, $sField, $mValue)) {
                        if (self::validLength($aRule, $sField, $mValue)) {
                            $aSaveData[$sField] = $mValue;
                        }
                    }
                }
            } else {
                if ($bIsNew and $aRule['bRequire']) {
                    error::addFieldError($sField, error::TYPE_EMPTY, 'require', '');
                }
            }
        }
        if (error::isError()) {
            return self::returnValidErrors(error::getErrors());
        } else {
            // 过滤属性字段
            if ($bIsNew) {
                return self::returnRow($aSaveData);
            } else {
                $aPropData = [];
                foreach ($aSaveData as $sField => $mValue) {
                    if (in_array($sField, $aPropertyFields)) {
                        $aPropData[$sField] = $mValue;
                    }
                }
                // foreach ($aPropertyFields as $sField) {
                // if (isset($aSaveData[$sField])) {
                // $aPropData[$sField] = $aSaveData[$sField];
                // }
                // }
                return self::returnRow($aPropData);
            }
        }
    }

    /**
     * 随机返回列表中的数据
     * 为了方便测试,制造数据等.严禁用于生产.
     * 所以是public方法,但使用非public方法的命名规则
     *
     * @param array $aList            
     * @param int $iCount            
     * @return string|array
     */
    static function _fakeList($aList, $iCount = 1)
    {
        if (1 == $iCount) {
            $iIndex = array_rand($aList, 1);
            return $aList[$iIndex];
        } else {
            $aIndexs = array_rand($aList, $iCount);
            $aReturns = [];
            foreach ($aIndexs as $iIndex) {
                $aReturns[] = $aList[$iIndex];
            }
            return $aReturns;
        }
    }

    /**
     * 生成随机字符串数据
     *
     * @param string $sPrefix            
     * @return string
     */
    static function _fakeName($sPrefix = '')
    {
        return $sPrefix . date('His') . strings::getRand(3);
    }

    /**
     * 生成随机手机号码
     *
     * @return string
     */
    static function _fakeCellphone()
    {
        return rand(139000, 139999) . strings::addZero(rand(0, 99999), 5);
    }

    /**
     * 生成随机电话号码
     *
     * @return string
     */
    static function _fakeTelephone()
    {
        return strings::addZero(rand(10, 999), 3) . rand(20000000, 69999999);
    }
}
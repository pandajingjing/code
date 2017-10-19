<?php

/**
 * Response
 *
 * 各类接口的返回规范
 * @namespace panda\lib\traits
 * @package lib_sys
 */
namespace panda\lib\traits;

use panda\util\Error;

/**
 * Response
 *
 * 各类接口的返回规范
 */
trait Response {

    /**
     * 返回主键值
     *
     * 在对数据做增删改操作时使用
     *
     * @param mix $p_mPrimary            
     * @return array
     */
    static function returnPrimary($p_mPrimary)
    {
        return [
            'iStatus' => 1,
            'mPrimary' => $p_mPrimary
        ];
    }

    /**
     * 返回哈希数据
     *
     * 在获取单行数据时使用
     *
     * @param array $p_aRow            
     * @return array
     */
    static function returnRow($p_aRow)
    {
        return [
            'iStatus' => 1,
            'aData' => $p_aRow
        ];
    }

    /**
     * 返回数值
     *
     * 返回数量时使用
     *
     * @param mix $p_iCount            
     * @return array
     */
    static function returnCount($p_iCount)
    {
        return [
            'iStatus' => 1,
            'iCount' => $p_iCount
        ];
    }

    /**
     * 返回列表数据
     *
     * @param array $p_aLists            
     * @param int $p_iTotal            
     * @return array
     */
    static function returnList($p_aLists, $p_iTotal)
    {
        return [
            'iStatus' => 1,
            'aLists' => $p_aLists,
            'iTotal' => $p_iTotal
        ];
    }

    /**
     * 返回消息
     *
     * @param string $p_sInfo            
     * @return array
     */
    static function returnInfo($p_sInfo)
    {
        return [
            'iStatus' => 1,
            'sData' => $p_sInfo
        ];
    }

    /**
     * 返回错误消息
     *
     * @param string $p_sError            
     * @return array
     */
    static function returnError($p_sError)
    {
        return [
            'iStatus' => 0,
            'sError' => $p_sError
        ];
    }

    /**
     * 返回验证错误
     *
     * @param array $p_aErrors            
     * @return array
     */
    static function returnValidErrors($p_aErrors)
    {
        return [
            'iStatus' => 0,
            'sType' => 'validation',
            'aErrors' => $p_aErrors
        ];
    }

    /**
     * 返回逻辑错误数据
     *
     * @param string $sErrField            
     * @param string $sErrType            
     * @param string $sRule            
     * @param mix $mValue            
     * @return array
     */
    static function returnLogicError($p_sErrField, $p_sErrType, $p_sRule, $p_mValue)
    {
        return [
            'iStatus' => 0,
            'sType' => 'logic',
            'aError' => [
                'iCode' => Error::getErrCode($p_sErrType),
                'sField' => $p_sErrField,
                'sType' => $p_sErrType,
                'sRule' => $p_sRule,
                'mValue' => $p_mValue
            ]
        ];
    }

    /**
     * 返回系统错误
     *
     * @param int $p_iCode            
     * @return array
     */
    static function returnSystemError($p_iCode = 503)
    {
        return [
            'iStatus' => 0,
            'sType' => 'system',
            'aError' => [
                'iCode' => $p_iCode,
                'sType' => 'RuntimeException'
            ]
        ];
    }

    /**
     * 将内网传递数据格式转换为外网格式
     *
     * @param array $p_aData            
     * @return array
     */
    static function keyMap($p_aData)
    {
        return $p_aData;
    }
}
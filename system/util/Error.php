<?php

/**
 * Error
 *
 * 错误的定义,收集和返回,可以被业务使用
 * @namespace panda\util
 * @package util
 */
namespace panda\util;

/**
 * Error
 *
 * 错误的定义,收集和返回,可以被业务使用
 */
class Error
{

    /**
     * 错误类型-不在某个可选范围内
     *
     * @var string
     */
    const TYPE_INVALID = 'Invalid';

    /**
     * 错误类型-格式不对
     *
     * @var string
     */
    const TYPE_FORMAT_ERROR = 'Format_Error';

    /**
     * 错误类型-为空
     *
     * @var string
     */
    const TYPE_EMPTY = 'Empty';

    /**
     * 错误类型-未找到
     *
     * @var string
     */
    const TYPE_NOT_FOUND = 'Not_Found';

    /**
     * 错误类型-长度太长
     *
     * @var string
     */
    const TYPE_LENGTH_LONG = 'Length_Long';

    /**
     * 错误类型-长度太短
     *
     * @var string
     */
    const TYPE_LENGTH_SHORT = 'Length_Short';

    /**
     * 错误类型-值过大
     *
     * @var string
     */
    const TYPE_VALUE_BIG = 'Value_Big';

    /**
     * 错误类型-值过小
     *
     * @var string
     */
    const TYPE_VALUE_SMALL = 'Value_Small';

    /**
     * 错误类型-未知错误
     *
     * @var string
     */
    const TYPE_UNKNOWN_ERROR = 'Unknown_Error';

    /**
     * 收集的错误
     *
     * @var array
     */
    private static $_aErrs = [];

    /**
     * 添加字段检验错误
     *
     * 字段校验错误主要发生在bll层或者controller层,当数据不符合业务需要的数据类型或者格式时触发该错误
     *
     * @param string $p_sErrField            
     * @param string $p_sErrType            
     * @param string $p_sRule            
     * @param mix $p_mErrValue            
     * @return void
     */
    static function addFieldError($p_sErrField, $p_sErrType, $p_sRule, $p_mErrValue)
    {
        self::$_aErrs[$p_sErrField] = [
            'iCode' => self::getErrCode($p_sErrType),
            'sType' => $p_sErrType,
            'sRule' => $p_sRule,
            'mValue' => $p_mErrValue
        ];
    }

    /**
     * 错误编码
     *
     * @var array
     */
    const ERROR_CODE = [
        self::TYPE_EMPTY => 400,
        self::TYPE_INVALID => 403,
        self::TYPE_NOT_FOUND => 404,
        self::TYPE_FORMAT_ERROR => 406,
        self::TYPE_UNKNOWN_ERROR => 503,
        self::TYPE_LENGTH_LONG => 1001,
        self::TYPE_LENGTH_SHORT => 1002,
        self::TYPE_VALUE_BIG => 1003,
        self::TYPE_VALUE_SMALL => 1004
    ];

    /**
     * 根据错误类型返回错误编码
     *
     * @param string $p_sErrType            
     * @return int
     */
    static function getErrCode($p_sErrType)
    {
        return self::ERROR_CODE[$p_sErrType] ?: 0;
    }

    /**
     * 是否已经收集到错误
     *
     * @return true|false
     */
    static function isError()
    {
        if (empty(self::$_aErrs)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 获取所有收集到的错误
     *
     * @return array
     */
    static function getErrors()
    {
        return self::$_aErrs;
    }

    /**
     * 获取最后一个错误
     *
     * @return array
     */
    static function getLastError()
    {
        return self::$_aErrs[count(self::$_aErrs) - 1];
    }

    /**
     * 清空错误
     *
     * @return void
     */
    static function initError()
    {
        self::$_aErrs = [];
    }
}
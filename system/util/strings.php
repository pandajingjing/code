<?php
/**
 * data
 * 
 * 数据处理工具
 * @namespace panda\util
 */
namespace panda\util;

/**
 * data
 *
 * 数据处理工具
 */
class strings
{

    /**
     * 删除字符串首尾字符
     *
     * @param mix $p_mValue            
     * @param string $p_sCharList            
     * @return null|true|false|string|array
     */
    static function trimStr($p_mValue, $p_sCharList = ' ')
    {
        if (is_null($p_mValue)) {
            return null;
        } elseif (is_bool($p_mValue)) {
            return $p_mValue;
        } elseif (is_array($p_mValue)) {
            foreach ($p_mValue as $sKey => $mValue) {
                $p_mValue[$sKey] = self::trimStr($mValue, $p_sCharList);
            }
            return $p_mValue;
        } else {
            return trim($p_mValue, $p_sCharList);
        }
    }

    /**
     * 检查类型-日期时间
     *
     * @var string
     */
    const TYPE_DATETIME = 'date_time';

    /**
     * 检查类型-枚举
     *
     * @var string
     */
    const TYPE_ENUM = 'enum';

    /**
     * 检查类型-正常
     *
     * @var string
     */
    const TYPE_NORMAL = 'normal';

    /**
     * 检查类型-整型
     *
     * @var string
     */
    const TYPE_INT = 'int';

    /**
     * 检查类型-浮点型
     *
     * @var string
     */
    const TYPE_FLOAT = 'float';

    /**
     * 检查类型-URL
     *
     * @var string
     */
    const TYPE_URL = 'url';

    /**
     * 检查类型-EMAIL
     *
     * @var string
     */
    const TYPE_EMAIL = 'email';

    /**
     * 检查类型-身份证
     *
     * @var string
     */
    const TYPE_IDCARD = 'idcard';

    /**
     * 检查类型-价格
     *
     * @var string
     */
    const TYPE_PRICE = 'price';

    /**
     * 检查类型-尺寸
     *
     * @var string
     */
    const TYPE_SIZE = 'size';

    /**
     * 检查类型-面积
     *
     * @var string
     */
    const TYPE_AREA = 'area';

    /**
     * 检查类型-手机
     *
     * @var string
     */
    const TYPE_CELLPHONE = 'cellphone';

    /**
     * 检查类型-座机
     *
     * @var string
     */
    const TYPE_PHONE = 'phone';

    /**
     * 检查类型-中文
     *
     * @var string
     */
    const TYPE_CHINESE = 'chinese';

    /**
     * 检查数据类型是否正确
     *
     * @param string $p_mData            
     * @param string $p_sDataType            
     * @return boolean
     */
    static function chkStrType($p_mData, $p_sDataType)
    {
        if ('' == $p_mData) {
            return false;
        }
        switch ($p_sDataType) {
            case self::TYPE_NORMAL:
            case self::TYPE_ENUM:
                return true;
                break;
            case self::TYPE_INT:
                return 0 < preg_match('/^-?[1-9]?\d+$/', $p_mData) ? true : false;
            case self::TYPE_FLOAT:
                return 0 < preg_match('/^-?[1-9]?\d+(\.\d+)?$/', $p_mData) ? true : false;
            case self::TYPE_URL:
                return 0 < preg_match('/^https?:\/\/([a-z0-9-]+\.)+[a-z0-9]{2,4}.*$/', $p_mData) ? true : false;
            case self::TYPE_EMAIL:
                return 0 < preg_match('/^[a-z0-9_+.-]+\@([a-z0-9-]+\.)+[a-z0-9]{2,4}$/i', $p_mData) ? true : false;
            case self::TYPE_IDCARD:
                return 0 < preg_match('/^[0-9]{15}$|^[0-9]{17}[a-zA-Z0-9]/', $p_mData) ? true : false;
            case self::TYPE_AREA:
            case self::TYPE_PRICE:
            case self::TYPE_SIZE:
                return 0 < preg_match('/^\d+(\.\d{1,2})?$/', $p_mData) ? true : false;
            case self::TYPE_CELLPHONE:
                return 0 < preg_match("/^((1[3-9][0-9])|200)[0-9]{8}$/", $p_mData) ? true : false;
            case self::TYPE_PHONE:
                return 0 < preg_match('/^(\d{3,4}-?)?\d{7,8}$/', $p_mData) ? true : false;
            case self::TYPE_CHINESE:
                return 0 < preg_match("/^[\x{4e00}-\x{9fa5}]+$/u", $p_mData) ? true : false;
            case self::TYPE_DATETIME:
                return 0 < strtotime($p_mData) ? true : false;
            default:
                return false;
        }
    }

    /**
     * 检查字符串长度是否正确
     *
     * @param string $p_sData            
     * @param int $p_iMinLength            
     * @param int $p_iMaxLength            
     * @param boolean $p_bMultiByte            
     * @return boolean
     */
    static function chkStrLength($p_sData, $p_iMinLength = 0, $p_iMaxLength = 0, $p_bMultiByte = false)
    {
        if ($p_bMultiByte) {
            $iLen = strlen($p_sData);
        } else {
            $iLen = mb_strlen($p_sData);
        }
        if ($p_iMinLength > 0) {
            if ($p_iMinLength > $iLen) {
                return false;
            }
        }
        if ($p_iMaxLength > 0) {
            if ($p_iMaxLength < $iLen) {
                return false;
            }
        }
        return true;
    }

    /**
     * 默认截取字符串的结尾
     *
     * @var string
     */
    const DEFAULT_SUBFIX = '...';

    /**
     * 截取字符串
     *
     * 根据长度和结尾字符串,截取字符串,并添加结尾字符串
     *
     * @param string $p_sData            
     * @param int $p_iLength            
     * @param string $p_sSubfix            
     * @param boolean $p_bMultiByte            
     * @return string
     */
    static function subStr($p_sData, $p_iLength, $p_sSubfix = self::DEFAULT_SUBFIX, $p_bMultiByte = false)
    {
        if ($p_bMultiByte) {
            if (strlen($p_sData) > $p_iLength) {
                return substr($p_sData, 0, $p_iLength - strlen($p_sSubfix)) . $p_sSubfix;
            } else {
                return $p_sData;
            }
        } else {
            if (mb_strlen($p_sData) > $p_iLength) {
                return mb_substr($p_sData, 0, $p_iLength - mb_strlen($p_sSubfix)) . $p_sSubfix;
            } else {
                return $p_sData;
            }
        }
    }

    /**
     * 默认随机字符串的类型
     *
     * 从左开始:
     * 第一位代表符号
     * 第二位代表数字
     * 第三位代表小写字母
     * 第四位代表大写字母
     *
     * @var string
     */
    const DEFAULT_RAND_STYLE = '0110';

    /**
     * 获取随机字符串
     *
     * 根据类型获取随机字符串,类型可以是符号,数字,小写字母,大写字幕中的一个或者几个
     *
     * @param int $p_iLength            
     * @param string $p_sStyle
     *            四位分别代表符号,数字,小写字母,大写字母
     * @return string
     */
    static function getRandStr($p_iLength, $p_sStyle = self::DEFAULT_RAND_STYLE)
    {
        $iStyle = bindec($p_sStyle);
        if ($iStyle < 1 or $iStyle > 15) {
            $p_sStyle = self::DEFAULT_RAND_STYLE;
        }
        $sStyle = substr('000' . $p_sStyle, - 4);
        $aSource = [
            '`-=[]\\;\',./~!@#$%^&*()_+{}|:"<>?',
            '0123456789',
            'abcdefghijklmnopqrstuvwxyz',
            'ABCEDFGHIJKLMNOPQRSTUVWXYZ'
        ];
        $sSource = '';
        for ($iIndex = 0; $iIndex < 4; ++ $iIndex) {
            if (1 == $sStyle[$iIndex]) {
                $sSource .= $aSource[$iIndex];
            }
        }
        return substr(str_shuffle(str_repeat($sSource, $p_iLength)), 0, $p_iLength);
    }

    /**
     * 左补0
     *
     * @param string $p_sData            
     * @param int $p_iLength            
     * @return string
     */
    static function addZero($p_sData, $p_iLength)
    {
        return substr(str_repeat('0', $p_iLength) . $p_sData, 0 - $p_iLength);
    }
}
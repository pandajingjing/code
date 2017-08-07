<?php

/**
 * Util_Sys_String
 * @author jxu
 * @package system_util_sys
 */

/**
 * 系统字符工具
 *
 * @author jxu
 *        
 */
class Util_Sys_String
{

    /**
     * 得到用户真实数据
     *
     * @param string/array $p_mValue            
     * @return string/array
     */
    static function delSlash($p_mValue)
    {
        if (is_null($p_mValue)) {
            return null;
        } elseif (is_bool($p_mValue)) {
            return $p_mValue;
        } elseif (is_array($p_mValue)) {
            foreach ($p_mValue as $sKey => $mValue) {
                $p_mValue[$sKey] = self::delSlash($mValue);
            }
        } else {
            if (0 != get_magic_quotes_gpc()) {
                $p_mValue = stripslashes($p_mValue);
            }
        }
        return $p_mValue;
    }

    /**
     * 删除字符串首尾字符
     *
     * @param mix $p_mValue            
     * @param string $p_sCharList            
     * @return mix
     */
    static function trimString($p_mValue, $p_sCharList = ' ')
    {
        if (is_null($p_mValue)) {
            return null;
        } elseif (is_bool($p_mValue)) {
            return $p_mValue;
        } elseif (is_array($p_mValue)) {
            foreach ($p_mValue as $sKey => $mValue) {
                $p_mValue[$sKey] = self::trimString($mValue, $p_sCharList);
            }
        } else {
            $p_mValue = trim($p_mValue, $p_sCharList);
        }
        return $p_mValue;
    }

    /**
     * 判断数据类型是否正确
     *
     * @param mix $p_mData            
     * @param string $p_sDataType            
     * @return true/false
     */
    static function chkDataType($p_mData, $p_sDataType)
    {
        if ('' == $p_mData) {
            return false;
        }
        switch ($p_sDataType) {
            case 'i':
            case 'int':
                return 0 < preg_match('/^-?[1-9]?[0-9]*$/', $p_mData) ? true : false;
            case 'url':
                return 0 < preg_match('/^https?:\/\/([a-z0-9-]+\.)+[a-z0-9]{2,4}.*$/', $p_mData) ? true : false;
            case 'email':
                return 0 < preg_match('/^[a-z0-9_+.-]+\@([a-z0-9-]+\.)+[a-z0-9]{2,4}$/i', $p_mData) ? true : false;
            case 'idcard':
                return 0 < preg_match('/^[0-9]{15}$|^[0-9]{17}[a-zA-Z0-9]/', $p_mData) ? true : false;
            case 'area':
            case 'money':
            case 'length':
                return 0 < preg_match('/^\d+(\.\d{1,2})?$/', $p_mData) ? true : false;
            case 'mobile':
                return 0 < preg_match("/^((1[3-9][0-9])|200)[0-9]{8}$/", $p_mData) ? true : false;
            case 'phone':
                return 0 < preg_match('/^(\d{3,4}-?)?\d{7,8}$/', $p_mData) ? true : false;
            case 'chinese':
                return 0 < preg_match("/^[\x{4e00}-\x{9fa5}]+$/u", $p_mData) ? true : false;
            default:
                return false;
        }
    }
}
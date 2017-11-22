<?php

/**
 * Util_Error
 * @author jxu
 * @package movie-service_util
 */

/**
 * Movie系统错误
 *
 * @author jxu
 *        
 */
class Util_Error
{

    /**
     * 错误码
     *
     * @var int
     */
    const ERRCODE_404 = 404;

    /**
     * 错误标签
     *
     * @var array
     */
    private static $_aErrMsg = array(
        self::ERRCODE_404 => 'Resource Not Found.'
    );

    /**
     * 获取系统错误标签
     *
     * @param int $p_iErrCode            
     * @return string
     */
    static function getErrMsg($p_iErrCode)
    {
        return isset(self::$_aErrMsg[$p_iErrCode]) ? self::$_aErrMsg[$p_iErrCode] : 'Unknown Error.';
    }
}
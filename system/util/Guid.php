<?php

/**
 * util_guid
 *
 * guid工具,用于生成guid,可以被业务使用
 * @namespace panda\util
 * @package util
 */
namespace panda\util;

use panda\lib\sys\Variable;

/**
 * util_guid
 *
 * guid工具,用于生成guid,可以被业务使用
 */
class Guid
{

    /**
     * 获取guid
     *
     * 生成符合规范的guid
     *
     * @param string $p_sJoin            
     * @return string
     */
    static function getGuid($p_sJoin = '')
    {
        mt_srand(Variable::getInstance()->getRealTime());
        $sRaw = md5(uniqid(rand(), true));
        return substr($sRaw, 0, 8) . $p_sJoin . substr($sRaw, 8, 4) . $p_sJoin . substr($sRaw, 12, 4) . $p_sJoin . substr($sRaw, 16, 4) . $p_sJoin . substr($sRaw, 20);
    }
}
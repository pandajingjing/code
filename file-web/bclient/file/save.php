<?php

/**
 * bclient_file_save
 * @author jxu
 * @package bclient_file
 */

/**
 * bclient_file_save
 *
 * @author jxu
 */
class bclient_file_save extends bclient_file_base
{

    static function saveInfo($p_sDomainKey, $p_sBiz, $p_sIP, $p_iTime, $p_sFileName, $p_blFile)
    {
        return parent::_call(__CLASS__, __FUNCTION__, func_get_args());
    }
}
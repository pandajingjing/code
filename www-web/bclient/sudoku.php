<?php

/**
 * bclient_sudoku
 * @author jxu
 * @package bclient
 */

/**
 * bclient_sudoku
 *
 * @author jxu
 */
class bclient_sudoku extends lib_sys_bclient
{

    /**
     * 计算数独数据
     *
     * @param array $p_aData            
     * @return array
     */
    static function calSudoku($p_aData)
    {
        return parent::_call(__CLASS__, __FUNCTION__, func_get_args());
    }
}
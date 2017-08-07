<?php

/**
 * controller_sudoku
 * @author jxu
 * @package www-web_controller
 */
/**
 * controller_sudoku
 *
 * @author jxu
 */
class controller_sudoku extends controller_base
{

    function doRequest()
    {
        $aData = [];
        $aOriData = $this->getParam('d','get');
        if ('' == $aOriData) {
            $aOriData = [];
        }
        for ($iRowIndex = 0; $iRowIndex < 9; ++ $iRowIndex) {
            for ($iColIndex = 0; $iColIndex < 9; ++ $iColIndex) {
                $aData[$iRowIndex][$iColIndex] = isset($aOriData[$iRowIndex][$iColIndex]) ? ('' == $aOriData[$iRowIndex][$iColIndex] ? '123456789' : $aOriData[$iRowIndex][$iColIndex]) : '123456789';
            }
        }
        $aData = bclient_sudoku::calSudoku($aData);
        $this->setData('aData', $aData);
        return 'sudoku';
    }
}
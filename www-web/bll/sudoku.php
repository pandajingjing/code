<?php

/**
 * 数独
 *
 * @author jxu
 *
 */
class bll_sudoku extends lib_sys_bll
{

    /**
     * 大九宫格往小九宫格映射
     *
     * @var array
     */
    private $_aTable0 = [
        [
            0,
            0
        ],
        [
            0,
            1
        ],
        [
            0,
            2
        ],
        [
            1,
            0
        ],
        [
            1,
            1
        ],
        [
            1,
            2
        ],
        [
            2,
            0
        ],
        [
            2,
            1
        ],
        [
            2,
            2
        ]
    ];

    /**
     * 大九宫格往小九宫格映射
     *
     * @var array
     */
    private $_aTable1 = [
        [
            0,
            3
        ],
        [
            0,
            4
        ],
        [
            0,
            5
        ],
        [
            1,
            3
        ],
        [
            1,
            4
        ],
        [
            1,
            5
        ],
        [
            2,
            3
        ],
        [
            2,
            4
        ],
        [
            2,
            5
        ]
    ];

    /**
     * 大九宫格往小九宫格映射
     *
     * @var array
     */
    private $_aTable2 = [
        [
            0,
            6
        ],
        [
            0,
            7
        ],
        [
            0,
            8
        ],
        [
            1,
            6
        ],
        [
            1,
            7
        ],
        [
            1,
            8
        ],
        [
            2,
            6
        ],
        [
            2,
            7
        ],
        [
            2,
            8
        ]
    ];

    /**
     * 大九宫格往小九宫格映射
     *
     * @var array
     */
    private $_aTable3 = [
        [
            3,
            0
        ],
        [
            3,
            1
        ],
        [
            3,
            2
        ],
        [
            4,
            0
        ],
        [
            4,
            1
        ],
        [
            4,
            2
        ],
        [
            5,
            0
        ],
        [
            5,
            1
        ],
        [
            5,
            2
        ]
    ];

    /**
     * 大九宫格往小九宫格映射
     *
     * @var array
     */
    private $_aTable4 = [
        [
            3,
            3
        ],
        [
            3,
            4
        ],
        [
            3,
            5
        ],
        [
            4,
            3
        ],
        [
            4,
            4
        ],
        [
            4,
            5
        ],
        [
            5,
            3
        ],
        [
            5,
            4
        ],
        [
            5,
            5
        ]
    ];

    /**
     * 大九宫格往小九宫格映射
     *
     * @var array
     */
    private $_aTable5 = [
        [
            3,
            6
        ],
        [
            3,
            7
        ],
        [
            3,
            8
        ],
        [
            4,
            6
        ],
        [
            4,
            7
        ],
        [
            4,
            8
        ],
        [
            5,
            6
        ],
        [
            5,
            7
        ],
        [
            5,
            8
        ]
    ];

    /**
     * 大九宫格往小九宫格映射
     *
     * @var array
     */
    private $_aTable6 = [
        [
            6,
            0
        ],
        [
            6,
            1
        ],
        [
            6,
            2
        ],
        [
            7,
            0
        ],
        [
            7,
            1
        ],
        [
            7,
            2
        ],
        [
            8,
            0
        ],
        [
            8,
            1
        ],
        [
            8,
            2
        ]
    ];

    /**
     * 大九宫格往小九宫格映射
     *
     * @var array
     */
    private $_aTable7 = [
        [
            6,
            3
        ],
        [
            6,
            4
        ],
        [
            6,
            5
        ],
        [
            7,
            3
        ],
        [
            7,
            4
        ],
        [
            7,
            5
        ],
        [
            8,
            3
        ],
        [
            8,
            4
        ],
        [
            8,
            5
        ]
    ];

    /**
     * 大九宫格往小九宫格映射
     *
     * @var array
     */
    private $_aTable8 = [
        [
            6,
            6
        ],
        [
            6,
            7
        ],
        [
            6,
            8
        ],
        [
            7,
            6
        ],
        [
            7,
            7
        ],
        [
            7,
            8
        ],
        [
            8,
            6
        ],
        [
            8,
            7
        ],
        [
            8,
            8
        ]
    ];

    /**
     * 大九宫格往小九宫格映射
     *
     * @var array
     */
    private $_aMap = [];

    function __construct()
    {
        $this->_aMap = [
            [
                $this->_aTable0,
                $this->_aTable0,
                $this->_aTable0,
                $this->_aTable1,
                $this->_aTable1,
                $this->_aTable1,
                $this->_aTable2,
                $this->_aTable2,
                $this->_aTable2
            ],
            [
                $this->_aTable0,
                $this->_aTable0,
                $this->_aTable0,
                $this->_aTable1,
                $this->_aTable1,
                $this->_aTable1,
                $this->_aTable2,
                $this->_aTable2,
                $this->_aTable2
            ],
            [
                $this->_aTable0,
                $this->_aTable0,
                $this->_aTable0,
                $this->_aTable1,
                $this->_aTable1,
                $this->_aTable1,
                $this->_aTable2,
                $this->_aTable2,
                $this->_aTable2
            ],
            [
                $this->_aTable3,
                $this->_aTable3,
                $this->_aTable3,
                $this->_aTable4,
                $this->_aTable4,
                $this->_aTable4,
                $this->_aTable5,
                $this->_aTable5,
                $this->_aTable5
            ],
            [
                $this->_aTable3,
                $this->_aTable3,
                $this->_aTable3,
                $this->_aTable4,
                $this->_aTable4,
                $this->_aTable4,
                $this->_aTable5,
                $this->_aTable5,
                $this->_aTable5
            ],
            [
                $this->_aTable3,
                $this->_aTable3,
                $this->_aTable3,
                $this->_aTable4,
                $this->_aTable4,
                $this->_aTable4,
                $this->_aTable5,
                $this->_aTable5,
                $this->_aTable5
            ],
            [
                $this->_aTable6,
                $this->_aTable6,
                $this->_aTable6,
                $this->_aTable7,
                $this->_aTable7,
                $this->_aTable7,
                $this->_aTable8,
                $this->_aTable8,
                $this->_aTable8
            ],
            [
                $this->_aTable6,
                $this->_aTable6,
                $this->_aTable6,
                $this->_aTable7,
                $this->_aTable7,
                $this->_aTable7,
                $this->_aTable8,
                $this->_aTable8,
                $this->_aTable8
            ],
            [
                $this->_aTable6,
                $this->_aTable6,
                $this->_aTable6,
                $this->_aTable7,
                $this->_aTable7,
                $this->_aTable7,
                $this->_aTable8,
                $this->_aTable8,
                $this->_aTable8
            ]
        ];
    }

    /**
     * 计算九宫格
     *
     * @param array $p_aData            
     * @return array
     */
    function calSudoku($p_aData)
    {
        // 遍历大九宫格
        for ($iRowIndex = 0; $iRowIndex < 9; ++ $iRowIndex) {
            for ($iColIndex = 0; $iColIndex < 9; ++ $iColIndex) {
                // debug('当前坐标:' . $iRowIndex . '|' . $iColIndex);
                if (isset($p_aData[$iRowIndex][$iColIndex][1])) { // 该单元格还有2个及以上数字
                    $sSource = $p_aData[$iRowIndex][$iColIndex];
                    // debug('源坐标:' . $iRowIndex . '|' . $iColIndex . ',源数字串 :' . $sSource);
                    for ($iSourceIndex = 0; $iSourceIndex < strlen($sSource); ++ $iSourceIndex) { // 看看别的格子里是否还有重复的数字,如果有就删除,直到最后一个,就可以确定
                                                                                                  // debug('查找其他坐标是否有该数字:' . $sSource[$iSourceIndex]);
                        $bFound = false;
                        for ($iIndex = 0; $iIndex < 9; ++ $iIndex) { // 同一行里面寻找
                            if ($iIndex == $iColIndex) { // 跳过自己
                                                         // debug('跳过自己坐标:' . $iRowIndex . '|' . $iIndex);
                                continue;
                            }
                            $sTarget = $p_aData[$iRowIndex][$iIndex];
                            // debug('同行目标坐标:' . $iRowIndex . '|' . $iIndex . ',目标数字串:' . $sTarget);
                            if (false !== strstr($sTarget, $sSource[$iSourceIndex])) { // 找到了
                                                                                       // debug('找到数字:' . $sSource[$iSourceIndex]);
                                $bFound = true;
                                break;
                            }
                        }
                        if (! $bFound) {
                            // debug('同行目标里没有找到:' . $sSource[$iSourceIndex]);
                            $p_aData[$iRowIndex][$iColIndex] = $sSource[$iSourceIndex];
                            continue;
                        }
                        $bFound = false;
                        for ($iIndex = 0; $iIndex < 9; ++ $iIndex) { // 同一列里面寻找
                            if ($iIndex == $iRowIndex) { // 跳过自己
                                                         // debug('跳过自己坐标:' . $iIndex . '|' . $iColIndex);
                                continue;
                            }
                            $sTarget = $p_aData[$iIndex][$iColIndex];
                            // debug('同列目标坐标:' . $iIndex . '|' . $iColIndex . ',目标数字串:' . $sTarget);
                            if (false !== strstr($sTarget, $sSource[$iSourceIndex])) { // 找到了
                                                                                       // debug('找到数字:' . $sSource[$iSourceIndex]);
                                $bFound = true;
                                break;
                            }
                        }
                        if (! $bFound) {
                            // debug('同列目标里没有找到:' . $sSource[$iSourceIndex]);
                            $p_aData[$iRowIndex][$iColIndex] = $sSource[$iSourceIndex];
                            continue;
                        }
                        $bFound = false;
                        $aTable = $this->_aMap[$iRowIndex][$iColIndex];
                        foreach ($aTable as $aCoordinate) { // 删除小九宫格中的数字
                            if (($aCoordinate[0] == $iRowIndex) and ($aCoordinate[1] == $iColIndex)) { // 跳过自己
                                                                                                       // debug('跳过自己坐标:' . $aCoordinate[0] . '|' . $aCoordinate[1]);
                                continue;
                            }
                            $sTarget = $p_aData[$aCoordinate[0]][$aCoordinate[1]];
                            // debug('小九宫格目标坐标:' . $aCoordinate[0] . '|' . $aCoordinate[1] . ',目标数字串:' . $sTarget);
                            if (false !== strstr($sTarget, $sSource[$iSourceIndex])) { // 找到了
                                                                                       // debug('找到数字:' . $sSource[$iSourceIndex]);
                                $bFound = true;
                                break;
                            }
                        }
                        if (! $bFound) {
                            // debug('同小九宫格目标里没有找到:' . $sSource[$iSourceIndex]);
                            $p_aData[$iRowIndex][$iColIndex] = $sSource[$iSourceIndex];
                            continue;
                        }
                    }
                } else {
                    if (isset($p_aData[$iRowIndex][$iColIndex][0])) { // 找到一个单个的数字
                        $iSingleNum = $p_aData[$iRowIndex][$iColIndex];
                        // debug('找到单个数字:' . $iSingleNum . ',' . $iRowIndex . '|' . $iColIndex);
                        for ($iIndex = 0; $iIndex < 9; ++ $iIndex) { // 删除同一行的数字
                            if ($iColIndex != $iIndex) {
                                $p_aData[$iRowIndex][$iIndex] = str_replace($iSingleNum, '', $p_aData[$iRowIndex][$iIndex]);
                            }
                        }
                        for ($iIndex = 0; $iIndex < 9; ++ $iIndex) { // 删除同一列的数字
                            if ($iRowIndex != $iIndex) {
                                $p_aData[$iIndex][$iColIndex] = str_replace($iSingleNum, '', $p_aData[$iIndex][$iColIndex]);
                            }
                        }
                        $aTable = $this->_aMap[$iRowIndex][$iColIndex];
                        foreach ($aTable as $aCoordinate) { // 删除小九宫格中的数字
                            if (($aCoordinate[0] != $iRowIndex) and ($aCoordinate[1] != $iColIndex)) {
                                $p_aData[$aCoordinate[0]][$aCoordinate[1]] = str_replace($iSingleNum, '', $p_aData[$aCoordinate[0]][$aCoordinate[1]]);
                            }
                        }
                    } else { // 已经没有数字了
                        $p_aData[$iRowIndex][$iIndex] = '出现了异常';
                    }
                }
            }
        }
        return $p_aData;
    }
}
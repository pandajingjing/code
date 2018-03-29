<?php
/**
 * bcjh
 *
 * @namespace app\bll\game
 */
namespace app\bll\game;

use panda\lib\sys\bll;
use panda\util\browser;

/**
 * bcjh
 *
 * @namespace app\bll\game
 */
class bcjh extends bll
{

    /**
     * 我拥有的厨师
     *
     * @var array
     */
    private $_aIGetChefs = [
        [
            'iChefId' => 157, // 嫦娥
            'iStirfry' => 16, // 炒
            'iBoil' => 99, // 煮
            'iCut' => 0, // 切
            'iFry' => 66, // 炸
            'iRoast' => 0, // 烤
            'iSteam' => 33 // 蒸
        ],
        [
            'iChefId' => 184, // 湘思
            'iStirfry' => 0, // 炒
            'iBoil' => 22, // 煮
            'iCut' => 91, // 切
            'iFry' => 0, // 炸
            'iRoast' => 45, // 烤
            'iSteam' => 137 // 蒸
        ],
        [
            'iChefId' => 94, // 堂雪
            'iStirfry' => 22, // 炒
            'iBoil' => 137, // 煮
            'iCut' => 0, // 切
            'iFry' => 0, // 炸
            'iRoast' => 45, // 烤
            'iSteam' => 91 // 蒸
        ],
        [
            'iChefId' => 37, // 申公子
            'iStirfry' => 0, // 炒
            'iBoil' => 0, // 煮
            'iCut' => 22, // 切
            'iFry' => 114, // 炸
            'iRoast' => 45, // 烤
            'iSteam' => 114 // 蒸
        ],
        [
            'iChefId' => 4, // 熊麓
            'iStirfry' => 42, // 炒
            'iBoil' => 0, // 煮
            'iCut' => 85, // 切
            'iFry' => 127, // 炸
            'iRoast' => 42, // 烤
            'iSteam' => 0 // 蒸
        ],
        [
            'iChefId' => 1, // 羽十六
            'iStirfry' => 185, // 炒
            'iBoil' => 154, // 煮
            'iCut' => 61, // 切
            'iFry' => 0, // 炸
            'iRoast' => 0, // 烤
            'iSteam' => 30 // 蒸
        ],
        [
            'iChefId' => 238,
            'iStirfry' => 0, // 炒
            'iBoil' => 25, // 煮
            'iCut' => 51, // 切
            'iFry' => 76, // 炸
            'iRoast' => 0, // 烤
            'iSteam' => 51 // 蒸
        ],
        [
            'iChefId' => 211,
            'iStirfry' => 62, // 炒
            'iBoil' => 0, // 煮
            'iCut' => 94, // 切
            'iFry' => 31, // 炸
            'iRoast' => 15, // 烤
            'iSteam' => 0 // 蒸
        ],
        [
            'iChefId' => 124,
            'iStirfry' => 0, // 炒
            'iBoil' => 33, // 煮
            'iCut' => 0, // 切
            'iFry' => 8, // 炸
            'iRoast' => 16, // 烤
            'iSteam' => 49 // 蒸
        ],
        [
            'iChefId' => 112,
            'iStirfry' => 92, // 炒
            'iBoil' => 0, // 煮
            'iCut' => 138, // 切
            'iFry' => 46, // 炸
            'iRoast' => 23, // 烤
            'iSteam' => 0 // 蒸
        ],
        [
            'iChefId' => 106,
            'iStirfry' => 31, // 炒
            'iBoil' => 62, // 煮
            'iCut' => 0, // 切
            'iFry' => 94, // 炸
            'iRoast' => 15, // 烤
            'iSteam' => 0 // 蒸
        ],
        [
            'iChefId' => 121,
            'iStirfry' => 62, // 炒
            'iBoil' => 31, // 煮
            'iCut' => 0, // 切
            'iFry' => 15, // 炸
            'iRoast' => 94, // 烤
            'iSteam' => 0 // 蒸
        ],
        [
            'iChefId' => 109,
            'iStirfry' => 8, // 炒
            'iBoil' => 0, // 煮
            'iCut' => 49, // 切
            'iFry' => 16, // 炸
            'iRoast' => 0, // 烤
            'iSteam' => 33 // 蒸
        ],
        
        [
            'iChefId' => 100,
            'iStirfry' => 138, // 炒
            'iBoil' => 46, // 煮
            'iCut' => 0, // 切
            'iFry' => 92, // 炸
            'iRoast' => 0, // 烤
            'iSteam' => 23 // 蒸
        ],
        [
            'iChefId' => 79, // 盲女
            'iStirfry' => 0, // 炒
            'iBoil' => 92, // 煮
            'iCut' => 138, // 切
            'iFry' => 23, // 炸
            'iRoast' => 46, // 烤
            'iSteam' => 0 // 蒸
        ],
        [
            'iChefId' => 76,
            'iStirfry' => 62, // 炒
            'iBoil' => 0, // 煮
            'iCut' => 0, // 切
            'iFry' => 94, // 炸
            'iRoast' => 15, // 烤
            'iSteam' => 31 // 蒸
        ],
        [
            'iChefId' => 28, // 鱼不斩
            'iStirfry' => 138, // 炒
            'iBoil' => 0, // 煮
            'iCut' => 92, // 切
            'iFry' => 0, // 炸
            'iRoast' => 46, // 烤
            'iSteam' => 23 // 蒸
        ],
        [
            'iChefId' => 25,
            'iStirfry' => 15, // 炒
            'iBoil' => 94, // 煮
            'iCut' => 0, // 切
            'iFry' => 0, // 炸
            'iRoast' => 31, // 烤
            'iSteam' => 62 // 蒸
        ],
        [
            'iChefId' => 22,
            'iStirfry' => 94, // 炒
            'iBoil' => 0, // 煮
            'iCut' => 15, // 切
            'iFry' => 0, // 炸
            'iRoast' => 62, // 烤
            'iSteam' => 31 // 蒸
        ],
        [
            'iChefId' => 19,
            'iStirfry' => 15, // 炒
            'iBoil' => 0, // 煮
            'iCut' => 62, // 切
            'iFry' => 0, // 炸
            'iRoast' => 31, // 烤
            'iSteam' => 94 // 蒸
        ],
        [
            'iChefId' => 97,
            'iStirfry' => 0, // 炒
            'iBoil' => 0, // 煮
            'iCut' => 0, // 切
            'iFry' => 33, // 炸
            'iRoast' => 100, // 烤
            'iSteam' => 66 // 蒸
        ],
        [
            'iChefId' => 73,
            'iStirfry' => 66, // 炒
            'iBoil' => 100, // 煮
            'iCut' => 0, // 切
            'iFry' => 0, // 炸
            'iRoast' => 0, // 烤
            'iSteam' => 33 // 蒸
        ],
        [
            'iChefId' => 70,
            'iStirfry' => 0, // 炒
            'iBoil' => 100, // 煮
            'iCut' => 66, // 切
            'iFry' => 0, // 炸
            'iRoast' => 0, // 烤
            'iSteam' => 33 // 蒸
        ],
        [
            'iChefId' => 67,
            'iStirfry' => 0, // 炒
            'iBoil' => 33, // 煮
            'iCut' => 66, // 切
            'iFry' => 0, // 炸
            'iRoast' => 100, // 烤
            'iSteam' => 0 // 蒸
        ],
        [
            'iChefId' => 58,
            'iStirfry' => 0, // 炒
            'iBoil' => 66, // 煮
            'iCut' => 33, // 切
            'iFry' => 0, // 炸
            'iRoast' => 0, // 烤
            'iSteam' => 100 // 蒸
        ],
        [
            'iChefId' => 46,
            'iStirfry' => 0, // 炒
            'iBoil' => 23, // 煮
            'iCut' => 0, // 切
            'iFry' => 70, // 炸
            'iRoast' => 46, // 烤
            'iSteam' => 0 // 蒸
        ],
        [
            'iChefId' => 34,
            'iStirfry' => 0, // 炒
            'iBoil' => 92, // 煮
            'iCut' => 30, // 切
            'iFry' => 15, // 炸
            'iRoast' => 0, // 烤
            'iSteam' => 61 // 蒸
        ],
        [
            'iChefId' => 31, // 雷椒
            'iStirfry' => 0, // 炒
            'iBoil' => 0, // 煮
            'iCut' => 8, // 切
            'iFry' => 21, // 炸
            'iRoast' => 20, // 烤
            'iSteam' => 4 // 蒸
        ],
        [
            'iChefId' => 16,
            'iStirfry' => 66, // 炒
            'iBoil' => 100, // 煮
            'iCut' => 0, // 切
            'iFry' => 0, // 炸
            'iRoast' => 0, // 烤
            'iSteam' => 33 // 蒸
        ],
        [
            'iChefId' => 13,
            'iStirfry' => 0, // 炒
            'iBoil' => 0, // 煮
            'iCut' => 61, // 切
            'iFry' => 30, // 炸
            'iRoast' => 92, // 烤
            'iSteam' => 15 // 蒸
        ],
        [
            'iChefId' => 10,
            'iStirfry' => 40, // 炒
            'iBoil' => 0, // 煮
            'iCut' => 40, // 切
            'iFry' => 120, // 炸
            'iRoast' => 0, // 烤
            'iSteam' => 0 // 蒸
        ],
        [
            'iChefId' => 7,
            'iStirfry' => 0, // 炒
            'iBoil' => 80, // 煮
            'iCut' => 0, // 切
            'iFry' => 0, // 炸
            'iRoast' => 20, // 烤
            'iSteam' => 100 // 蒸
        ],
        [
            'iChefId' => 238,
            'iStirfry' => 0, // 炒
            'iBoil' => 13, // 煮
            'iCut' => 27, // 切
            'iFry' => 40, // 炸
            'iRoast' => 0, // 烤
            'iSteam' => 27 // 蒸
        ],
        [
            'iChefId' => 52, // 面小解
            'iStirfry' => 0, // 炒
            'iBoil' => 0, // 煮
            'iCut' => 18, // 切
            'iFry' => 4, // 炸
            'iRoast' => 4, // 烤
            'iSteam' => 27 // 蒸
        ],
        [
            'iChefId' => 91, // 苏吴安
            'iStirfry' => 0, // 炒
            'iBoil' => 0, // 煮
            'iCut' => 46, // 切
            'iFry' => 23, // 炸
            'iRoast' => 0, // 烤
            'iSteam' => 70 // 蒸
        ],
        [
            'iChefId' => 64, // 熊妮
            'iStirfry' => 10, // 炒
            'iBoil' => 0, // 煮
            'iCut' => 64, // 切
            'iFry' => 43, // 炸
            'iRoast' => 0, // 烤
            'iSteam' => 21 // 蒸
        ],
        [
            'iChefId' => 61, // 椒娃
            'iStirfry' => 0, // 炒
            'iBoil' => 7, // 煮
            'iCut' => 0, // 切
            'iFry' => 21, // 炸
            'iRoast' => 14, // 烤
            'iSteam' => 10 // 蒸
        ],
        [
            'iChefId' => 49, // 傣弟
            'iStirfry' => 0, // 炒
            'iBoil' => 43, // 煮
            'iCut' => 10, // 切
            'iFry' => 21, // 炸
            'iRoast' => 0, // 烤
            'iSteam' => 64 // 蒸
        ]
    ];

    /**
     * 我拥有的菜谱
     *
     * @var array
     */
    private $_aIGetRecipes = [
        1,
        2,
        3,
        4,
        5,
        6,
        9,
        10,
        11,
        12,
        13,
        14,
        15,
        17,
        18,
        19,
        20,
        21,
        26,
        28,
        30,
        31,
        32,
        33,
        34,
        35,
        36,
        37,
        38,
        40,
        43,
        44,
        46,
        49,
        51,
        54,
        55,
        58,
        62,
        63,
        64,
        65,
        68,
        69,
        70,
        72,
        74,
        75,
        78,
        80,
        81,
        82,
        85,
        89,
        90,
        91,
        93,
        97,
        99,
        102,
        106,
        108,
        109,
        118,
        121,
        122,
        127,
        128,
        130,
        133,
        141,
        142,
        147,
        153,
        155,
        157,
        158,
        163,
        164,
        165,
        166,
        168,
        170,
        172,
        183,
        223,
        238,
        240,
        246,
        247,
        248,
        254
    ];

    /**
     * 菜单
     *
     * @var array
     */
    private $_aRecipes = [];

    /**
     * 探索场地
     *
     * @var array
     */
    private $_aOrigins = [];

    /**
     * 食材
     *
     * @var array
     */
    private $_aIngredients = [];

    /**
     * 符文
     *
     * @var array
     */
    private $_aRunes = [];

    /**
     * 贵宾
     *
     * @var array
     */
    private $_aGuests = [];

    /**
     * 厨师
     *
     * @var array
     */
    private $_aChefs = [];

    /**
     * 构造函数,获取全部的数据
     */
    function __construct()
    {
        parent::__construct();
        $mData = $this->getCache(__FUNCTION__, func_get_args());
        if (false === $mData) {
            // https://foodgame.github.io/
            browser::setOption(CURLOPT_TIMEOUT_MS, 10000);
            $mData = browser::getData('https://foodgame.github.io/data/data.json?_=1519134097387');
            if (false !== $mData) {
                $this->setCache($mData, __FUNCTION__, func_get_args(), 604800);
            }
        }
        isset($mData['recipes']) ? $this->_aRecipes = $mData['recipes'] : '';
        isset($mData['origins']) ? $this->_aOrigins = $mData['origins'] : '';
        isset($mData['ingredients']) ? $this->_aIngredients = $mData['ingredients'] : '';
        isset($mData['runes']) ? $this->_aRunes = $mData['runes'] : '';
        isset($mData['guests']) ? $this->_aGuests = $mData['guests'] : '';
        isset($mData['chefs']) ? $this->_aChefs = $mData['chefs'] : '';
    }

    /**
     * 获取菜单
     *
     * @return array
     */
    function getRecipes()
    {
        foreach ($this->_aRecipes as $iIndex => $aRecipe) {
            if (in_array($aRecipe['recipeId'], $this->_aIGetRecipes)) {
                $this->_aRecipes[$iIndex]['iGet'] = true;
            } else {
                $this->_aRecipes[$iIndex]['iGet'] = false;
            }
            $this->_aRecipes[$iIndex]['unlockId'] = 0;
            foreach ($this->_aRecipes as $aUnlockRecipe) {
                if ($aRecipe['unlock'] == $aUnlockRecipe['name']) {
                    $this->_aRecipes[$iIndex]['unlockId'] = $aUnlockRecipe['recipeId'];
                    continue 2;
                }
            }
        }
        return $this->_aRecipes;
    }

    /**
     * 获取我可以解锁的菜单
     */
    function getICanUnlockRecipes()
    {
        $aAllRecipes = $this->getRecipes();
        $aIGetIds = $aIMissIds = [];
        foreach ($aAllRecipes as $aRecipe) {
            if ($aRecipe['iGet']) {
                $aIGetIds[] = $aRecipe['recipeId'];
            } else {
                $aIMissIds[] = $aRecipe['recipeId'];
            }
        }
        $aICanUnlockRecipes = [];
        foreach ($aAllRecipes as $aRecipe) {
            if (in_array($aRecipe['unlockId'], $aIMissIds) and in_array($aRecipe['recipeId'], $aIGetIds)) {
                $aICanUnlockRecipes[] = $aRecipe;
            }
        }
        usort($aICanUnlockRecipes, [
            $this,
            '_orderRecipe'
        ]);
        return $this->_joinGodChef($aICanUnlockRecipes);
    }

    /**
     * 菜单排序,按照需求技能之和排序
     *
     * @param array $a            
     * @param array $b            
     * @return int
     */
    private function _orderRecipe($a, $b)
    {
        $iTotalA = $a['stirfry'] + $a['boil'] + $a['cut'] + $a['fry'] + $a['roast'] + $a['steam'];
        $iTotalB = $b['stirfry'] + $b['boil'] + $b['cut'] + $b['fry'] + $b['roast'] + $b['steam'];
        if ($iTotalA > $iTotalB) {
            return - 1;
        } else {
            if ($iTotalA == $iTotalB) {
                return 0;
            } else {
                return 1;
            }
        }
    }

    /**
     * 查找厨师
     *
     * @param array $p_aRecipes            
     * @return array
     */
    private function _joinChef($p_aRecipes)
    {
        $aIGetChefs = $this->getIGetChefs();
        foreach ($p_aRecipes as $iIndex => $aRecipe) {
            $aGodChefNames = [];
            foreach ($aIGetChefs as $aIGetChef) {
                if (($aRecipe['stirfry'] <= $aIGetChef['iStirfry']) and ($aRecipe['boil'] <= $aIGetChef['iBoil']) and ($aRecipe['cut'] <= $aIGetChef['iCut']) and ($aRecipe['fry'] <= $aIGetChef['iFry']) and ($aRecipe['roast'] <= $aIGetChef['iRoast']) and ($aRecipe['steam'] <= $aIGetChef['iSteam'])) {
                    $aGodChefNames[] = $aIGetChef['sName'];
                }
            }
            $p_aRecipes[$iIndex]['aGodChefNames'] = $aGodChefNames;
        }
        return $p_aRecipes;
    }

    /**
     * 查找厨师
     *
     * @param array $p_aRecipes            
     * @return array
     */
    private function _joinGodChef($p_aRecipes)
    {
        $aIGetChefs = $this->getIGetChefs();
        foreach ($p_aRecipes as $iIndex => $aRecipe) {
            $aGodChefNames = [];
            foreach ($aIGetChefs as $aIGetChef) {
                if (($aRecipe['stirfry'] * 4 <= $aIGetChef['iStirfry']) and ($aRecipe['boil'] * 4 <= $aIGetChef['iBoil']) and ($aRecipe['cut'] * 4 <= $aIGetChef['iCut']) and ($aRecipe['fry'] * 4 <= $aIGetChef['iFry']) and ($aRecipe['roast'] * 4 <= $aIGetChef['iRoast']) and ($aRecipe['steam'] * 4 <= $aIGetChef['iSteam'])) {
                    $aGodChefNames[] = $aIGetChef['sName'];
                }
            }
            $p_aRecipes[$iIndex]['aGodChefNames'] = $aGodChefNames;
        }
        return $p_aRecipes;
    }

    /**
     * 需要获取的符文
     *
     * @var array
     */
    private $_aIWantRunNames = [
        '蒸汽宝石', // 银
        '一昧真火', // 银
        '耐住的水草', // 银
        '油火虫', // 铜
        '烤焦的菊花', // 铜
        '蒸汽耳环', // 铜
        '防水的柠檬', // 铜
        '五香果' // 铜
    ];

    /**
     * 获取需要获取的符文
     *
     * @return array
     */
    function getIWantRuneNames()
    {
        return $this->_aIWantRunNames;
    }

    /**
     * 获取符文的厨师
     */
    function getIWantRunesChefs()
    {
        $aRuneNames = $this->getIWantRuneNames();
        $aAllGuests = $this->getGuests();
        $aAllRecipeNames = [];
        foreach ($aAllGuests as $aGuest) {
            $aRecipeNames = [];
            $bIGetRune = false;
            foreach ($aGuest['gifts'] as $aGift) {
                $aRecipeNames[] = $aGift['recipe'];
                if (in_array($aGift['rune'], $aRuneNames)) {
                    $bIGetRune = true;
                }
            }
            if ($bIGetRune) {
                $aAllRecipeNames = array_merge($aAllRecipeNames, $aRecipeNames);
            }
        }
        $aAllRecipeNames = array_unique($aAllRecipeNames);
        $aAllRecipes = $this->getRecipes();
        $aIWantRunesRecipes = [];
        foreach ($aAllRecipes as $aRecipes) {
            if ($aRecipes['iGet'] and in_array($aRecipes['name'], $aAllRecipeNames)) {
                $aIWantRunesRecipes[] = $aRecipes;
            }
        }
        usort($aIWantRunesRecipes, [
            $this,
            '_orderRecipe'
        ]);
        $aIWantRunesRecipes = $this->_joinChef($aIWantRunesRecipes);
        // debug($aIWantRunesRecipes);
        $aIWantRunesChefs = [];
        foreach ($aIWantRunesRecipes as $aRecipe) {
            foreach ($aRecipe['aGodChefNames'] as $sChefName) {
                if (isset($aIWantRunesChefs[$sChefName])) {
                    $aIWantRunesChefs[$sChefName]['aRecipes'][] = $aRecipe['name'];
                } else {
                    $aIWantRunesChefs[$sChefName] = [
                        'sName' => $sChefName,
                        'aRecipes' => [
                            $aRecipe['name']
                        ]
                    ];
                }
            }
        }
        usort($aIWantRunesChefs, [
            $this,
            '_sortRunesChefs'
        ]);
        return $aIWantRunesChefs;
    }

    /**
     * 排序符文厨师
     *
     * @param array $a            
     * @param array $b            
     * @return int
     */
    private function _sortRunesChefs($a, $b)
    {
        $iCntA = count($a['aRecipes']);
        $iCntB = count($b['aRecipes']);
        if ($iCntA > $iCntB) {
            return - 1;
        } elseif ($iCntA < $iCntB) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * 获取探索场地
     *
     * @return array
     */
    function getOrigins()
    {
        return $this->_aOrigins;
    }

    /**
     * 获取食材
     *
     * @return array
     */
    function getIngredients()
    {
        return $this->_aIngredients;
    }

    /**
     * 获取符文
     *
     * @return array
     */
    function getRunes()
    {
        return $this->_aRunes;
    }

    /**
     * 获取贵宾
     *
     * @return array
     */
    function getGuests()
    {
        return $this->_aGuests;
    }

    /**
     * 获取厨师
     *
     * @return array
     */
    function getChefs()
    {
        return $this->_aChefs;
    }

    /**
     * 获取我得到的厨师
     *
     * @return array
     */
    function getIGetChefs()
    {
        $aAllChefs = $this->getChefs();
        $aIGetChefs = $this->_aIGetChefs;
        foreach ($aIGetChefs as $iIndex => $aIGetChef) {
            foreach ($aAllChefs as $aChef) {
                if ($aIGetChef['iChefId'] == $aChef['chefId']) {
                    $aIGetChefs[$iIndex]['sName'] = $aChef['name'];
                    continue 2;
                }
            }
        }
        return $aIGetChefs;
    }

    /**
     * 获取我得到的菜单
     *
     * @return array
     */
    function getIGetRecipes()
    {
        $aIGetRecipes = [];
        $aAllRecipes = $this->getRecipes();
        foreach ($aAllRecipes as $aRecipes) {
            if ($aRecipes['iGet']) {
                $aIGetRecipes[] = $aRecipes;
            }
        }
        usort($aIGetRecipes, [
            $this,
            '_orderRecipe'
        ]);
        return $aIGetRecipes;
    }
}
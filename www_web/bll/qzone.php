<?php

/**
 * bll_qzone
 * @author jxu
 * @package bll
 */

/**
 * bll_qzone
 *
 * @author jxu
 */
class bll_qzone extends lib_sys_bll
{

    /**
     * 文章列表
     *
     * @var array
     */
    private $_aArticleKeys = [];

    /**
     * 顶部文章列表
     *
     * @var array
     */
    private $_aTopKeys = [];

    function __construct()
    {
        parent::__construct();
        $this->_aArticleKeys[] = 'in_my_dream';
        for ($iIndex = 13; $iIndex > 0; -- $iIndex) {
            $sKey = '2016dbz_' . substr('00' . $iIndex, - 2);
            $this->_aArticleKeys[] = $sKey;
            $this->_aTopKeys[] = $sKey;
        }
    }

    /**
     * 获取文章列表
     *
     * @param int $p_iPage            
     * @param int $p_iPageSize            
     * @return array
     */
    function getArticleKeyList($p_iPage = 1, $p_iPageSize = 20)
    {
        if (is_numeric($p_iPage) and $p_iPage > 0) {
            $p_iStart = ($p_iPage - 1) * $p_iPageSize;
        } else {
            $p_iStart = 0;
        }
        $aTemp = $this->_aArticleKeys;
        return $this->returnList(array_splice($aTemp, $p_iStart, $p_iPageSize), count($this->_aArticleKeys));
    }

    /**
     * 获取顶部文章列表
     *
     * @return array
     */
    function getTopArticleKeyList()
    {
        return $this->returnList($this->_aTopKeys, count($this->_aTopKeys));
    }

    /**
     * 文章是否存在
     *
     * @param string $p_sKey            
     * @return boolean
     */
    function isExist($p_sKey)
    {
        return in_array($p_sKey, $this->_aArticleKeys);
    }
}
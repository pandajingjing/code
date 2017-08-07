<?php

/**
 * bclient_qzone
 * @author jxu
 * @package bclient
 */

/**
 * bclient_qzone
 *
 * @author jxu
 */
class bclient_qzone extends lib_sys_bclient
{

    /**
     * 获取顶部文章列表
     *
     * @return array
     */
    static function getTopArticleKeyList()
    {
        return parent::_call(__CLASS__, __FUNCTION__, func_get_args());
    }
}
<?php
/**
 * miss
 *
 * @namespace app\controller\home
 */
namespace app\controller\home;

use app\controller\base;

/**
 * home
 */
class miss extends base
{

    function doRequest()
    {
        /* 开始获取外部数据 */
        /* 获取外部数据结束 */
        
        /* 开始生成当前控制器所需的变量 */
        $sHomeUrl = $this->createInUrl('\\app\\controller\\home\\home');
        /* 生成当前控制器所需的变量结束 */
        
        /* 开始初始化业务逻辑代码所需的变量 */
        /* 初始化业务逻辑代码所需的变量结束 */
        
        /* 控制器逻辑代码开始 */
        /* 控制器逻辑代码结束 */
        
        /* 开始设置外部数据 */
        /* 设置外部数据结束 */
        
        /* 开始设置当前控制器所生成的变量 */
        $this->setPageData('sHomeUrl', $sHomeUrl);
        /* 设置当前控制器所生成的变量结束 */
        
        /* 开始设置业务逻辑代码所生成的变量 */
        /* 设置业务逻辑代码所生成的变量结束 */
        return '/home/404';
    }
}
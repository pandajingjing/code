<?php
/**
 * logout
 *
 * @namespace app\controller\home
 */
namespace app\controller\home;

use common_service\bll\session;
use panda\util\strings;
use app\controller\base;

/**
 * logout
 */
class logout extends base
{

    function doRequest()
    {
        /* 开始获取外部数据 */
        $sEncodeBackUrl = $this->getParam('back_url', 'router');
        /* 获取外部数据结束 */
        
        /* 开始生成当前控制器所需的变量 */
        $oBllSession = $this->getControllerData(parent::DKEY_SESSION);
        $sBackUrl = base64_decode($sEncodeBackUrl);
        $iMemberId = $oBllSession->get(session::KEY_MEMBER_ID);
        /* 生成当前控制器所需的变量结束 */
        
        /* 开始初始化业务逻辑代码所需的变量 */
        /* 初始化业务逻辑代码所需的变量结束 */
        
        /* 控制器逻辑代码开始 */
        if ($iMemberId == null or $iMemberId == 0) {
            $this->redirectUrl($this->createInUrl('\\app\\controller\\home\\login'));
        } else {
            $oBllSession->clear(session::KEY_MEMBER_ID);
            if (strings::chkStrType($sBackUrl, strings::TYPE_URL)) {
                $this->redirectUrl($sBackUrl);
            } else {
                $this->redirectUrl($this->createInUrl('\\app\\controller\\home\\login'));
            }
        }
        /* 控制器逻辑代码结束 */
        
        /* 开始设置外部数据 */
        /* 设置外部数据结束 */
        
        /* 开始设置当前控制器所生成的变量 */
        /* 设置当前控制器所生成的变量结束 */
        
        /* 开始设置业务逻辑代码所生成的变量 */
        /* 设置业务逻辑代码所生成的变量结束 */
    }
}
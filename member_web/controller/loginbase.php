<?php
/**
 * loginbase
 * 
 * @namespace app\controller
 */
namespace app\controller;

use common_service\bll\session;

/**
 * loginbase
 */
abstract class loginbase extends base
{

    /**
     * 当前登陆用户ID
     *
     * @var int
     */
    const DKEY_MEMBER_ID = 'iMemberId';

    /**
     * 在控制器开始时执行（调度使用）
     */
    function beforeRequest()
    {
        parent::beforeRequest();
        // do something
        /* 开始获取外部数据 */
        $sCurrentUrl = $this->getParam('INTEGRATED_URL', 'server');
        /* 获取外部数据结束 */
        
        /* 开始生成当前控制器所需的变量 */
        $sEncodeCurrentUrl = base64_encode($sCurrentUrl);
        $sLogoutUrl = $this->createInUrl('\\app\\controller\\home\\logout', [
            'back_url' => $sEncodeCurrentUrl
        ]); // 退出url
        /* 生成当前控制器所需的变量结束 */
        
        /* 开始初始化业务逻辑代码所需的变量 */
        $oBllSession = $this->getControllerData(parent::DKEY_SESSION);
        /* 初始化业务逻辑代码所需的变量结束 */
        
        /* 控制器逻辑代码开始 */
        // 判断是否登录
        $mMemberId = $oBllSession->get(session::KEY_MEMBER_ID);
        if ($mMemberId == null or $mMemberId == 0) {
            $this->redirectUrl($this->createInUrl('\\app\\controller\\home\\login', [
                'back_url' => $sEncodeCurrentUrl
            ]));
        } else {
            $oBllSession->set(session::KEY_MEMBER_ID, $mMemberId);
            $this->setControllerData(static::DKEY_MEMBER_ID, $mMemberId);
        }
        /* 控制器逻辑代码结束 */
        
        /* 开始设置外部数据 */
        /* 设置外部数据结束 */
        
        /* 开始设置当前控制器所生成的变量 */
        $this->setPageData('sLogoutUrl', $sLogoutUrl);
        /* 设置当前控制器所生成的变量结束 */
        
        /* 开始设置业务逻辑代码所生成的变量 */
        /* 设置业务逻辑代码所生成的变量结束 */
    }

    /**
     * 在控制器结束时执行（调度使用）
     */
    function afterRequest()
    {
        // do something
        parent::afterRequest();
    }
}

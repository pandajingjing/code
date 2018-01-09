<?php
/**
 * base
 * 
 * @namespace app\controller
 */
namespace app\controller;

use member_service\bll\session;

/**
 * base
 */
abstract class loginbase extends base
{

    /**
     * 在控制器开始时执行（调度使用）
     */
    function beforeRequest()
    {
        parent::beforeRequest();
        // 判断是否登录
        $oBllSession = $this->getControllerData(parent::DKEY_SESSION);
        $mMemberId = $oBllSession->get(session::KEY_MEMBER_ID);
        $sUrl = $this->getParam('INTEGRATED_URL', 'server');
        if ($mMemberId == null or $mMemberId == 0) {
            $this->redirectUrl($this->createInUrl('\\app\\controller\\home\\login', [
                'back_url' => base64_encode($sUrl)
            ]));
        } else {
            $oBllSession->set(session::KEY_MEMBER_ID, $mMemberId);
        }
        // 补充登陆后的顶部菜单
        $aTopUrls = $this->getControllerData('aTopUrls');
        $aTopUrls['aStock']['sAddNew'] = $this->createInUrl('\\app\\controller\\stock\\edit');
        $aTopUrls['aStock']['sListing'] = $this->createInUrl('\\app\\controller\\stock\\listing');
        $aTopUrls['sLogOut'] = $this->createInUrl('\\app\\controller\\home\\logout', [
            'back_url' => base64_encode($sUrl)
        ]);
        $this->setPageData('aTopUrls', $aTopUrls);
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
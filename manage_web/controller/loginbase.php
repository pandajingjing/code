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
            $this->setControllerData(parent::DKEY_OPERATOR_ID, $mMemberId);
        }
        // 顶部菜单
        $aTopUrl = [];
        // 首页
        $aTopUrl['sDefault'] = $this->createInUrl('\\app\\controller\\home\\home');
        // 会员管理-开始
        $aTopUrl['aMember']['sAddNew'] = $this->createInUrl('\\app\\controller\\member\\edit');
        $aTopUrl['aMember']['sListing'] = $this->createInUrl('\\app\\controller\\member\\listing');
        // 会员管理-结束
        $aTopUrl['sLogOut'] = $this->createInUrl('\\app\\controller\\home\\logout', [
            'back_url' => base64_encode($sUrl)
        ]);
        $this->setPageData('aTopUrl', $aTopUrl);
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

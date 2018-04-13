<?php
/**
 * base
 *
 * @namespace app\controller
 */
namespace app\controller;

use panda\lib\controller\web;
use panda\util\guid;
use common_service\bll\session;

/**
 * base
 */
abstract class base extends web
{

    /**
     * session数据
     *
     * @var string
     */
    const DKEY_SESSION = 'oSession';

    /**
     * 在控制器开始时执行（调度使用）
     */
    function beforeRequest()
    {
        parent::beforeRequest();
        // do something
        /* 开始获取外部数据 */
        $sGuid = $this->getParam('guid', 'cookie');
        $iVisitTime = $this->getVisitTime();
        /* 获取外部数据结束 */
        
        /* 开始生成当前控制器所需的变量 */
        $aTopUrl = []; // 顶部菜单
        $aTopUrl['sHome'] = $this->createInUrl('\\app\\controller\\home\\home'); // 当前首页
        $aTopUrl['sWWW'] = $this->createOutUrl('sWwwSchemeDomain', 'sHome'); // www首页
        $aTopUrl['sWiki'] = $this->createOutUrl('sWikiSchemeDomain', 'sHome'); // wiki首页
        $aTopUrl['sMall'] = $this->createOutUrl('sMallSchemeDomain', 'sHome'); // 商城首页
        $aTopUrl['aMember']['sHome'] = $this->createOutUrl('sMemberSchemeDomain', 'sHome'); // 用户中心-首页
        /* 用户中心-账户与安全-开始 */
        $aTopUrl['aMember']['aInfo']['sEditBase'] = $this->createOutUrl('sMemberSchemeDomain', 'sInfoEditBase'); // 修改基本信息
        $aTopUrl['aMember']['aInfo']['sEditPwd'] = $this->createOutUrl('sMemberSchemeDomain', 'sInfoEditPwd'); // 修改密码
        $aTopUrl['aMember']['aInfo']['sBindMobile'] = $this->createOutUrl('sMemberSchemeDomain', 'sInfoEditPwd'); // 绑定手机
        /* 用户中心-账户与安全-结束 */
        /* 用户中心-我的积分-开始 */
        $aTopUrl['aMember']['aScore']['sHome'] = $this->createOutUrl('sMemberSchemeDomain', 'sScoreHome'); // 积分首页
        $aTopUrl['aMember']['aScore']['sListing'] = $this->createOutUrl('sMemberSchemeDomain', 'sScoreListing'); // 积分明细
        /* 用户中心-我的积分-结束 */
        /* 生成当前控制器所需的变量结束 */
        
        /* 开始初始化业务逻辑代码所需的变量 */
        /* 初始化业务逻辑代码所需的变量结束 */
        
        /* 控制器逻辑代码开始 */
        if ('' == $sGuid) {
            $sGuid = guid::getGuid();
        }
        $oBllSession = new session($sGuid, $iVisitTime); // 加载session
        $oBllSession->setClientIp($this->getParam('CLIENTIP', 'server'));
        $oBllSession->setUserAgent($this->getParam('HTTP_USER_AGENT', 'server'));
        $oBllSession->load();
        /* 控制器逻辑代码结束 */
        
        /* 开始设置外部数据 */
        $this->setCookie('guid', $sGuid, 31536000);
        $this->setPageData('sRemoteIp', $this->getParam('CLIENTIP', 'server'));
        /* 设置外部数据结束 */
        
        /* 开始设置当前控制器所生成的变量 */
        $this->setPageData('aTopUrl', $aTopUrl);
        $this->setPageData('iVisitTime', $iVisitTime);
        /* 设置当前控制器所生成的变量结束 */
        
        /* 开始设置业务逻辑代码所生成的变量 */
        $this->setControllerData(self::DKEY_SESSION, $oBllSession);
        /* 设置业务逻辑代码所生成的变量结束 */
    }

    /**
     * 在控制器结束时执行（调度使用）
     */
    function afterRequest()
    {
        // do something
        /* 开始获取外部数据 */
        /* 获取外部数据结束 */
        
        /* 开始生成当前控制器所需的变量 */
        /* 生成当前控制器所需的变量结束 */
        
        /* 开始初始化业务逻辑代码所需的变量 */
        $oBllSession = $this->getControllerData(self::DKEY_SESSION);
        /* 初始化业务逻辑代码所需的变量结束 */
        
        /* 控制器逻辑代码开始 */
        $oBllSession->save();
        $fScriptEndTime = $this->getRealTime(true);
        /* 控制器逻辑代码结束 */
        
        /* 开始设置外部数据 */
        /* 设置外部数据结束 */
        
        /* 开始设置当前控制器所生成的变量 */
        $this->setPageData('fScriptTime', $fScriptEndTime - PANDA_STARTTIME);
        /* 设置当前控制器所生成的变量结束 */
        
        /* 开始设置业务逻辑代码所生成的变量 */
        /* 设置业务逻辑代码所生成的变量结束 */
        parent::afterRequest();
    }

    /**
     * 获取表单错误信息
     *
     * @param array $p_aResult            
     * @return array
     */
    static function getFormError($p_aResult)
    {
        $aError = [];
        if ($p_aResult['iStatus'] == 0) {
            if ($p_aResult['sType'] == 'logic') {
                $aError[$p_aResult['aError']['sField']] = $p_aResult['aError'];
            } elseif ($p_aResult['sType'] == 'validation') {
                foreach ($p_aResult['aErrors'] as $sField => $aFieldError) {
                    $aError[$sField] = $aFieldError;
                }
            }
        }
        return $aError;
    }
}
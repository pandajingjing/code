<?php
/**
 * base
 * 
 * @namespace app\controller
 */
namespace app\controller;

use panda\lib\controller\web;
use panda\util\guid;
use member_service\bll\session;

/**
 * base
 */
abstract class base extends web
{

    /**
     * 脚本开始时间
     *
     * @var string
     */
    const DKEY_SCRIPT_STARTTIME = 'fScriptStartTime';

    /**
     * session数据
     *
     * @var string
     */
    const DKEY_SESSION = 'session';

    /**
     * 在控制器开始时执行（调度使用）
     */
    function beforeRequest()
    {
        parent::beforeRequest();
        // do something
        $this->setControllerData(self::DKEY_SCRIPT_STARTTIME, $this->getRealTime(true));
        $this->setPageData('sRemoteIp', $this->getParam('CLIENTIP', 'server'));
        $sGuid = $this->getParam('guid', 'cookie');
        if ('' == $sGuid) {
            $sGuid = guid::getGuid();
        }
        $this->setCookie('guid', $sGuid, 31536000);
        $this->setPageData('iVisitTime', $this->getVisitTime());
        // 加载session
        $oBllSession = new session($sGuid, $this->getVisitTime());
        $oBllSession->setClientIp($this->getParam('CLIENTIP','server'));
        $oBllSession->setUserAgent($this->getParam('HTTP_USER_AGENT', 'server'));
        $oBllSession->load();
        $this->setControllerData(self::DKEY_SESSION, $oBllSession);
        
        $aTopUrls = [
            'sDefault' => $this->createInUrl('\\app\\controller\\home\\home')
        ];
        $this->setControllerData('aTopUrls', $aTopUrls);
        $this->setPageData('aTopUrls', $aTopUrls);
    }

    /**
     * 在控制器结束时执行（调度使用）
     */
    function afterRequest()
    {
        // do something
        $oBllSession = $this->getControllerData(self::DKEY_SESSION);
        $oBllSession->save();
        $fScriptStartTime = $this->getControllerData(self::DKEY_SCRIPT_STARTTIME);
        $fScriptEndTime = $this->getRealTime(true);
        $this->setPageData('fScriptTime', $fScriptEndTime - $fScriptStartTime);
        parent::afterRequest();
    }
}
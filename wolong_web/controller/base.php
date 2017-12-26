<?php
/**
 * base
 * 
 * @namespace app\controller
 */
namespace app\controller;

use panda\lib\controller\web;
use panda\util\guid;

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
        
        $aTopUrls = [
            'sDefault' => $this->createInUrl('\\app\\controller\\home\\home'),
            'sPhotoList' => $this->createInUrl('\\app\\controller\\photo\\listing'),
            'sDocList' => $this->createInUrl('\\app\\controller\\document\\listing'),
            'sVideoList' => $this->createInUrl('\\app\\controller\\video\\listing'),
            'sManage' => $this->createInUrl('\\app\\controller\\manage\\dashboard')
        ];
        $this->setPageData('aTopUrls', $aTopUrls);
    }

    /**
     * 在控制器结束时执行（调度使用）
     */
    function afterRequest()
    {
        // do something
        $fScriptStartTime = $this->getControllerData(self::DKEY_SCRIPT_STARTTIME);
        $fScriptEndTime = $this->getRealTime(true);
        $this->setPageData('fScriptTime', $fScriptEndTime - $fScriptStartTime);
        parent::afterRequest();
    }
}
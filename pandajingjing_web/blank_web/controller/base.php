<?php
/**
 * base
 * 
 * @namespace app\controller
 */
namespace app\controller;

use panda\lib\controller\web;
use panda\util\guid;
use blank_service\bll\doc;

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
        
        $oBllDoc = new doc();
        $aChapters = $oBllDoc->getChapters();
        $aChapterList = [];
        foreach ($aChapters as $aChapter) {
            $sAnchor = 'doc_' . $aChapter['iIndex'];
            $aChapterList[] = [
                'sAnchor' => $sAnchor,
                'sTitle' => $aChapter['sTitle'],
                'sUrl' => $this->createInUrl('\\app\\controller\\doc', [], $sAnchor)
            ];
        }
        
        $aTopUrl = [
            'sDefault' => $this->createInUrl('\\app\\controller\\home\\home'),
            'aChapterList' => $aChapterList
        ];
        $this->setPageData('aTopUrl', $aTopUrl);
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
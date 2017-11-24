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
     * 在控制器开始时执行（调度使用）
     */
    function beforeRequest()
    {
        parent::beforeRequest();
        // do something
        $this->setData('fScriptStartTime', $this->getRealTime(true));
        $this->setData('sRemoteIP', $this->getParam('CLIENTIP', 'server'));
        $sGuid = $this->getParam('guid', 'cookie');
        if ('' == $sGuid) {
            $sGuid = guid::getGuid();
        }
        $this->setCookie('guid', $sGuid, 31536000);
        $this->setData('iVisitTime', $this->getVisitTime());
        
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
        
        $aTopURLs = [
            'sDefault' => $this->createInUrl('\\app\\controller\\home\\home'),
            'aChapterList' => $aChapterList
        ];
        $this->setData('aTopURLs', $aTopURLs);
    }

    /**
     * 在控制器结束时执行（调度使用）
     */
    function afterRequest()
    {
        // do something
        $this->setData('fScriptEndTime', $this->getRealTime(true));
        parent::afterRequest();
    }
}
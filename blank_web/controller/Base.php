<?php
/**
 * base
 * 
 * @namespace app\controller
 * @package blank_web_controller
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
        
        $aTopURLs = [
            'sDefault' => $this->createInUrl('\\app\\controller\\home\\home'),
            'aDocList' => [
                $this->createInUrl('\\app\\controller\\doc', [], '_doc_1'),
                $this->createInUrl('\\app\\controller\\doc', [], '_doc_2'),
                $this->createInUrl('\\app\\controller\\doc', [], '_doc_3'),
                $this->createInUrl('\\app\\controller\\doc', [], '_doc_4'),
                $this->createInUrl('\\app\\controller\\doc', [], '_doc_5'),
                $this->createInUrl('\\app\\controller\\doc', [], '_doc_6'),
                $this->createInUrl('\\app\\controller\\doc', [], '_doc_7'),
                $this->createInUrl('\\app\\controller\\doc', [], '_doc_8')
            ]
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
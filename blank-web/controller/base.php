<?php

/**
 * Base
 * 
 * @author jxu
 * @package blank-web_controller
 */
namespace app\controller;

use panda\lib\controller\Web;
use panda\util\Guid;

/**
 * Base
 *
 * @author jxu
 */
abstract class Base extends Web
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
        $sGUID = $this->getParam('guid', 'cookie');
        if ('' == $sGUID) {
            $sGUID = Guid::getGuid();
        }
        $this->setCookie('guid', $sGUID, 31536000);
        $this->setData('iVisitTime', $this->getVisitTime());
        
        $aTopURLs = [
            'sDefault' => $this->createInURL('\\app\\controller\\home\\Home'),
            'aDocList' => [
                $this->createInURL('\\app\\controller\\Doc', [], '_doc_1'),
                $this->createInURL('\\app\\controller\\Doc', [], '_doc_2'),
                $this->createInURL('\\app\\controller\\Doc', [], '_doc_3'),
                $this->createInURL('\\app\\controller\\Doc', [], '_doc_4'),
                $this->createInURL('\\app\\controller\\Doc', [], '_doc_5'),
                $this->createInURL('\\app\\controller\\Doc', [], '_doc_6'),
                $this->createInURL('\\app\\controller\\Doc', [], '_doc_7'),
                $this->createInURL('\\app\\controller\\Doc', [], '_doc_8')
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
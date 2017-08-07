<?php

/**
 * controller_base
 * @author jxu
 * @package blank-web_controller
 */
/**
 * controller_base
 *
 * @author jxu
 */
abstract class controller_base extends lib_controller_web
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
            $sGUID = util_guid::getGuid();
        }
        $this->setCookie('guid', $sGUID, 31536000);
        $this->setData('iVisitTime', $this->getVisitTime());
        
        $aTopURLs = [
            'sDefault' => $this->createInURL('controller_home_home'),
            'aDocList' => []
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
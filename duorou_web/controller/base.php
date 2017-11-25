<?php

/**
 * Base
 * 
 * @author jxu
 * @package duorou_web_controller
 */
namespace app\controller;

use panda\lib\controller\Web;
use panda\util\Guid;

/**
 * Base
 *
 * @author jxu
 */
abstract class base extends Web
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
        $this->setPageData('sRemoteIP', $this->getParam('CLIENTIP', 'server'));
        $sGUID = $this->getParam('guid', 'cookie');
        if ('' == $sGUID) {
            $sGUID = Guid::getGuid();
        }
        $this->setCookie('guid', $sGUID, 31536000);
        $this->setPageData('iVisitTime', $this->getVisitTime());
        
        $aTopURLs = [
            'sDefault' => $this->createInURL('\\app\\controller\\home\\Home')
        ];
        $this->setPageData('aTopURLs', $aTopURLs);
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
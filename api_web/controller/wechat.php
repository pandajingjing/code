<?php
/**
 * wechat
 *
 * @namespace app\controller
 */
namespace app\controller;

use panda\lib\controller\api;
use api_service\bll\wechat as bll_wechat;
use panda\util\xml;

/**
 * home
 */
class wechat extends api
{

    /**
     * 默认返回格式
     *
     * @var string
     */
    protected $sResponseType = 'xml';

    function doRequest()
    {
        $sSignature = $this->getParam('signature', 'get');
        $iTimestamp = $this->getParam('timestamp', 'get');
        $iNonce = $this->getParam('nonce', 'get');
        $oBll = new bll_wechat();
        $aResult = $oBll->verify($sSignature, $iTimestamp, $iNonce);
        if ($aResult['iStatus'] == 1) { // 验证通过
            $sEchoStr = $this->getParam('echostr', 'get');
            if ($sEchoStr != '') { // 验证token是否正确
                return $this->setInfData($sEchoStr, 'txt');
            } else { // 用户发消息过来了
                $aResult = $oBll->replyMsg($this->parseMsg(), $this->getVisitTime());
                $this->addLog('we answer', var_export($aResult, true), 'wechat');
                if ($aResult['iStatus'] == 1) {
                    return $this->setInfData($aResult['aData']);
                } else {
                    return $this->setInfData('success', 'txt');
                }
            }
        } else { // 验证失败
            $this->addLog('verify failed', var_export($aResult, true), 'wechat');
            return $this->setInfData('success', 'txt');
        }
    }

    /**
     * 解析获得的消息
     *
     * @return array;
     */
    protected function parseMsg()
    {
        $sRawData = file_get_contents('php://input');
        return xml::parseStr($sRawData);
    }
}
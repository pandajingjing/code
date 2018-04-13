<?php
/**
 * dispatch controller
 * @package app-mq-shared_cmd_mq
 */
load_lib('/cmd/controller');

/**
 * dispatch controller
 *
 * @author jxu
 * @package app-mq-shared_cmd_mq
 */
class mq_dispatchcontroller extends cmd_controller
{

    /**
     * 入口方法
     */
    function doRequest()
    {
        $aParam = $this->getParam('REQUEST_ARGV', 'server');
        if (isset($aParam['BID'])) {} else {
            $this->stdOut('Usage: BIN -bid $bid.');
            return;
        }
        $iBID = $aParam['BID'];
        
        load_lib('/bll/mq/domsg');
        $this->stdOut('start dispatch');
        $iDBErrTime = 0;
        $oBll = new bll_mq_domsg();
        for ($iIndex = 0; $iIndex < 100; ++ $iIndex) {
            try {
                $iRealTime = $this->getRealTime();
                $iMsgCnt = $oBll->getRemainMsgCnt($iBID, $iRealTime);
                $this->stdOut('[' . $iIndex . ']left queue count:' . $iMsgCnt);
                $aMessages = $oBll->getRemainMsg($iBID, $iRealTime);
            } catch (Exception $ex) {
                // 消息获取异常
                if ($iDBErrTime < 10) { // 隔段时间重试,重试N次
                    ++ $iDBErrTime;
                    sleep(180);
                    continue;
                } else { // 全部失败发送邮件报警
                    $oBll->sendWarningMail('消息获取异常', $ex->getMessage());
                    $iDBErrTime = 0;
                    sleep(1800);
                    break;
                }
            }
            $iDBErrTime = 0;
            $this->stdOut('[' . $iIndex . ']current message count: ' . count($aMessages));
            foreach ($aMessages as $aMsg) {
                $sCmd = $oBll->dispatchMsg($aMsg['iBID'], $aMsg['sMsgID']);
                $this->stdOut('[' . $iIndex . ']' . $sCmd);
                if (0 < $aMsg['iWait']) {
                    $oHandle = popen($sCmd, 'r');
                    $sRet = '';
                    while (! feof($oHandle)) {
                        $sRet .= fread($oHandle, 1024);
                    }
                    fclose($oHandle);
                    if ('' != $sRet) {
                        $this->stdOut('[' . $iIndex . ']consumer: ' . $sRet);
                    }
                } else {
                    pclose(popen($sCmd, 'w'));
                }
            }
            if ($oBll->needSleep($iMsgCnt)) {
                $this->stdOut('[' . $iIndex . ']I need sleep');
                sleep(1);
                $this->stdOut('[' . $iIndex . ']I wake up');
            }
        }
        $this->stdOut('end dispatch');
    }
}
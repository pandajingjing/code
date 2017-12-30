<?php
/**
 * bll domsg
 * @package app-mq-shared_lib_bll_mq
 */
load_lib('/bll/mq/base');

/**
 * bll domsg
 *
 * @author jxu
 * @package app-mq-shared_lib_bll_mq
 */
class bll_mq_domsg extends bll_mq_base
{

    /**
     * 获取剩余消息的数量
     *
     * @param int $p_iBID            
     * @param int $p_iTime            
     * @return int
     */
    function getRemainMsgCnt($p_iBID = 0, $p_iTime = 0)
    {
        load_lib('/dao/mq/queuedao');
        $aParam = array(
            'iPlanTime' => $p_iTime,
            'iStatus' => dao_mq_queuedao::MSG_STATUS_PENDING,
            'iBID' => $p_iBID
        );
        return dao_mq_queuedao::getCnt($aParam);
    }

    /**
     * 获取剩余消息
     *
     * @param int $p_iBID            
     * @param int $p_iTime            
     * @return array
     */
    function getRemainMsg($p_iBID = 0, $p_iTime = 0)
    {
        $aParam = array(
            'iPlanTime' => $p_iTime,
            'iStatus' => dao_mq_queuedao::MSG_STATUS_PENDING,
            'iBID' => $p_iBID
        );
        load_lib('/dao/mq/queuedao');
        return dao_mq_queuedao::getPageList($aParam, 0, dao_mq_queuedao::getConfig('iEachTimeGetMsgCnt', 'mq'), 'iPlanTime desc');
    }

    /**
     * 派遣消息
     *
     * @param int $p_iBID            
     * @param string $p_sMsgID            
     */
    function dispatchMsg($p_iBID, $p_sMsgID)
    {
        load_lib('/dao/mq/queuedao');
        dao_mq_queuedao::updData(array(
            'iBID' => $p_iBID,
            'sMsgID' => $p_sMsgID,
            'iStatus' => dao_mq_queuedao::MSG_STATUS_HANDLE
        ));
        $sCmd = dao_mq_queuedao::getConfig('sPHPConsumer', 'mq');
        $sCmd .= ' -msgid ' . $p_sMsgID . ' -bid ' . $p_iBID . ' &';
        return $sCmd;
    }

    /**
     * 消费消息
     *
     * @param int $p_iBID            
     * @param string $p_sMsgID            
     * @param int $p_iTime            
     * @return true/false
     */
    function consumeMsg($p_iBID, $p_sMsgID, $p_iTime)
    {
        load_lib('/dao/mq/queuedao');
        $aMsg = dao_mq_queuedao::getMsgDetail($p_iBID, $p_sMsgID);
        if (null === $aMsg) {
            throw new Exception('No Message.');
            return false;
        }
        if (dao_mq_queuedao::MSG_STATUS_HANDLE == $aMsg['iStatus']) {
            $aCallBack = json_decode($aMsg['sCallBack'], true);
            if (isset($aCallBack['sCallbackUrl'])) {
                // 执行消息操作
                try {
                    load_lib('/client/browser');
                    $aRet = client_browser::postData($aCallBack['sCallbackUrl'], $aCallBack['aData']);
                } catch (Exception $oEx) {
                    $sErrMsg = $oEx->getMessage();
                    $bSuccess = false;
                }
                if (empty($aRet)) {
                    throw new Exception('Error Message Feedback.');
                    return false;
                }
                // 执行消息操作结束
                if (1 == $aRet['iStatus']) {
                    if (isset($aCallBack['sOnSuccessUrl'])) {
                        load_lib('/client/browser');
                        $aRet = client_browser::postData($aCallBack['sOnSuccessUrl'], array_merge($aCallBack['aData'], $aRet['aData']));
                    }
                    dao_mq_queuedao::updData(array(
                        'iBID' => $p_iBID,
                        'sMsgID' => $p_sMsgID,
                        'iStatus' => dao_mq_queuedao::MSG_STATUS_SUCCESS,
                        'iFinishTime' => $p_iTime
                    ));
                    return true;
                } else {
                    if ($aMsg['iRunTimes'] < $aMsg['iRetry']) { // 不再重试
                        dao_mq_queuedao::updData(array(
                            'iBID' => $p_iBID,
                            'sMsgID' => $p_sMsgID,
                            'iStatus' => dao_mq_queuedao::MSG_STATUS_RETRY,
                            'iFinishTime' => $p_iTime
                        ));
                        load_lib('/util/guid');
                        dao_mq_queuedao::addData(array(
                            'sMsgID' => util_guid::getGuid(),
                            'iBID' => $aMsg['iBID'],
                            'iRetry' => $aMsg['iRetry'],
                            'iInterval' => $aMsg['iInterval'],
                            'sCallBack' => $aMsg['sCallBack'],
                            'iPlanTime' => $p_iTime + $aMsg['iInterval'],
                            'iRunTimes' => ($aMsg['iRunTimes'] + 1)
                        ));
                    } else {
                        if (isset($aCallBack['sOnErrorUrl'])) {
                            load_lib('/client/browser');
                            $aRet = client_browser::postData($aCallBack['sOnErrorUrl'], array_merge($aCallBack['aData'], $aRet['aData']));
                        }
                        dao_mq_queuedao::updData(array(
                            'iBID' => $p_iBID,
                            'sMsgID' => $p_sMsgID,
                            'iStatus' => dao_mq_queuedao::MSG_STATUS_FAIL,
                            'iFinishTime' => $p_iTime
                        ));
                    }
                    return $aRet['aErrInfo']['sMsg'];
                }
            } else {
                // 查找Controller
                $sControllerPath = controllername_to_path($aCallBack['sController']);
                load_cmd($sControllerPath);
                $aAllClass = get_declared_classes();
                $bGetController = false;
                foreach ($aAllClass as $sDeclaredClass) {
                    if ($aCallBack['sController'] == $sDeclaredClass) {
                        $bGetController = true;
                        break;
                    }
                }
                if (! $bGetController) {
                    throw new Exception('Error Message Controller.');
                    return false;
                }
                // 查找Controller结束
                // 查找Handle,OnSuccess,OnError
                $oRelClass = new ReflectionClass($aCallBack['sController']);
                if ($oRelClass->hasMethod($aCallBack['sHandle'])) {
                    if (isset($aCallBack['sOnSuccess'])) {
                        if ($oRelClass->hasMethod($aCallBack['sOnSuccess'])) {} else {
                            throw new Exception('Error Message OnSuccess.');
                            return false;
                        }
                    }
                    if (isset($aCallBack['sOnError'])) {
                        if ($oRelClass->hasMethod($aCallBack['sOnError'])) {} else {
                            throw new Exception('Error Message OnError.');
                            return false;
                        }
                    }
                } else {
                    throw new Exception('Error Message Handle.');
                    return false;
                }
                // 查找Handle,OnSuccess,OnError结束
                // 执行消息操作
                try {
                    $oRelInstance = $oRelClass->newInstance();
                    $oRelMethod = $oRelClass->getMethod('beforeRequest');
                    $oRelMethod->invoke($oRelInstance);
                    $oRelMethod = $oRelClass->getMethod($aCallBack['sHandle']);
                    $bSuccess = $oRelMethod->invoke($oRelInstance, $aCallBack['aData']);
                    if (! is_bool($bSuccess)) {
                        throw new Exception('Unexpected Return.');
                        return false;
                    }
                    $oRelMethod = $oRelClass->getMethod('afterRequest');
                    $oRelMethod->invoke($oRelInstance);
                    $sErrMsg = 'Handler return false.';
                } catch (Exception $oEx) {
                    $sErrMsg = $oEx->getMessage();
                    $bSuccess = false;
                }
                // 执行消息操作结束
                if ($bSuccess) {
                    if (isset($aCallBack['sOnSuccess'])) {
                        $oRelMethod = $oRelClass->getMethod($aCallBack['sOnSuccess']);
                        $oRelMethod->invoke($oRelInstance, $aCallBack['aData']);
                    }
                    dao_mq_queuedao::updData(array(
                        'iBID' => $p_iBID,
                        'sMsgID' => $p_sMsgID,
                        'iStatus' => dao_mq_queuedao::MSG_STATUS_SUCCESS,
                        'iFinishTime' => $p_iTime
                    ));
                    return true;
                } else {
                    if ($aMsg['iRunTimes'] < $aMsg['iRetry']) { // 不再重试
                        dao_mq_queuedao::updData(array(
                            'iBID' => $p_iBID,
                            'sMsgID' => $p_sMsgID,
                            'iStatus' => dao_mq_queuedao::MSG_STATUS_RETRY,
                            'iFinishTime' => $p_iTime
                        ));
                        load_lib('/util/guid');
                        dao_mq_queuedao::addData(array(
                            'sMsgID' => util_guid::getGuid(),
                            'iBID' => $aMsg['iBID'],
                            'iRetry' => $aMsg['iRetry'],
                            'iInterval' => $aMsg['iInterval'],
                            'sCallBack' => $aMsg['sCallBack'],
                            'iPlanTime' => $p_iTime + $aMsg['iInterval'],
                            'iRunTimes' => ($aMsg['iRunTimes'] + 1)
                        ));
                    } else {
                        if (isset($aCallBack['sOnError'])) {
                            $oRelMethod = $oRelClass->getMethod($aCallBack['sOnError']);
                            $oRelMethod->invoke($oRelInstance, $aCallBack['aData']);
                        }
                        dao_mq_queuedao::updData(array(
                            'iBID' => $p_iBID,
                            'sMsgID' => $p_sMsgID,
                            'iStatus' => dao_mq_queuedao::MSG_STATUS_FAIL,
                            'iFinishTime' => $p_iTime
                        ));
                    }
                    return $sErrMsg;
                }
            }
        } else {
            return 'Error Message status(' . $aMsg['iStatus'] . ').';
        }
    }

    /**
     * 是否需要休息
     *
     * @param int $p_iMsgCnt            
     * @return true/false
     */
    function needSleep($p_iMsgCnt)
    {
        if ($p_iMsgCnt < dao_mq_queuedao::getConfig('iSleepMsgCnt', 'mq')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 发送报警邮件
     *
     * @param string $p_sTitle            
     * @param string $p_sContent            
     */
    function sendWarningMail($p_sTitle, $p_sContent)
    {}
}
<?php
/**
 * bll base
 * @package app-mq-shared_lib_bll_mq
 */
load_lib('/bll/bll');
/**
 * bll base
 * @author jxu
 * @package app-mq-shared_lib_bll_mq
 */
class bll_mq_base extends bll_bll{

	/**
	 * 邮件服务-发送邮件
	 * @var int
	 */
	const BID_MAIL_SEND = 0;

	/**
	 * 文件服务-获取文件信息
	 * @var int
	 */
	const BID_APP_FILE_INFO = 1;

	/**
	 * 文件服务-获取图片信息
	 * @var int
	 */
	const BID_APP_FILE_IMAGEINFO = 2;

	/**
	 * 文件服务-备份文件
	 * @var int
	 */
	const BID_APP_FILE_BACKUP = 3;

	/**
	 * 文件服务-删除文件
	 * @var int
	 */
	const BID_APP_FILE_DELETE = 4;

	/**
	 * 短信服务-发送短信
	 * @var int
	 */
	const BID_SMS_SEND = 5;

	/**
	 * 短信服务-获取回执报告
	 * @var int
	 */
	const BID_SMS_REPORT = 6;

	/**
     * 收银台服务-补单
     * @var int
     */
    const BID_PAY_FIX_ORDER = 7;

    /**
     * 好房宝服务-查询交易状态
     * @var int
     */
    const BID_CRONJOB_PAY = 8;

	/**
     * cronjob-www
     * @var int
     */
	const BID_CRONJOB = 9;

    /**
     * 消息推送
     * @var int
     * Added by Randy Hong 2014-06-18
     */
    const BID_PUSH_MSG = 11;

	/**
     * solrbuild-www
     * @var int
     */
	const BID_SOLRJOB = 10;

	/**
	 * loupan-allot-xf
	 * @var int
	 */
	const BID_ALLOTJOB = 13;

	/**
	 * 看房团相关job
	 * @var unknown
	 */
	const BID_KFT_JOB = 12;

	/**
	 * 百盘大战相关job
	 * @var unknown
	 */
	const BID_BPDZ_JOB = 14;


     /* 问答系统相关job
     * @var int
     */
    const BID_ASK_JOB = 15;


    /**
     * 财务系统 - 对账
     * @var unknown
     */
    const BID_FINANCE_CHK = 17;



    const BID_PUBLISH_MSG = 16;

    /**
     * 代付相关job
     * @var int
     */
    const BID_CASHOUT_JOB = 18;


    /**
     * 二手房相关job
     * @var int
     */
    const BID_ESF_JOB = 19;

    /**
     * 租房相关job
     */
    const BID_ZF_JOB = 20;

	/**
	 * 通用队列
	 * @var int
	 */
	const BID_GENERAL = 99;


	/**
	 * 发送消息
	 *
	 * @param array $p_aParam
	 *            sCallbackUrl => 'sCallbackUrl',//接受数组post做为参数,按内部接口规则返回返回true和false<br />
	 *            sOnSuccessUrl => 'sOnSuccessUrl',//callback执行成功后执行啥,接受aData参数和sCallbackUrl返回的参数<br />
	 *            sOnErrorUrl => 'sOnErrorUrl',//callback执行成功失败执行啥,接受aData参数和sCallbackUrl返回的参数<br />
	 *            aData => array(),//传给sCallbackUrl的参数<br />
	 *            iInterval => 10,//重试每次间隔时间>0<br />
	 *            iRetry => 10,//重试次数>-1,<256<br />
	 *            iPlanTime => time(),//消息执行时间<br />
	 *            iWait => 1,//是否需要等待上一条消息执行结束后再执行下一条消息</p>
	 */
	function sendMsg($p_aParam)
	{
		if (isset($p_aParam['sCallbackUrl'])) {
			return $this->sendCallBack($p_aParam);
		}
		$aParam = $aCallBack = array();
		if (isset($p_aParam['sController'])) {
			$aCallBack['sController'] = $p_aParam['sController'];
		} else {
			throw new Exception('No Controller.');
			return false;
		}
		if (isset($p_aParam['sHandle'])) {
			$aCallBack['sHandle'] = $p_aParam['sHandle'];
		} else {
			throw new Exception('No Handle.');
			return false;
		}
		if (isset($p_aParam['sOnSuccess'])) {
			$aCallBack['sOnSuccess'] = $p_aParam['sOnSuccess'];
		}
		if (isset($p_aParam['sOnError'])) {
			$aCallBack['sOnError'] = $p_aParam['sOnError'];
		}
		if (isset($p_aParam['iWait'])) {
			$aParam['iWait'] = $p_aParam['iWait'];
		} else {
			$aParam['iWait'] = 0;
		}
		if (isset($p_aParam['aData'])) {
			$aCallBack['aData'] = $p_aParam['aData'];
		} else {
			$aCallBack['aData'] = array();
		}
		if (isset($p_aParam['iBID'])) {
			if ($this->chkBID($p_aParam['iBID'])) {
				$aParam['iBID'] = $p_aParam['iBID'];
			} else {
				throw new Exception('Error BID.');
				return false;
			}
		} else {
			$aParam['iBID'] = 0;
		}
		if (isset($p_aParam['iRetry'])) {
			if (0 < $p_aParam['iRetry']) {
				if (255 < $p_aParam['iRetry']) {
					$aParam['iRetry'] = 10;
				} else {
					$aParam['iRetry'] = $p_aParam['iRetry'];
				}
				if (isset($p_aParam['iInterval'])) {
					if (0 < $p_aParam['iInterval'] and 256 > $p_aParam['iInterval']) {
						$aParam['iInterval'] = $p_aParam['iInterval'];
					} else {
						$aParam['iInterval'] = 10;
					}
				} else {
					$aParam['iInterval'] = 10;
				}
			} else {
				$aParam['iRetry'] = 0;
				$aParam['iInterval'] = 0;
			}
		} else {
			$aParam['iRetry'] = 0;
			$aParam['iInterval'] = 0;
		}
		if (isset($p_aParam['iPlanTime'])) {
			$aParam['iPlanTime'] = $p_aParam['iPlanTime'];
		}
		$aParam['sCallBack'] = json_encode($aCallBack);
		load_lib('/util/guid');

		$aParam['sMsgID'] = util_guid::getGuid();
		$aParam['iRunTimes'] = 0;
		load_lib('/dao/mq/queuedao');
		// print_r($aParam);
		return dao_mq_queuedao::addData($aParam);
	}

	private function sendCallBack($p_aParam)
	{
		$aParam = $aCallBack = array();
		if (isset($p_aParam['sCallbackUrl'])) {
			$aCallBack['sCallbackUrl'] = $p_aParam['sCallbackUrl'];
		} else {
			throw new Exception('No CallbackUrl.');
			return false;
		}
		if (isset($p_aParam['sOnSuccessUrl'])) {
			$aCallBack['sOnSuccessUrl'] = $p_aParam['sOnSuccessUrl'];
		}
		if (isset($p_aParam['sOnErrorUrl'])) {
			$aCallBack['sOnErrorUrl'] = $p_aParam['sOnErrorUrl'];
		}
		if (isset($p_aParam['iWait'])) {
			$aParam['iWait'] = $p_aParam['iWait'];
		} else {
			$aParam['iWait'] = 0;
		}
		if (isset($p_aParam['aData'])) {
			$aCallBack['aData'] = $p_aParam['aData'];
		} else {
			$aCallBack['aData'] = array();
		}
		$aParam['iBID'] = 99;
		if (isset($p_aParam['iRetry'])) {
			if (0 < $p_aParam['iRetry']) {
				if (255 < $p_aParam['iRetry']) {
					$aParam['iRetry'] = 10;
				} else {
					$aParam['iRetry'] = $p_aParam['iRetry'];
				}
				if (isset($p_aParam['iInterval'])) {
					if (0 < $p_aParam['iInterval'] and 256 > $p_aParam['iInterval']) {
						$aParam['iInterval'] = $p_aParam['iInterval'];
					} else {
						$aParam['iInterval'] = 10;
					}
				} else {
					$aParam['iInterval'] = 10;
				}
			} else {
				$aParam['iRetry'] = 0;
				$aParam['iInterval'] = 0;
			}
		} else {
			$aParam['iRetry'] = 0;
			$aParam['iInterval'] = 0;
		}
		if (isset($p_aParam['iPlanTime'])) {
			$aParam['iPlanTime'] = $p_aParam['iPlanTime'];
		}
		$aParam['sCallBack'] = json_encode($aCallBack);
		load_lib('/util/guid');

		$aParam['sMsgID'] = util_guid::getGuid();
		$aParam['iRunTimes'] = 0;
		load_lib('/dao/mq/queuedao');
		// print_r($aParam);
		return dao_mq_queuedao::addData($aParam);
	}

	/**
	 * 检查业务ID
	 * @param int $p_iBID
	 * @return true/false
	 */
	private function chkBID($p_iBID){
		$oReflect = new ReflectionClass($this);
		$aBIDs = $oReflect->getConstants();
		if(in_array($p_iBID, $aBIDs)){
			return true;
		}else{
			return false;
		}
	}
}

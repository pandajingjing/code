<?php
/**
 * consumer controller
 * @package app-mq-shared_cmd_mq
 */
load_lib('/cmd/controller');
/**
 * consumer controller
 * @author jxu
 * @package app-mq-shared_cmd_mq
 */
class mq_consumercontroller extends cmd_controller{

	/**
	 * 入口方法
	 */
	function doRequest(){
		$aParam = $this->getParam('REQUEST_ARGV', 'server');
		if(isset($aParam['BID']) and isset($aParam['MSGID'])){
			$this->stdOut('start consumer: msgid(' . $aParam['MSGID'] . '),bid(' . $aParam['BID'] . ')');
			load_lib('/bll/mq/domsg');
			$oBll = new bll_mq_domsg();
			$mResult = $oBll->consumeMsg($aParam['BID'], $aParam['MSGID'], $this->getTime());
			if(true === $mResult){
				$this->stdOut('consumer success');
			}else{
				$this->stdOut('consumer error: ' . $mResult);
			}
		}else{
			$this->stdOut('Usage: BIN -bid $bid -msgid $msgid.');
		}
	}
}
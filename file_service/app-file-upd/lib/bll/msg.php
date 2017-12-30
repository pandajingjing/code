<?php
/**
 * bll msg
 * @package app-file_lib_bll
 */
load_lib('/bll/file');
/**
 * bll msg
 * @author jxu
 * @package app-file_lib_bll
 */
class bll_msg extends bll_file{

	function __construct(){
		parent::__construct();
	}

	/**
	 * 保存文件信息
	 * @param array $p_aParam   
	 * @return int/false     	
	 */
	function saveInfo($p_aParam){
		load_lib('/dao/dfsinfodao');
		return dao_dfsinfodao::addData($p_aParam);
	}

	/**
	 * 保存图片信息
	 * @param array $p_aParam
	 * @return int/false  
	 */
	function saveImageInfo($p_aParam){
		load_lib('/dao/dfsimageinfodao');
		return dao_dfsimageinfodao::addData($p_aParam);
	}

	/**
	 * 所有备份全部损坏,清除文件数据,等待重新上传
	 * @param array $p_aParam
	 */
	function delFile($p_aParam){
		load_lib('/dao/dfsdao');
		load_lib('/dao/dfsimageinfodao');
		load_lib('/dao/dfsinfodao');
		load_lib('/dao/dfsbackupdao');
		load_lib('/util/file');
		$sFileKey = $p_aParam['sFileKey'];
		dao_dfsdao::delData($sFileKey);
		dao_dfsinfodao::delData($sFileKey);
		dao_dfsimageinfodao::delData($sFileKey);
		$aBackupInfo = dao_dfsbackupdao::getList(array( 
				'sFileKey' => $sFileKey 
		), '');
		$sBaseDir = dao_dfsbackupdao::getConfig('sRawDir', 'storage');
		$sSubDir = $this->dispatchFile($sFileKey);
		foreach($aBackupInfo as $aBackup){
			dao_dfsbackupdao::delData($aBackup['iAutoID']);
			$sDestDir = $sBaseDir . DIRECTORY_SEPARATOR . $aBackup['iHostID'] . $sSubDir . $sFileKey;
			util_file::tryDeleteFile($sDestDir);
		}
		return true;
	}

	/**
	 * 备份文件
	 * @param array $p_aParam        	
	 */
	function backupFile($p_aParam){
		load_lib('/dao/dfsbackupdao');
		$sFileKey = $p_aParam['sFileKey'];
		$aBackupInfo = dao_dfsbackupdao::getList(array( 
				'sFileKey' => $sFileKey 
		), '');
		if(empty($aBackupInfo)){
			$aBackedHostID = array( 
					$p_aParam['iHostID'] 
			);
			dao_dfsbackupdao::addData(array( 
					'sFileKey' => $sFileKey,
					'iHostID' => $p_aParam['iHostID'] 
			));
		}else{
			if(isset($p_aParam['aErrHostIDs'])){
				$aTmpIDs = array();
				foreach($aBackupInfo as $aBackup){
					if(in_array($aBackup['iHostID'], $p_aParam['aErrHostIDs'])){ //把拦掉的那些拿掉
						dao_dfsbackupdao::delData($aBackup['iAutoID']);
					}else{
						$aTmpIDs[] = $aBackup['iHostID'];
					}
				}
				$aBackedHostID = $aTmpIDs;
			}
		}
		$iBackupCnt = dao_dfsbackupdao::getConfig('iBackupCnt', 'backup');
		$iBackedCnt = count($aBackedHostID);
		$iNeedBackupCnt = $iBackupCnt - $iBackedCnt;
		for($i = 0; $i < $iNeedBackupCnt; ++$i){
			$iBackupHostID = $this->dispatchBackupHostID($aBackedHostID);
			if(null === $iBackupHostID){
				break;
			}else{
				$sSubDir = $this->dispatchFile($sFileKey);
				$sBaseDir = dao_dfsbackupdao::getConfig('sRawDir', 'storage');
				$sDestDir = $sBaseDir . DIRECTORY_SEPARATOR . $iBackupHostID . $sSubDir;
				load_lib('/util/file');
				if(!is_dir($sDestDir)){
					if(false === util_file::tryMakeDir($sDestDir, 0755, true)){
						throw new Exception(__CLASS__ . ': can not create path(' . $sDestDir . ').');
						return false;
					}
				}
				$sDestFile = $sDestDir . $sFileKey;
				$sSourceDir = $sBaseDir . DIRECTORY_SEPARATOR . $p_aParam['iHostID'] . $sSubDir;
				$sSourceFile = $sSourceDir . $sFileKey;
				if(false === util_file::tryCopyFile($sSourceFile, $sDestFile, true)){
					throw new Exception(__CLASS__ . ': can not copy (' . $sDestFile . ') from (' . $sSourceFile . ').');
					return false;
				}
				$aBackedHostID[] = $iBackupHostID;
				dao_dfsbackupdao::addData(array( 
						'sFileKey' => $sFileKey,
						'iHostID' => $iBackupHostID 
				));
			}
		}
		return true;
	}

	/**
	 * 获取分配备份存储设备
	 * @param array $p_aExceptHostIDs
	 * @return int        	
	 */
	function dispatchBackupHostID($p_aExceptHostIDs){
		load_lib('/dao/dfsbackupdao');
		$aBackupHostIDs = dao_dfsbackupdao::getConfig('aStorageHost', 'backup');
		for($i = 0; $i < 50; ++$i){ // 只循环50次,如果始终找不到hostid,要么是存储设备不够,要么备份存储设备太多
			$iHostID = $this->dispatchHostID($aBackupHostIDs);
			if(in_array($iHostID, $p_aExceptHostIDs)){
				continue;
			}
			return $iHostID;
		}
		return null;
	}
}
<?php
/**
 * bll cropfile
 * @package app-file-upd_lib_bll
 */
load_lib('/bll/file');
/**
 * bll cropfile
 * @author jxu
 * @package app-file-upd_lib_bll
 */
class bll_cropfile extends bll_file{

	function __construct(){
		parent::__construct();
	}

	function cropFile($p_sKey, $p_sExt, $p_sBiz, $p_iPointX, $p_iPointY, $p_iWidth, $p_iHeight, $p_sIp, $p_iTime, &$o_aCropFile){
		load_lib('/dao/dfsdao');
		load_lib('/util/error');
		if(!isset($p_sKey[39])){
			return util_error::tagError('Key', util_error::TYPE_EMPTY);
		}
		$aFileInfo = dao_dfsdao::getDetail($p_sKey);
		if(null === $aFileInfo){
			return util_error::tagError('Info', util_error::TYPE_EMPTY);
		}
		$p_sExt = strtolower($p_sExt);
		load_lib('/util/file');
		if($p_sExt == util_file::getExtension($aFileInfo['sMimeType'])){
			$aImageType = dao_dfsdao::getConfig('aImageType');
			if(in_array($p_sExt, $aImageType)){
				if(!$this->chkBiz($p_sBiz)){
					return util_error::tagError('Biz', util_error::TYPE_INVALID);
				}
				if('' != $p_sBiz){
					load_lib('/dao/dfsbizdao');
					$aBizs = dao_dfsbizdao::getList(array( 
							'sFileKey' => $p_sKey 
					), '');
					$bGet = false;
					foreach($aBizs as $aBiz){
						if($p_sBiz == $aBiz['sBiz']){
							$bGet = true;
							break;
						}
					}
					if(!$bGet){
						return util_error::tagError('Biz', util_error::TYPE_INVALID);
					}
				}
				if($p_iWidth > 0 and $p_iHeight > 0){ //切图尺寸
					$aCropBiz = dao_dfsdao::getConfig('aCrop', 'crop');
					$bGetIt = false;
					foreach($aCropBiz as $sPattern => $aConfig){
						if(1 === preg_match($sPattern, $this->_sDomain)){
							$bGetIt = true;
							break;
						}
					}
					if($bGetIt){ //该通道可以切图
						if(in_array($p_sBiz, $aConfig)){
							//开始处理
							$sOriginalPath = $this->getOriginalPath($p_sKey, $aFileInfo['iHostID']);
							if(false === $sOriginalPath){
								return util_error::tagError('File', util_error::TYPE_ERROR);
							}else{
								load_lib('/util/image');
								try{
									$blImage = util_image::cropImage($sOriginalPath, $p_iPointX, $p_iPointY, $p_iWidth, $p_iHeight, $p_sExt);
									if(false === $blImage){
										return util_error::tagError('Crop', util_error::TYPE_ERROR);
									}
								}catch(Exception $oEx){
									load_lib('/bll/mq/base');
									$oBll = new bll_mq_base();
									$oBll->sendMsg(array( 
											'iBID' => $oBll::BID_APP_FILE_DELETE,
											'sController' => 'domsgcontroller',
											'sHandle' => 'delFile',
											'aData' => array( 
													'sFileKey' => $p_sKey 
											),
											'iRetry' => 10 
									));
									return util_error::tagError('Crop', $oEx->getMessage());
								}
							}
							$sFileKey = sha1($blImage);
							$aFileInfo = dao_dfsdao::getDetail($sFileKey);
							if(null === $aFileInfo){
								$this->saveBiz($sFileKey, $p_sBiz);
								$iHostID = $this->dispatchUploadHostID();
								$sSubDir = $this->dispatchFile($sFileKey);
								$sBaseDir = dao_dfsdao::getConfig('sRawDir', 'storage');
								$sDir = $sBaseDir . DIRECTORY_SEPARATOR . $iHostID . $sSubDir;
								$sDestFile = $sDir . $sFileKey;
								if(!is_dir($sDir)){
									util_file::tryMakeDir($sDir, 0755, true);
								}
								util_file::tryWriteFile($sDestFile, $blImage);
								$aImageInfo = $this->getImageInfo($sDestFile);
								$o_aCropFile = array( 
										'sKey' => $sFileKey,
										'sExt' => $p_sExt,
										'iSize' => filesize($sDestFile),
										'iWidth' => $aImageInfo['iWidth'],
										'iHeight' => $aImageInfo['iHeight'] 
								);
								$aDFS = array( 
										'sFileKey' => $sFileKey,
										'sMimeType' => util_file::getMimeType($p_sExt),
										'iHostID' => $iHostID 
								);
								dao_dfsdao::addData($aDFS);
								load_lib('/bll/mq/base');
								$oBllMQ = new bll_mq_base();
								$oBllMQ->sendMsg(array( 
										'iBID' => $oBllMQ::BID_APP_FILE_INFO,
										'sController' => 'domsgcontroller',
										'sHandle' => 'saveInfo',
										'aData' => array( 
												'sFileKey' => $sFileKey,
												'iFileSize' => filesize($sDestFile),
												'sFileExt' => $p_sExt,
												'sFromDomain' => $this->_sDomain,
												'sFromIP' => $p_sIp,
												'iCreateTime' => $p_iTime 
										),
										'iRetry' => 10 
								));
								$oBllMQ->sendMsg(array( 
										'iBID' => $oBllMQ::BID_APP_FILE_BACKUP,
										'sController' => 'domsgcontroller',
										'sHandle' => 'backupFile',
										'aData' => array( 
												'sFileKey' => $sFileKey,
												'iHostID' => $iHostID 
										),
										'iRetry' => 10 
								));
								$oBllMQ->sendMsg(array( 
										'iBID' => $oBllMQ::BID_APP_FILE_IMAGEINFO,
										'sController' => 'domsgcontroller',
										'sHandle' => 'saveImageInfo',
										'aData' => array( 
												'sFileKey' => $sFileKey,
												'iWidth' => $aImageInfo['iWidth'],
												'iHeight' => $aImageInfo['iHeight'],
												'iChannels' => $aImageInfo['iChannels'],
												'iBits' => $aImageInfo['iBits'] 
										),
										'iRetry' => 10 
								));
								return true;
							}else{
								load_lib('/dao/dfsimageinfodao');
								$aImageInfo = dao_dfsimageinfodao::getDetail($aFileInfo['sFileKey']);
								if(null === $aImageInfo){
									$sSubDir = $this->dispatchFile($sFileKey);
									$sBaseDir = dao_dfsdao::getConfig('sRawDir', 'storage');
									$sDir = $sBaseDir . DIRECTORY_SEPARATOR . $aFileInfo['iHostID'] . $sSubDir;
									$sDestFile = $sDir . $sFileKey;
									$aImageInfo = $this->getImageInfo($sDestFile);
									load_lib('/bll/mq/base');
									$oBllMQ = new bll_mq_base();
									$oBllMQ->sendMsg(array( 
											'iBID' => $oBllMQ::BID_APP_FILE_IMAGEINFO,
											'sController' => 'domsgcontroller',
											'sHandle' => 'saveImageInfo',
											'aData' => array( 
													'sFileKey' => $aFileInfo['sFileKey'],
													'iWidth' => $aImageInfo['iWidth'],
													'iHeight' => $aImageInfo['iHeight'],
													'iChannels' => $aImageInfo['iChannels'],
													'iBits' => $aImageInfo['iBits'] 
											),
											'iRetry' => 10 
									));
								}
								$o_aCropFile = array( 
										'sKey' => $sFileKey,
										'sExt' => $p_sExt,
										'iSize' => filesize($sDestFile),
										'iWidth' => $aImageInfo['iWidth'],
										'iHeight' => $aImageInfo['iHeight'] 
								);
								return true;
							}
						}else{
							return util_error::tagError('CropBiz', util_error::TYPE_INVALID);
						}
					}else{
						return util_error::tagError('CropBiz', util_error::TYPE_INVALID);
					}
				}else{ // 源图
					return util_error::tagError('WH', util_error::TYPE_EMPTY);
				}
			}else{
				return util_error::tagError('Ext', util_error::TYPE_INVALID);
			}
		}else{
			return util_error::tagError('Ext', util_error::TYPE_INVALID);
		}
	}
}
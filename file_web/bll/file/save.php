<?php

/**
 * bll savefile
 * @package app-file-upd_lib_bll
 */

/**
 * bll savefile
 *
 * @author jxu
 * @package app-file-upd_lib_bll
 */
class bll_file_save extends bll_file_base
{

    function __construct()
    {
        parent::__construct();
    }

    function saveInfo($p_sDomainKey, $p_sBiz, $p_sIp, $p_iTime, $p_sFileName, $p_blFile)
    {
        if ($this->chkBiz($p_sDomainKey, $p_sBiz)) {
            if (isset($this->_aUpdConfig[$p_sDomainKey][$p_sBiz])) {
                $aUpdConfig = $this->_aUpdConfig[$p_sDomainKey][$p_sBiz];
                util_error::initError();
                if (isset($aUpdConfig['aUpdSize'])) {
                    $aUpdSize = $aUpdConfig['aUpdSize'];
                    $iSize = strlen($p_blFile);
                    if ($iSize < $aUpdSize['iMin']) {
                        util_error::addBizError('size', util_error::TYPE_VALUE_SMALL, $iSize);
                    } elseif ($iSize > $aUpdSize['iMax']) {
                        util_error::addBizError('size', util_error::TYPE_VALUE_BIG, $iSize);
                    }
                } else {
                    throw new Exception(__CLASS__ . ': configuration(upload size) lost.');
                }
                
                if (isset($aUpdConfig['aUpdTypes'])) {
                    $aUpdTypes = $aUpdConfig['aUpdTypes'];
                    $oFInfo = finfo_open();
                    $sMimeType = finfo_buffer($oFInfo, $p_blFile, FILEINFO_MIME_TYPE);
                    finfo_close($oFInfo);
                    $sExtension = util_file::getExtension($sMimeType);
                    if ('dat' == $sExtension) {
                        $this->addLog('unknow_mime', var_export([
                            'sFilename' => $p_sFileName,
                            'sMimetype' => $sMimeType
                        ], true), 'unknow_mime');
                    }
                    if ('office' == $sExtension) {
                        $aFileInfo = pathinfo($p_sFileName);
                        $sExtension = $aFileInfo['extension'];
                        $sMimeType = util_file::getMimeType($sExtension);
                    }
                    if (in_array($sExtension, $aUpdTypes)) {} else {
                        util_error::addBizError('ext', util_error::TYPE_INVALID, $sExtension);
                    }
                } else {
                    throw new Exception(__CLASS__ . ': configuration(upload type) lost.');
                }
                
                if (util_error::isError()) {
                    return $this->returnErrors(util_error::getErrors());
                }
                
                $sFileKey = md5($p_blFile) . sha1($p_blFile);
            } else {
                throw new Exception(__CLASS__ . ': configuration(' . $p_sDomainKey . '/' . $p_sBiz . ') lost.');
            }
            return '';
            // load_lib('/util/guid');
            // $sFileKey = util_guid::getGuid();
            $sFileKey = sha1_file($p_sTmpName);
            $aFileInfo = dao_dfsdao::getDetail($sFileKey);
            $aImageType = dao_dfsdao::getConfig('aImageType');
            if (in_array($sExtension, $aImageType)) {
                $bImage = true;
            } else {
                $bImage = false;
            }
            $this->saveBiz($sFileKey, $p_sBiz);
            if (null === $aFileInfo) {
                $iHostID = $this->dispatchUploadHostID();
                $sSubDir = $this->dispatchFile($sFileKey);
                $sBaseDir = dao_dfsdao::getConfig('sRawDir', 'storage');
                $sDir = $sBaseDir . DIRECTORY_SEPARATOR . $iHostID . $sSubDir;
                if (! is_dir($sDir)) {
                    util_file::tryMakeDir($sDir, 0755, true);
                }
                $sDestFile = $sDir . $sFileKey;
                $aImageInfo = array();
                if ($bImage) {
                    $aImageInfo = $this->getImageInfo($p_sTmpName);
                    $o_aFileInfo = array(
                        'sFilename' => $p_sName,
                        'sKey' => $sFileKey,
                        'sExt' => $sExtension,
                        'iSize' => $p_iSize,
                        'iWidth' => $aImageInfo['iWidth'],
                        'iHeight' => $aImageInfo['iHeight']
                    );
                } else {
                    $o_aFileInfo = array(
                        'sFilename' => $p_sName,
                        'sKey' => $sFileKey,
                        'sExt' => $sExtension,
                        'iSize' => $p_iSize
                    );
                }
                if (false === move_uploaded_file($p_sTmpName, $sDestFile)) {
                    throw new Exception(__CLASS__ . ': can not move upload file to file(' . $sDestFile . ').');
                    return false;
                } else {
                    $aDFS = array(
                        'sFileKey' => $sFileKey,
                        'sMimeType' => $sMimeType,
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
                            'sFileName' => $p_sName,
                            'sFileKey' => $sFileKey,
                            'iFileSize' => $p_iSize,
                            'sFileExt' => $sExtension,
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
                    if ($bImage) {
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
                    }
                    return true;
                }
            } else {
                if ($bImage) {
                    load_lib('/dao/dfsimageinfodao');
                    $aImageInfo = dao_dfsimageinfodao::getDetail($aFileInfo['sFileKey']);
                    if (null === $aImageInfo) {
                        $aImageInfo = $this->getImageInfo($p_sTmpName);
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
                    $o_aFileInfo = array(
                        'sFilename' => $p_sName,
                        'sKey' => $sFileKey,
                        'sExt' => $sExtension,
                        'iSize' => $p_iSize,
                        'iWidth' => $aImageInfo['iWidth'],
                        'iHeight' => $aImageInfo['iHeight']
                    );
                } else {
                    $o_aFileInfo = array(
                        'sFilename' => $p_sName,
                        'sKey' => $sFileKey,
                        'sExt' => $sExtension,
                        'iSize' => $p_iSize
                    );
                }
                return true;
            }
        } else {
            util_error::initError();
            util_error::addBizError('biz', util_error::TYPE_INVALID, $p_sBiz);
            return $this->returnErrors(util_error::getErrors());
        }
    }

    /**
     * 保存上传文件
     *
     * @param string $p_sName            
     * @param string $p_sTmpName            
     * @param int $p_iError            
     * @param int $p_iSize            
     * @param string $p_sIp            
     * @param int $p_iTime            
     * @param string $p_sBiz            
     * @param array $o_aFileInfo            
     * @return true/string
     */
    function saveFile1($p_sName, $p_sTmpName, $p_iError, $p_iSize, $p_sIp, $p_iTime, $p_sBiz, &$o_aFileInfo)
    {
        load_lib('/util/error');
        if ($p_iError > 0) {
            $this->addLog($this->mkUpdErr(util_error::tagError('Upload', util_error::TYPE_ERROR, util_error::LEVEL_SYS), $p_iError), 'upd_error');
            return util_error::tagError('Upload', util_error::TYPE_ERROR, util_error::LEVEL_SYS);
        }
        if (is_uploaded_file($p_sTmpName)) {
            if ($this->chkBiz($p_sBiz)) {
                load_lib('/dao/dfsdao');
                $aAllowedSize = dao_dfsdao::getConfig('aUpdSize', 'upload');
                $bGetIt = false;
                foreach ($aAllowedSize as $sPattern => $aSize) {
                    if (1 === preg_match($sPattern, $this->_sDomain)) {
                        $bGetIt = true;
                        break;
                    }
                }
                if ($bGetIt) {
                    $aAllowedSize = $this->getBizConfig($p_sBiz, $aSize);
                } else {
                    throw new Exception(__CLASS__ . ': configuration(upload size) lost.');
                    return false;
                }
                if ($p_iSize < $aAllowedSize['iMin']) {
                    $this->addLog($this->mkUpdErr(util_error::tagError('Size', util_error::TYPE_VALUE_SMALL), $p_iSize), 'upd_error');
                    return util_error::tagError('Size', util_error::TYPE_VALUE_SMALL);
                } elseif ($p_iSize > $aAllowedSize['iMax']) {
                    $this->addLog($this->mkUpdErr(util_error::tagError('Size', util_error::TYPE_VALUE_BIG), $p_iSize), 'upd_error');
                    return util_error::tagError('Size', util_error::TYPE_VALUE_BIG);
                }
                load_lib('/util/file');
                $oFInfo = finfo_open();
                $sMimeType = finfo_file($oFInfo, $p_sTmpName, FILEINFO_MIME_TYPE);
                finfo_close($oFInfo);
                $sExtension = util_file::getExtension($sMimeType);
                if ('dat' == $sExtension) {
                    $this->addLog(array(
                        'sFilename' => $p_sName,
                        'sMimetype' => $sMimeType
                    ), 'unknow_mime');
                }
                $aAllowedExtension = dao_dfsdao::getConfig('aUpdType', 'upload');
                $bGetIt = false;
                foreach ($aAllowedExtension as $sPattern => $aExtension) {
                    if (1 === preg_match($sPattern, $this->_sDomain)) {
                        $bGetIt = true;
                        break;
                    }
                }
                if ($bGetIt) {
                    $aAllowedExtension = $this->getBizConfig($p_sBiz, $aExtension);
                } else {
                    throw new Exception(__CLASS__ . ': configuration(upload type) lost.');
                    return false;
                }
                if ('office' == $sExtension) {
                    $aFileInfo = pathinfo($p_sName);
                    $sExtension = $aFileInfo['extension'];
                    $sMimeType = util_file::getMimeType($sExtension);
                }
                if (! in_array($sExtension, $aAllowedExtension)) {
                    $this->addLog($this->mkUpdErr(util_error::tagError('Ext', util_error::TYPE_INVALID), $sExtension), 'upd_error');
                    return util_error::tagError('Ext', util_error::TYPE_INVALID);
                }
                // load_lib('/util/guid');
                // $sFileKey = util_guid::getGuid();
                $sFileKey = sha1_file($p_sTmpName);
                $aFileInfo = dao_dfsdao::getDetail($sFileKey);
                $aImageType = dao_dfsdao::getConfig('aImageType');
                if (in_array($sExtension, $aImageType)) {
                    $bImage = true;
                } else {
                    $bImage = false;
                }
                $this->saveBiz($sFileKey, $p_sBiz);
                if (null === $aFileInfo) {
                    $iHostID = $this->dispatchUploadHostID();
                    $sSubDir = $this->dispatchFile($sFileKey);
                    $sBaseDir = dao_dfsdao::getConfig('sRawDir', 'storage');
                    $sDir = $sBaseDir . DIRECTORY_SEPARATOR . $iHostID . $sSubDir;
                    if (! is_dir($sDir)) {
                        util_file::tryMakeDir($sDir, 0755, true);
                    }
                    $sDestFile = $sDir . $sFileKey;
                    $aImageInfo = array();
                    if ($bImage) {
                        $aImageInfo = $this->getImageInfo($p_sTmpName);
                        $o_aFileInfo = array(
                            'sFilename' => $p_sName,
                            'sKey' => $sFileKey,
                            'sExt' => $sExtension,
                            'iSize' => $p_iSize,
                            'iWidth' => $aImageInfo['iWidth'],
                            'iHeight' => $aImageInfo['iHeight']
                        );
                    } else {
                        $o_aFileInfo = array(
                            'sFilename' => $p_sName,
                            'sKey' => $sFileKey,
                            'sExt' => $sExtension,
                            'iSize' => $p_iSize
                        );
                    }
                    if (false === move_uploaded_file($p_sTmpName, $sDestFile)) {
                        throw new Exception(__CLASS__ . ': can not move upload file to file(' . $sDestFile . ').');
                        return false;
                    } else {
                        $aDFS = array(
                            'sFileKey' => $sFileKey,
                            'sMimeType' => $sMimeType,
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
                                'sFileName' => $p_sName,
                                'sFileKey' => $sFileKey,
                                'iFileSize' => $p_iSize,
                                'sFileExt' => $sExtension,
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
                        if ($bImage) {
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
                        }
                        return true;
                    }
                } else {
                    if ($bImage) {
                        load_lib('/dao/dfsimageinfodao');
                        $aImageInfo = dao_dfsimageinfodao::getDetail($aFileInfo['sFileKey']);
                        if (null === $aImageInfo) {
                            $aImageInfo = $this->getImageInfo($p_sTmpName);
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
                        $o_aFileInfo = array(
                            'sFilename' => $p_sName,
                            'sKey' => $sFileKey,
                            'sExt' => $sExtension,
                            'iSize' => $p_iSize,
                            'iWidth' => $aImageInfo['iWidth'],
                            'iHeight' => $aImageInfo['iHeight']
                        );
                    } else {
                        $o_aFileInfo = array(
                            'sFilename' => $p_sName,
                            'sKey' => $sFileKey,
                            'sExt' => $sExtension,
                            'iSize' => $p_iSize
                        );
                    }
                    return true;
                }
            } else {
                $this->addLog($this->mkUpdErr(util_error::tagError('Biz', util_error::TYPE_INVALID), $p_sBiz), 'upd_error');
                return util_error::tagError('Biz', util_error::TYPE_INVALID);
            }
        } else {
            $this->addLog($this->mkUpdErr(util_error::tagError('Upload', util_error::TYPE_ERROR, util_error::LEVEL_SYS), $p_sTmpName), 'upd_error');
            return util_error::tagError('Upload', util_error::TYPE_ERROR, util_error::LEVEL_SYS);
        }
    }

    /**
     * 保存文件信息
     *
     * @param array $p_aParam            
     * @return int/false
     */
    function saveInfo1($p_aParam)
    {
        load_lib('/dao/dfsinfodao');
        return dao_dfsinfodao::addData($p_aParam);
    }

    /**
     * 保存图片信息
     *
     * @param array $p_aParam            
     * @return int/false
     */
    function saveImageInfo($p_aParam)
    {
        load_lib('/dao/dfsimageinfodao');
        return dao_dfsimageinfodao::addData($p_aParam);
    }

    /**
     * 所有备份全部损坏,清除文件数据,等待重新上传
     *
     * @param array $p_aParam            
     */
    function delFile($p_aParam)
    {
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
        foreach ($aBackupInfo as $aBackup) {
            dao_dfsbackupdao::delData($aBackup['iAutoId']);
            $sDestDir = $sBaseDir . DIRECTORY_SEPARATOR . $aBackup['iHostID'] . $sSubDir . $sFileKey;
            util_file::tryDeleteFile($sDestDir);
        }
        return true;
    }

    /**
     * 备份文件
     *
     * @param array $p_aParam            
     */
    function backupFile($p_aParam)
    {
        load_lib('/dao/dfsbackupdao');
        $sFileKey = $p_aParam['sFileKey'];
        $aBackupInfo = dao_dfsbackupdao::getList(array(
            'sFileKey' => $sFileKey
        ), '');
        if (empty($aBackupInfo)) {
            $aBackedHostID = array(
                $p_aParam['iHostID']
            );
            dao_dfsbackupdao::addData(array(
                'sFileKey' => $sFileKey,
                'iHostID' => $p_aParam['iHostID']
            ));
        } else {
            if (isset($p_aParam['aErrHostIDs'])) {
                $aTmpIDs = array();
                foreach ($aBackupInfo as $aBackup) {
                    if (in_array($aBackup['iHostID'], $p_aParam['aErrHostIDs'])) { // 把拦掉的那些拿掉
                        dao_dfsbackupdao::delData($aBackup['iAutoId']);
                    } else {
                        $aTmpIDs[] = $aBackup['iHostID'];
                    }
                }
                $aBackedHostID = $aTmpIDs;
            }
        }
        $iBackupCnt = dao_dfsbackupdao::getConfig('iBackupCnt', 'backup');
        $iBackedCnt = count($aBackedHostID);
        $iNeedBackupCnt = $iBackupCnt - $iBackedCnt;
        for ($i = 0; $i < $iNeedBackupCnt; ++ $i) {
            $iBackupHostID = $this->dispatchBackupHostID($aBackedHostID);
            if (null === $iBackupHostID) {
                break;
            } else {
                $sSubDir = $this->dispatchFile($sFileKey);
                $sBaseDir = dao_dfsbackupdao::getConfig('sRawDir', 'storage');
                $sDestDir = $sBaseDir . DIRECTORY_SEPARATOR . $iBackupHostID . $sSubDir;
                load_lib('/util/file');
                if (! is_dir($sDestDir)) {
                    if (false === util_file::tryMakeDir($sDestDir, 0755, true)) {
                        throw new Exception(__CLASS__ . ': can not create path(' . $sDestDir . ').');
                        return false;
                    }
                }
                $sDestFile = $sDestDir . $sFileKey;
                $sSourceDir = $sBaseDir . DIRECTORY_SEPARATOR . $p_aParam['iHostID'] . $sSubDir;
                $sSourceFile = $sSourceDir . $sFileKey;
                if (false === util_file::tryCopyFile($sSourceFile, $sDestFile, true)) {
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
     *
     * @param array $p_aExceptHostIDs            
     * @return int
     */
    function dispatchBackupHostID($p_aExceptHostIDs)
    {
        load_lib('/dao/dfsbackupdao');
        $aBackupHostIDs = dao_dfsbackupdao::getConfig('aStorageHost', 'backup');
        for ($i = 0; $i < 50; ++ $i) { // 只循环50次,如果始终找不到hostid,要么是存储设备不够,要么备份存储设备太多
            $iHostID = $this->dispatchHostID($aBackupHostIDs);
            if (in_array($iHostID, $p_aExceptHostIDs)) {
                continue;
            }
            return $iHostID;
        }
        return null;
    }
}
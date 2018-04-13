<?php
/**
 * bll savefile
 * @package app-file-upd_lib_bll
 */
load_lib('/bll/file');

/**
 * bll savefile
 *
 * @author jxu
 * @package app-file-upd_lib_bll
 */
class bll_savefile extends bll_file
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 保存上传文件
     *
     * @param string $p_sName            
     * @param string $p_sTmpName            
     * @param int $p_iError            
     * @param int $p_iSize            
     * @param string $p_sIP            
     * @param int $p_iTime            
     * @param string $p_sBiz            
     * @param array $o_aFileInfo            
     * @return true/string
     */
    function saveFile($p_sName, $p_sTmpName, $p_iError, $p_iSize, $p_sIP, $p_iTime, $p_sBiz, &$o_aFileInfo)
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
                                'sFromIP' => $p_sIP,
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
}
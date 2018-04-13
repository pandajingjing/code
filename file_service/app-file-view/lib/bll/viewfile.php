<?php
/**
 * bll viewfile
 * @package app-file-view_lib_bll
 */
load_lib('/bll/file');

/**
 * bll viewfile
 *
 * @author jxu
 * @package app-file-view_lib_bll
 */
class bll_viewfile extends bll_file
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 查看图片
     *
     * @param string $p_sKey            
     * @param string $p_sExt            
     * @param string $p_sBiz            
     * @param string $o_sMimeType            
     * @param string $o_sErrTag            
     * @param int $p_iWidth            
     * @param int $p_iHeight            
     * @param string $p_sOption            
     * @return object/false
     */
    function viewFile($p_sKey, $p_sExt, $p_sBiz, &$o_sMimeType, &$o_sErrTag, $p_iWidth = 0, $p_iHeight = 0, $p_sOption = '')
    {
        load_lib('/dao/dfsdao');
        load_lib('/util/error');
        $aFileInfo = dao_dfsdao::getDetail($p_sKey);
        $o_sMimeType = '';
        if (null === $aFileInfo) {
            $o_sErrTag = util_error::tagError('Info', util_error::TYPE_EMPTY);
            return false;
        }
        $p_sExt = strtolower($p_sExt);
        load_lib('/util/file');
        if ($p_sExt == util_file::getExtension($aFileInfo['sMimeType'])) {
            if (! $this->chkBiz($p_sBiz)) {
                $o_sErrTag = util_error::tagError('Biz', util_error::TYPE_INVALID);
                return false;
            }
            if ('' != $p_sBiz) {
                load_lib('/dao/dfsbizdao');
                $aBizs = dao_dfsbizdao::getList(array(
                    'sFileKey' => $p_sKey
                ), '');
                $bGet = false;
                foreach ($aBizs as $aBiz) {
                    if ($p_sBiz == $aBiz['sBiz']) {
                        $bGet = true;
                        break;
                    }
                }
                if (! $bGet) {
                    $o_sErrTag = util_error::tagError('Biz', util_error::TYPE_INVALID);
                    return false;
                }
            }
            $o_sMimeType = $aFileInfo['sMimeType'];
            $aImageType = dao_dfsdao::getConfig('aImageType');
            if (in_array($p_sExt, $aImageType)) {
                if ($p_iWidth > 0 and $p_iHeight > 0) { // 尺寸图
                    return $this->readSizedFile($p_sKey, $aFileInfo['iHostID'], $p_sExt, $p_sBiz, $p_iWidth, $p_iHeight, $p_sOption, $o_sErrTag);
                } else { // 源图
                    if ($this->chkOriginalBiz($p_sBiz)) {
                        $mResult = $this->readOriginalFile($p_sKey, $aFileInfo['iHostID']);
                        if (false === $mResult) {
                            $o_sErrTag = util_error::tagError('File', util_error::TYPE_ERROR);
                            return false;
                        } else {
                            return $mResult;
                        }
                    } else {
                        $o_sErrTag = util_error::tagError('OriginalBiz', util_error::TYPE_INVALID);
                        return false;
                    }
                }
            } else {
                if ($p_iWidth > 0 or $p_iHeight > 0) {
                    $o_sErrTag = util_error::tagError('WH', util_error::TYPE_VALUE_BIG);
                    return false;
                } else {
                    if ($this->chkOriginalBiz($p_sBiz)) {
                        $mResult = $this->readOriginalFile($p_sKey, $aFileInfo['iHostID']);
                        if (false === $mResult) {
                            $o_sErrTag = util_error::tagError('File', util_error::TYPE_ERROR);
                            return false;
                        } else {
                            return $mResult;
                        }
                    } else {
                        $o_sErrTag = util_error::tagError('OriginalBiz', util_error::TYPE_INVALID);
                        return false;
                    }
                }
            }
        } else {
            $o_sErrTag = util_error::tagError('Ext', util_error::TYPE_INVALID);
            return false;
        }
    }

    /**
     * 检查展示原图的业务
     *
     * @param string $p_sBiz            
     * @return boolean
     */
    protected function chkOriginalBiz($p_sBiz)
    {
        load_lib('/dao/dfsdao');
        $aShowOriginal = dao_dfsdao::getConfig('aOriginal', 'view');
        $bGetIt = false;
        foreach ($aShowOriginal as $sPattern => $aOriginalBiz) {
            if (1 === preg_match($sPattern, $this->_sDomain)) {
                $bGetIt = true;
                break;
            }
        }
        if ($bGetIt) {
            if (in_array($p_sBiz, $aOriginalBiz)) {
                return true;
            }
            return false;
        } else {
            return false;
        }
    }

    /**
     * 读取原始文件
     *
     * @param string $p_sKey            
     * @param int $p_iHostID            
     * @return object/false
     */
    protected function readOriginalFile($p_sKey, $p_iHostID)
    {
        load_lib('/util/file');
        $sPath = $this->getOriginalPath($p_sKey, $p_iHostID);
        if (false === $sPath) {
            return false;
        } else {
            return util_file::tryReadFile($sPath);
        }
    }

    /**
     * 读取尺寸图
     *
     * @param string $p_sKey            
     * @param int $p_iHostID            
     * @param string $p_sExt            
     * @param string $p_sBiz            
     * @param int $p_iWidth            
     * @param int $p_iHeight            
     * @param string $p_sOption            
     * @param string $o_sErrMsg            
     * @return object
     */
    protected function readSizedFile($p_sKey, $p_iHostID, $p_sExt, $p_sBiz, $p_iWidth, $p_iHeight, $p_sOption, &$o_sErrTag)
    {
        load_lib('/dao/dfsdao');
        load_lib('/util/error');
        $aResizeConfig = dao_dfsdao::getConfig('aResize', 'view');
        $bGetIt = false;
        foreach ($aResizeConfig as $sPattern => $aConfig) {
            if (1 === preg_match($sPattern, $this->_sDomain)) {
                $bGetIt = true;
                break;
            }
        }
        if ($bGetIt) {
            $aResizeConfig = $this->getBizConfig($p_sBiz, $aConfig);
        } else {
            throw new Exception(__CLASS__ . ': configuration(resize) lost.');
            return false;
        }
        $bGetIt = false;
        foreach ($aResizeConfig as $aConfig) {
            if ($aConfig['iWidth'] == $p_iWidth and $aConfig['iHeight'] == $p_iHeight) {
                $bGetIt = true;
                if ('' == $p_sOption) {
                    $aOption = $aConfig['aOption']['aDefault'];
                } else {
                    if (isset($aConfig['aOption'][$p_sOption])) {
                        $aOption = array_merge($aConfig['aOption']['aDefault'], $aConfig['aOption'][$p_sOption]);
                    } else {
                        $o_sErrTag = util_error::tagError('Opt', util_error::TYPE_INVALID);
                        return false;
                    }
                }
                break;
            }
        }
        if (! $bGetIt) {
            $o_sErrTag = util_error::tagError('WH', util_error::TYPE_INVALID);
            return false;
        }
        $sSubDir = $this->dispatchFile($p_sKey);
        $sDesBaseDir = dao_dfsdao::getConfig('sResizeDir', 'storage');
        $sDesDir = $sDesBaseDir . DIRECTORY_SEPARATOR . $p_iHostID . $sSubDir;
        $sDesFilename = $sDesDir . $p_sKey . '_' . $p_iWidth . 'x' . $p_iHeight;
        if ('' !== $p_sOption) {
            $sDesFilename = $sDesFilename . '_' . $p_sOption;
        }
        if ('' != $p_sBiz) {
            $sDesFilename = $sDesFilename . '_' . $p_sBiz;
        }
        if (file_exists($sDesFilename)) {
            $mResult = util_file::tryReadFile($sDesFilename);
            if (false === $mResult) {
                $o_sErrTag = util_error::tagError('File', util_error::TYPE_ERROR);
                return false;
            } else {
                return $mResult;
            }
        } else {
            $sOriginalPath = $this->getOriginalPath($p_sKey, $p_iHostID);
            if (false === $sOriginalPath) {
                $o_sErrTag = util_error::tagError('File', util_error::TYPE_ERROR);
                return false;
            } else {
                // if($this->isCompleteFile($sOriginalPath, $p_sExt)){//mod by jxu 201405141743
                load_lib('/util/image');
                try {
                    $oImage = util_image::resizeImage($sOriginalPath, $p_iWidth, $p_iHeight, $p_sExt, $aOption);
                } catch (Exception $oEx) {
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
                    $o_sErrTag = util_error::tagError('Resize', $oEx->getMessage());
                    return false;
                }
                $bCacheable = dao_dfsdao::getConfig('bCacheable', 'view');
                if ($bCacheable) {
                    if (! is_dir($sDesDir)) {
                        util_file::tryMakeDir($sDesDir, 0755, true);
                    }
                    util_file::tryWriteFile($sDesFilename, $oImage);
                }
                return $oImage;
                /*
                 * }else{ load_lib('/bll/mq/base'); $oBll = new bll_mq_base(); $oBll->sendMsg(array( 'iBID' => $oBll::BID_APP_FILE_DELETE, 'sController' => 'domsgcontroller', 'sHandle' => 'delFile', 'aData' => array( 'sFileKey' => $p_sKey ), 'iRetry' => 10 )); return false; }
                 */
            }
        }
    }
}
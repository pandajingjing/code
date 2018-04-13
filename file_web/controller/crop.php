<?php
/**
 * crop controller
 * @package app-file-upd_controller
 */
load_controller('/file');

/**
 * crop controller
 *
 * @author jxu
 * @package app-file-upd_controller
 */
class cropcontroller extends filecontroller
{

    function doRequest()
    {
        load_lib('/bll/cropfile');
        $oBll = new bll_cropfile();
        $aCropFiles = $aErrMsg = array();
        $bErrFnd = false;
        $sFromURL = $this->getParam('HTTP_REFERER', 'server');
        $sAgent = $this->getParam('HTTP_USER_AGENT', 'server');
        $mResult = $oBll->chkAllowedDomain($sFromURL, $sAgent);
        
        $aCropFile = array();
        $sBiz = $this->getParam('sAction', 'url');
        $sKey = $this->getParam('sKey', 'get');
        $sExt = $this->getParam('sExt', 'get');
        $iPointX = $this->getParam('iPointX', 'get', 'i');
        $iPointY = $this->getParam('iPointY', 'get', 'i');
        $iWidth = $this->getParam('iWidth', 'get', 'i');
        $iHeight = $this->getParam('iHeight', 'get', 'i');
        $mResult = $oBll->cropFile($sKey, $sExt, $sBiz, $iPointX, $iPointY, $iWidth, $iHeight, $this->getClientIP(), $this->getTime(), $aCropFile);
        if (true === $mResult) {
            $aCropFiles[$sKey] = $aCropFile;
        } else {
            $bErrFnd = true;
            $aErrMsg = array(
                $mResult
            );
        }
        $this->setPageData('sCrossDomain', $oBll->getCrossDomain($this->getParam('needjs', 'get')));
        $this->setPageData('jsonErrMsg', json_encode($aErrMsg));
        $this->setPageData('bErrFnd', $bErrFnd);
        $this->setPageData('jsonCropFiles', json_encode($aCropFiles));
        return '/crop';
    }
}
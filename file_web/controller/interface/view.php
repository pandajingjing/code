<?php
/**
 * view controller
 * @package app-file-view_controller_interface
 */
load_lib('/app/interface');

/**
 * view controller
 *
 * @author jxu
 * @package app-file-view_controller
 */
class interface_viewcontroller extends interface_controller
{

    function doRequest()
    {
        load_lib('/bll/viewfile');
        $clsBll = new bll_viewfile();
        $bErrFnd = false;
        $sErrMsg = '';
        $sFromURL = $this->getParam('HTTP_REFERER', 'server');
        $mResult = $clsBll->chkAllowedDomain($sFromURL);
        if (true === $mResult) {
            $aParams = $this->getAllParam('url');
            $sMimeType = '';
            switch (count($aParams)) {
                case 6:
                    $mResult = $clsBll->viewFile($aParams[1], $aParams[5], $aParams[0], $sMimeType, $sErrMsg, $aParams[2], $aParams[3], $aParams[4]);
                    // '/\/view\/([a-z]{1,10})\/([a-z0-9]{40})\/(\d*)x(\d*)\_([a-z]+)\.(jpg|gif|png|bmp)/i',//6
                    break;
                case 5:
                    if (is_numeric($aParams[1])) {
                        $mResult = $clsBll->viewFile($aParams[0], $aParams[4], '', $sMimeType, $sErrMsg, $aParams[1], $aParams[2], $aParams[3]);
                        // '/\/view\/([a-z0-9]{40})\/(\d*)x(\d*)\_([a-z]+)\.(jpg|gif|png|bmp)/i',//5
                    } else {
                        $mResult = $clsBll->viewFile($aParams[1], $aParams[4], $aParams[0], $sMimeType, $sErrMsg, $aParams[2], $aParams[3]);
                        // '/\/view\/([a-z]{1,10})\/([a-z0-9]{40})\/(\d*)x(\d*)\.(jpg|gif|png|bmp)/i',//5
                    }
                    break;
                case 4:
                    $mResult = $clsBll->viewFile($aParams[0], $aParams[3], '', $sMimeType, $sErrMsg, $aParams[1], $aParams[2]);
                    // '/\/view\/([a-z0-9]{40})\/(\d*)x(\d*)\.(jpg|gif|png|bmp)/i',//4
                    break;
                case 3:
                    $mResult = $clsBll->viewFile($aParams[1], $aParams[2], $aParams[0], $sMimeType, $sErrMsg);
                    // '/\/view\/([a-z]{1,10})\/([a-z0-9]{40})\.(jpg|gif|png|bmp)/i',//3
                    break;
                case 2:
                    $mResult = $clsBll->viewFile($aParams[0], $aParams[1], '', $sMimeType, $sErrMsg);
                    // '/\/view\/([a-z0-9]{40})\.(.*)/i' //2
                    break;
                default:
                    $mResult = false;
                    break;
            }
            
            if (false === $mResult) {
                $bErrFnd = true;
                $mResult = $sErrMsg;
            } else {
                // $this->addHeader('Content-type: ' . $sMimeType);
                // $this->addHeader('Cache-Control: max-age=315360000');
                // $this->addHeader('Expires: '.date('r',$this->getTime()+315360000));
                // $this->addHeader('Last-Modified: ' . date('r', 1395891028));
            }
        } else {
            $bErrFnd = true;
        }
        if ($bErrFnd) {
            $this->addHeader('DFS-Info: ' . $mResult);
            $this->addHeader('HTTP/1.0 404 Not Found');
        }
        $this->setPageData('bErrFnd', $bErrFnd);
        $this->setPageData('oFile', $mResult);
        return '/view';
    }
}
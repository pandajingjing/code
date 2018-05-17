<?php

/**
 * controller_upload
 *
 * 上传文件控制器
 *
 * @package controller
 */

/**
 * controller_upload
 *
 * 上传控制器
 */
class controller_upload extends lib_controller_service
{

    /**
     * 控制器入口函数
     *
     * @return string
     */
    function doRequest()
    {
        $sFromURL = $this->getParam('HTTP_REFERER', 'server');
        $sFromURL = 'http://member.jxulife.com/reg/';
        $sAgent = $this->getParam('HTTP_USER_AGENT', 'server');
        
        $this->addHeader('Cache-Control: no-cache, must-revalidate');
        $this->addHeader('Pramga: no-cache');
        $this->addHeader('Expires: -1');
        
        $aResult = bclient_file_save::getCrossDomain();
        if (1 == $aResult['iStatus']) {
            $aPatterns = $aResult['aList'];
            if (isset($sFromURL[0])) {
                $aURL = parse_url($sFromURL);
                if (is_array($aURL) and isset($aURL['host']) and isset($aURL['scheme'])) {
                    foreach ($aPatterns as $sPattern) {
                        if (preg_match($sPattern, $aURL['host']) > 0) {
                            $this->addHeader(sprintf('Access-Control-Allow-Origin:%s://%s', $aURL['scheme'], $aURL['host']));
                        }
                    }
                }
            }
        }
        debug(util_crypt::enCrypt('jxulife','0fc613bdc6'));
        $aResult = bclient_file_save::chkAllowedDomain($sFromURL, $sAgent);
        if (1 === $aResult['iStatus']) {
            $sDomainKey = $aResult['mOne'];
            $aFiles = $this->uploadMulti();
            $sBiz = $this->getParam('sBiz', 'router');
            $sIp = $this->getParam('CLIENTIP', 'server');
            $iTime = $this->getVisitTime();
            $aUpdResult = [];
            //debug($aFiles);
            foreach ($aFiles as $aFile) {
                if ($aFile['error'] > 0) {
                    util_error::initError();
                    util_error::addSysError('upload', util_error::TYPE_UNKNOWN_ERROR, $aFile['error']);
                    $aUpdResult[$aFile['key']] = util_error::getErrors();
                } else {
                    if (is_uploaded_file($aFile['tmp_name'])) {
                        $aResult = bclient_file_save::saveInfo($sDomainKey, $sBiz, $sIp, $iTime, $aFile['name'], file_get_contents($aFile['tmp_name']));
                        if (0 == $aResult['iStatus']) {
                            $aUpdResult[$aFile['key']] = $aResult['aErrors'];
                        } else {
                            $aUpdResult[$aFile['key']] = $aResult['aRow'];
                        }
                    } else {
                        util_error::initError();
                        util_error::addSysError('upload', util_error::TYPE_UNKNOWN_ERROR, - 1);
                        $aUpdResult[$aFile['key']] = util_error::getErrors();
                    }
                }
            }
            return $this->returnRow($aUpdResult);
        } else {
            return $this->returnErrors($aResult['aErrors']);
        }
    }

    /**
     * 获取所有上传的文件信息
     *
     * @return array
     */
    protected function uploadMulti()
    {
        $aFiles = $this->getAllParam('file');
        $aMulti = [];
        foreach ($aFiles as $sKey => $mFiles) {
            if (is_array($mFiles['name'])) {
                $iCnt = count($mFiles['name']);
                for ($i = 0; $i < $iCnt; ++ $i) {
                    $aMulti[] = [
                        'key' => $sKey . '_' . $i,
                        'name' => $mFiles['name'][$i],
                        'tmp_name' => $mFiles['tmp_name'][$i],
                        'error' => $mFiles['error'][$i],
                        'size' => $mFiles['size'][$i]
                    ];
                }
            } else {
                $aMulti[] = [
                    'key' => $sKey,
                    'name' => $mFiles['name'],
                    'tmp_name' => $mFiles['tmp_name'],
                    'error' => $mFiles['error'],
                    'size' => $mFiles['size']
                ];
            }
        }
        return $aMulti;
    }
}
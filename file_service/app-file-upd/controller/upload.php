<?php
/**
 * upload controller
 * @package app-file-upd_controller
 */
load_controller('/file');

/**
 * upload controller
 *
 * @author jxu
 * @package app-file-upd_controller
 */
class uploadcontroller extends filecontroller
{

    function doRequest()
    {
        set_time_limit(0);
        load_lib('/bll/savefile');
        $oBll = new bll_savefile();
        $aUpdFiles = $aErrMsg = array();
        $bErrFnd = false;
        $sFromURL = $this->getParam('HTTP_REFERER', 'server');
        $sAgent = $this->getParam('HTTP_USER_AGENT', 'server');
        $mResult = $oBll->chkAllowedDomain($sFromURL, $sAgent);
        if (true === $mResult) {
            $aFiles = $this->uploadMulti();
            $sBiz = $this->getParam('sAction', 'url');
            $sIP = $this->getClientIP();
            $iTime = $this->getTime();
            foreach ($aFiles as $aFile) { // @todo批量上传图片的优化
                $aUpdFile = array();
                $mResult = $oBll->saveFile($aFile['name'], $aFile['tmp_name'], $aFile['error'], $aFile['size'], $sIP, $iTime, $sBiz, $aUpdFile);
                if (true === $mResult) {
                    $aUpdFiles[$aFile['key']] = $aUpdFile;
                } else {
                    $aUpdFiles[$aFile['key']] = $mResult;
                }
            }
        } else {
            $bErrFnd = true;
            $aErrMsg = array(
                $mResult
            );
        }
        $this->addHeader('Cache-Control: no-cache, must-revalidate');
        $this->addHeader('Pramga: no-cache');
        $this->addHeader('Last-Modified: ' . gmdate('r', 0));
        $this->addHeader('Expires: ' . gmdate('r', 0));
        // $this->addHeader('Expires: '.date('r',$this->getTime()+315360000));
        // $this->addHeader('Last-Modified: ' . date('r', 1395891028));
        
        $sOrigin = '';
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            $sOrigin = $_SERVER['HTTP_ORIGIN'];
        } else if (strlen($sFromURL) > 0) {
            $sOrigin = $sFromURL;
        }
        if (! empty($sOrigin)) {
            $aUrl = parse_url($sOrigin);
            if (is_array($aUrl) && isset($aUrl['host'])) {
                $this->addHeader(sprintf('Access-Control-Allow-Origin:%s://%s', $aUrl['scheme'], $aUrl['host']));
            }
        }
        $this->setData('sCrossDomain', $oBll->getCrossDomain($this->getParam('needjs', 'get')));
        $this->setData('jsonErrMsg', json_encode($aErrMsg));
        $this->setData('bErrFnd', $bErrFnd);
        $this->setData('jsonUpdFiles', json_encode($aUpdFiles));
        return '/upload';
    }

    /**
     * 获取所有上传的文件信息
     *
     * @return array
     */
    protected function uploadMulti()
    {
        $aFiles = $this->getParams('file');
        $aMulti = array();
        foreach ($aFiles as $sKey => $mFiles) {
            if (is_array($mFiles['name'])) {
                $iCnt = count($mFiles['name']);
                for ($i = 0; $i < $iCnt; ++ $i) {
                    $aMulti[] = array(
                        'key' => $sKey . '_' . $i,
                        'name' => $mFiles['name'][$i],
                        'tmp_name' => $mFiles['tmp_name'][$i],
                        'error' => $mFiles['error'][$i],
                        'size' => $mFiles['size'][$i]
                    );
                }
            } else {
                $aMulti[] = array(
                    'key' => $sKey,
                    'name' => $mFiles['name'],
                    'tmp_name' => $mFiles['tmp_name'],
                    'error' => $mFiles['error'],
                    'size' => $mFiles['size']
                );
            }
        }
        return $aMulti;
    }
}
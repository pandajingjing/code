<?php

/**
 * controller_file
 * @author jxu
 * @package www_web_controller
 */
/**
 * controller_file
 *
 * @author jxu
 */
class controller_file extends controller_base
{

    function doRequest()
    {
        $sRootDir = '/data/winserver_share'; // 根目录,不可见
        $sRootDir='/data/backup';
        $sVScanDir = '/tv/成长的烦恼'; // 虚拟管理目录,可见
        $sVScanDir='';
        $sScanDir = $sRootDir . $sVScanDir; // 真实管理目录,不可见
        
        $iRename = $this->getParam('rename', 'post');
        $sFileListURL = $this->createInURL('controller_file');
        
        if (is_dir($sScanDir)) {
            $aScanFileList = scandir($sScanDir);
        } else {
            $aScanFileList = [];
        }
        $aFileList = [];
        $aPatternList = [
            'magic_kaito1412_mkv' => '/\[APTX4869\]\[MAGIC\_KAITO1412\]\[(\d+)\]\[HDRIP\]\[1080P\]\[AVC_AAC\]\[CHS\_CHT\_JPN\]\([a-zA-Z0-9]*\)\.([a-zA-Z0-9]{2,4})/i',
            'magic_kaito1412_mp4' => '/\[APTX4869\]\[MAGIC\_KAITO1412\]\[(\d+)\]\[720P\]\[AVC_AAC\]\[CHS\]\([a-zA-Z0-9]*\)\.([a-zA-Z0-9]{2,4})/i',
            'temp' => '/(.*)(a)\.([a-zA-Z0-9]{2,4})/i'
        ];
        
        $sPattern = $aPatternList['temp'];
        //$sPattern='/(.*)\.(web)\.tar\.gz/i';
        $bFound = false;
        
        foreach ($aScanFileList as $sFileName) {
            if (in_array($sFileName, [
                '.',
                '..'
            ])) {
                continue;
            }
            $aMatch = [];
            if (preg_match($sPattern, $sFileName, $aMatch)) {
                $bFound = true;
                $iMatchCnt = count($aMatch);
                $aMatch = util_string::trimString($aMatch);
                switch ($iMatchCnt) {
                    case 3:
                        $aFile = [
                            'sOldName' => $sFileName,
                            'sNewName' => $aMatch[1] . '.' . $aMatch[2]
                        ];
                        break;
                    case 4:
                        $aFile = [
                            'sOldName' => $sFileName,
                            'sNewName' => $aMatch[1] . 'b.' . $aMatch[3]
                        ];
                        break;
                    default:
                        break;
                }
                $aFile['sOldPath'] = $sVScanDir . '/' . $aFile['sOldName'];
                $aFile['sNewPath'] = $sVScanDir . '/' . $aFile['sNewName'];
                $aFileList[] = $aFile;
                if (1 == $iRename) {
                    rename($sRootDir . $aFile['sOldPath'], $sRootDir . $aFile['sNewPath']);
                }
            } else {
                $aFileList[] = [
                    'sOldName' => $sFileName,
                    'sNewName' => '',
                    'sOldPath' => $sVScanDir . '/' . $sFileName,
                    'sNewPath' => ''
                ];
            }
        }
        
        if ($iRename) {
            $this->redirectURL($sFileListURL);
        }
        $this->setData('aFileList', $aFileList);
        $this->setData('bFound', $bFound);
        $this->setData('sVScanDir', $sVScanDir);
        $this->setData('sPattern', $sPattern);
        return 'file';
    }
}
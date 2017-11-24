<?php

/**
 * controller_spider_mengsangge
 * @author jxu
 * @package message_console_controller_spider
 */
/**
 * controller_spider_mengsangge
 *
 * @author jxu
 */
class controller_spider_mengsangge extends lib_controller_cmd
{

    function doRequest()
    {
        $pdo = lib_data_pooling::getInstance()->getConnect('spider_db');
        // debug($sDuoRouURL, $sHash, $sImgURL, $sValue); // timex2
        $stmt = $pdo->prepare('insert into t_duorou_raw (sOriURL,sHash,sImgURL,sIntro,sIntro2,iCreateTime,iUpdateTime,sNameCN,sNameEN,sClassBigCN)values(:sOriURL,:sHash,:sImgURL,:sIntro,:sIntro2,:iCreateTime,:iUpdateTime,:sNameCN,:sNameEN,:sClassBigCN);');
        // var_dump($pdo);
        util_browser::setUserAgent();
        $sReferer = 'http://www.mengsang.com/duorou/';
        for ($iIndex = 1; $iIndex < 31; ++ $iIndex) {
            sleep(1);
            $sCurrentListURL = 'http://www.mengsang.com/duorou/list_1_' . $iIndex . '.html';
            $this->stdOut($sCurrentListURL);
            if (1 == $iIndex) {
                $sCurrentListURL = 'http://www.mengsang.com/duorou/';
            }
            util_browser::setReferer($sReferer);
            $sReferer = $sCurrentListURL;
            // debug($sCurrentListURL);
            $sContent = util_browser::getData($sCurrentListURL, 'string');
            $sContent = mb_convert_encoding($sContent, 'utf8', 'gb2312');
            $sContent = str_replace('gb2312', 'utf-8', $sContent);
            $oDomDoc = new DOMDocument();
            @$oDomDoc->loadHTML($sContent);
            $aTblList = $oDomDoc->getElementsByTagName('table');
            foreach ($aTblList as $oDomTbl) {
                // debug($oDomTbl);
                if ($oDomTbl->hasAttribute('class') and 'tImgUlTable' == $oDomTbl->getAttribute('class')) {
                    // debug($oDomTbl);
                    $oDomTD = $oDomTbl->firstChild->firstChild;
                    foreach ($oDomTD->childNodes as $oDom) {
                        if ('span' == $oDom->nodeName) {
                            foreach ($oDom->childNodes as $oDom) {
                                if ('a' == $oDom->nodeName) {
                                    $sDuoRouURL = $oDom->getAttribute('href'); //
                                    $this->stdOut($sDuoRouURL);
                                    $sContent = util_browser::getData($sDuoRouURL, 'string');
                                    $sContent = mb_convert_encoding($sContent, 'utf8', 'gb2312');
                                    $sContent = str_replace('gb2312', 'utf-8', $sContent);
                                    $sHash = md5($sContent); //
                                    $iCreateTime = $iUpdateTime = $this->getRealTime();
                                    $oDomDuoRou = new DOMDocument();
                                    @$oDomDuoRou->loadHTML($sContent);
                                    // 找图片
                                    $oDomImgList = $oDomDuoRou->getElementsByTagName('img');
                                    $sImgURL = '';
                                    foreach ($oDomImgList as $oDomImg) {
                                        if ($oDomImg->hasAttribute('style') and 'border: 0px; vertical-align: middle; width: 512px; height: 512px;' == $oDomImg->getAttribute('style')) {
                                            $sImgURL = 'http://www.mengsang.com/' . $oDomImg->getAttribute('src');
                                        }
                                    }
                                    // 从左边开始找
                                    $oDomDivList = $oDomDuoRou->getElementsByTagName('div');
                                    $sNameCN = $sNameEN = $sValue = '';
                                    $o = 0;
                                    foreach ($oDomDivList as $oDomDiv) {
                                        if ($oDomDiv->hasAttribute('class') and 'imgCenter' == $oDomDiv->getAttribute('class') and $sValue == '') { // 左边的框
                                            foreach ($oDomDiv->childNodes as $oDom) {
                                                $sValue .= str_replace([
                                                    "\t",
                                                    "\r",
                                                    "\n"
                                                ], [
                                                    '',
                                                    "\n",
                                                    ''
                                                ], $oDom->nodeValue);
                                            }
                                        }
                                        if ($oDomDiv->hasAttribute('class') and 'pt5' == $oDomDiv->getAttribute('class')) { // 右边的框
                                            ++ $o;
                                            if (5 == $o) {
                                                $sNameCN = str_replace('中文种名：', '', $oDomDiv->nodeValue);
                                            }
                                            if (6 == $o) {
                                                $sNameEN = str_replace('英文学名：', '', $oDomDiv->nodeValue);
                                            }
                                        }
                                    }
                                    $aTmp = explode('简介', $sValue);
                                    $sIntro = $sIntro2 = '';
                                    if (count($aTmp) > 0) {
                                        $sIntro = array_pop($aTmp);
                                        $sIntro2 = join($aTmp, ',');
                                    }
                                    $aTmp = [];
                                    $sTmp = '';
                                    preg_match_all('/\<div\ class\=\"mainBoxTitle\"\>\<span\ class\=\"mainBoxTitleCon\"\>(.*)\<\/span\>\<\/div\>/', $sContent, $aTmp);
                                    if (isset($aTmp[1][1])) {
                                        $sTmp = $aTmp[1][1];
                                    }
                                    $this->stdOut($sTmp);
                                    $stmt->bindParam(':sOriURL', $sDuoRouURL);
                                    $stmt->bindParam(':sHash', $sHash);
                                    $stmt->bindParam(':sImgURL', $sImgURL);
                                    $stmt->bindParam(':sIntro', $sIntro);
                                    $stmt->bindParam(':sIntro2', $sIntro2);
                                    $stmt->bindParam(':iCreateTime', $iCreateTime);
                                    $stmt->bindParam(':iUpdateTime', $iUpdateTime);
                                    $stmt->bindParam(':sNameEN', $sNameEN);
                                    $stmt->bindParam(':sNameCN', $sNameCN);
                                    $stmt->bindParam(':sClassBigCN', $sTmp);
                                    $stmt->execute();
                                    // exit();
                                    // debug($sDuoRouURL, $sHash, $sImgURL, $sValue); // timex2
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

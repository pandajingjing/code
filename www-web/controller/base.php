<?php

/**
 * controller_base
 * @author jxu
 * @package www-web_controller
 */
/**
 * controller_base
 *
 * @author jxu
 */
abstract class controller_base extends lib_controller_web
{

    /**
     * 在控制器开始时执行（调度使用）
     */
    function beforeRequest()
    {
        parent::beforeRequest();
        // do something
        $this->setData('fScriptStartTime', $this->getRealTime(true));
        $this->setData('sRemoteIP', $this->getParam('CLIENTIP', 'server'));
        $sGUID = $this->getParam('guid', 'cookie');
        if ('' == $sGUID) {
            $sGUID = util_guid::getGuid();
        }
        $this->setCookie('guid', $sGUID, 31536000);
        $this->setData('iVisitTime', $this->getVisitTime());
        
        $aTopURLs = [
            'sDefault' => $this->createInURL('controller_home_home'),
            'sHome' => $this->createInURL('controller_home_home'),
            'sSudoku' => $this->createInURL('controller_sudoku'),
            'sItem' => $this->createInURL('controller_item_list'),
            'sNote' => $this->createInURL('controller_note'),
            'sFile' => $this->createInURL('controller_file'),
            'aQZoneTopList' => []
        ];
        $aResult = bclient_qzone::getTopArticleKeyList();
        $aTopArticleKeyList = $aResult['aDataList'];
        foreach ($aTopArticleKeyList as $sKey) {
            $aTopURLs['aQZoneTopList'][$sKey] = $this->createInURL('controller_qzone_show', [
                'article' => $sKey
            ]);
        }
        unset($aTopArticleKeyList);
        $aTopURLs['aQZoneTopList']['sOther'] = $this->createInURL('controller_qzone_list');
        $this->setData('aTopURLs', $aTopURLs);
    }

    /**
     * 在控制器结束时执行（调度使用）
     */
    function afterRequest()
    {
        // do something
        $this->setData('fScriptEndTime', $this->getRealTime(true));
        parent::afterRequest();
    }
}
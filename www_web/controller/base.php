<?php

/**
 * controller_base
 * @author jxu
 * @package www_web_controller
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
        $this->setPageData('fScriptStartTime', $this->getRealTime(true));
        $this->setPageData('sRemoteIp', $this->getParam('CLIENTIP', 'server'));
        $sGuid = $this->getParam('guid', 'cookie');
        if ('' == $sGuid) {
            $sGuid = util_guid::getGuid();
        }
        $this->setCookie('guid', $sGuid, 31536000);
        $this->setPageData('iVisitTime', $this->getVisitTime());
        
        $aTopUrls = [
            'sDefault' => $this->createInUrl('controller_home_home'),
            'sHome' => $this->createInUrl('controller_home_home'),
            'sSudoku' => $this->createInUrl('controller_sudoku'),
            'sItem' => $this->createInUrl('controller_item_list'),
            'sNote' => $this->createInUrl('controller_note'),
            'sFile' => $this->createInUrl('controller_file'),
            'aQZoneTopList' => []
        ];
        $aResult = bclient_qzone::getTopArticleKeyList();
        $aTopArticleKeyList = $aResult['aDataList'];
        foreach ($aTopArticleKeyList as $sKey) {
            $aTopUrls['aQZoneTopList'][$sKey] = $this->createInUrl('controller_qzone_show', [
                'article' => $sKey
            ]);
        }
        unset($aTopArticleKeyList);
        $aTopUrls['aQZoneTopList']['sOther'] = $this->createInUrl('controller_qzone_list');
        $this->setPageData('aTopUrls', $aTopUrls);
    }

    /**
     * 在控制器结束时执行（调度使用）
     */
    function afterRequest()
    {
        // do something
        $this->setPageData('fScriptEndTime', $this->getRealTime(true));
        parent::afterRequest();
    }
}
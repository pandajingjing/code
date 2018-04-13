<?php
/**
 * home
 * 
 * @namespace app\controller\home
 */
namespace app\controller\home;

use app\controller\base;
use blank_service\bll\doc;

/**
 * home
 */
class home extends base
{

    function doRequest()
    {
        $oBllDoc = new doc();
        $aChapters = $oBllDoc->getChapters();
        $aChapterList = [];
        foreach ($aChapters as $aChapter) {
            $sAnchor = 'doc_' . $aChapter['iIndex'];
            $aChapterList[] = [
                'sAnchor' => $sAnchor,
                'sTitle' => $aChapter['sTitle'],
                'sUrl' => $this->createInUrl('\\app\\controller\\doc', [], $sAnchor)
            ];
        }
        $this->setPageData('aChapterList', $aChapterList);
        return '/home/home';
    }
}
<?php
/**
 * doc
 * 
 * @namespace app\controller
 */
namespace app\controller;

use blank_service\bll\doc as blldoc;

/**
 * doc
 */
class doc extends base
{

    function doRequest()
    {
        $oBllDoc = new blldoc();
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
        return '/doc';
    }
}
<?php
/**
 * home
 * 
 * @namespace app\controller\home
 * @package blank_web\controller_home
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
        $oBllDoc=new doc();
        $aChapters=$oBllDoc->getChapters();
        $aDocList = [
            $this->createInUrl('\\app\\controller\\doc', [], '_doc_1'),
            $this->createInUrl('\\app\\controller\\doc', [], '_doc_2'),
            $this->createInUrl('\\app\\controller\\doc', [], '_doc_3'),
            $this->createInUrl('\\app\\controller\\doc', [], '_doc_4'),
            $this->createInUrl('\\app\\controller\\doc', [], '_doc_5'),
            $this->createInUrl('\\app\\controller\\doc', [], '_doc_6'),
            $this->createInUrl('\\app\\controller\\doc', [], '_doc_7'),
            $this->createInUrl('\\app\\controller\\doc', [], '_doc_8')
        ];
        
        $this->setData('aDocList', $aDocList);
        return 'home_home';
    }
}
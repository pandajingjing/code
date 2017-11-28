<?php
/**
 * home
 *
 * @namespace app\controller\home
 */
namespace app\controller\home;

use app\controller\base;
use duorou_service\orm\spider\demo;
use panda\util\strings;

/**
 * home
 */
class home extends base
{

    function doRequest()
    {
        // 增
        $oDemo = new demo();
        $oDemo->sName = strings::getRandStr(10);
        $oDemo->aOtherInfo = [
            'test' => strings::getRandStr(5),
            'key2' => strings::getRandStr(5)
        ];
        $oDemo->addData();
        // 删
        $oDemo = new demo();
        $oDemo->iAutoId = 6;
        $oTest = $oDemo->delData();
        // 改
        $oDemo = new demo();
        $oDemo->iAutoId = 11;
        $oDemo->sName = strings::getRandStr(10);
        $oTest = $oDemo->updData();
        // 查
        $oDemo = new demo();
        $oDemo->setRecycled();
        $oDemo->iAutoId = 5;
        $oDemo->getDetail();
        
        $oDemo->getBizCnt('sName', [
            'sName' => '%6%'
        ]);
        $oDemo->setFetchRow(5);
        $oTest = $oDemo->getBizList('sName', [
            'sName' => '%6%'
        ]);
        $oDemo->getListByPkVals('1,2,3,4,5,6,7,8,9,10');
        
        $oDemo->addFilter('sName', 'like', '%6%');
        $oDemo->addFilter('iAutoId', '!=', 5);
        $oDemo->addFilter('iAutoId', 'in', [
            1,
            2,
            4,
            5,
            6
        ]);
        $oDemo->addFilter('iAddTime>:iAddTime', [
            'iAddTime' => 5
        ]);
        $oDemo->addFilter('iAutoId<:iAutoId', [
            'iAutoId' => PHP_INT_MAX
        ]);
        $oDemo->getList();
        $oDemo->getCnt();
        return 'home_home';
    }
}
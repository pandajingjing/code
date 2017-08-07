<?php

/**
 * bll_blank_demo
 * @author jxu
 * @package bll_blank
 */

/**
 * bll_blank_demo
 *
 * @author jxu
 */
class bll_blank_demo extends lib_sys_bll
{

    /**
     * 返回列表数据
     *
     * @param int $p_iPage            
     * @return array
     */
    function getSomeList($p_iPage)
    {
        for ($iIndex = 0; $iIndex < 50; ++ $iIndex) {
            $aTotalList[] = [
                'iAutoID' => ($iIndex + 1),
                'sName' => util_string::getRand(10)
            ];
        }
        $iPageSize = 5;
        $iOffset = ($p_iPage - 1) * $iPageSize;
        $aList = array_slice($aTotalList, $iOffset, $iPageSize);
        return $this->returnList($aList, count($aTotalList));
    }

    /**
     * 返回单行数据
     *
     * @param int $p_iAutoID            
     * @return array
     */
    function getDemoDetail($p_iAutoID)
    {
        $oORM = new orm_spider_demo();
        $oORM->iAutoID = $p_iAutoID;
        $mResult = $oORM->getRow(true);
        if (null === $mResult) {
            util_error::initError();
            util_error::addBizError('iAutoID', util_error::TYPE_NOT_FOUND);
            return $this->returnErrors(util_error::getErrors());
        } else {
            return $this->returnSuccess($mResult->getSource());
        }
    }

    /**
     * 新增一条数据
     *
     * @param string $p_sName            
     * @param int $p_iAddTime            
     * @return array
     */
    function addDemo($p_sName, $p_iAddTime)
    {
        $oORM = new orm_spider_demo();
        $oORM->sName = $p_sName;
        $oORM->iAddTime = $p_iAddTime;
        $mResult = $oORM->addData();
        if (false === $mResult) {
            util_error::initError();
            util_error::addSysError('db', util_error::TYPE_UNKNOWN_ERROR);
            return $this->returnErrors(util_error::getErrors());
        } else {
            return $this->returnSuccess([
                'iAutoID' => $mResult
            ]);
        }
    }

    /**
     * 更新一条数据
     *
     * @param string $p_sName            
     * @param int $p_iAutoID            
     * @return array
     */
    function updDemo($p_sName, $p_iAutoID)
    {
        $oORM = new orm_spider_demo();
        $oORM->iAutoID = $p_iAutoID;
        $oORM->sName = $p_sName;
        $mResult = $oORM->updData();
        return $this->returnSuccess([
            $mResult
        ]);
    }
}
<?php

/**
 * Controller_Ajax_Weight
 * @author jxu
 * @package bodybuild-web_controller_Ajax
 */
/**
 * Controller_Ajax_Weight
 *
 * @author jxu
 */
class Controller_Ajax_Weight extends Controller_Sys_Ajax
{

    function doRequest()
    {
        $iDay = $this->getParam('iDay', 'router', 'int');
        $aWeightDatas = $this->_getData();
        $iCnt = count($aWeightDatas);
        $aPager = util_pager::getPager(1, $iDay, $iCnt, 5);
        $aDatas = array_slice($aWeightDatas, ($aPager['aCurrentPage']['iIndex'] - 1) * $aPager['iPageSize'], $aPager['iPageSize']);
        // print_r($aWeightDatas);
        // print_r($aPager);
        // print_r($aDatas);
        return $this->setList(count($aDatas), $aDatas);
    }

    private function _getData()
    {
        return array(
            array(
                '2015-08-02',
                73.9
            ),
            array(
                '2015-08-01',
                74.3
            ),
            array(
                '2015-07-31',
                74.5
            ),
            array(
                '2015-07-30',
                74.6
            ),
            array(
                '2015-07-29',
                74.8
            ),
            array(
                '2015-07-28',
                74.8
            ),
            array(
                '2015-07-27',
                75.1
            ),
            array(
                '2015-07-26',
                74.3
            ),
            array(
                '2015-07-25',
                74.8
            ),
            array(
                '2015-07-24',
                75
            ),
            array(
                '2015-07-23',
                74.6
            ),
            array(
                '2015-07-22',
                73.8
            ),
            array(
                '2015-07-21',
                74.1
            ),
            array(
                '2015-07-20',
                74.3
            ),
            array(
                '2015-07-19',
                74.5
            ),
            array(
                '2015-07-18',
                75.3
            ),
            array(
                '2015-07-17',
                75.6
            ),
            array(
                '2015-07-16',
                74.8
            ),
            array(
                '2015-07-15',
                75
            ),
            array(
                '2015-07-14',
                75.1
            ),
            array(
                '2015-07-13',
                75
            ),
            array(
                '2015-07-12',
                74.6
            ),
            array(
                '2015-07-11',
                74.7
            ),
            array(
                '2015-07-10',
                75.1
            ),
            array(
                '2015-07-09',
                75.1
            ),
            array(
                '2015-07-08',
                75.5
            ),
            array(
                '2015-07-07',
                75.8
            ),
            array(
                '2015-07-06',
                76.3
            ),
            array(
                '2015-07-05',
                75.4
            ),
            array(
                '2015-07-04',
                76
            ),
            array(
                '2015-07-03',
                75.7
            ),
            array(
                '2015-07-02',
                76.1
            ),
            array(
                '2015-07-01',
                76.3
            ),
            array(
                '2015-06-30',
                76.6
            ),
            array(
                '2015-06-29',
                76.1
            ),
            array(
                '2015-06-28',
                76.7
            ),
            array(
                '2015-06-27',
                76.8
            ),
            array(
                '2015-06-26',
                76.9
            ),
            array(
                '2015-06-25',
                77.1
            ),
            array(
                '2015-06-24',
                77.3
            ),
            array(
                '2015-06-23',
                78.1
            ),
            array(
                '2015-06-22',
                78.7
            ),
            array(
                '2016-06-21',
                78.7
            ),
            array(
                '2016-06-20',
                79
            )
        );
    }
}
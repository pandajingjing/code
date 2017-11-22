<?php

/**
 * Home
 * @author jxu
 * @package blank-web_controller_home
 */
namespace app\controller\home;

use app\controller\Base;

/**
 * Home
 *
 * @author jxu
 */
class Home extends Base
{

    function doRequest()
    {
        $aDocList = [
            $this->createInURL('\\app\\controller\\Doc', [], '_doc_1'),
            $this->createInURL('\\app\\controller\\Doc', [], '_doc_2'),
            $this->createInURL('\\app\\controller\\Doc', [], '_doc_3'),
            $this->createInURL('\\app\\controller\\Doc', [], '_doc_4'),
            $this->createInURL('\\app\\controller\\Doc', [], '_doc_5'),
            $this->createInURL('\\app\\controller\\Doc', [], '_doc_6'),
            $this->createInURL('\\app\\controller\\Doc', [], '_doc_7'),
            $this->createInURL('\\app\\controller\\Doc', [], '_doc_8')
        ];
        
        $this->setData('aDocList', $aDocList);
        return 'home_home';
    }
}
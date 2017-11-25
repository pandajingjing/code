<?php
namespace app\controller\home;

use app\controller\Base;

/**
 * controller_home_home
 *
 * @author jxu
 * @package duorou_web_controller_home
 */
/**
 * controller_home_home
 *
 * @author jxu
 */
class Images extends Base
{

    function doRequest()
    {
        $aOriImgs = file(dirname(__FILE__) . '/img.txt');
        $aImgs = [];
        foreach ($aOriImgs as $sOriImg) {
            $iPosA = strpos($sOriImg, 'http');
            $iPosB = strpos($sOriImg, 'jpg');
            $aImgs[] = trim(substr($sOriImg, $iPosA, ($iPosB - $iPosA + 3)));
        }
        $aImgs = array_slice($aImgs, rand(0, count($aImgs)), 20);
        $this->setPageData('aImgs', $aImgs);
        return 'home_images';
    }
}
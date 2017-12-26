<?php
/**
 * tools
 *
 * @namespace app\controller\home
 */
namespace app\controller\home;

use app\controller\base;

/**
 * tools
 */
class tools extends base
{

    function doRequest()
    {
        $aFormData = [
            'eType' => $this->getParam('type', 'post') ?? 'rand',
            'sDate' => $this->getParam('date', 'post') ?? date('Y.m.d'),
            'sPinZhong' => $this->getParam('pinzhong', 'post') ?? '',
            'eSpec' => $this->getParam('spec', 'post') ?? 'one',
            'fSize' => $this->getParam('size', 'post') ?? '',
            'iYear' => $this->getParam('year', 'post') ?? '2',
            'fPrice' => $this->getParam('price', 'post') ?? '',
            'eExpress' => $this->getParam('express', 'post') ?? 'kd',
            'sRandWord' => $this->getParam('randword', 'post') ?? ''
        ];
        $sNextAction = $this->getParam('next_action', 'post') ?? '';
        $aSendData = [
            'sService' => '只包开箱黑腐',
            'sQq' => '414805921',
            'sWeChat' => 'aiduorou511',
            'sMobile' => '17317974568',
            'sWangWang' => '爱多肉的程序媛',
            'sCity' => '上海',
            'sDate' => $aFormData['sDate'],
            'sPinZhong' => $aFormData['sPinZhong'],
            'sSize' => '尺寸参考' . $aFormData['fSize'] . '厘米盆',
            'sPrice' => $aFormData['fPrice'] . '米',
            'sYear' => '多年生',
            'sRandWord' => $aFormData['sRandWord']
        ];
        switch ($aFormData['eType']) {
            case 'one':
                $aSendData['sType'] = '一物一拍';
                break;
            case 'rand':
            default:
                $aSendData['sType'] = '随机不挑';
                break;
        }
        switch ($aFormData['eSpec']) {
            case 'more':
                $aSendData['sSpec'] = '群生';
                break;
            case 'two':
                $aSendData['sSpec'] = '双头';
                break;
            case 'one':
            default:
                $aSendData['sSpec'] = '单头';
                break;
        }
        switch ($aFormData['eExpress']) {
            case 'all':
                $aSendData['sExpress'] = '包邮';
                break;
            case 'df':
                $aSendData['sExpress'] = '到付';
                break;
            case 'kd':
            default:
                $aSendData['sExpress'] = '默认圆中6,偏远15,满68包';
                break;
        }
        // debug($aFormData, $aSendData);
        $aContent = [];
        if ($sNextAction == 'save') {
            $aContent = [];
            
            $aContent['aQQ'] = [];
            $aContent['aQQ']['sRemark'] = 'QQ(9张图)';
            $aContent['aQQ']['sPlace'] = '湄浦花木城招租办公室';
            $aContent['aQQ']['sContent1'] = $aSendData['sPinZhong'];
            $aContent['aQQ']['sContent1'] .= '.' . $aSendData['sRandWord'];
            $aContent['aQQ']['sContent2'] = '大棚地址:上海市宝山区湄浦花木城东区C6-7.Q:' . $aSendData['sQq'] . '.V:' . $aSendData['sWeChat'] . '.全部自家大棚养殖,景天,生石花,玉露等全状态.1号线友谊西路,坐地铁就能逛.';
            
            $aContent['aWeChat'] = [];
            $aContent['aWeChat']['sRemark'] = '微信(9张图)';
            $aContent['aWeChat']['sPlace'] = '湄浦花木城';
            $aContent['aWeChat']['sContent1'] = $aSendData['sPinZhong'];
            $aContent['aWeChat']['sContent1'] .= '.' . $aSendData['sRandWord'];
            $aContent['aWeChat']['sContent2'] = '大棚地址:上海市宝山区湄浦花木城东区C6-7.Q:' . $aSendData['sQq'] . '.V:' . $aSendData['sWeChat'] . '.全部自家大棚养殖,景天,生石花,玉露等全状态.1号线友谊西路,坐地铁就能逛.';
            
            $aContent['aDuoRouZhiWuApp_TuanGou'] = [];
            $aContent['aDuoRouZhiWuApp_TuanGou']['sRemark'] = '多肉植物App团购';
            $aContent['aDuoRouZhiWuApp_TuanGou']['sPlace'] = '?';
            $aContent['aDuoRouZhiWuApp_TuanGou']['sContent1'] = '';
            
            $aContent['aDuoJiangZhiWuJiaoYiBa'] = [];
            $aContent['aDuoJiangZhiWuJiaoYiBa']['sRemark'] = '多浆植物交易吧(3张图)';
            $aContent['aDuoJiangZhiWuJiaoYiBa']['sPlace'] = '友谊西路地铁站';
            $aContent['aDuoJiangZhiWuJiaoYiBa']['sTitle'] = '[' . $aSendData['sType'] . '][' . $aSendData['sCity'] . '][' . $aSendData['sDate'] . ']' . $aSendData['sPinZhong'] . '.' . $aSendData['sSpec'] . '.' . $aSendData['sRandWord'];
            $aContent['aDuoJiangZhiWuJiaoYiBa']['sContent1'] = $aSendData['sPinZhong'];
            $aContent['aDuoJiangZhiWuJiaoYiBa']['sContent1'] .= '.' . $aSendData['sSpec'];
            $aContent['aDuoJiangZhiWuJiaoYiBa']['sContent1'] .= '.' . $aSendData['sSize'];
            $aContent['aDuoJiangZhiWuJiaoYiBa']['sContent1'] .= '.自家种植,欢迎交流经验';
            $aContent['aDuoJiangZhiWuJiaoYiBa']['sContent2'] = '加~v:' . $aSendData['sWeChat'] . "\r\n";
            $aContent['aDuoJiangZhiWuJiaoYiBa']['sContent2'] .= '扣:' . $aSendData['sQq'] . "\r\n";
            $aContent['aDuoJiangZhiWuJiaoYiBa']['sContent2'] .= $aSendData['sPrice'] . "\r\n";
            $aContent['aDuoJiangZhiWuJiaoYiBa']['sContent2'] .= $aSendData['sExpress'] . "\r\n";
            $aContent['aDuoJiangZhiWuJiaoYiBa']['sContent2'] .= $aSendData['sService'] . "\r\n";
            
            $aContent['aDuoRouZhiWuJiaoYiBa'] = [];
            $aContent['aDuoRouZhiWuJiaoYiBa']['sRemark'] = '多肉植物交易吧(3张图),只能发一贴';
            $aContent['aDuoRouZhiWuJiaoYiBa']['sPlace'] = '友谊西路地铁站';
            $aContent['aDuoRouZhiWuJiaoYiBa']['sTitle'] = '[' . $aSendData['sType'] . '][' . $aSendData['sCity'] . '][' . $aSendData['sDate'] . ']' . $aSendData['sPinZhong'] . '.' . $aSendData['sSpec'] . '.' . $aSendData['sRandWord'];
            $aContent['aDuoRouZhiWuJiaoYiBa']['sContent1'] = $aSendData['sPinZhong'];
            $aContent['aDuoRouZhiWuJiaoYiBa']['sContent1'] .= '.' . $aSendData['sSpec'];
            $aContent['aDuoRouZhiWuJiaoYiBa']['sContent1'] .= '.' . $aSendData['sSize'];
            $aContent['aDuoRouZhiWuJiaoYiBa']['sContent1'] .= '.自家种植,欢迎交流经验';
            $aContent['aDuoRouZhiWuJiaoYiBa']['sContent2'] = '旺旺:' . $aSendData['sWangWang'] . "\r\n";
            $aContent['aDuoRouZhiWuJiaoYiBa']['sContent2'] .= '扣:' . $aSendData['sQq'] . "\r\n";
            $aContent['aDuoRouZhiWuJiaoYiBa']['sContent2'] .= $aSendData['sPrice'] . "\r\n";
            $aContent['aDuoRouZhiWuJiaoYiBa']['sContent2'] .= $aSendData['sExpress'] . "\r\n";
            $aContent['aDuoRouZhiWuJiaoYiBa']['sContent2'] .= $aSendData['sService'] . "\r\n";
            
            $aContent['aDuoRouJiaoYiBa'] = [];
            $aContent['aDuoRouJiaoYiBa']['sRemark'] = '多肉交易吧(3张图)';
            $aContent['aDuoRouJiaoYiBa']['sPlace'] = '友谊西路地铁站';
            $aContent['aDuoRouJiaoYiBa']['sTitle'] = '[' . $aSendData['sType'] . '][' . $aSendData['sCity'] . '][' . $aSendData['sDate'] . ']' . $aSendData['sPinZhong'] . '.' . $aSendData['sSpec'] . '.' . $aSendData['sRandWord'];
            $aContent['aDuoRouJiaoYiBa']['sContent1'] = $aSendData['sPinZhong'];
            $aContent['aDuoRouJiaoYiBa']['sContent1'] .= '.' . $aSendData['sSpec'];
            $aContent['aDuoRouJiaoYiBa']['sContent1'] .= '.' . $aSendData['sSize'];
            $aContent['aDuoRouJiaoYiBa']['sContent1'] .= '.自家种植,欢迎交流经验';
            $aContent['aDuoRouJiaoYiBa']['sContent2'] = '加~v:' . $aSendData['sWeChat'] . "\r\n";
            $aContent['aDuoRouJiaoYiBa']['sContent2'] .= '扣:' . $aSendData['sQq'] . "\r\n";
            $aContent['aDuoRouJiaoYiBa']['sContent2'] .= $aSendData['sPrice'] . "\r\n";
            $aContent['aDuoRouJiaoYiBa']['sContent2'] .= $aSendData['sExpress'] . "\r\n";
            $aContent['aDuoRouJiaoYiBa']['sContent2'] .= $aSendData['sService'] . "\r\n";
            
            $aContent['aDuoRouAiHaoZheYuTang'] = [];
            $aContent['aDuoRouAiHaoZheYuTang']['sRemark'] = '多肉爱好者鱼塘(5张图)';
            $aContent['aDuoRouAiHaoZheYuTang']['sPlace'] = '上海宝山区';
            $aContent['aDuoRouAiHaoZheYuTang']['sTitle'] = $aSendData['sPinZhong'] . '.' . $aSendData['sRandWord'];
            $aContent['aDuoRouAiHaoZheYuTang']['sContent1'] = $aSendData['sPinZhong'];
            $aContent['aDuoRouAiHaoZheYuTang']['sContent1'] .= '.' . $aSendData['sSpec'];
            $aContent['aDuoRouAiHaoZheYuTang']['sContent1'] .= '.' . $aSendData['sSize'];
            $aContent['aDuoRouAiHaoZheYuTang']['sContent1'] .= '.自家种植,欢迎交流经验';
            $aContent['aDuoRouAiHaoZheYuTang']['sContent2'] = '加~v:' . $aSendData['sWeChat'] . "\r\n";
            $aContent['aDuoRouAiHaoZheYuTang']['sContent2'] .= '扣:' . $aSendData['sQq'] . "\r\n";
            $aContent['aDuoRouAiHaoZheYuTang']['sContent2'] .= '手机:' . $aSendData['sMobile'] . "\r\n";
            $aContent['aDuoRouAiHaoZheYuTang']['sContent2'] .= $aSendData['sExpress'] . "\r\n";
            $aContent['aDuoRouAiHaoZheYuTang']['sContent2'] .= $aSendData['sService'] . "\r\n";
            
            $aContent['aDuoRouZhiWuYuTang'] = [];
            $aContent['aDuoRouZhiWuYuTang']['sRemark'] = '多肉植物鱼塘(5张图)';
            $aContent['aDuoRouZhiWuYuTang']['sPlace'] = '上海宝山区';
            $aContent['aDuoRouZhiWuYuTang']['sTitle'] = $aSendData['sPinZhong'] . '.' . $aSendData['sRandWord'];
            $aContent['aDuoRouZhiWuYuTang']['sContent1'] = $aSendData['sPinZhong'];
            $aContent['aDuoRouZhiWuYuTang']['sContent1'] .= '.' . $aSendData['sSpec'];
            $aContent['aDuoRouZhiWuYuTang']['sContent1'] .= '.' . $aSendData['sSize'];
            $aContent['aDuoRouZhiWuYuTang']['sContent1'] .= '.自家种植,欢迎交流经验';
            $aContent['aDuoRouZhiWuYuTang']['sContent2'] = '交易方式:闲鱼' . "\r\n";
            $aContent['aDuoRouZhiWuYuTang']['sContent2'] .= $aSendData['sExpress'] . "\r\n";
            $aContent['aDuoRouZhiWuYuTang']['sContent2'] .= $aSendData['sService'] . "\r\n";
        }
        // ($aSendData, $aContent);
        $this->setPageData('aFormData', $aFormData);
        $this->setPageData('sNextAction', $sNextAction);
        $this->setPageData('aContent', $aContent);
        return '/home/tools';
    }
}
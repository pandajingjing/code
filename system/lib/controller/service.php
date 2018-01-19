<?php
/**
 * service
 *
 * 内部服务控制器基类
 * @namespace panda\lib\controller
 */
namespace panda\lib\controller;

use panda\lib\traits\response;
use panda\util\xml;

/**
 * service
 *
 * 内部服务控制器基类
 */
abstract class service extends http
{
    /**
     * 定义结构体
     */
    use response;

    /**
     * 默认返回格式
     *
     * @var string
     */
    protected $sResponseType = 'json';

    /**
     * 在控制器开始时执行（调度使用）
     *
     * @return void
     */
    function beforeRequest()
    {
        parent::beforeRequest();
        // do something
        $sResponseType = $this->getParam('restype', 'get');
        if (in_array($sResponseType, [
            'json',
            'xml',
            'txt'
        ])) {
            $this->sResponseType = $sResponseType;
        }
    }

    /**
     * 在控制器结束时执行（调度使用）
     *
     * @return void
     */
    function afterRequest()
    {
        switch ($this->sResponseType) {
            case 'json':
                $this->addHeader('Content-type: application/json;charset=utf-8');
                break;
            case 'xml':
                $this->addHeader('Content-type:text/xml;charset=utf-8');
                break;
            case 'txt':
                $this->addHeader('Content-type: text/plain;charset=utf-8');
                break;
        }
        parent::afterRequest();
    }

    /**
     * 设置接口返回数据
     *
     * @param array $p_mData            
     * @param string $p_eType            
     * @return string
     */
    protected function setInfData($p_mData, $p_eType = '')
    {
        if ('' == $p_eType) {
            $p_eType = $this->sResponseType;
        } else {
            $this->sResponseType = $p_eType;
        }
        switch ($p_eType) {
            case 'json':
                $this->setPageData('jData', $p_mData);
                return '/service/json';
                break;
            case 'txt':
                $this->setPageData('sData', $p_mData);
                return '/service/txt';
                break;
            case 'xml':
                $this->setPageData('sData', xml::parseArr($p_mData));
                return '/service/txt';
                break;
        }
    }
}
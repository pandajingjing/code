<?php

/**
 * Template
 *
 * 系统模板类,根据页面数据调用页面模板,并且输出数据
 * @namespace panda\lib\sys
 * @package lib_sys
 */
namespace panda\lib\sys;

/**
 * Template
 *
 * 系统模板类,根据页面数据调用页面模板,并且输出数据
 */
class Template
{

    /**
     * 实例自身
     *
     * @var object
     */
    private static $_oInstance = null;

    /**
     * 页面数据
     *
     * @var array
     */
    private $_aPageData = [];

    /**
     * 获取实例
     *
     * @return object
     */
    static function getInstance()
    {
        if (! self::$_oInstance instanceof self) {
            self::$_oInstance = new self();
        }
        return self::$_oInstance;
    }

    /**
     * 实例化函数
     *
     * @return void
     */
    protected function __construct()
    {}

    /**
     * 克隆函数
     *
     * @return void
     */
    protected function __clone()
    {}

    /**
     * 设置页面数据
     *
     * @param array $p_aPageData            
     * @return void
     */
    function setPageData($p_aPageData)
    {
        $this->_aPageData = $p_aPageData;
    }

    /**
     * 渲染页面
     *
     * @param string $p_sPageName            
     * @return void
     */
    function render($p_sPageName)
    {
        if (! function_exists('panda')) { // 区别作用域
            include __DIR__ . '/TFunction.php';
        }
        $this->pandaTpl($p_sPageName);
    }

    /**
     * 加载模版
     *
     * @param string $p_sPageName            
     * @param array $p_aExtendDatas            
     * @throws Exception
     * @return true
     */
    protected function pandaTpl($p_sPageName, $p_aExtendDatas = [])
    {
        global $G_PAGE_DIR;
        $aTmp = explode('_', $p_sPageName);
        $sSubPath = strtolower(join(DIRECTORY_SEPARATOR, $aTmp));
        foreach ($G_PAGE_DIR as $sLoadDir) {
            $sLoadFilePath = $sLoadDir . DIRECTORY_SEPARATOR . $sSubPath . '.phtml';
            // echo $sLoadFilePath,'<br />';
            if (file_exists($sLoadFilePath)) {
                extract(array_merge($this->_aPageData, $p_aExtendDatas));
                unset($p_aExtendDatas);
                include $sLoadFilePath;
                return true;
            }
        }
        throw new \Exception(__CLASS__ . ': can not found template(' . $p_sPageName . ').');
    }
}
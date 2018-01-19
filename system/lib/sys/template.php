<?php
/**
 * template
 *
 * 系统模板类,根据页面数据调用页面模板,并且输出数据
 * @namespace panda\lib\sys
 */
namespace panda\lib\sys;

/**
 * template
 *
 * 系统模板类,根据页面数据调用页面模板,并且输出数据
 */
class template
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
    private function __construct()
    {}

    /**
     * 克隆函数
     *
     * @return void
     */
    private function __clone()
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
     * 语言包
     *
     * @var array
     */
    protected $aLanguage = [];

    /**
     * 加载语言
     *
     * @param string $p_sKey            
     * @return string
     */
    function pandaLang($p_sKey)
    {
        // debug($p_sKey);
        if (isset($this->aLanguage[$p_sKey])) {
            return $this->aLanguage[$p_sKey];
        } else {
            if (false === strstr($p_sKey, '/')) {
                throw new \Exception(__CLASS__ . ': language key must start with \'/\' ');
            }
            global $G_PAGE_DIR;
            $aTmp = explode('/', $p_sKey);
            array_pop($aTmp);
            $sSubDir = join(DIRECTORY_SEPARATOR, $aTmp);
            foreach ($G_PAGE_DIR as $sLoadDir) {
                $sLoadFilePath = $sLoadDir . $sSubDir . DIRECTORY_SEPARATOR . 'language.php';
                // debug($sSubDir, $sLoadFilePath);
                if (file_exists($sLoadFilePath)) {
                    $aLanguage = include $sLoadFilePath;
                    $this->aLanguage = array_merge($this->aLanguage, $aLanguage);
                }
            }
            // debug($this->aLanguage);
            if (isset($this->aLanguage[$p_sKey])) {
                return $this->aLanguage[$p_sKey];
            } else {
                return $p_sKey;
            }
        }
    }

    /**
     * 加载资源文件
     *
     * @param string $p_sPath            
     * @param string $p_sSchemeDomainKey            
     * @return string
     */
    function pandaRes($p_sPath, $p_sSchemeDomainKey)
    {
        // debug($p_sPath);
        if (false === strstr($p_sPath, '/')) {
            throw new \Exception(__CLASS__ . ': resource path must start with \'/\' ');
        }
        // debug($p_sPath);
        $sStaticSchemeDomain = variable::getInstance()->getConfig($p_sSchemeDomainKey, 'domain');
        global $G_PAGE_DIR;
        foreach ($G_PAGE_DIR as $sLoadDir) {
            $sLoadFilePath = $sLoadDir . DIRECTORY_SEPARATOR . 'resmap' . $p_sPath;
            // debug($sLoadFilePath);
            if (file_exists($sLoadFilePath)) {
                return $sStaticSchemeDomain . file_get_contents($sLoadFilePath);
            }
        }
        return $sStaticSchemeDomain . $p_sPath . '?t=' . variable::getInstance()->getRealTime();
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
            include __DIR__ . '/tfunction.php';
        }
        $this->pandaTpl($p_sPageName);
    }

    /**
     * 加载模版
     *
     * @param string $p_sSubPath            
     * @param array $p_aExtendDatas            
     * @throws Exception
     * @return true
     */
    protected function pandaTpl($p_sSubPath, $p_aExtendDatas = [])
    {
        if (false === strstr($p_sSubPath, '/')) {
            throw new \Exception(__CLASS__ . ': template path must start with \'/\' ');
        } else {
            if ('\\' == DIRECTORY_SEPARATOR) {
                $sSubPath = str_replace('/', DIRECTORY_SEPARATOR, $p_sSubPath);
            } else {
                $sSubPath = $p_sSubPath;
            }
        }
        global $G_PAGE_DIR;
        foreach ($G_PAGE_DIR as $sLoadDir) {
            $sLoadFilePath = $sLoadDir . $sSubPath . '.phtml';
            // echo $sLoadFilePath,'<br />';
            if (file_exists($sLoadFilePath)) {
                extract(array_merge($this->_aPageData, $p_aExtendDatas));
                unset($p_aExtendDatas);
                include $sLoadFilePath;
                return true;
            }
        }
        throw new \Exception(__CLASS__ . ': can not found template(' . $p_sSubPath . ').');
    }
}
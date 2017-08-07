<?php

/**
 * Lib_Sys_Template
 * @author jxu
 * @package system_lib_sys
 */

/**
 * 系统模版
 *
 * @author jxu
 *        
 */
class Lib_Sys_Template
{

    /**
     * 实例自身
     *
     * @var object
     */
    private static $_oInstance = null;

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
     * 实例化
     */
    protected function __construct()
    {}

    /**
     * 克隆
     */
    protected function __clone()
    {}

    /**
     * 渲染页面
     *
     * @param string $p_sPageName            
     * @param array $p_aDatas            
     */
    public function render($p_sPageName, $p_aDatas)
    {

        /**
         * 输出数据
         *
         * @param string $p_sVar            
         * @param bool $p_bHtml            
         */
        function tEcho($p_sVar, $p_bHtml = false)
        {
            if ($p_bHtml) {
                echo $p_sVar;
            } else {
                echo htmlspecialchars($p_sVar);
            }
        }

        /**
         * 加载模版
         *
         * @param string $p_sPageName            
         * @param array $p_aDatas            
         */
        function tLoadTpl($p_sPageName, $p_aDatas = array())
        {
            global $G_PAGE_DIR;
            $aTmp = explode('_', $p_sPageName);
            $sSubPath = strtolower(join(DIRECTORY_SEPARATOR, $aTmp));
            foreach ($G_PAGE_DIR as $sLoadDir) {
                $sLoadFilePath = $sLoadDir . DIRECTORY_SEPARATOR . $sSubPath . '.phtml';
                if (file_exists($sLoadFilePath)) {
                    extract($p_aDatas);
                    unset($p_aDatas);
                    include $sLoadFilePath;
                }
            }
        }

        /**
         * 获取静态资源路径
         *
         * @param string $p_sPath            
         * @return string
         */
        function getRes($p_sPath)
        {
            $sStaticDomain = get_config('sStaticDomain', 'domain');
            return $sStaticDomain . $p_sPath;
        }
        
        if (get_config('bMinify', 'system')) {
            ob_start('Util_Sys_Minifyhtml::minify');
        }
        tLoadTpl($p_sPageName, $p_aDatas);
        
    }
}
<?php
/**
 * Loader
 *
 * 系统加载类
 * 
 * 根据规范自动加载相关文件
 * @namespace panda\lib\sys
 * @package lib_sys
 */
namespace panda\lib\sys;

/**
 * Loader
 *
 * 系统加载类
 *
 * 根据规范自动加载相关文件
 */
class Loader
{

    /**
     * 类映射
     *
     * @var array
     */
    private static $_aMap = [];

    /**
     * PSR4加载变量1
     *
     * @var array
     */
    private static $_aPrefixLengthsPsr4 = [];

    /**
     * PSR4加载变量2
     *
     * @var array
     */
    private static $_aPrefixDirsPsr4 = [];

    /**
     * PSR4加载变量3
     *
     * @var array
     */
    private static $_aFallbackDirsPsr4 = [];

    /**
     * PSR0加载变量1
     *
     * @var array
     */
    private static $_aPrefixesPsr0 = [];

    /**
     * PSR0加载变量2
     *
     * @var array
     */
    private static $_aFallbackDirsPsr0 = [];

    /**
     * 自动加载文件
     *
     * @var array
     */
    private static $_AutoloadFiles = [];

    /**
     * 注册自动加载机制
     *
     * @return void
     */
    static function register()
    {
        spl_autoload_register('panda\\lib\\sys\\loader::autoLoad', true, true);
        global $G_APP_DIR;
        foreach ($G_APP_DIR as $sNameSpace => $sPath) {
            self::_addPsr4($sNameSpace, $sPath, true);
        }
        if (is_dir(PANDA_VPATH . '/composer')) {
            self::_addComposerLoader();
        }
        // 自动加载extend目录
        // self::$_aFallbackDirsPsr4[] = rtrim(EXTEND_PATH, DS);
    }

    /**
     * 自动加载函数
     *
     * @param string $p_sClassName            
     * @return true|false
     */
    static function autoLoad($p_sClassName)
    {
        $mFilePath = self::_findFile($p_sClassName);
        if (false === $mFilePath) {
            return false;
        } else {
            includeFile($mFilePath);
            return true;
        }
    }

    /**
     * 查找文件
     *
     * @param string $p_sClassName            
     * @return string|false
     */
    private static function _findFile($p_sClassName)
    {
        if (isset(self::$_aMap[$p_sClassName])) {
            return self::$_aMap[$p_sClassName];
        }
        // 查找 PSR-4
        $sLogicalPathPsr4 = strtr($p_sClassName, '\\', DIRECTORY_SEPARATOR) . '.php';
        $sFirst = $p_sClassName[0];
        if (isset(self::$_aPrefixLengthsPsr4[$sFirst])) {
            foreach (self::$_aPrefixLengthsPsr4[$sFirst] as $sPrefix => $iLength) {
                if (0 === strpos($p_sClassName, $sPrefix)) {
                    foreach (self::$_aPrefixDirsPsr4[$sPrefix] as $sDir) {
                        $sFilePath = $sDir . DIRECTORY_SEPARATOR . substr($sLogicalPathPsr4, $iLength);
                        if (is_file($sFilePath)) {
                            return $sFilePath;
                        }
                    }
                }
            }
        }
        // 查找 PSR-4 fallback dirs
        foreach (self::$_aFallbackDirsPsr4 as $sDir) {
            $sFilePath = $sDir . DS . $sLogicalPathPsr4;
            if (is_file($sFilePath)) {
                return $sFilePath;
            }
        }
        // 查找 PSR-0
        $mPos = strrpos($p_sClassName, '\\');
        if (false === $mPos) { // PEAR-like class name
            $sLogicalPathPsr0 = strtr($p_sClassName, '_', DIRECTORY_SEPARATOR) . '.php';
        } else { // namespaced class name
            $sLogicalPathPsr0 = substr($sLogicalPathPsr4, 0, $mPos + 1) . strtr(substr($sLogicalPathPsr4, $mPos + 1), '_', DIRECTORY_SEPARATOR);
        }
        if (isset(self::$_aPrefixesPsr0[$sFirst])) {
            foreach (self::$_aPrefixesPsr0[$sFirst] as $sPrefix => $aDirs) {
                if (0 === strpos($p_sClassName, $sPrefix)) {
                    foreach ($aDirs as $sDir) {
                        $sFilePath = $sDir . DS . $sLogicalPathPsr0;
                        if (is_file($sFilePath)) {
                            return $sFilePath;
                        }
                    }
                }
            }
        }
        // 查找 PSR-0 fallback dirs
        foreach (self::$_aFallbackDirsPsr0 as $sDir) {
            $sFilePath = $sDir . DIRECTORY_SEPARATOR . $sLogicalPathPsr0;
            if (is_file($sFilePath)) {
                return $sFilePath;
            }
        }
        return self::$_aMap[$p_sClassName] = false;
    }

    /**
     * 添加类名映射
     *
     * @param mix $p_mClass            
     * @param array $p_aMap            
     * @return void
     */
    private static function _addClassMap($p_mClass, $p_aMap = [])
    {
        if (is_array($p_mClass)) {
            self::$_aMap = array_merge(self::$_aMap, $p_mClass);
        } else {
            self::$_aMap[$p_mClass] = $p_aMap;
        }
    }

    /**
     * 添加一个psr0命名空间
     *
     * @param string $p_sPrefix            
     * @param mix $p_mPaths            
     * @param boolean $p_bPrepend            
     * @return void
     */
    private static function _addPsr0($p_sPrefix, $p_mPaths, $p_bPrepend = false)
    {
        if (is_array($p_mPaths)) {
            $aPaths = $p_mPaths;
        } else {
            $aPaths = [
                $p_mPaths
            ];
        }
        if ($p_sPrefix == '') {
            if ($p_bPrepend) {
                self::$_aFallbackDirsPsr0 = array_merge($aPaths, self::$_aFallbackDirsPsr0);
            } else {
                self::$_aFallbackDirsPsr0 = array_merge(self::$_aFallbackDirsPsr0, $aPaths);
            }
        } else {
            $sFirst = $p_sPrefix[0];
            if (isset(self::$_aPrefixesPsr0[$sFirst][$p_sPrefix])) {
                if ($p_bPrepend) {
                    self::$_aPrefixesPsr0[$sFirst][$p_sPrefix] = array_merge($aPaths, self::$_aPrefixesPsr0[$sFirst][$p_sPrefix]);
                } else {
                    self::$_aPrefixesPsr0[$sFirst][$p_sPrefix] = array_merge(self::$_aPrefixesPsr0[$sFirst][$p_sPrefix], $aPaths);
                }
            } else {
                self::$_aPrefixesPsr0[$sFirst][$p_sPrefix] = $aPaths;
            }
        }
    }

    /**
     * 添加一个psr4命名空间
     *
     * @param string $p_sPrefix            
     * @param mix $p_mPaths            
     * @param boolean $p_bPrepend            
     * @throws \InvalidArgumentException
     * @return void
     */
    private static function _addPsr4($p_sPrefix, $p_mPaths, $p_bPrepend = false)
    {
        if (is_array($p_mPaths)) {
            $aPaths = $p_mPaths;
        } else {
            $aPaths = [
                $p_mPaths
            ];
        }
        if ($p_sPrefix == '') { // 将整个mPath合并入加载目录,mPath是key=>value格式.
            if ($p_bPrepend) {
                self::$_aFallbackDirsPsr4 = array_merge($aPaths, self::$_aFallbackDirsPsr4);
            } else {
                self::$_aFallbackDirsPsr4 = array_merge(self::$_aFallbackDirsPsr4, $aPaths);
            }
        } else {
            if (isset(self::$_aPrefixDirsPsr4[$p_sPrefix])) { // 给命名空间添加一个新的路径
                if ($p_bPrepend) {
                    self::$_aPrefixDirsPsr4[$p_sPrefix] = array_merge($aPaths, self::$_aPrefixDirsPsr4[$p_sPrefix]);
                } else {
                    self::$_aPrefixDirsPsr4[$p_sPrefix] = array_merge(self::$_aPrefixDirsPsr4[$p_sPrefix], $aPaths);
                }
            } else { // 注册一个新的命名空间
                $iLength = strlen($p_sPrefix);
                if ('\\' !== $p_sPrefix[$iLength - 1]) {
                    throw new \InvalidArgumentException("A non-empty PSR-4 prefix must end with a namespace separator.");
                }
                self::$_aPrefixLengthsPsr4[$p_sPrefix[0]][$p_sPrefix] = $iLength;
                self::$_aPrefixDirsPsr4[$p_sPrefix] = $aPaths;
            }
        }
    }

    /**
     * 添加composer自动加载的内容
     *
     * @return void
     */
    private static function _addComposerLoader()
    {
        $sFilePath = PANDA_VPATH . '/composer/autoload_namespaces.php';
        if (is_file($sFilePath)) {
            $aMap = include $sFilePath;
            foreach ($aMap as $sNameSpace => $aPaths) {
                self::_addPsr0($sNameSpace, $aPaths);
            }
        }
        $sFilePath = PANDA_VPATH . '/composer/autoload_psr4.php';
        if (is_file($sFilePath)) {
            $aMap = include $sFilePath;
            foreach ($aMap as $sNameSpace => $aPaths) {
                self::_addPsr4($sNameSpace, $aPaths);
            }
        }
        $sFilePath = PANDA_VPATH . '/composer/autoload_classmap.php';
        if (is_file($sFilePath)) {
            $aMap = include $sFilePath;
            if (! empty($aMap)) {
                self::_addClassMap($aMap);
            }
        }
        $sFilePath = PANDA_VPATH . '/composer/autoload_files.php';
        if (is_file($sFilePath)) {
            $aIncludeFile = include $sFilePath;
            foreach ($aIncludeFile as $sIdentifier => $sFilePath) {
                if (empty(self::$_AutoloadFiles[$sIdentifier])) {
                    includeFile($sFilePath);
                    self::$_AutoloadFiles[$sIdentifier] = true;
                }
            }
        }
    }

    /**
     * 调试用
     *
     * @return void
     */
    private static function _debug()
    {
        echo PHP_EOL, '$aMap', PHP_EOL;
        print_r(self::$_aMap);
        echo PHP_EOL, '$aPrefixLengthsPsr4', PHP_EOL;
        print_r(self::$_aPrefixLengthsPsr4);
        echo PHP_EOL, '$aPrefixDirsPsr4', PHP_EOL;
        print_r(self::$_aPrefixDirsPsr4);
        echo PHP_EOL, '$aFallbackDirsPsr4', PHP_EOL;
        print_r(self::$_aFallbackDirsPsr4);
        echo PHP_EOL, '$aPrefixesPsr0', PHP_EOL;
        print_r(self::$_aPrefixesPsr0);
        echo PHP_EOL, '$aFallbackDirsPsr0', PHP_EOL;
        print_r(self::$_aFallbackDirsPsr0);
        echo PHP_EOL, '$AutoloadFiles', PHP_EOL;
        print_r(self::$_AutoloadFiles);
    }
}

/**
 * 作用范围隔离
 *
 * @param string $file            
 * @return mixed
 */
function includeFile($p_sFilePath)
{
    return include $p_sFilePath;
}
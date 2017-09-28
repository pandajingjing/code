<?php

/**
 * FileCache
 *
 * 文件缓存,与memcached有类似的函数
 * @namespace panda\lib\data
 * @package lib_data
 */
namespace panda\lib\data;

use panda\util\File;
use panda\lib\sys\Variable;

/**
 * FileCache
 *
 * 文件缓存,与memcached有类似的函数
 */
class FileCache
{

    /**
     * 服务器版本
     *
     * @var string
     */
    private static $_sVersion = '0.1';

    /**
     * 服务器路径
     *
     * @var array
     */
    private $_aServerPathList = [];

    /**
     * 服务器路径数量
     *
     * @var int
     */
    private $_iServerCnt = 0;

    /**
     * 数据是否需要压缩
     *
     * @var boolean
     */
    private $_bDataCompress = false;

    /**
     * 构造函数
     *
     * @return void
     */
    function __construct()
    {}

    /**
     * 设置数据是否压缩
     *
     * @param boolean $p_bCompress            
     * @return void
     */
    function setCompress($p_bCompress)
    {
        $this->_bDataCompress = $p_bCompress;
    }

    /**
     * 添加项目
     *
     * 有效期不能超过30天,即2592000秒.如果超过2592000秒则做为过期时间戳.
     *
     * @param string $p_sKey            
     * @param mix $p_mValue            
     * @param int $p_iLifeTime            
     * @return true|false
     */
    function add($p_sKey, $p_mValue, $p_iLifeTime = 0)
    {
        if (isset($p_sKey[0])) {
            $mValue = $this->get($p_sKey);
            if (false === $mValue) {
                return $this->set($p_sKey, $p_mValue, $p_iLifeTime);
            } else {
                return false;
            }
        } else {
            trigger_error(__CLASS__ . ': key cannot be empty.', E_USER_ERROR);
            return false;
        }
    }

    /**
     * 添加缓存目录
     *
     * @param string $p_sPath            
     * @param int $p_iWeight            
     * @throws Exception
     * @return true
     */
    function addDir($p_sPath, $p_iWeight = 0)
    {
        $this->_aServerPathList = array_merge($this->_aServerPathList, array_fill(0, $p_iWeight, $p_sPath));
        $this->_iServerCnt += $p_iWeight;
        if (! in_array($p_sPath, $this->_aServerPathList)) {
            if (! is_dir($p_sPath)) {
                if (false === File::tryMakeDir($p_sPath, 0755, true)) {
                    throw new \Exception(__CLASS__ . ': can not create path(' . $p_sPath . ').');
                }
            }
        }
        return true;
    }

    /**
     * 批量添加缓存路径
     *
     * @param array $p_aDirList            
     * @return true|false
     */
    function addDirs($p_aDirList)
    {
        foreach ($p_aDirList as $aDir) {
            $bResult = $this->addDir($aDir[0], $aDir[1]);
            if (! $bResult) {
                return false;
            }
        }
        return true;
    }

    /**
     * 删除某个缓存项目
     *
     * 有效期不能超过30天,即2592000秒.如果超过2592000秒则做为过期时间戳.
     * 如果有效期大于0,则在<var>$p_iLifeTime</var>秒后删除项目.
     *
     * @param string $p_sKey            
     * @param int $p_iLifeTime            
     * @return true|false
     */
    function delete($p_sKey, $p_iLifeTime = 0)
    {
        if ($p_iLifeTime > 0) {
            return $this->set($p_sKey, $this->get($p_sKey), $p_iLifeTime);
        } else {
            return File::tryDeleteFile($this->_dispatchCacheFile($p_sKey));
        }
    }

    /**
     * 批量删除某些缓存项目
     *
     * 有效期不能超过30天,即2592000秒.如果超过2592000秒则做为过期时间戳.
     *
     * @param array $p_aKeyLists            
     * @param int $p_iLifeTime            
     * @return true|false
     */
    function deleteMulti($p_aKeyLists, $p_iLifeTime = 0)
    {
        foreach ($p_aKeyLists as $sKey) {
            $bResult = $this->delete($sKey, $p_iLifeTime);
            if (! $bResult) {
                return false;
            }
        }
        return true;
    }

    /**
     * 删除服务器中所有项目
     *
     * @return true|false
     */
    function flush()
    {
        $aUniqServerPathList = array_unique($this->_aServerPathList);
        foreach ($aUniqServerPathList as $sServerPath) {
            if (! File::tryDeleteDir($sServerPath)) {
                return false;
            }
        }
        return true;
    }

    /**
     * 获取某个缓存项目
     *
     * @param string $p_sKey            
     * @return mix
     */
    function get($p_sKey)
    {
        if (isset($p_sKey[0])) {
            $sCacheFilePath = $this->_dispatchCacheFile($p_sKey);
            if (! file_exists($sCacheFilePath)) {
                return false;
            }
            $mCache = File::tryReadFile($sCacheFilePath);
            if (false === $mCache) {
                return false;
            } else {
                $mCache = $this->_cache2Value($mCache);
                if (false === $mCache) {
                    return false;
                } else {
                    if (0 == $mCache['iExpireTime']) {
                        return $mCache['mData'];
                    } else {
                        if (Variable::getInstance()->getRealTime() > $mCache['iExpireTime']) {
                            File::tryDeleteFile($sCacheFilePath);
                            return false;
                        } else {
                            return $mCache['mData'];
                        }
                    }
                }
            }
        } else {
            trigger_error(__CLASS__ . ': key cannot be empty.', E_USER_ERROR);
            return false;
        }
    }

    /**
     * 批量获取某些缓存项目
     *
     * @param array $p_aKeyList            
     * @return array|false
     */
    function getMulti($p_aKeyList)
    {
        if (is_array($p_aKeyList)) {
            $aReturnList = [];
            foreach ($p_aKeyList as $iIndex => $sKey) {
                $mTmp = $this->get($sKey);
                if (false !== $mTmp) {
                    $aReturnList[$sKey] = $mTmp;
                }
            }
            return $aReturnList;
        } else {
            return false;
        }
    }

    /**
     * 获取服务器版本
     *
     * @return string
     */
    function getVersion()
    {
        return self::$_sVersion;
    }

    /**
     * 替换项目
     *
     * 有效期不能超过30天,即2592000秒.如果超过2592000秒则做为过期时间戳.
     *
     * @param string $p_sKey            
     * @param mix $p_mValue            
     * @param int $p_iLifeTime            
     * @return true|false
     */
    function replace($p_sKey, $p_mValue, $p_iLifeTime = 0)
    {
        if (isset($p_sKey[0])) {
            $mValue = $this->get($p_sKey);
            if (false === $mValue) {
                return false;
            } else {
                return $this->set($p_sKey, $p_mValue, $p_iLifeTime);
            }
        } else {
            trigger_error(__CLASS__ . ': key cannot be empty.', E_USER_ERROR);
            return false;
        }
    }

    /**
     * 保存项目
     *
     * 有效期不能超过30天,即2592000秒.如果超过2592000秒则做为过期时间戳.
     *
     * @param string $p_sKey            
     * @param mix $p_mValue            
     * @param int $p_iLifeTime            
     * @return true|false
     */
    function set($p_sKey, $p_mValue, $p_iLifeTime = 0)
    {
        if (isset($p_sKey[0])) {
            $mCache = $this->_value2Cache($p_mValue, $this->_getExpireTime($p_iLifeTime));
            $sFileName = $this->_dispatchCacheFile($p_sKey);
            if (false === File::tryWriteFile($sFileName, $mCache, LOCK_EX)) {
                if (false === File::tryMakeDir(dirname($sFileName), 0755, true)) {
                    return false;
                } else {
                    return File::tryWriteFile($sFileName, $mCache);
                }
                return false;
            } else {
                return true;
            }
        } else {
            trigger_error(__CLASS__ . ': key cannot be empty.', E_USER_ERROR);
            return false;
        }
    }

    /**
     * 批量保存项目
     *
     * 有效期不能超过30天,即2592000秒.如果超过2592000秒则做为过期时间戳.
     *
     * @param array $p_aData            
     * @param int $p_iLifeTime            
     * @return true|false
     */
    function setMulti($p_aData, $p_iLifeTime = 0)
    {
        if (is_array($p_aData)) {
            $bFoundErr = false;
            foreach ($p_aData as $sKey => $mValue) {
                if (! $this->set($sKey, $mValue, $p_iLifeTime)) {
                    $bFoundErr = true;
                }
            }
            if ($bFoundErr) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * 分配存储路径
     *
     * @param string $p_sKey            
     * @return string
     */
    private function _dispatchCacheFile($p_sKey)
    {
        $iKey = abs(crc32($p_sKey));
        $sDir = '';
        while ($iKey > 0) {
            $sSubDir = $iKey % 100;
            $sDir = $sSubDir . DIRECTORY_SEPARATOR . $sDir;
            $iKey = intval($iKey / 100);
        }
        return $this->_aServerPathList[$iKey % $this->_iServerCnt] . DIRECTORY_SEPARATOR . $sDir . $p_sKey;
    }

    /**
     * 得到缓存过期时间
     *
     * @param int $p_iLifeTime            
     * @return int
     */
    private function _getExpireTime($p_iLifeTime)
    {
        if ($p_iLifeTime > 2592000) {
            return $p_iLifeTime;
        } else {
            return 0 == $p_iLifeTime ? 0 : Variable::getInstance()->getRealTime() + $p_iLifeTime;
        }
    }

    /**
     * 将数据转换为缓存数据
     *
     * @param mix $p_mValue            
     * @param int $p_iExpireTime            
     * @return array
     */
    private function _value2Cache($p_mValue, $p_iExpireTime)
    {
        if ($this->_bDataCompress) {
            return gzcompress(serialize([
                'mData' => $p_mValue,
                'iExpireTime' => $p_iExpireTime
            ]), 9);
        } else {
            return serialize([
                'mData' => $p_mValue,
                'iExpireTime' => $p_iExpireTime
            ]);
        }
    }

    /**
     * 将缓存数据转换为数据
     *
     * @param mix $p_mCache            
     * @return array|false
     */
    private function _cache2Value($p_mCache)
    {
        if ($this->_bDataCompress) {
            $mTmp = unserialize(gzuncompress($p_mCache));
        } else {
            $mTmp = unserialize($p_mCache);
        }
        if (is_array($mTmp) and isset($mTmp['mData']) and isset($mTmp['iExpireTime'])) {
            return $mTmp;
        } else {
            return false;
        }
    }
}
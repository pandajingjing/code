<?php

/**
 * Pooling
 *
 * 数据连接池
 * @namespace panda\lib\data
 * @package lib_data
 */
namespace panda\lib\data;

use panda\lib\sys\Variable;
use panda\util\Crypt;

/**
 * Pooling
 *
 * 数据连接池
 */
class Pooling
{

    /**
     * 数据连接池实例
     *
     * @var object
     */
    private static $_oInstance = null;

    /**
     * 密码密钥
     *
     * @var string
     */
    private $_sDeCrypt = '0fc613bdc6';
    // substr(md5('jxu'),-10)
    
    /**
     * 数据连接池
     *
     * @var array
     */
    private $_aConnect = [];

    /**
     * 构造函数
     *
     * @return void
     */
    private function __construct()
    {}

    /**
     * 析构函数
     *
     * @return void
     */
    function __destruct()
    {}

    /**
     * 克隆函数
     *
     * @return void
     */
    private function __clone()
    {}

    /**
     * 获取实例
     *
     * @return object
     */
    static function getInstance()
    {
        if (! (self::$_oInstance instanceof self)) {
            self::$_oInstance = new self();
        }
        return self::$_oInstance;
    }

    /**
     * 获取数据连接
     *
     * @param string $p_sConnectName            
     * @return object
     */
    function getConnect($p_sConnectName)
    {
        if (! isset($this->_aConnect[$p_sConnectName])) {
            $this->_aConnect[$p_sConnectName] = $this->_loadConnect($p_sConnectName);
        }
        return $this->_aConnect[$p_sConnectName];
    }

    /**
     * 加载数据连接
     *
     * @param string $p_sConnectName            
     * @throws Exception
     * @return object|null
     */
    private function _loadConnect($p_sConnectName)
    {
        $aConfig = Variable::getInstance()->getConfig($p_sConnectName, 'data');
        $oConnect = null;
        switch ($aConfig['sType']) {
            case 'mysql':
                $oConnect = new PandaPDO($aConfig['sDSN'], $aConfig['sUserName'], Crypt::deCrypt($aConfig['sUserPassword'], $this->_sDeCrypt));
                foreach ($aConfig['aInitSQL'] as $sSQL) {
                    $oConnect->exec($sSQL);
                }
                break;
            case 'filecache':
                $oConnect = new FileCache();
                $oConnect->addDirs($aConfig['aDirList']);
                $oConnect->setCompress($aConfig['bCompress']);
                break;
            case 'memcached':
                $oConnect = new \Memcached();
                $oConnect->addServers($aConfig['aServerList']);
                break;
            case 'redis':
                $oConnect = new \Redis();
                $oConnect->connect($aConfig['aServer'][0], $aConfig['aServer'][1]);
                $oConnect->auth($aConfig['sUserPassword']);
                $oConnect->select($aConfig['iIndex']);
                break;
            default:
                throw new \Exception(get_class($this) . ': unknown connect type(' . $aConfig['sType'] . ')');
                break;
        }
        return $oConnect;
    }
}
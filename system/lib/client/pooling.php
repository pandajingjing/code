<?php
/**
 * pooling
 *
 * 客户端连接池
 * @namespace panda\lib\client
 * @package lib_client
 */
namespace panda\lib\client;

/**
 * pooling
 *
 * 客户端连接池
 */
class pooling
{

    /**
     * 客户端连接池实例
     *
     * @var object
     */
    private static $_oInstance = null;

    /**
     * 客户端连接池
     *
     * @var array
     */
    private $_aClient = [];

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
     * 获取客户端连接
     *
     * @param string $p_sClientType            
     * @return object
     */
    function getClient($p_sClientType)
    {
        if (! isset($this->_aClient[$p_sClientType])) {
            $this->_aClient[$p_sClientType] = $this->_loadClient($p_sClientType);
        }
        return $this->_aClient[$p_sClientType];
    }

    /**
     * 加载客户端
     *
     * @param string $p_sClientType            
     * @return object
     */
    private function _loadClient($p_sClientType)
    {
        switch ($p_sClientType) {
            case 'curl':
            default:
                $oClient = new curl();
                break;
        }
        return $oClient;
    }
}
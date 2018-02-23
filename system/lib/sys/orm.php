<?php
/**
 * orm
 *
 * 系统数据关系映射类
 * @namespace panda\lib\sys
 */
namespace panda\lib\sys;

use panda\lib\data\pooling;

/**
 * orm
 *
 * 系统数据关系映射类
 * 不允许跨表查询,每个表均有且仅有一个主键
 * 写入缓存的数据是ormdata的数组格式,不包含保留字段数据
 * 设置数据的时候通过属性,获取数据的时候返回数组
 *
 * @todo debug内容的调整,memcached::delmulti的debug返回
 */
abstract class orm
{

    /**
     * Master数据库连接名,在子类中配置
     *
     * @var string
     */
    protected $sMasterDbName = '';

    /**
     * Slave数据库连接名,在子类中配置
     *
     * @var string
     */
    protected $sSlaveDbName = '';

    /**
     * 表名称
     *
     * @var string
     */
    protected $sTblName = '';

    /**
     * 缓存连接名
     *
     * @var string
     */
    protected $sCacheName = 'ormcache';

    /**
     * 主键字段
     *
     * @var string
     */
    protected $sPkField = '';

    /**
     * 数据库表结构
     *
     * @var array
     */
    protected $aDbField = [
        'int/tinyint' => [
            'sType' => 'int/tinyint',
            'bUnsigned' => true
        ],
        'string' => [
            'sType' => 'string',
            'iLength' => 255
        ]
    ];

    /**
     * Orm字段结构
     *
     * @var array
     */
    protected $aOrmField = [
        'int/tinyint' => [
            'sType' => 'int/tinyint',
            'bUnsigned' => true
        ],
        'float' => [
            'sType' => 'float',
            'bUnsigned' => true
        ],
        'string' => [
            'sType' => 'string',
            'iLength' => 255
        ],
        'array' => [
            'sType' => 'array'
        ]
    ];

    /**
     * 数据库索引顺序
     */
    protected $aDbIndexOrders = [
        'iAutoId'
    ];

    /**
     * 业务Sql语句
     *
     * @var array
     */
    protected $aBizSql = [
        'itemname_57' => 'iBuyTime>:iBuyTime ORDER BY iAutoId asc'
    ];

    /**
     * 是否需要回收站数据
     *
     * @var boolean
     */
    protected $bNeedDelData = false;
    
    // 系统属性,子类不允许修改
    
    /**
     * 所有执行的Sql语句
     *
     * @var array
     */
    private static $_aAllSqls = [];

    /**
     * 类名
     *
     * @var string
     */
    private $_sClassName = '';

    /**
     * 数据库操作次数
     *
     * @var int
     */
    private static $_iQueryCnt = 0;

    /**
     * 缓存操作次数
     *
     * @var int
     */
    private static $_iCacheCnt = 0;

    /**
     * PHP静态变量缓存
     *
     * @var array
     */
    private static $_aStaticCache = [];

    /**
     * 数据库连接池
     *
     * @var array
     */
    private static $_aDbPool = [];

    /**
     * 数据库陈述
     *
     * @var object
     */
    private static $_oDbStmt = null;

    /**
     * 最后准备的Sql
     *
     * @var string
     */
    private static $_sLastPreparedSql = '';

    /**
     * 缓存连接池
     *
     * @var array
     */
    private static $_aCachePool = [];

    /**
     * 默认缓存深度
     *
     * @var int
     */
    const DEFAULT_CACHE_LEVEL = 7;

    /**
     * 默认缓存时间
     *
     * @var int
     */
    const DEFAULT_CACHE_TIME = 86400;

    /**
     * 操作缓存最大尝试次数
     *
     * @var int
     */
    const MAX_CACHE_TRY = 5;

    /**
     * 调试对象
     *
     * @var object
     */
    private static $_oDebugger = null;

    /**
     * 用于保存调试信息
     *
     * @var mix
     */
    private static $_mDebugResult = null;

    /**
     * 变量绑定占位符
     *
     * @var string
     */
    private static $_sBindHolder = ':';

    /**
     * Orm数据
     *
     * @var array
     */
    private $_aOrmData = [];

    /**
     * 是否开启缓存
     *
     * @var boolean
     */
    private $_bolNeedCache = true;

    /**
     * 是否物理删除数据
     *
     * @var boolean
     */
    private static $_bPhyDelete = false;

    /**
     * 过滤条件操作符
     *
     * @var array
     */
    private static $_aFilterOperators = [
        '=',
        '!=',
        '<',
        '>',
        '<=',
        '>=',
        'in',
        'like'
    ];

    /**
     * 排序
     *
     * @var string
     */
    private $_sOrder = '';

    /**
     * 默认列表开始行数
     *
     * @var int
     */
    const DEFAULT_START_ROW = 0;

    /**
     * 默认列表获取行数
     *
     * @var int
     */
    const DEFAULT_FETCH_ROW = 20;

    /**
     * 开始行数
     *
     * @var int
     */
    private $_iStartRow = self::DEFAULT_START_ROW;

    /**
     * 获取行数
     *
     * @var int
     */
    private $_iFetchRow = self::DEFAULT_FETCH_ROW;

    /**
     * 过滤条件
     *
     * @var array
     */
    private $_aFilters = [];

    /**
     * 查询获取数据类型-一列
     *
     * @var int
     */
    const Sql_FETCH_TYPE_COLUMN = 1;

    /**
     * 查询获取数据类型-一行
     *
     * @var int
     */
    const Sql_FETCH_TYPE_ROW = 2;

    /**
     * 查询获取数据类型-多行
     *
     * @var int
     */
    const Sql_FETCH_TYPE_LIST = 3;

    /**
     * 系统保留字段
     *
     * @var array
     */
    private static $_aReservedField = [
        'iCreateTime' => [
            'sType' => 'int',
            'bUnsigned' => true
        ],
        'iUpdateTime' => [
            'sType' => 'int',
            'bUnsigned' => true
        ],
        'iDeleteTime' => [
            'sType' => 'int',
            'bUnsigned' => true
        ]
    ];

    /**
     * 所有数据库表字段
     *
     * @var array
     */
    private $_aAllDbField = [];

    /**
     * 创建实例
     *
     * @param boolean $p_bStrictMaster            
     * @return void
     */
    function __construct($p_bStrictMaster = false)
    {
        $this->_sClassName = get_class($this);
        self::$_oDebugger = debugger::getInstance();
        if ($p_bStrictMaster) {
            $this->sSlaveDbName = $this->sMasterDbName;
        }
        $this->_aAllDbField = array_merge($this->aDbField, self::$_aReservedField);
        self::$_oDebugger->showMsg($this->_sClassName . '->Info: sMasterName: ' . $this->sMasterDbName . ', sSlaveName: ' . $this->sSlaveDbName . ', sCacheName: ' . $this->sCacheName);
    }

    /**
     * 析构函数
     *
     * @return void
     */
    function __destruct()
    {
        self::$_oDebugger->showMsg($this->_sClassName . '->Info: Query time: ' . self::$_iQueryCnt . ', Cache time: ' . self::$_iCacheCnt);
    }

    /**
     * 得到所有执行的Sql语句
     *
     * @return array;
     */
    static function getAllSqls()
    {
        return self::$_aAllSqls;
    }

    /**
     * 返回数据库操作次数
     *
     * @return int
     */
    static function getQueryCnt()
    {
        return self::$_iQueryCnt;
    }

    /**
     * 返回缓存操作次数
     *
     * @return int
     */
    static function getCacheCnt()
    {
        return self::$_iCacheCnt;
    }

    /**
     * 设置需要已删除数据
     *
     * @param boolean $pbNeedDelData            
     */
    function setRecycled($pbNeedDelData = true)
    {
        $this->bNeedDelData = $pbNeedDelData;
    }

    /**
     * 设置排序
     *
     * @param string $p_sOrder            
     * @return void
     */
    function setOrder($p_sOrder)
    {
        $this->_sOrder = $p_sOrder;
    }

    /**
     * 设置开始行数
     *
     * @param int $p_iStart            
     * @return void
     */
    function setStartRow($p_iStartRow)
    {
        if (is_numeric($p_iStartRow)) {
            $p_iStartRow = intval($p_iStartRow);
            if ($p_iStartRow > 0) {
                $this->_iStartRow = $p_iStartRow;
            } else {
                throw new \Exception($this->_sClassName . ': start row need a positive integer which is not(' . $p_iStartRow . ').');
            }
        } else {
            throw new \Exception($this->_sClassName . ': you gave a nonnumeric value(' . var_export($p_iStartRow, true) . ') to start row which need a number, maybe is ' . gettype($p_iStartRow) . '.');
        }
    }

    /**
     * 设置获取行数
     *
     * @param int $p_iFetchRow            
     * @return void
     */
    function setFetchRow($p_iFetchRow)
    {
        if (is_numeric($p_iFetchRow)) {
            $p_iFetchRow = intval($p_iFetchRow);
            if ($p_iFetchRow > 0) {
                $this->_iFetchRow = $p_iFetchRow;
            } else {
                throw new \Exception($this->_sClassName . ': fetch row need a positive integer which is not(' . $p_iFetchRow . ').');
            }
        } else {
            throw new \Exception($this->_sClassName . ': you gave a nonnumeric value(' . var_export($p_iFetchRow, true) . ') to fetch row which need a number, maybe is ' . gettype($p_iFetchRow) . '.');
        }
    }

    /**
     * 添加过滤器
     *
     * 用法1,addFilter('field','opeartor','value');<br />
     * 用法2,addFilter('sql','value')<br />
     *
     * @param string $p_sDbFieldOrSql            
     * @param string $p_sOperatorOrParam            
     * @param mix $p_mValue            
     * @throws Exception
     * @return void
     */
    function addFilter($p_sDbFieldOrSql, $p_sOperatorOrParam, $p_mValue = '')
    {
        if ('' == $p_mValue) { // 用法2
            if (isset($this->_aFilters['_aSqls'])) {
                $this->_aFilters['_aSqls'][] = [
                    'sSql' => $p_sDbFieldOrSql,
                    'aParam' => $p_sOperatorOrParam
                ];
            } else {
                $this->_aFilters['_aSqls'] = [
                    [
                        'sSql' => $p_sDbFieldOrSql,
                        'aParam' => $p_sOperatorOrParam
                    ]
                ];
            }
        } else { // 用法1
            if (isset($this->aOrmField[$p_sDbFieldOrSql])) {
                if (in_array($p_sOperatorOrParam, self::$_aFilterOperators)) {
                    if (isset($this->_aFilters[$p_sDbFieldOrSql])) {
                        $this->_aFilters[$p_sDbFieldOrSql][] = [
                            'sOperator' => $p_sOperatorOrParam,
                            'mValue' => $p_mValue
                        ];
                    } else {
                        $this->_aFilters[$p_sDbFieldOrSql] = [
                            [
                                'sOperator' => $p_sOperatorOrParam,
                                'mValue' => $p_mValue
                            ]
                        ];
                    }
                } else {
                    throw new \Exception($this->_sClassName . ': you use an unexpected operator(' . $p_sOperatorOrParam . ') of Orm instance.');
                }
            } else {
                throw new \Exception($this->_sClassName . ': you add an unexpected filter(' . $p_sDbFieldOrSql . ') to Orm instance.');
            }
        }
    }

    /**
     * 清除过滤器
     *
     * @return void
     */
    function initFilter()
    {
        $this->_aFilters = [];
    }

    /**
     * 获取Orm数据
     *
     * @return array
     */
    function getSource()
    {
        $this->_aOrmData = [];
        foreach ($this->aOrmField as $sField => $aFieldSet) {
            if (null !== $this->$sField) {
                $this->_aOrmData[$sField] = $this->$sField;
            }
        }
        return $this->_aOrmData;
    }

    /**
     * 关闭缓存功能
     *
     * @return void
     */
    function disableCache()
    {
        $this->_bolNeedCache = false;
    }

    /**
     * 根据主键删除Orm单行缓存
     *
     * @param mix $p_mPkVal            
     * @return true|false
     */
    function clearRowCache($p_mPkVal)
    {
        return self::_clearCacheData([
            self::_getCacheRowKey($this->_sClassName, $p_mPkVal)
        ], $this->sCacheName, $this->_sClassName);
    }

    /**
     * 添加数据
     *
     * @param boolean $p_bUpdate            
     *
     * @return int|false
     */
    function addData($p_bUpdate = false)
    {
        $aOrmData = self::_checkField($this->getSource(), $this->aOrmField, $this->_sClassName);
        $aDbData = $this->beforeSave($aOrmData);
        $aDbData = self::_checkField($aDbData, $this->_aAllDbField, $this->_sClassName);
        $aDbData['iCreateTime'] = variable::getInstance()->getRealTime();
        $aSqlParam = self::_joinAddString($this->_aAllDbField, $aDbData, $this->_sClassName);
        $sSql = 'insert into ' . $this->dispatchTable($this->sTblName) . ' (' . $aSqlParam['sFieldStr'] . ')values(' . $aSqlParam['sParamStr'] . ')';
        if ($p_bUpdate) {
            $aDbData['iUpdateTime'] = $aDbData['iCreateTime'];
            unset($aDbData['iCreateTime']);
            $aUpdSql = self::_joinUpdString($this->_aAllDbField, $aDbData, $this->sPkField, $this->_sClassName);
            $aSqlParam['aValue'] = array_merge($aSqlParam['aValue'], $aUpdSql['aValue']);
            $sSql = $sSql . ' on duplicate key update ' . $aUpdSql['sFieldStr'];
            if (isset($aDbData[$this->sPkField])) {
                $this->clearRowCache($aDbData[$this->sPkField]);
            }
        }
        return self::_insertDbData($sSql, $aSqlParam['aValue'], $this->dispatchDb($this->sMasterDbName), $this->_aAllDbField, $this->_sClassName);
    }

    /**
     * 获取一行数据
     *
     * @param boolean $p_bStrictFreshCache            
     * @return array|null
     */
    function getDetail($p_bStrictFreshCache = false)
    {
        $aOrmData = self::_checkField($this->getSource(), $this->aOrmField, $this->_sClassName);
        $aDbData = $this->beforeSave($aOrmData);
        $aDbData = self::_checkField($aDbData, $this->aDbField, $this->_sClassName);
        $aPkParam = self::_joinPKWhereString($this->sPkField, $aDbData, $this->_sClassName, $this->bNeedDelData);
        $sCacheKey = self::_getCacheRowKey($this->_sClassName, $aPkParam['aValue'][$this->sPkField]);
        if ($p_bStrictFreshCache or ! $this->_bolNeedCache) {
            $aOrmData = [];
        } else {
            $aCacheData = $this->_getCacheData([
                $sCacheKey
            ], $this->sCacheName, $this->_sClassName);
            if (empty($aCacheData)) {
                $aOrmData = [];
            } else {
                $aOrmData = $aCacheData[$sCacheKey];
            }
        }
        if (empty($aOrmData)) {
            $sSql = 'select ' . self::_joinSelectString($this->aDbField, $this->_sClassName) . ' from ' . $this->dispatchTable($this->sTblName) . ' where ' . $aPkParam['sFieldStr'];
            $aDbData = self::_getDbData($sSql, $aPkParam['aValue'], self::Sql_FETCH_TYPE_ROW, $this->dispatchDb($this->sSlaveDbName), $this->_aAllDbField, $this->_sClassName);
            if (null === $aDbData) {
                return null;
            }
            $aOrmData = $this->beforeRead($aDbData);
            if (! $this->bNeedDelData) {
                $this->_setCacheData([
                    $sCacheKey => $aOrmData
                ], self::DEFAULT_CACHE_LEVEL, $this->sCacheName, $this->_sClassName);
            }
        }
        return $aOrmData;
    }

    /**
     * 更新数据
     *
     * @return int
     */
    function updData()
    {
        $aNewOrmData = self::_checkField($this->getSource(), $this->aOrmField, $this->_sClassName);
        $oOldOrmData = $this->getDetail();
        if (null === $oOldOrmData) {
            return 0;
        }
        $aNewDbData = $this->beforeSave($aNewOrmData);
        $aOldDbData = $this->beforeSave($oOldOrmData);
        foreach ($aNewDbData as $sDbField => $sValue) {
            if ($sDbField != $this->sPkField and $sValue == $aOldDbData[$sDbField]) {
                unset($aNewDbData[$sDbField]);
            }
        }
        if (1 == count($aNewDbData)) {
            return 0;
        }
        $aNewDbData['iUpdateTime'] = variable::getInstance()->getRealTime();
        $aSqlParam = self::_joinUpdString($this->_aAllDbField, $aNewDbData, $this->sPkField, $this->_sClassName);
        $aPkParam = self::_joinPKWhereString($this->sPkField, $aNewDbData, $this->_sClassName);
        $sSql = 'update ' . $this->dispatchTable($this->sTblName) . ' set ' . $aSqlParam['sFieldStr'] . ' where ' . $aPkParam['sFieldStr'];
        $this->clearRowCache($aPkParam['aValue'][$this->sPkField]);
        return self::_updDbData($sSql, array_merge($aSqlParam['aValue'], $aPkParam['aValue']), $this->dispatchDb($this->sMasterDbName), $this->_aAllDbField, $this->_sClassName);
    }

    /**
     * 删除数据
     *
     * @return int
     */
    function delData()
    {
        $aOrmData = self::_checkField($this->getSource(), $this->aOrmField, $this->_sClassName);
        $aDbData = $this->beforeSave($aOrmData);
        $aDbData['iDeleteTime'] = variable::getInstance()->getRealTime();
        $aPkParam = self::_joinPKWhereString($this->sPkField, $aDbData, $this->_sClassName);
        if (self::$_bPhyDelete) {
            $sSql = 'delete from ' . $this->dispatchTable($this->sTblName) . ' where ' . $aPkParam['sFieldStr'];
            $this->clearRowCache($aPkParam['aValue'][$this->sPkField]);
            return $this->_updDbData($sSql, $aPkParam['aValue'], $this->dispatchDb($this->sMasterDbName), $this->_aAllDbField, $this->_sClassName);
        } else {
            $aSqlParam = self::_joinUpdString($this->_aAllDbField, $aDbData, $this->sPkField, $this->_sClassName);
            $sSql = 'update ' . $this->dispatchTable($this->sTblName) . ' set ' . $aSqlParam['sFieldStr'] . ' where ' . $aPkParam['sFieldStr'];
            $this->clearRowCache($aPkParam['aValue'][$this->sPkField]);
            return self::_updDbData($sSql, array_merge($aSqlParam['aValue'], $aPkParam['aValue']), $this->dispatchDb($this->sMasterDbName), $this->_aAllDbField, $this->_sClassName);
        }
    }

    /**
     * 获取多行数据
     *
     * @param boolean $p_bStrictFreshCache            
     * @throws Exception
     * @return array
     */
    function getList($p_bStrictFreshCache = false)
    {
        $sSql = 'select ' . $this->sPkField . ' from ' . $this->dispatchTable($this->sTblName);
        $aWhereParam = self::_joinWhereString($this->_aFilters, $this->aDbIndexOrders, $this->sPkField, $this->_sClassName, $this->bNeedDelData);
        $sSql .= ' where ' . $aWhereParam['sFieldStr'];
        if ('' == $this->_sOrder) {
            $sSql .= ' order by ' . $this->sPkField . ' desc';
        } else {
            $sSql .= ' order by ' . $this->_sOrder;
            $this->_sOrder = '';
        }
        $sSql .= ' limit :iStartRow, :iFetchRow';
        $aWhereParam['aValue']['iStartRow'] = $this->_iStartRow;
        $aWhereParam['aValue']['iFetchRow'] = $this->_iFetchRow;
        $this->_iStartRow = self::DEFAULT_START_ROW;
        $this->_iFetchRow = self::DEFAULT_FETCH_ROW;
        $aPkVals = self::_getDbData($sSql, $aWhereParam['aValue'], self::Sql_FETCH_TYPE_LIST, $this->dispatchDb($this->sSlaveDbName), $this->_aAllDbField, $this->_sClassName);
        if (empty($aPkVals)) {
            return [];
        } else {
            return self::_orderPkDataList($aPkVals, $this->getListByPkVals($aPkVals, $p_bStrictFreshCache), $this->sPkField);
        }
    }

    /**
     * 得到统计数据
     *
     * @return int
     */
    function getCnt()
    {
        $sSql = 'select count(*) as cnt from ' . $this->dispatchTable($this->sTblName);
        $aWhereParam = self::_joinWhereString($this->_aFilters, $this->aDbIndexOrders, $this->sPkField, $this->_sClassName, $this->bNeedDelData);
        $sSql .= ' where ' . $aWhereParam['sFieldStr'];
        return $this->_getDbData($sSql, $aWhereParam['aValue'], self::Sql_FETCH_TYPE_COLUMN, $this->dispatchDb($this->sSlaveDbName), $this->_aAllDbField, $this->_sClassName);
    }

    /**
     * 根据PK获取数据
     *
     * @param mix $p_mPkVals            
     * @param boolean $p_bStrictFreshCache            
     * @return array
     */
    function getListByPkVals($p_mPkVals, $p_bStrictFreshCache = false)
    {
        $aPkVals = self::_rebuildPkVals($p_mPkVals, $this->sPkField);
        if (empty($aPkVals)) {
            return [];
        }
        $aResults = [];
        if ($this->_bolNeedCache and ! $p_bStrictFreshCache) {
            $aCacheMissPkVals = $aCacheKeys = [];
            foreach ($aPkVals as $mPkVal) {
                $aCacheKeys[] = self::_getCacheRowKey($this->_sClassName, $mPkVal);
            }
            $aCacheData = self::_getCacheData($aCacheKeys, $this->sCacheName, $this->_sClassName);
            foreach ($aPkVals as $mPkVal) {
                $sCacheKey = self::_getCacheRowKey($this->_sClassName, $mPkVal);
                if (isset($aCacheData[$sCacheKey])) {} else {
                    $aCacheMissPkVals[] = $mPkVal;
                }
            }
        } else {
            $aCacheMissPkVals = $aPkVals;
            $aCacheData = [];
        }
        if (empty($aCacheMissPkVals)) {
            $aDbCacheData = [];
        } else {
            $aPkValsHolders = $aPkParam = $aDbCacheData = [];
            foreach ($aCacheMissPkVals as $iIndex => $mVal) {
                $sHolder = $this->sPkField . '_' . $iIndex;
                $aPkValsHolders[] = self::$_sBindHolder . $sHolder;
                $aPkParam[$sHolder] = $mVal;
            }
            if ($this->bNeedDelData) {
                $sSql = 'select ' . self::_joinSelectString($this->aDbField, $this->_sClassName) . ' from ' . $this->dispatchTable($this->sTblName) . ' where `' . $this->sPkField . '` in (' . join(' ,', $aPkValsHolders) . ')';
            } else {
                $sSql = 'select ' . self::_joinSelectString($this->aDbField, $this->_sClassName) . ' from ' . $this->dispatchTable($this->sTblName) . ' where `' . $this->sPkField . '` in (' . join(' ,', $aPkValsHolders) . ') and `iDeleteTime`=:iDeleteTime_99';
                $aPkParam['iDeleteTime_99'] = 0;
            }
            $aDbDatas = self::_getDbData($sSql, $aPkParam, self::Sql_FETCH_TYPE_LIST, $this->dispatchDb($this->sSlaveDbName), $this->_aAllDbField, $this->_sClassName);
            if (empty($aDbDatas)) {} else {
                if (! $this->bNeedDelData) {
                    foreach ($aDbDatas as $aData) {
                        $sCacheKey = self::_getCacheRowKey($this->_sClassName, $aData[$this->sPkField]);
                        $aDbCacheData[$sCacheKey] = $this->beforeRead($aData);
                    }
                    $this->_setCacheData($aDbCacheData, self::DEFAULT_CACHE_LEVEL, $this->sCacheName, $this->_sClassName);
                }
            }
        }
        $aDatas = [];
        foreach ($aCacheData as $aData) {
            $aDatas[] = $aData;
        }
        foreach ($aDbCacheData as $aData) {
            $aDatas[] = $aData;
        }
        return $aDatas;
    }

    /**
     * 根据PK删除数据
     *
     * @param mix $p_mPkVals            
     * @return int
     */
    function delDataByPkVals($p_mPkVals)
    {
        $aPkVals = self::_rebuildPkVals($p_mPkVals, $this->sPkField);
        if (empty($aPkVals)) {
            return 0;
        }
        $aPkValsHolders = $aCacheKeys = $aPkParam = [];
        foreach ($aPkVals as $iIndex => $mVal) {
            $sHolder = $this->sPkField . '_' . $iIndex;
            $aPkValsHolders[] = self::$_sBindHolder . $sHolder;
            $aPkParam[$sHolder] = $mVal;
            $aCacheKeys[] = self::_getCacheRowKey($this->_sClassName, $mVal);
        }
        if (self::$_bPhyDelete) {
            $sSql = 'delete from ' . $this->dispatchTable($this->sTblName) . ' where `' . $this->sPkField . '` in (' . join(' ,', $aPkValsHolders) . ')';
            $iLastAffectedCnt = $this->_updDbData($sSql, $aPkParam, $this->dispatchDb($this->sMasterDbName), $this->aDbField, $this->_sClassName);
        } else {
            $aDbData = [];
            $aDbData['iDeleteTime'] = variable::getInstance()->getRealTime();
            $aDbData['iDeleteTime_99'] = 0;
            $sSql = 'update ' . $this->dispatchTable($this->sTblName) . ' set `iDeleteTime`=:iDeleteTime where `' . $this->sPkField . '` in (' . join(' ,', $aPkValsHolders) . ') and `iDeleteTime`=:iDeleteTime_99';
            $iLastAffectedCnt = self::_updDbData($sSql, array_merge($aDbData, $aPkParam), $this->dispatchDb($this->sMasterDbName), $this->_aAllDbField, $this->_sClassName);
        }
        self::_clearCacheData($aCacheKeys, $this->sCacheName, $this->_sClassName);
        return $iLastAffectedCnt;
    }

    /**
     * 根据PK更新数据
     *
     * @param mix $p_mPkVals            
     * @return int
     */
    function updListByPkVals($p_mPkVals)
    {
        $aPkVals = self::_rebuildPkVals($p_mPkVals, $this->sPkField);
        if (empty($aPkVals)) {
            return [];
        }
        $aOrmData = self::_checkField($this->getSource(), $this->aOrmField, $this->_sClassName);
        $aDbData = $this->beforeSave($aOrmData);
        $aDbData = self::_checkField($aDbData, $this->aDbField, $this->_sClassName);
        $aDbData['iUpdateTime'] = variable::getInstance()->getRealTime();
        $aSqlParam = self::_joinUpdString($this->_aAllDbField, $aDbData, $this->sPkField, $this->_sClassName);
        $aPkValsHolders = $aCacheKeys = $aPkParam = [];
        foreach ($aPkVals as $iIndex => $mVal) {
            $sHolder = $this->sPkField . '_' . $iIndex;
            $aPkValsHolders[] = self::$_sBindHolder . $sHolder;
            $aPkParam[$sHolder] = $mVal;
            $aCacheKeys[] = self::_getCacheRowKey($this->_sClassName, $mVal);
        }
        $sSql = 'update ' . $this->dispatchTable($this->sTblName) . ' set ' . $aSqlParam['sFieldStr'] . ' where `' . $this->sPkField . '` in (' . join(' ,', $aPkValsHolders) . ') and iDeleteTime=:iDeleteTime_99';
        $iLastAffectedCnt = self::_updDbData($sSql, array_merge($aSqlParam['aValue'], $aPkParam, [
            'iDeleteTime_99' => 0
        ]), $this->dispatchDb($this->sMasterDbName), $this->_aAllDbField, $this->_sClassName);
        self::_clearCacheData($aCacheKeys, $this->sCacheName, $this->_sClassName);
        return $iLastAffectedCnt;
    }

    /**
     * 获取复杂业务的数据列表
     *
     * @param string $p_sSqlName            
     * @param array $p_aParam            
     * @param boolean $p_bStrictFreshCache            
     * @throws Exception
     * @return array
     */
    function getBizList($p_sSqlName, $p_aParam = [], $p_bStrictFreshCache = false)
    {
        if (isset($this->aBizSql[$p_sSqlName])) {
            if ($this->bNeedDelData) {
                $sSql = 'select ' . $this->sPkField . ' from ' . $this->dispatchTable($this->sTblName) . ' where ' . $this->aBizSql[$p_sSqlName];
            } else {
                $sSql = 'select ' . $this->sPkField . ' from ' . $this->dispatchTable($this->sTblName) . ' where iDeleteTime=:iDeleteTime and ' . $this->aBizSql[$p_sSqlName];
                $p_aParam['iDeleteTime'] = 0;
            }
            $sSql .= ' limit :iStartRow, :iFetchRow';
            $p_aParam['iStartRow'] = $this->_iStartRow;
            $p_aParam['iFetchRow'] = $this->_iFetchRow;
            $this->_iStartRow = self::DEFAULT_START_ROW;
            $this->_iFetchRow = self::DEFAULT_FETCH_ROW;
            $aPkVals = $this->_getDbData($sSql, $p_aParam, self::Sql_FETCH_TYPE_LIST, $this->dispatchDb($this->sSlaveDbName), $this->_aAllDbField, $this->_sClassName);
            if (empty($aPkVals)) {
                return [];
            } else {
                return self::_orderPkDataList($aPkVals, $this->getListByPkVals($aPkVals, $p_bStrictFreshCache), $this->sPkField);
            }
        } else {
            throw new \Exception($this->_sClassName . ': you gave an invalid Sql name(' . $p_sSqlName . ').');
        }
    }

    /**
     * 获取复杂业务的统计数字
     *
     * @param string $p_sSqlName            
     * @param array $p_aParam            
     * @throws Exception
     * @return array|string
     */
    function getBizCnt($p_sSqlName, $p_aParam = [])
    {
        if (isset($this->aBizSql[$p_sSqlName])) {
            if ($this->bNeedDelData) {
                $sSql = 'select count(*) as `cnt` from ' . $this->dispatchTable($this->sTblName) . ' where ' . $this->aBizSql[$p_sSqlName];
            } else {
                $sSql = 'select count(*) as `cnt` from ' . $this->dispatchTable($this->sTblName) . ' where iDeleteTime=:iDeleteTime and ' . $this->aBizSql[$p_sSqlName];
                $p_aParam['iDeleteTime'] = 0;
            }
            return $this->_getDbData($sSql, $p_aParam, self::Sql_FETCH_TYPE_COLUMN, $this->dispatchDb($this->sSlaveDbName), $this->_aAllDbField, $this->_sClassName);
        } else {
            throw new \Exception($this->_sClassName . ': you gave an invalid Sql name(' . $p_sSqlName . ').');
        }
    }

    /**
     * 开始一个事务
     *
     * @return void
     */
    function beginTransaction()
    {
        $sDbName = $this->dispatchDb($this->sMasterDbName);
        self::_connectDb($sDbName);
        self::$_aDbPool[$sDbName]->beginTransaction();
    }

    /**
     * 提交事务
     *
     * @return void
     */
    function commit()
    {
        $sDbName = $this->dispatchDb($this->sMasterDbName);
        self::_connectDb($sDbName);
        self::$_aDbPool[$sDbName]->commit();
    }

    /**
     * 回滚事务
     *
     * @return void
     */
    function rollBack()
    {
        $sDbName = $this->dispatchDb($this->sMasterDbName);
        self::_connectDb($sDbName);
        self::$_aDbPool[$sDbName]->rollBack();
    }

    /**
     * 分配Db
     *
     * @param string $p_sDbName            
     * @return string
     */
    protected function dispatchDb($p_sDbName)
    {
        return $p_sDbName;
    }

    /**
     * 分配表
     *
     * @param string $psTblName            
     * @return string
     */
    protected function dispatchTable($psTblName)
    {
        return $psTblName;
    }

    /**
     * 在保存数据前的钩子
     *
     * @param array $p_aOrmData            
     * @return array
     */
    protected function beforeSave($p_aOrmData)
    {
        return $p_aOrmData;
    }

    /**
     * 在读取数据前的钩子
     *
     * @param array $p_aDbData            
     * @return array
     */
    protected function beforeRead($p_aDbData)
    {
        return $p_aDbData;
    }

    /**
     * 检查字段内容
     *
     * @param array $p_aData            
     * @param array $p_aField            
     * @param array $p_sClassName            
     * @throws Exception
     * @return array
     */
    private static function _checkField($p_aData, $p_aField, $p_sClassName)
    {
        $aData = [];
        foreach ($p_aField as $sField => $aFieldSet) {
            if (isset($p_aData[$sField])) {
                $mValue = $p_aData[$sField];
                switch ($aFieldSet['sType']) {
                    case 'int':
                    case 'tinyint':
                    case 'float':
                        $o_sOperator = $o_iParam = '';
                        if (! self::_isSelfOperate($sField, $mValue, $o_sOperator, $o_iParam)) {
                            if (is_numeric($mValue)) {} else {
                                throw new \Exception($p_sClassName . ': you gave a nonnumeric value(' . var_export($mValue, true) . ') to an attribute(' . $sField . ') which need a number, maybe is ' . gettype($mValue) . '.');
                            }
                        }
                        break;
                    case 'string':
                        if (is_string($mValue)) {
                            $iLength = mb_strlen($mValue);
                            if ($iLength > $aFieldSet['iLength']) {
                                throw new \Exception($p_sClassName . ': you gave an overlength(' . $iLength . ') string(' . var_export($mValue, true) . ') to an attribute(' . $sField . ') which max length is ' . $aFieldSet['iLength'] . '.');
                            }
                        } else {
                            throw new \Exception($p_sClassName . ': you gave a non-string value(' . var_export($mValue, true) . ') to an attribute(' . $sField . ') which needed a string, maybe is ' . gettype($mValue) . '.');
                        }
                        break;
                    case 'array':
                        if (! is_array($mValue)) {
                            throw new \Exception($p_sClassName . ': you gave a non-array value(' . var_export($mValue, true) . ') to an attribute(' . $sField . ') which needed an array, maybe is ' . gettype($mValue) . '.');
                        }
                        break;
                }
                $aData[$sField] = $mValue;
            }
        }
        return $aData;
    }

    /**
     * 获取缓存连接
     *
     * @param string $p_sCacheName            
     * @return void
     */
    private static function _connectCache($p_sCacheName)
    {
        if (isset(self::$_aCachePool[$p_sCacheName])) {} else {
            self::$_aCachePool[$p_sCacheName] = pooling::getInstance()->getConnect($p_sCacheName);
        }
    }

    /**
     * 获取数据库连接
     *
     * @param string $p_sDbName            
     * @return void
     */
    private static function _connectDb($p_sDbName)
    {
        if (isset(self::$_aDbPool[$p_sDbName])) {} else {
            self::$_aDbPool[$p_sDbName] = pooling::getInstance()->getConnect($p_sDbName);
        }
    }

    /**
     * 获取数据库数据
     *
     * @param string $p_sSql            
     * @param array $p_aParam            
     * @param int $p_iType            
     * @param string $p_sDbName            
     * @param array $p_aDbField            
     * @param string $p_sClassName            
     * @return array|string
     */
    private static function _getDbData($p_sSql, $p_aParam, $p_iType, $p_sDbName, $p_aDbField, $p_sClassName)
    {
        self::_connectDb($p_sDbName);
        self::$_aAllSqls[] = $p_sSql;
        if (self::$_sLastPreparedSql == $p_sSql) {} else {
            self::$_sLastPreparedSql = $p_sSql;
            self::$_oDbStmt = self::$_aDbPool[$p_sDbName]->prepare($p_sSql);
        }
        self::_bindData(self::_parseParameter($p_aParam, $p_aDbField, $p_sClassName));
        self::$_oDbStmt->execute();
        ++ self::$_iQueryCnt;
        switch ($p_iType) {
            case self::Sql_FETCH_TYPE_COLUMN:
                $mData = self::$_oDbStmt->fetchColumn();
                break;
            case self::Sql_FETCH_TYPE_ROW:
                $mData = self::$_oDbStmt->fetch();
                if (false === $mData) {
                    $mData = null;
                }
                break;
            case self::Sql_FETCH_TYPE_LIST:
                $mData = self::$_oDbStmt->fetchAll();
                break;
        }
        self::$_oDebugger->showMsg($p_sClassName . '->Execute: ' . $p_sSql);
        self::$_oDebugger->showMsg($p_sClassName . '->Parameter: ' . var_export($p_aParam, true));
        self::$_oDebugger->showMsg($p_sClassName . '->Result: ' . var_export($mData, true));
        return $mData;
    }

    /**
     * 更新数据库数据
     *
     * @param string $p_sSql            
     * @param array $p_aParam            
     * @param string $p_sDbName            
     * @param array $p_aDbField            
     * @param string $p_sClassName            
     * @return int
     */
    private static function _updDbData($p_sSql, $p_aParam, $p_sDbName, $p_aDbField, $p_sClassName)
    {
        self::_connectDb($p_sDbName);
        self::$_aAllSqls[] = $p_sSql;
        if (self::$_sLastPreparedSql == $p_sSql) {} else {
            self::$_sLastPreparedSql = $p_sSql;
            self::$_oDbStmt = self::$_aDbPool[$p_sDbName]->prepare($p_sSql);
        }
        self::_bindData(self::_parseParameter($p_aParam, $p_aDbField, $p_sClassName));
        self::$_mDebugResult = self::$_oDbStmt->execute();
        ++ self::$_iQueryCnt;
        $iLastAffectedCnt = self::$_oDbStmt->rowCount();
        self::$_oDebugger->showMsg($p_sClassName . '->Execute: ' . $p_sSql);
        self::$_oDebugger->showMsg($p_sClassName . '->Parameter: ' . var_export($p_aParam, true));
        self::$_oDebugger->showMsg($p_sClassName . '->Result: ' . var_export(self::$_mDebugResult, true));
        self::$_oDebugger->showMsg($p_sClassName . '->Affected row count: ' . $iLastAffectedCnt);
        if (false === self::$_mDebugResult) {
            self::$_oDebugger->showMsg($p_sClassName . '->ErrorInfo:' . var_export(self::$_oDbStmt->errorInfo(), true));
        }
        return $iLastAffectedCnt;
    }

    /**
     * 插入数据库数据
     *
     * @param string $p_sSql            
     * @param array $p_aParam            
     * @param string $p_sDbName            
     * @param array $p_aDbField            
     * @param string $p_sClassName            
     * @return int|false
     */
    private static function _insertDbData($p_sSql, $p_aParam, $p_sDbName, $p_aDbField, $p_sClassName)
    {
        self::_connectDb($p_sDbName);
        self::$_aAllSqls[] = $p_sSql;
        if (self::$_sLastPreparedSql == $p_sSql) {} else {
            self::$_sLastPreparedSql = $p_sSql;
            self::$_oDbStmt = self::$_aDbPool[$p_sDbName]->prepare($p_sSql);
        }
        self::_bindData(self::_parseParameter($p_aParam, $p_aDbField, $p_sClassName));
        self::$_mDebugResult = self::$_oDbStmt->execute();
        ++ self::$_iQueryCnt;
        $iLastInsertID = self::$_aDbPool[$p_sDbName]->lastInsertId();
        self::$_oDebugger->showMsg($p_sClassName . '->Execute: ' . $p_sSql);
        self::$_oDebugger->showMsg($p_sClassName . '->Parameter: ' . var_export($p_aParam, true));
        self::$_oDebugger->showMsg($p_sClassName . '->Result: ' . var_export(self::$_mDebugResult, true));
        self::$_oDebugger->showMsg($p_sClassName . '->LastID: ' . $iLastInsertID);
        if (false === self::$_mDebugResult) {
            self::$_oDebugger->showMsg($p_sClassName . '->ErrorInfo:' . var_export(self::$_oDbStmt->errorInfo(), true));
        }
        return $iLastInsertID;
    }

    /**
     * 根据Key删除缓存
     *
     * @param array $p_aCacheKeys            
     * @param string $p_sCacheName            
     * @param string $p_sClassName            
     * @return true|false
     */
    private static function _clearCacheData($p_aCacheKeys, $p_sCacheName, $p_sClassName)
    {
        ++ self::$_iCacheCnt;
        self::_clearStaticCacheData($p_aCacheKeys, $p_sClassName);
        self::_clearAPCCacheData($p_aCacheKeys, $p_sClassName);
        return self::_clearMemCacheData($p_aCacheKeys, $p_sCacheName, $p_sClassName);
    }

    /**
     * 根据Key删除静态缓存
     *
     * @param array $p_aCacheKeys            
     * @param string $p_sClassName            
     * @return true
     */
    private static function _clearStaticCacheData($p_aCacheKeys, $p_sClassName)
    {
        foreach ($p_aCacheKeys as $sKey) {
            unset(self::$_aStaticCache[$sKey]);
            self::$_oDebugger->showMsg($p_sClassName . '[StaticCache]->Delete: ' . $sKey . '|true');
        }
        return true;
    }

    /**
     * 根据Key删除APC缓存
     *
     * @param array $p_aCacheKeys            
     * @param string $p_sClassName            
     * @return true
     */
    private static function _clearAPCCacheData($p_aCacheKeys, $p_sClassName)
    {
        return true;
    }

    /**
     * 根据Key删除Memcache
     *
     * @param array $p_aCacheKeys            
     * @param string $p_sCacheName            
     * @param string $p_sClassName            
     * @todo 再检查
     *      
     * @return true|false
     */
    private static function _clearMemCacheData($p_aCacheKeys, $p_sCacheName, $p_sClassName)
    {
        self::_connectCache($p_sCacheName);
        for ($iIndex = 0; $iIndex < self::MAX_CACHE_TRY; ++ $iIndex) {
            $bFoundErr = false;
            self::$_mDebugResult = self::$_aCachePool[$p_sCacheName]->deleteMulti($p_aCacheKeys);
            self::$_oDebugger->showMsg($p_sClassName . '[Memcache]->Delete: Set: Multi Key|' . var_export($p_aCacheKeys, true) . '|' . var_export(self::$_mDebugResult, true));
            foreach (self::$_mDebugResult as $sKey => $mResult) {
                if (true === $mResult) {} else {
                    if (\Memcached::RES_NOTFOUND == $mResult) {} else {
                        $aErrKeys[] = $sKey;
                    }
                }
            }
            if (empty($aErrKeys)) {
                return true;
            } else {
                $p_aCacheKeys = $aErrKeys;
            }
        }
        return false;
    }

    /**
     * 写入缓存数据
     *
     * @param array $p_aCache            
     * @param int $p_iDeepLevel            
     * @param string $p_sCacheName            
     * @param string $p_sClassName            
     * @return void
     */
    private static function _setCacheData($p_aCache, $p_iDeepLevel, $p_sCacheName, $p_sClassName)
    {
        ++ self::$_iCacheCnt;
        if ($p_iDeepLevel < 1 or $p_iDeepLevel > 7) {
            $p_iDeepLevel = self::DEFAULT_CACHE_LEVEL;
        }
        $sStyle = substr('00' . decbin($p_iDeepLevel), - 3);
        if (1 == $sStyle[2]) {
            self::_setStaticCacheData($p_aCache, $p_sClassName);
        }
        if (1 == $sStyle[1]) {
            self::_setAPCCacheData($p_aCache, $p_sClassName);
        }
        if (1 == $sStyle[0]) {
            self::_setMemCacheData($p_aCache, $p_sCacheName, $p_sClassName);
        }
    }

    /**
     * 写入静态缓存数据
     *
     * @param array $p_aCache            
     * @param string $p_sClassName            
     * @return void
     */
    private static function _setStaticCacheData($p_aCache, $p_sClassName)
    {
        foreach ($p_aCache as $sKey => $mValue) {
            self::$_aStaticCache[$sKey] = $mValue;
            self::$_oDebugger->showMsg($p_sClassName . '[StaticCache]->Set: ' . $sKey . '|true');
        }
    }

    /**
     * 写入APC缓存数据
     *
     * @param array $p_aCache            
     * @param string $p_sClassName            
     * @return void
     */
    private static function _setAPCCacheData($p_aCache, $p_sClassName)
    {}

    /**
     * 写入Memcache缓存数据
     *
     * @param array $p_aCache            
     * @param string $p_sCacheName            
     * @param string $p_sClassName            
     * @return void
     */
    private static function _setMemCacheData($p_aCache, $p_sCacheName, $p_sClassName)
    {
        self::_connectCache($p_sCacheName);
        foreach ($p_aCache as $sKey => $mValue) {
            $p_aCache[$sKey] = self::_implodeCache($mValue, self::DEFAULT_CACHE_TIME);
        }
        for ($iIndex = 0; $iIndex < self::MAX_CACHE_TRY; ++ $iIndex) {
            self::$_mDebugResult = self::$_aCachePool[$p_sCacheName]->setMulti($p_aCache, self::DEFAULT_CACHE_TIME);
            if (true === self::$_mDebugResult) {
                break;
            }
        }
        self::$_oDebugger->showMsg($p_sClassName . '[Memcache]->Set: Multi Key|' . var_export($p_aCache, true) . '|' . var_export(self::$_mDebugResult, true));
    }

    /**
     * 获取缓存数据
     *
     * @param array $p_aCacheKeys            
     * @param string $p_sCacheName            
     * @param string $p_sClassName            
     * @return array
     */
    private static function _getCacheData($p_aCacheKeys, $p_sCacheName, $p_sClassName)
    {
        ++ self::$_iCacheCnt;
        $aMissKeys = $mData = [];
        foreach ($p_aCacheKeys as $sCacheKey) {
            if (isset(self::$_aStaticCache[$sCacheKey])) {
                $mData[$sCacheKey] = self::$_aStaticCache[$sCacheKey];
                self::$_oDebugger->showMsg($p_sClassName . '[StaticCache]->Get: ' . $sCacheKey . '|' . var_export(self::$_aStaticCache[$sCacheKey], true));
            } else {
                $aMissKeys[] = $sCacheKey;
                self::$_oDebugger->showMsg($p_sClassName . '[StaticCache]->Get: ' . $sCacheKey . '|false');
            }
        }
        if (empty($aMissKeys)) {
            return $mData;
        }
        // @todo get apc cache
        self::_connectCache($p_sCacheName);
        $mCacheData = self::$_aCachePool[$p_sCacheName]->getMulti($aMissKeys);
        if (false !== $mCacheData) {
            foreach ($aMissKeys as $sCacheKey) {
                if (isset($mCacheData[$sCacheKey])) {
                    $aEachCacheData = $mCacheData[$sCacheKey];
                    $mData[$sCacheKey] = $aEachCacheData['mData'];
                    self::$_oDebugger->showMsg($p_sClassName . '[Memcache]->Get: ' . $sCacheKey . '|' . var_export($aEachCacheData['mData'], true));
                    self::$_oDebugger->showMsg($p_sClassName . '[Memcache]->Info: Key=>' . $sCacheKey . ' Create=>' . date('Y-m-d H:i:s', $aEachCacheData['iCreateTime']) . ' Expire=>' . (0 == $aEachCacheData['iLifeTime'] ? 'unlimit' : date('Y-m-d H:i:s', $aEachCacheData['iCreateTime'] + $aEachCacheData['iLifeTime'])));
                    self::_setStaticCacheData([
                        $sCacheKey => $aEachCacheData['mData']
                    ], $p_sClassName);
                } else {
                    self::$_oDebugger->showMsg($p_sClassName . '[Memcache]->Get: ' . $sCacheKey . '|false');
                }
            }
        }
        return $mData;
    }

    /**
     * 分析Sql参数
     *
     * @param array $p_aParam            
     * @param array $p_aDbField            
     * @param string $p_sClassName            
     * @throws Exception
     * @return array
     */
    private static function _parseParameter($p_aParam, $p_aDbField, $p_sClassName)
    {
        $aParams = [];
        $iPDOType = 0;
        $p_aDbField['iStartRow'] = $p_aDbField['iFetchRow'] = [
            'sType' => 'int',
            'bUnsigned' => true
        ];
        foreach ($p_aParam as $sField => $mValue) {
            $aField = [];
            if (0 < preg_match('/([a-zA-Z0-9]+)(\_\d)?/', $sField, $aField)) {
                switch ($p_aDbField[$aField[1]]['sType']) {
                    case 'int':
                    case 'tinyint':
                        $iPDOType = \PDO::PARAM_INT;
                        break;
                    case 'string':
                        $iPDOType = \PDO::PARAM_STR;
                        break;
                    default:
                        throw new \Exception($p_sClassName . ': you gave an unknown database field(' . $sField . ') type(' . $p_aDbField[$sField]['sType'] . ').');
                        break;
                }
                $aParams[] = [
                    'sField' => $sField,
                    'mValue' => $mValue,
                    'iPDOType' => $iPDOType
                ];
            } else {
                throw new \Exception($p_sClassName . ': you gave an invalid database field(' . $sField . ').');
                break;
            }
        }
        return $aParams;
    }

    /**
     * 绑定变量
     *
     * @param array $p_aParams            
     * @return void
     */
    private static function _bindData($p_aParams)
    {
        foreach ($p_aParams as $aParam) {
            $mValue = $aParam['mValue'];
            self::$_oDbStmt->bindParam(self::$_sBindHolder . $aParam['sField'], $mValue, $aParam['iPDOType']);
            unset($mValue);
        }
    }

    /**
     * 获取Orm获取数据所需Sql信息
     *
     * @param array $p_aDbField            
     * @param string $p_sClassName            
     * @throws Exception
     * @return string
     */
    private static function _joinSelectString($p_aDbField, $p_sClassName)
    {
        $sFieldStr = '';
        foreach ($p_aDbField as $sField => $aFieldSet) {
            $sFieldStr .= ', `' . $sField . '`';
        }
        if (isset($sFieldStr[0])) {
            return substr($sFieldStr, 2);
        } else {
            throw new \Exception($p_sClassName . ': your database field(' . var_export($p_aDbField, true) . ') is empty.');
        }
    }

    /**
     * 获取查询条件
     *
     * @param array $p_aFilters            
     * @param array $paDbIndexOrders            
     * @param string $p_sPkField            
     * @param string $p_sClassName            
     * @param boolean $pbNeedDelData            
     * @throws Exception
     * @return array
     */
    private static function _joinWhereString($p_aFilters, $paDbIndexOrders, $p_sPkField, $p_sClassName, $pbNeedDelData = false)
    {
        $sFieldStr = '';
        $aIndex = $aValue = [];
        if (! $pbNeedDelData) {
            $p_aFilters['iDeleteTime'] = [
                [
                    'sOperator' => '=',
                    'mValue' => 0
                ]
            ];
        }
        foreach ($paDbIndexOrders as $sDbField) { // 按索引顺序拼接查询语句
            if (isset($p_aFilters[$sDbField])) {
                foreach ($p_aFilters[$sDbField] as $aFilter) {
                    if (! isset($aIndex[$sDbField])) {
                        $aIndex[$sDbField] = 100;
                    }
                    if ('in' == $aFilter['sOperator']) {
                        $aHolders = [];
                        $aPkVals = self::_rebuildPkVals($aFilter['mValue'], $p_sPkField);
                        foreach ($aPkVals as $mPkVal) {
                            $sHolder = $sDbField . '_' . ++ $aIndex[$sDbField];
                            $aHolders[] = self::$_sBindHolder . $sHolder;
                            $aValue[$sHolder] = $mPkVal;
                        }
                        $sFieldStr .= ' and (`' . $sDbField . '` ' . $aFilter['sOperator'] . ' (' . join(',', $aHolders) . '))';
                    } else {
                        $sHolder = $sDbField . '_' . ++ $aIndex[$sDbField];
                        $sFieldStr .= ' and (`' . $sDbField . '` ' . $aFilter['sOperator'] . ' ' . self::$_sBindHolder . $sHolder . ')';
                        $aValue[$sHolder] = $aFilter['mValue'];
                    }
                }
            }
        }
        if (isset($p_aFilters['_aSqls'])) { // 拼接自定义查询语句
            foreach ($p_aFilters['_aSqls'] as $aSql) {
                $sFieldStr .= ' and (' . $aSql['sSql'] . ')';
                foreach ($aSql['aParam'] as $sField => $mValue) {
                    if (isset($aValue[$sField])) { // 已经被绑定过了
                        throw new \Exception($p_sClassName . ': param field(' . $sField . ') has been binded.');
                    } else {
                        $aValue[$sField] = $mValue;
                    }
                }
            }
        }
        if (isset($sFieldStr[0])) {
            return [
                'sFieldStr' => substr($sFieldStr, 5),
                'aValue' => $aValue
            ];
        } else {
            throw new \Exception($p_sClassName . ': orm do not allowed get all data.');
        }
    }

    /**
     * 获取Orm添加信息所需Sql信息
     *
     * @param array $p_aDbField            
     * @param array $p_aData            
     * @param string $p_sClassName            
     * @throws Exception
     * @return array
     */
    private static function _joinAddString($p_aDbField, $p_aData, $p_sClassName)
    {
        $sFieldStr = '';
        $sParams = '';
        $aValue = [];
        foreach ($p_aDbField as $sField => $aFieldSet) {
            if (isset($p_aData[$sField])) {
                $sFieldStr .= ', `' . $sField . '`';
                $sParams .= ', ' . self::$_sBindHolder . $sField;
                $aValue[$sField] = $p_aData[$sField];
            }
        }
        if (isset($sFieldStr[0])) {
            return [
                'sFieldStr' => substr($sFieldStr, 2),
                'sParamStr' => substr($sParams, 2),
                'aValue' => $aValue
            ];
        } else {
            throw new \Exception($p_sClassName . ': you have no data(' . var_export($p_aData, true) . ') to insert.');
        }
    }

    /**
     * 获取Orm更新信息所需Sql信息
     *
     * @param array $p_aDbField            
     * @param array $p_aData            
     * @param string $p_sPkField            
     * @param string $p_sClassName            
     * @throws Exception
     * @return array
     */
    private static function _joinUpdString($p_aDbField, $p_aData, $p_sPkField, $p_sClassName)
    {
        $sFieldStr = '';
        $aValue = [];
        foreach ($p_aDbField as $sField => $aFieldSet) {
            if ($p_sPkField != $sField and isset($p_aData[$sField])) {
                $sSelfOperator = $iSelfParam = '';
                if (self::_isSelfOperate($sField, $p_aData[$sField], $sSelfOperator, $iSelfParam)) {
                    $sFieldStr .= ', `' . $sField . '`=`' . $sField . '`' . $sSelfOperator . self::$_sBindHolder . $sField;
                    $aValue[$sField] = $iSelfParam;
                } else {
                    $sFieldStr .= ', `' . $sField . '`=' . self::$_sBindHolder . $sField;
                    $aValue[$sField] = $p_aData[$sField];
                }
            }
        }
        if (isset($sFieldStr[0])) {
            return [
                'sFieldStr' => substr($sFieldStr, 2),
                'aValue' => $aValue
            ];
        } else {
            throw new \Exception($p_sClassName . ': your database fields(' . var_export($p_aDbField, true) . ') are all primary key(' . $p_sPkField . ') or have no data(' . var_export($p_aData, true) . ') to update.');
        }
    }

    /**
     * 判断是否为自运算
     *
     * @param string $p_sField            
     * @param mix $p_mValue            
     * @param string $o_sOperator            
     * @param int $o_iParam            
     * @return true|false
     */
    private static function _isSelfOperate($p_sField, $p_mValue, &$o_sOperator, &$o_iParam)
    {
        $sPattern = '/^' . $p_sField . '([+\-*\/])(\d+)$/i';
        $aResult = [];
        if (1 == preg_match($sPattern, $p_mValue, $aResult)) {
            $o_sOperator = $aResult[1];
            $o_iParam = $aResult[2];
            return true;
        } else {
            return false;
        }
    }

    /**
     * 重新生成新的主键列表
     *
     * @param array $p_mPkVals            
     * @param string $p_sPkField            
     * @return array
     */
    private static function _rebuildPkVals($p_mPkVals, $p_sPkField)
    {
        if (is_array($p_mPkVals)) {
            if (empty($p_mPkVals)) {
                return [];
            } else {
                $mPkVal = array_pop($p_mPkVals);
                if (is_array($mPkVal)) {
                    $aPkVals = [];
                    foreach ($p_mPkVals as $aPkVal) {
                        $aPkVals[] = $aPkVal[$p_sPkField];
                    }
                    $aPkVals[] = $mPkVal[$p_sPkField];
                } else {
                    $aPkVals = $p_mPkVals;
                    $aPkVals[] = $mPkVal;
                }
                array_unique($aPkVals);
                return $aPkVals;
            }
        } else {
            return array_unique(explode(',', $p_mPkVals));
        }
    }

    /**
     * 根据主键顺序重新排序
     *
     * @param array $p_aPkVals            
     * @param array $p_aOrms            
     * @param string $p_sPkField            
     * @return array
     */
    private static function _orderPkDataList($p_aPkVals, $p_aDatas, $p_sPkField)
    {
        $aResults = [];
        foreach ($p_aPkVals as $aPkVal) {
            foreach ($p_aDatas as $aData) {
                if ($aPkVal[$p_sPkField] == $aData[$p_sPkField]) {
                    $aResults[] = $aData;
                    break;
                }
            }
        }
        return $aResults;
    }

    /**
     * 根据主键数据生成where条件
     *
     * @param string $p_sPkField            
     * @param array $p_aData            
     * @param string $p_sClassName            
     * @param boolean $pbNeedDelData            
     * @throws Exception
     * @return array
     */
    private static function _joinPKWhereString($p_sPkField, $p_aData, $p_sClassName, $pbNeedDelData = false)
    {
        if (isset($p_aData[$p_sPkField])) {
            if ($pbNeedDelData) {
                return [
                    'sFieldStr' => '`' . $p_sPkField . '`=' . self::$_sBindHolder . $p_sPkField,
                    'aValue' => [
                        $p_sPkField => $p_aData[$p_sPkField]
                    ]
                ];
            } else {
                return [
                    'sFieldStr' => '`' . $p_sPkField . '`=' . self::$_sBindHolder . $p_sPkField . ' and `iDeleteTime`=:iDeleteTime_99',
                    'aValue' => [
                        $p_sPkField => $p_aData[$p_sPkField],
                        'iDeleteTime_99' => 0
                    ]
                ];
            }
        } else {
            throw new \Exception($p_sClassName . ': you missed Orm primary key value(' . $p_sPkField . ').');
        }
    }

    /**
     * 获取Orm数据缓存Key
     *
     * @param string $p_sOrmName            
     * @param mix $p_mPkVal            
     * @return string
     */
    private static function _getCacheRowKey($p_sOrmName, $p_mPkVal)
    {
        return $p_sOrmName . '_r_' . $p_mPkVal;
    }

    /**
     * 生成cache的数据
     *
     * @param mix $p_mValue            
     * @param int $p_iLifeTime            
     * @return array
     */
    private static function _implodeCache($p_mValue, $p_iLifeTime)
    {
        return [
            'mData' => $p_mValue,
            'iCreateTime' => variable::getInstance()->getRealTime(),
            'iLifeTime' => $p_iLifeTime
        ];
    }
}
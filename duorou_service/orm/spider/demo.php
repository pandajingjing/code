<?php
/**
 * demo
 *
 * @namespace duorou_service\orm\spider
 */
namespace duorou_service\orm\spider;

use panda\lib\sys\orm;

/**
 * demo
 */
class demo extends orm
{

    /**
     * Master数据库连接名,在子类中配置
     *
     * @var string
     */
    protected $_sMasterDbName = 'spider_master';

    /**
     * Slave数据库连接名,在子类中配置
     *
     * @var string
     */
    protected $_sSlaveDbName = 'spider_slave';

    /**
     * 主键ID
     *
     * @var int
     */
    public $iAutoId;

    /**
     * 姓名
     *
     * @var string
     */
    public $sName;

    /**
     * 成员添加时间
     *
     * @var int
     */
    public $iAddTime;

    /**
     * 成员其他信息
     *
     * @var array
     */
    public $aOtherInfo;

    /**
     * 表名称
     *
     * @var string
     */
    protected $_sTblName = 't_demo';

    /**
     * 主键字段
     *
     * @var string
     */
    protected $_sPkField = 'iAutoId';

    /**
     * 数据库表结构
     *
     * @var array
     */
    protected $_aDbField = [
        'iAutoId' => [
            'sType' => 'int',
            'bUnsigned' => true
        ],
        'sName' => [
            'sType' => 'string',
            'iLength' => 255
        ],
        'iAddTime' => [
            'sType' => 'int',
            'bUnsigned' => true
        ],
        'sOtherInfo' => [
            'sType' => 'string',
            'iLength' => 255
        ]
    ];

    /**
     * Orm字段结构
     *
     * @var array
     */
    protected $_aOrmField = [
        'iAutoId' => [
            'sType' => 'int',
            'bUnsigned' => true
        ],
        'sName' => [
            'sType' => 'string',
            'iLength' => 255
        ],
        'iAddTime' => [
            'sType' => 'int',
            'bUnsigned' => true
        ],
        'aOtherInfo' => [
            'sType' => 'array'
        ]
    ];

    /**
     * 数据库索引顺序
     */
    protected $_aDbIndexOrders = [
        'iAutoId',
        'sName'
    ];

    /**
     * 业务Sql语句
     *
     * @var array
     */
    protected $_aBizSql = [
        'sName' => 'sName like :sName ORDER BY iAutoId desc'
    ];

    /**
     * 在保存数据前的钩子
     *
     * @param array $p_aOrmData            
     * @return array
     */
    protected function beforeSave($p_aOrmData)
    {
        if (isset($p_aOrmData['aOtherInfo'])) {
            $p_aOrmData['sOtherInfo'] = json_encode($p_aOrmData['aOtherInfo'], JSON_NUMERIC_CHECK | JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);
            unset($p_aOrmData['aOtherInfo']);
        }
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
        if (isset($p_aDbData['sOtherInfo'])) {
            $p_aDbData['aOtherInfo'] = json_decode($p_aDbData['sOtherInfo'], true);
            unset($p_aDbData['sOtherInfo']);
        }
        return $p_aDbData;
    }
}
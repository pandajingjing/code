<?php
/**
 * duorou_variety
 *
 * @namespace duorou_service\orm\duorou
 */
namespace duorou_service\orm\duorou;

use panda\lib\sys\orm;

/**
 * duorou_variety
 */
class duorou_variety extends orm
{

    /**
     * Master数据库连接名,在子类中配置
     *
     * @var string
     */
    protected $sMasterDbName = 'duorou_master';

    /**
     * Slave数据库连接名,在子类中配置
     *
     * @var string
     */
    protected $sSlaveDbName = 'duorou_slave';

    /**
     * 主键ID
     *
     * @var int
     */
    public $iAutoId;

    /**
     * 中文名字
     *
     * @var string
     */
    public $sNameCn;

    /**
     * 英文名字
     *
     * @var string
     */
    public $sNameEn;

    /**
     * 表名称
     *
     * @var string
     */
    protected $sTblName = 't_duorou_variety';

    /**
     * 主键字段
     *
     * @var string
     */
    protected $sPkField = 'iAutoId';

    /**
     * 数据库表结构
     *
     * @var array
     */
    protected $aDbField = [
        'iAutoId' => [
            'sType' => 'int',
            'bUnsigned' => true
        ],
        'sNameCn' => [
            'sType' => 'string',
            'iLength' => 255
        ],
        'sNameEn' => [
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
        'iAutoId' => [
            'sType' => 'int',
            'bUnsigned' => true
        ],
        'sNameCn' => [
            'sType' => 'string',
            'iLength' => 255
        ],
        'sNameEn' => [
            'sType' => 'string',
            'iLength' => 255
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
    protected $aBizSql = [];
}
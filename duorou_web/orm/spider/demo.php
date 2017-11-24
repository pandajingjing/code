<?php

/**
 * orm_spider_demo
 *
 * spider_db.t_demo映射类
 *
 * @package orm_spider
 */

/**
 * orm_spider_demo
 *
 * spider_db.t_demo映射类
 */
class orm_spider_demo extends lib_sys_orm
{

    /**
     * Master数据库连接名,在子类中配置
     *
     * @var string
     */
    protected $_sMasterDBName = 'spider_db';

    /**
     * Slave数据库连接名,在子类中配置
     *
     * @var string
     */
    protected $_sSlaveDBName = 'spider_db';

    /**
     * 主键ID
     *
     * @var int
     */
    public $iAutoID;

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
    protected $_sPKField = 'iAutoID';

    /**
     * 数据库表结构
     *
     * @var array
     */
    protected $_aDBField = [
        'iAutoID' => [
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
        ]
    ];

    /**
     * ORM字段结构
     *
     * @var array
     */
    protected $_aORMField = [
        'iAutoID' => [
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
        ]
    ];

    /**
     * 业务SQL语句
     *
     * @var array
     */
    protected $_aBizSQL = [
        'sName' => 'sName like :sName ORDER BY iAutoID asc'
    ];
}
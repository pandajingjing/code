<?php
/**
 * member
 *
 * @namespace member_service\orm
 */
namespace member_service\orm;

use panda\lib\sys\orm;

/**
 * member
 */
class member extends orm
{

    /**
     * Master数据库连接名,在子类中配置
     *
     * @var string
     */
    protected $sMasterDbName = 'member_master';

    /**
     * Slave数据库连接名,在子类中配置
     *
     * @var string
     */
    protected $sSlaveDbName = 'member_slave';

    /**
     * 自增Id
     *
     * @var int
     */
    public $iAutoId;

    /**
     * 用户昵称
     *
     * @var string
     */
    public $sNickName;

    /**
     * 用户真实姓名
     *
     * @var string
     */
    public $sRealName;

    /**
     * 用户渠道
     *
     * @var string
     */
    public $eChannel;

    /**
     * 手机
     *
     * @var string
     */
    public $sMobile;

    /**
     * 微信号
     *
     * @var string
     */
    public $sWeChat;

    /**
     * 注册时间
     *
     * @var int
     */
    public $iRegistrationTime;

    /**
     * 平台总积分
     *
     * @var int
     */
    public $iPlatformScore;

    /**
     * 表名称
     *
     * @var string
     */
    protected $sTblName = 't_member';

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
        'sNickName' => [
            'sType' => 'string',
            'iLength' => 255
        ],
        'sRealName' => [
            'sType' => 'string',
            'iLength' => 255
        ],
        'eChannel' => [
            'sType' => 'string',
            'iLength' => 255
        ],
        'sMobile' => [
            'sType' => 'string',
            'iLength' => 11
        ],
        'sWeChat' => [
            'sType' => 'string',
            'iLength' => 255
        ],
        'eChannel' => [
            'sType' => 'string',
            'iLength' => 255
        ],
        'iRegistrationTime' => [
            'sType' => 'int',
            'bUnsigned' => true
        ],
        'iPlatformScore' => [
            'sType' => 'int',
            'bUnsigned' => true
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
        'sNickName' => [
            'sType' => 'string',
            'iLength' => 255
        ],
        'sRealName' => [
            'sType' => 'string',
            'iLength' => 255
        ],
        'eChannel' => [
            'sType' => 'string',
            'iLength' => 255
        ],
        'sMobile' => [
            'sType' => 'string',
            'iLength' => 11
        ],
        'sWeChat' => [
            'sType' => 'string',
            'iLength' => 255
        ],
        'eChannel' => [
            'sType' => 'string',
            'iLength' => 255
        ],
        'iRegistrationTime' => [
            'sType' => 'int',
            'bUnsigned' => true
        ],
        'iPlatformScore' => [
            'sType' => 'int',
            'bUnsigned' => true
        ]
    ];

    /**
     * 数据库索引顺序
     */
    protected $aDbIndexOrders = [
        'iAutoId',
        'sNickName',
        'sMobile',
        'sRealName'
    ];
}
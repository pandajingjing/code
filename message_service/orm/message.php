<?php
/**
 * session
 *
 * @namespace member_service\orm
 */
namespace member_service\orm;

use panda\lib\sys\orm;

/**
 * session
 */
class session extends orm
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
     * guid
     *
     * @var string
     */
    public $sGuid;

    /**
     * 客户端Ip
     *
     * @var string
     */
    public $sClientIp;

    /**
     * 客户端标示
     *
     * @var string
     */
    public $sUserAgent;

    /**
     * session数据
     *
     * @var array
     */
    public $aData;

    /**
     * 表名称
     *
     * @var string
     */
    protected $sTblName = 't_session';

    /**
     * 主键字段
     *
     * @var string
     */
    protected $sPkField = 'sGuid';

    /**
     * 数据库表结构
     *
     * @var array
     */
    protected $aDbField = [
        'sGuid' => [
            'sType' => 'string',
            'iLength' => 40
        ],
        'sClientIp' => [
            'sType' => 'string',
            'iLength' => 15
        ],
        'sUserAgent' => [
            'sType' => 'string',
            'iLength' => 255
        ],
        'sData' => [
            'sType' => 'string',
            'iLength' => 1023
        ]
    ];

    /**
     * Orm字段结构
     *
     * @var array
     */
    protected $aOrmField = [
        'sGuid' => [
            'sType' => 'string',
            'iLength' => 40
        ],
        'sClientIp' => [
            'sType' => 'string',
            'iLength' => 15
        ],
        'sUserAgent' => [
            'sType' => 'string',
            'iLength' => 255
        ],
        'aData' => [
            'sType' => 'array'
        ]
    ];

    /**
     * 数据库索引顺序
     */
    protected $aDbIndexOrders = [
        'sGuid'
    ];

    /**
     * 保存数据前的钩子
     *
     * {@inheritdoc}
     *
     * @see \panda\lib\sys\orm::beforeSave()
     */
    protected function beforeSave($p_aOrmData)
    {
        if (isset($p_aOrmData['aData'])) {
            $p_aOrmData['sData'] = json_encode($p_aOrmData['aData'], JSON_NUMERIC_CHECK | JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);
            unset($p_aOrmData['aData']);
        }
        return $p_aOrmData;
    }

    /**
     * 在读取数据前的钩子
     *
     * {@inheritdoc}
     *
     * @see \panda\lib\sys\orm::beforeRead()
     */
    protected function beforeRead($p_aDbData)
    {
        if (isset($p_aDbData['sData'])) {
            $p_aDbData['aData'] = json_decode($p_aDbData['sData'], true);
            unset($p_aDbData['sData']);
        }
        return $p_aDbData;
    }
}
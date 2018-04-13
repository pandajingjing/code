<?php

class orm_jxuhome_item extends orm_jxuhome_base
{

    /**
     * 主键ID
     *
     * @var int
     */
    public $iAutoId;

    /**
     * 物品名称
     *
     * @var string
     */
    public $sItemName;

    /**
     * 物品描述
     *
     * @var string
     */
    public $sItemDesc;

    /**
     * 添加时间
     *
     * @var int
     */
    public $iAddTime;

    /**
     * 修改时间
     *
     * @var int
     */
    public $iModTime;

    /**
     * 购买时间
     *
     * @var int
     */
    public $iBuyTime;

    /**
     * 表名称
     *
     * @var string
     */
    protected $sTblName = 't_item_copy';

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
        'sItemName' => [
            'sType' => 'string',
            'iLength' => 255
        ],
        'sItemDesc' => [
            'sType' => 'string',
            'iLength' => 255
        ],
        'iAddTime' => [
            'sType' => 'int',
            'bUnsigned' => true
        ],
        'iModTime' => [
            'sType' => 'int',
            'bUnsigned' => true
        ],
        'iBuyTime' => [
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
        'sItemName' => [
            'sType' => 'string',
            'iLength' => 255
        ],
        'sItemDesc' => [
            'sType' => 'string',
            'iLength' => 255
        ],
        'iAddTime' => [
            'sType' => 'int',
            'bUnsigned' => true
        ],
        'iModTime' => [
            'sType' => 'int',
            'bUnsigned' => true
        ],
        'iBuyTime' => [
            'sType' => 'int',
            'bUnsigned' => true
        ]
    ];
}
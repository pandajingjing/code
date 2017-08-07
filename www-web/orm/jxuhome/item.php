<?php

class orm_jxuhome_item extends orm_jxuhome_base
{

    /**
     * 主键ID
     *
     * @var int
     */
    public $iAutoID;

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
    protected $_sTblName = 't_item_copy';

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
     * ORM字段结构
     *
     * @var array
     */
    protected $_aORMField = [
        'iAutoID' => [
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
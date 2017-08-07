<?php

class orm_jxuhome_base extends lib_sys_orm
{
    /**
     * Master数据库连接名,在子类中配置
     *
     * @var string
     */
    protected $_sMasterDBName = 'jxuhome';
    
    /**
     * Slave数据库连接名,在子类中配置
     *
     * @var string
     */
    protected $_sSlaveDBName = 'jxuhome';
}
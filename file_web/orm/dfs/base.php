<?php

class orm_dfs_base extends lib_sys_orm
{

    /**
     * Master数据库连接名,在子类中配置
     * 
     * @var string
     */
    protected $_sMasterDbName = 'dfs_master';

    /**
     * Slave数据库连接名,在子类中配置
     * 
     * @var string
     */
    protected $_sSlaveDbName = 'dfs_slave';
}
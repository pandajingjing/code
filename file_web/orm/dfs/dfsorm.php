<?php

/**
 * orm dfsorm
 * @package app-file-base_lib_orm
 */
/**
 * orm dfsorm
 *
 * @author jxu
 * @package app-file-base_lib_orm
 */
class orm_dfsorm extends lib
{

    /**
     * Master数据库连接名,在子类中配置
     *
     * @var string
     */
    protected $sMasterDbName = 'dfs_master';

    /**
     * Slave数据库连接名,在子类中配置
     *
     * @var string
     */
    protected $sSlaveDbName = 'dfs_slave';
}
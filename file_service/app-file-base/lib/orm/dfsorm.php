<?php
/**
 * orm dfsorm
 * @package app-file-base_lib_orm
 */
load_lib('/orm/orm');

/**
 * orm dfsorm
 *
 * @author jxu
 * @package app-file-base_lib_orm
 */
class orm_dfsorm extends orm_orm
{

    /**
     * Master数据库连接名,在子类中配置
     *
     * @var string
     */
    protected $_sMasterDBName = 'dfs_master';

    /**
     * Slave数据库连接名,在子类中配置
     *
     * @var string
     */
    protected $_sSlaveDBName = 'dfs_slave';
}
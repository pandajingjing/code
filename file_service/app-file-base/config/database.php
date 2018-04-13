<?php
$G_CONFIG['database']['dfs_master'] = array(
    'sDSN' => 'mysql:host=192.168.10.33;dbname=dfs_db',
    'sUsername' => 'devadmin',
    'sUserpwd' => 'devadmin',
    'aInitsql' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
); // 线上配置
$G_CONFIG['database']['dfs_slave'] = array(
    'sDSN' => 'mysql:host=192.168.10.33;dbname=dfs_db',
    'sUsername' => 'readonly',
    'sUserpwd' => 'readonly',
    'aInitsql' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
); //线上配置
<?php

/**
 * lib_data_pdo
 *
 * 扩展PDO
 *
 * @package lib_data
 */
namespace panda\lib\data;

/**
 * lib_data_pdo
 *
 * 扩展PDO
 */
class PandaPDO extends \PDO
{

    /**
     * 默认返回关联数组格式
     *
     * @var int
     */
    private $_iDefaultFetchMode = \PDO::FETCH_ASSOC;

    /**
     * 准备执行计划
     *
     * @param string $p_sSQL            
     * @param array $p_aDriverOption            
     * @return object
     */
    function prepare($p_sSQL, $p_aDriverOption = [])
    {
        $oStatement = parent::prepare($p_sSQL, $p_aDriverOption);
        if ($oStatement instanceof \PDOStatement) {
            $oStatement->setFetchMode($this->_iDefaultFetchMode);
        }
        return $oStatement;
    }
}
<?php
/**
 * pandapdo
 *
 * 扩展PDO
 * @namespace panda\lib\data
 */
namespace panda\lib\data;

/**
 * pandapdo
 *
 * 扩展PDO
 */
class pandapdo extends \PDO
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
     * @param string $p_sSql            
     * @param array $p_aDriverOption            
     * @return object
     */
    function prepare($p_sSql, $p_aDriverOption = [])
    {
        $oStatement = parent::prepare($p_sSql, $p_aDriverOption);
        if ($oStatement instanceof \PDOStatement) {
            $oStatement->setFetchMode($this->_iDefaultFetchMode);
        }
        return $oStatement;
    }
}
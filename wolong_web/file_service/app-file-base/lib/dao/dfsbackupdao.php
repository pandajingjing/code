<?php
/**
 * dao dfsbackupdao
 * @package app-file-base_lib_dao
 */
load_lib('/dao/dao');
/**
 * dao dfsbackupdao
 * 
 * @author jxu
 * @package app-file-base_lib_dao
 */
class dao_dfsbackupdao extends dao_dao{

	/**
	 * 获取主键数据
	 * @param int $p_iPKID
	 * @return array/null
	 */
	static function getDetail($p_iPKID){}

	/**
	 * 新增数据
	 * @param array $p_aData
	 * @return int/false
	 */
	static function addData($p_aData){
		load_lib('/orm/dfsorm');
		$oORM = new orm_dfsorm('t_dfs_backup');
		$oORM->loadSource($p_aData);
		return $oORM->addData();
	}

	/**
	 * 更新数据
	 * @param array $p_aData
	 * @return int/false
	 */
	static function updData($p_aData){}

	/**
	 * 删除数据,实际为更改db状态位
	 * @param int $p_iPKID
	 * @return int/false
	 */
	static function delData($p_iPKID){
		load_lib('/orm/dfsorm');
		$oORM = new orm_dfsorm('t_dfs_backup');
		$oORM->iAutoID = $p_iPKID;
		return $oORM->delData();
	}

	/**
	 * 获取列表
	 * @param array $p_aParam
	 * @param string $p_sOrder
	 * @return array
	 */
	static function getList($p_aParam, $p_sOrder){
		load_lib('/orm/dfsorm');
		$oORM = new orm_dfsorm('t_dfs_backup');
		$oORM->sFileKey = $p_aParam['sFileKey'];
		$oORM->addFilter('sFileKey', '=', $p_aParam['sFileKey']);
		$oORM->setOrder($p_sOrder);
		return $oORM->getList();
	}

	/**
	 * 获取分页列表
	 * @param array $p_aParam
	 * @param int $p_iStart
	 * @param int $p_iLimit
	 * @param string $p_sOrder
	 * @return array
	 */
	static function getPageList($p_aParam, $p_iStart, $p_iLimit, $p_sOrder){}

	/**
	 * 获取主键列表
	 * @param array $p_aPKIDs
	 * @return array
	 */
	static function getPKIDList($p_aPKIDs){}

	/**
	 * 更新主键列表
	 * @param array $p_aData
	 * @param array $p_aPKIDs
	 * @return int/false
	 */
	static function updPKIDData($p_aData, $p_aPKIDs){}

	/**
	 * 获取数量
	 * @param array $p_aParam
	 * @return int
	 */
	static function getCnt($p_aParam){}
}
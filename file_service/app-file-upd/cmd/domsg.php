<?php
/**
 * domsg controller
 * @package app-file-upd_cmd
 */
load_lib('/cmd/controller');

/**
 * domsg controller
 *
 * @author jxu
 * @package app-file-upd_cmd
 */
class domsgcontroller extends cmd_controller
{

    /**
     * 入口方法
     */
    function doRequest()
    {
        $this->stdOut('app-file-upd cmd domsg');
    }

    /**
     * 保存文件信息
     *
     * @param array $p_aParam            
     * @return boolean
     */
    function saveInfo($p_aParam)
    {
        load_lib('/bll/msg');
        $oBll = new bll_msg();
        $p_aParam['iFromIP'] = ip2long($p_aParam['sFromIP']);
        unset($p_aParam['sFromIP']);
        $oBll->saveInfo($p_aParam);
        $this->stdOut('domsg::saveInfo' . "\t" . print_r($p_aParam, true));
        return true;
    }

    /**
     * 保存图片文件信息
     *
     * @param array $p_aParam            
     * @return boolean
     */
    function saveImageInfo($p_aParam)
    {
        load_lib('/bll/msg');
        $oBll = new bll_msg();
        $oBll->saveImageInfo($p_aParam);
        $this->stdOut('domsg::saveImageInfo' . "\t" . print_r($p_aParam, true));
        return true;
    }

    /**
     * 删除文件
     *
     * @param unknown_type $p_aParam            
     */
    function delFile($p_aParam)
    {
        load_lib('/bll/msg');
        $oBll = new bll_msg();
        $oBll->delFile($p_aParam);
        $this->stdOut('domsg::delFile' . "\t" . print_r($p_aParam, true));
        return true;
    }

    /**
     * 备份文件
     *
     * @param array $p_aParam            
     * @throws Exception
     * @return boolean
     */
    function backupFile($p_aParam)
    {
        load_lib('/bll/msg');
        $oBll = new bll_msg();
        $this->stdOut('domsg::backupFile' . "\t" . print_r($p_aParam, true));
        return $oBll->backupFile($p_aParam);
    }
}
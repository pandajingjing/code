<?php
/**
 * bll file
 * @package app-file-base_lib_bll
 */
load_lib('/bll/bll');

/**
 * bll file
 *
 * @author jxu
 * @package app-file-base_lib_bll
 */
class bll_file extends bll_bll
{

    /**
     * 文件服务支持的业务
     * 5-10个字符
     *
     * @var array
     */
    private $_aBizs = array(
        '',
        'banner',
        'avatar',
        'house',
        'document',
        'idcard',
        'secret',
        'ahb',
        'project',
        'export',
        'interact',
        'agreement'
    );

    /**
     * 当前提供服务的域名
     *
     * @var string
     */
    protected $_sDomain;

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 检查使用方是否是允许的域名
     *
     * @param string $p_sReferer            
     * @param string $p_sAgent            
     * @return true/string
     */
    function chkAllowedDomain($p_sReferer, $p_sAgent = '')
    {
        if (null === $p_sReferer) {
            if (preg_match('/flash/i', $p_sAgent)) {
                $this->_sDomain = 'flash.ipo.com';
            } else {
                $this->_sDomain = 'unknown';
                $this->addLog(array(
                    'referer' => $p_sReferer,
                    'agent' => $p_sAgent
                ), 'unknow_domain');
            }
        } else {
            $this->_sDomain = parse_url($p_sReferer, PHP_URL_HOST);
        }
        load_lib('/dao/dfsdao');
        $aAllowedDomain = dao_dfsdao::getConfig('aAllowedDomain');
        foreach ($aAllowedDomain as $sDomainPattern) {
            if (1 === preg_match($sDomainPattern, $this->_sDomain)) {
                return true;
                break;
            }
        }
        load_lib('/util/error');
        $this->addLog($this->mkUpdErr(util_error::tagError('Domain', util_error::TYPE_INVALID), $this->_sDomain), 'upd_error');
        return util_error::tagError('Domain', util_error::TYPE_INVALID);
    }

    /**
     * 给文件分配存储设备
     *
     * @return int
     */
    protected function dispatchUploadHostID()
    {
        load_lib('/dao/dfsdao');
        $aHostConfig = dao_dfsdao::getConfig('aStorageHost', 'upload');
        return $this->dispatchHostID($aHostConfig);
    }

    /**
     * 得到图片的扩展信息
     *
     * @param string $p_sTmpName            
     * @return array
     */
    protected function getImageInfo($p_sTmpName)
    {
        $aImageInfo = getimagesize($p_sTmpName);
        return array(
            'iWidth' => $aImageInfo[0],
            'iHeight' => $aImageInfo[1],
            'iChannels' => isset($aImageInfo['channels']) ? $aImageInfo['channels'] : '0',
            'iBits' => isset($aImageInfo['bits']) ? $aImageInfo['bits'] : '0'
        );
    }

    /**
     * 保存图片的业务
     *
     * @param string $p_sKey            
     * @param string $p_sBiz            
     */
    protected function saveBiz($p_sKey, $p_sBiz)
    {
        if ('' == $p_sBiz) {} else {
            load_lib('/dao/dfsbizdao');
            $aBizs = dao_dfsbizdao::getList(array(
                'sFileKey' => $p_sKey
            ), '');
            $bGet = false;
            foreach ($aBizs as $aBiz) {
                if ($p_sBiz == $aBiz['sBiz']) {
                    $bGet = true;
                    break;
                }
            }
            if ($bGet) {} else {
                dao_dfsbizdao::addData(array(
                    'sFileKey' => $p_sKey,
                    'sBiz' => $p_sBiz
                ));
            }
        }
    }

    /**
     * 获取原始文件的存储路径
     *
     * @param string $p_sKey            
     * @param int $p_iHostID            
     * @return string/false
     */
    protected function getOriginalPath($p_sKey, $p_iHostID)
    {
        $sSubDir = $this->dispatchFile($p_sKey);
        load_lib('/dao/dfsdao');
        $sDesBaseDir = dao_dfsdao::getConfig('sRawDir', 'storage');
        $sDesDir = $sDesBaseDir . DIRECTORY_SEPARATOR . $p_iHostID . $sSubDir;
        $sDesFilename = $sDesDir . $p_sKey;
        if (file_exists($sDesFilename)) {
            return $sDesFilename;
        } else {
            $aErrHostIDs = array(
                $p_iHostID
            );
            load_lib('/dao/dfsbackupdao');
            $aFileBackup = dao_dfsbackupdao::getList(array(
                'sFileKey' => $p_sKey
            ), '');
            load_lib('/bll/mq/base');
            $oBll = new bll_mq_base();
            if (empty($aFileBackup)) {
                $oBll->sendMsg(array(
                    'iBID' => $oBll::BID_APP_FILE_DELETE,
                    'sController' => 'domsgcontroller',
                    'sHandle' => 'delFile',
                    'aData' => array(
                        'sFileKey' => $p_sKey
                    ),
                    'iRetry' => 10
                ));
                return false;
            } else {
                foreach ($aFileBackup as $aBackup) {
                    if ($aBackup['iHostID'] == $p_iHostID) { // 当前存储已经损坏
                        continue;
                    }
                    $sDesDir = $sDesBaseDir . DIRECTORY_SEPARATOR . $aBackup['iHostID'] . $sSubDir;
                    $sDesFilename = $sDesDir . $p_sKey;
                    if (file_exists($sDesFilename)) {
                        dao_dfsdao::updData(array(
                            'sFileKey' => $p_sKey,
                            'iHostID' => $aBackup['iHostID']
                        )); // 更新主要存储
                        $oBll->sendMsg(array(
                            'iBID' => $oBll::BID_APP_FILE_BACKUP,
                            'sController' => 'domsgcontroller',
                            'sHandle' => 'backupFile',
                            'aData' => array(
                                'sFileKey' => $p_sKey,
                                'iHostID' => $aBackup['iHostID'],
                                'aErrHostIDs' => $aErrHostIDs
                            ),
                            'iRetry' => 10
                        )); // 主要存储损坏需要重新备份
                        return $sDesFilename;
                    } else {
                        $aErrHostIDs[] = $aBackup['iHostID'];
                    }
                }
                $oBll->sendMsg(array(
                    'iBID' => $oBll::BID_APP_FILE_DELETE,
                    'sController' => 'domsgcontroller',
                    'sHandle' => 'delFile',
                    'aData' => array(
                        'sFileKey' => $p_sKey
                    ),
                    'iRetry' => 10
                )); // 所有备份均损坏需要删除相关数据
                return false;
            }
        }
    }

    /**
     * 给文件分配路径
     *
     * @param string $p_sFileKey            
     * @return string
     */
    protected function dispatchFile($p_sFileKey)
    {
        $iDispatchKey = abs(crc32($p_sFileKey));
        $sDir = '';
        while ($iDispatchKey > 0) {
            $sSubDir = $iDispatchKey % 100;
            $sDir = $sSubDir . DIRECTORY_SEPARATOR . $sDir;
            $iDispatchKey = intval($iDispatchKey / 100);
        }
        return DIRECTORY_SEPARATOR . $sDir;
    }

    /**
     * 分配存储设备
     *
     * @param array $p_aHostCfg            
     * @return int
     */
    protected function dispatchHostID($p_aHostCfg)
    {
        $aRotation = array();
        foreach ($p_aHostCfg as $aHost) {
            $aRotation = array_merge($aRotation, array_fill(0, $aHost['iWeight'], $aHost['iHostID']));
        }
        return $aRotation[rand(0, count($aRotation) - 1)];
    }

    /**
     * 判断文件是否完整
     *
     * @param string $p_sPath            
     * @param string $p_sExt            
     * @return true/false
     */
    protected function isCompleteFile($p_sPath, $p_sExt)
    {
        switch ($p_sExt) {
            case 'jpg':
                $oHandle = fopen($p_sPath, 'rb');
                $iSize = filesize($p_sPath) - 2;
                fseek($oHandle, $iSize);
                $sData = fread($oHandle, $iSize);
                if (bin2hex($sData) == 'ffd9') {
                    return true;
                } else {
                    return false;
                }
                break;
            default:
                return true;
                break;
        }
    }

    /**
     * 判断业务是否合法
     *
     * @param string $p_sBiz            
     * @return true/false
     */
    protected function chkBiz($p_sBiz)
    {
        if (in_array($p_sBiz, $this->_aBizs)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取跨域的域名
     *
     * @param int $p_iDomainIndex            
     * @return string
     */
    function getCrossDomain($p_iDomainIndex)
    {
        load_lib('/dao/dfsdao');
        $aCrossDomain = dao_dfsdao::getConfig('aCrossDomain', 'upload');
        return isset($aCrossDomain[$p_iDomainIndex]) ? $aCrossDomain[$p_iDomainIndex] : '';
    }

    /**
     * 获取业务配置
     *
     * @param string $p_sBiz            
     * @param array $p_aConfig            
     * @return mix
     */
    protected function getBizConfig($p_sBiz, $p_aConfig)
    {
        return isset($p_aConfig[$p_sBiz]) ? $p_aConfig[$p_sBiz] : $p_aConfig[''];
    }

    protected function mkUpdErr($p_sTag, $p_mValue)
    {
        load_lib('/sys/variable');
        $oVar = sys_variable::getInstance();
        return array(
            'sTag' => $p_sTag,
            'mValue' => $p_mValue,
            'aServer' => $oVar->getParams('server')
        );
    }
}
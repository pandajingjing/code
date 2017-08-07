<?php

/**
 * bll_file_base
 * @package app-file-service_bll_file
 */

/**
 * bll_file_base
 *
 * @author jxu
 * @package app-file-service_bll_file
 */
class bll_file_base extends lib_sys_bll
{

    /**
     * 允许跨域的域名,用于crossdomain.xml直接输出
     *
     * @var array
     */
    protected $_aCrossDomain = [
        '*.jxulife.com'
    ];

    /**
     * 允许跨域的域名,用于头信息里对ajax的跨域请求
     *
     * @var array
     */
    protected $_aCrossDomainPreg = [
        '/\.jxulife\.com$/i'
    ];

    /**
     * 允许访问服务的域名
     *
     * @var array
     */
    protected $_aAllowedDomain = [
        '/jxulife\.com$/i' => 'jxulife.com'
    ];

    /**
     * 文件服务支持的业务
     * 5-10个字符
     *
     * @var array
     */
    protected $_aAllowdBizs = [
        'jxulife.com' => [
            'avatar',
            'document',
            'idcard',
            'painting'
        ]
    ];

    /**
     * 上传业务配置
     *
     * @var array
     */
    protected $_aUpdConfig = [
        'jxulife.com' => [
            'avatar' => [
                'aUpdTypes' => [
                    'jpg',
                    'png'
                ],
                'aUpdSize' => [
                    'iMin' => 1,
                    'iMax' => 15728640
                ]
            ]
        ]
    ];

    /**
     * 展示业务配置
     *
     * @var array
     */
    protected $_aViewConfig = [
        'jxulife.com' => [
            'avatar' => [
                'bOriginal' => false,
                'aResizeConfig' => [
                    '50x50' => [
                        [
                            'aDefault' => [
                                'mWatermark' => [
                                    'sFilePath' => '',
                                    'aEdge' => [
                                        'iRight' => 20,
                                        'iDown' => 20
                                    ]
                                ],
                                'bThumbnail' => true,
                                'sMode' => 'zoom', // cut
                                'sZoomMode' => 'scale', // fill
                                'sZoomScaleMode' => 'mix'
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ];

    /**
     * 头信息是flash时的来源域名
     *
     * @var string
     */
    const FLASH_DOMAIN = 'flash.jxulife.com';

    /**
     * 根据头信息
     *
     * @var string
     */
    const SELF_AGENT_PATTERN = '/flash/i';

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 检查使用方是否是允许的域名
     *
     * @param string $p_sFromURL            
     * @param string $p_sAgent            
     * @return array
     */
    function chkAllowedDomain($p_sFromURL, $p_sAgent)
    {
        if ('' == $p_sFromURL) {
            if (preg_match('/flash/i', $p_sAgent)) {
                $sDomain = self::FLASH_DOMAIN;
            } else {
                $sDomain = 'direct';
                $this->addLog('referer domain', var_export([
                    'referer' => $p_sFromURL,
                    'agent' => $p_sAgent
                ], true), 'unknow_domain');
            }
        } else {
            $sDomain = parse_url($p_sFromURL, PHP_URL_HOST);
        }
        foreach ($this->_aAllowedDomain as $sDomainPattern => $sConfigKey) {
            if (1 === preg_match($sDomainPattern, $sDomain)) {
                return $this->returnOne($sConfigKey);
                break;
            }
        }
        util_error::initError();
        util_error::addBizError('domain', util_error::TYPE_INVALID, $sDomain);
        return $this->returnErrors(util_error::getErrors());
    }

    /**
     * 返回跨域配置
     *
     * @param boolean $p_bPreg            
     * @return array
     */
    function getCrossDomain($p_bPreg = true)
    {
        if ($p_bPreg) {
            return $this->returnList($this->_aCrossDomainPreg, count($this->_aCrossDomainPreg));
        } else {
            return $this->returnList($this->_aCrossDomain, count($this->_aCrossDomain));
        }
    }

    /**
     * 判断业务是否合法
     *
     * @param string $p_sDomainKey            
     * @param string $p_sBiz            
     * @return true/false
     */
    protected function chkBiz($p_sDomainKey, $p_sBiz)
    {
        if (in_array($p_sBiz, $this->_aAllowdBizs[$p_sDomainKey])) {
            return true;
        } else {
            return false;
        }
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
     * 获取跨域的域名
     *
     * @param int $p_iDomainIndex            
     * @return string
     */
    function getCrossDomain1($p_iDomainIndex)
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
            'aServer' => $oVar->getAllParam('server')
        );
    }
}
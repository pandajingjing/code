<?php
/**
 * debugger
 *
 * 系统调试器类
 * @namespace panda\lib\sys
 */
namespace panda\lib\sys;

use panda\util\sys\cookie;

/**
 * debugger
 *
 * 系统调试器类
 */
class debugger
{

    /**
     * 系统debug实例
     *
     * @var object
     */
    private static $_oInstance = null;

    /**
     * 需要输出的信息
     *
     * @var array
     */
    private $_aMessages = [];

    /**
     * 调试信息
     *
     * @var array
     */
    private $_aDebugInfo = [];

    /**
     * 是否开启debug
     *
     * @var boolean
     */
    private $_bolNeedDebug = false;

    /**
     * 系统变量
     *
     * @var object
     */
    private $_oVari;

    /**
     * get开关值
     *
     * @var int
     */
    const GET_SWITCH_VALUE = 5;

    /**
     * get开关名称
     *
     * @var string
     */
    const GET_SWITCH_NAME = 'debug';

    /**
     * cookie开关值
     *
     * @var int
     */
    const COOKIE_SWITCH_VALUE = 20;

    /**
     * cookie开关名称
     *
     * @var string
     */
    const COOKIE_SWITCH_NAME = 'debug';

    /**
     * cookie开关时长
     *
     * @var int
     */
    const COOKIE_SWITCH_LIFETIME = 60;

    /**
     * 构造函数
     *
     * @return void
     */
    private function __construct()
    {
        $this->_oVari = variable::getInstance();
        if ($this->_oVari->getConfig('bDebug', 'debugger')) { // 系统配置
            $aAllowIPs = $this->_oVari->getConfig('aAllowedIpList', 'debugger'); // ip过滤
            $sIP = $this->_oVari->getParam('CLIENTIP', 'server');
            $bCanIP = false;
            foreach ($aAllowIPs as $sPattern) {
                if (preg_match($sPattern, $sIP)) {
                    $bCanIP = true;
                    break;
                }
            }
            if ($bCanIP) {
                $iCanCookie = $this->_oVari->getParam(self::COOKIE_SWITCH_NAME, 'cookie'); // cookie过滤
                $iCanGet = $this->_oVari->getParam(self::GET_SWITCH_NAME, 'get'); // get过滤
                $iExpireTime = $this->_oVari->getRealTime() + self::COOKIE_SWITCH_LIFETIME;
                if (null === $iCanCookie) {
                    if (self::GET_SWITCH_VALUE == $iCanGet) {
                        cookie::setCookie(self::COOKIE_SWITCH_NAME, self::COOKIE_SWITCH_VALUE, $iExpireTime);
                        $this->_bolNeedDebug = true;
                    } else {
                        $this->_bolNeedDebug = false;
                    }
                } else {
                    if (self::COOKIE_SWITCH_VALUE == $iCanCookie) {
                        if (null === $iCanGet) {
                            $this->_bolNeedDebug = true;
                            cookie::setCookie(self::COOKIE_SWITCH_NAME, self::COOKIE_SWITCH_VALUE, $iExpireTime);
                        } else {
                            if (self::GET_SWITCH_VALUE == $iCanGet) {
                                $this->_bolNeedDebug = true;
                                cookie::setCookie(self::COOKIE_SWITCH_NAME, self::COOKIE_SWITCH_VALUE, $iExpireTime);
                            } else {
                                $this->_bolNeedDebug = false;
                                cookie::delCookie(self::COOKIE_SWITCH_NAME);
                            }
                        }
                    } else {
                        $this->_bolNeedDebug = false;
                    }
                }
            } else {
                $this->_bolNeedDebug = false;
            }
        } else {
            $this->_bolNeedDebug = false;
        }
    }

    /**
     * 克隆函数
     *
     * @return void
     */
    private function __clone()
    {}

    /**
     * 获取实例
     *
     * @return object
     */
    static function getInstance()
    {
        if (! (self::$_oInstance instanceof self)) {
            self::$_oInstance = new self();
        }
        return self::$_oInstance;
    }

    /**
     * 添加输出信息
     *
     * @param string $p_sMsg            
     * @param boolean $p_bIsHTML            
     * @return void
     */
    function showMsg($p_sMsg, $p_bIsHTML = false)
    {
        if ($this->_bolNeedDebug) {
            $this->_aMessages[] = [
                'bIsHTML' => $p_bIsHTML,
                'sMsg' => $p_sMsg
            ];
        }
    }

    /**
     * 开始模块调试信息
     *
     * @param string $p_sModule            
     * @return void
     */
    function startDebug($p_sModule)
    {
        if ($this->_bolNeedDebug) {
            $this->_aDebugInfo[$p_sModule] = [
                'fStartTime' => $this->_oVari->getRealTime(true),
                'iStartMemory' => $this->_getMemoryUsage()
            ];
        }
    }

    /**
     * 结束模块调试信息
     *
     * @param string $p_sModule            
     * @return void
     */
    function stopDebug($p_sModule = '')
    {
        if ($this->_bolNeedDebug) {
            $this->_aDebugInfo[$p_sModule]['fEndTime'] = $this->_oVari->getRealTime(true);
            $this->_aDebugInfo[$p_sModule]['iEndMemory'] = $this->_getMemoryUsage();
        }
    }

    /**
     * 获取输出信息
     *
     * @return array
     */
    function getMsgs()
    {
        return $this->_aMessages;
    }

    /**
     * 获取调试信息
     *
     * @return array
     */
    function getDebugInfo()
    {
        return $this->_aDebugInfo;
    }

    /**
     * 获取内存使用量
     *
     * @return int
     */
    private function _getMemoryUsage()
    {
        return function_exists('memory_get_usage') ? memory_get_usage() : 0;
    }

    /**
     * 是否能够debug
     *
     * @return true|false
     */
    function canDebug()
    {
        return $this->_bolNeedDebug;
    }

    /**
     * 获取系统参数
     *
     * @return array
     */
    function getAllParam()
    {
        return [
            'aPost' => $this->_oVari->getAllParam('post'),
            'aGet' => $this->_oVari->getAllParam('get'),
            'aRouter' => $this->_oVari->getAllParam('router'),
            'aCookie' => $this->_oVari->getAllParam('cookie'),
            'aServer' => $this->_oVari->getAllParam('server'),
            'aConfig' => $this->_oVari->getAllParam('config')
        ];
    }
}
<?php
/**
 * session
 *
 * @namespace member_service\bll
 */
namespace member_service\bll;

use panda\lib\sys\bll;
use member_service\orm\session as ormsession;

/**
 * session
 */
class session extends bll
{

    /**
     * 用户Id
     *
     * @var string
     */
    const KEY_MEMBER_ID = 'sMemberId';

    /**
     * 用户Id过期时间
     *
     * @var int
     */
    const LIFETIME_MEMBER_ID = 1800;

    /**
     * 用户名
     *
     * @var string
     */
    const KEY_MEMBER_NICKNAME = 'sNickName';

    /**
     * 用户名过期时间
     *
     * @var integer
     */
    const LIFETIME_MEMBER_NICKNAME = 31536000;

    /**
     * session数据
     *
     * @var array
     */
    private $_aSessionData = [];

    /**
     * 当前guid
     */
    private $_sGuid = '';

    /**
     * 当前时间
     *
     * @var int
     */
    private $_iTimeNow = 0;

    /**
     * 客户端Ip
     *
     * @var string
     */
    private $_sClientIp = '';

    /**
     * 用户头信息
     *
     * @var string
     */
    private $_sUserAgent = '';

    /**
     * 类名
     *
     * @var string
     */
    private $_sClassName = '';

    /**
     * 构造函数
     *
     * @param string $p_sGuid            
     * @param int $p_iTimeNow            
     */
    function __construct($p_sGuid, $p_iTimeNow)
    {
        parent::__construct();
        $this->_sGuid = $p_sGuid;
        $this->_iTimeNow = $p_iTimeNow;
        $this->_sClassName = get_class($this);
    }

    /**
     * 设置客户端Ip
     *
     * @param string $p_sClientIp            
     */
    function setClientIp($p_sClientIp)
    {
        $this->_sClientIp = $p_sClientIp;
    }

    /**
     * 设置客户端标示
     *
     * @param string $p_sUserAgent            
     */
    function setUserAgent($p_sUserAgent)
    {
        $this->_sUserAgent = $p_sUserAgent;
    }

    /**
     * 加载session数据
     *
     * @return void
     */
    function load()
    {
        $oOrmSession = new ormsession();
        $oOrmSession->sGuid = $this->_sGuid;
        $mResult = $oOrmSession->getDetail();
        if ($mResult == null) {
            $this->_aSessionData = [];
        } else {
            $this->_aSessionData = $mResult['aData'];
        }
    }

    /**
     * 获取session数据
     *
     * @param string $p_sKey            
     * @return mix
     */
    function get($p_sKey)
    {
        if (isset($this->_aSessionData[$p_sKey])) {
            if ($this->_iTimeNow > $this->_aSessionData[$p_sKey]['iExpireTime'] ?? 0) {
                $this->showDebugMsg($this->_sClassName . '->Get: ' . $p_sKey . '|false');
                unset($this->_aSessionData[$p_sKey]);
                return null;
            } else {
                $this->showDebugMsg($this->_sClassName . '->Get: ' . $p_sKey . '|' . var_export($this->_aSessionData[$p_sKey]['mValue'], true));
                $this->showDebugMsg($this->_sClassName . '->Info: Key=>' . $p_sKey . ' Create=>' . date('Y-m-d H:i:s', $this->_aSessionData[$p_sKey]['iCreateTime']) . ' Expire=>' . date('Y-m-d H:i:s', $this->_aSessionData[$p_sKey]['iExpireTime']));
                return $this->_aSessionData[$p_sKey]['mValue'];
            }
        } else {
            $this->showDebugMsg($this->_sClassName . '->Get: ' . $p_sKey . '|false');
            return null;
        }
    }

    /**
     * 设置session数据
     *
     * @param string $p_sKey            
     * @param mix $p_mData            
     * @param int $p_iLifeTime            
     * @return void
     */
    function set($p_sKey, $p_mData, $p_iLifeTime)
    {
        $aData = $this->_implodeSession($p_mData, $p_iLifeTime);
        $this->_aSessionData[$p_sKey] = $aData;
        $this->showDebugMsg($this->_sClassName . '->Set: ' . var_export($aData, true));
    }

    /**
     * 清除session数据
     *
     * @param string $p_sKey            
     * @return void
     */
    function clear($p_sKey)
    {
        unset($this->_aSessionData[$p_sKey]);
        $this->showDebugMsg($this->_sClassName . '->Clear: true');
    }

    /**
     * 组合session数据
     *
     * @param mix $p_mData            
     * @param int $p_iLifeTime            
     * @return array
     */
    private function _implodeSession($p_mData, $p_iLifeTime)
    {
        return [
            'mValue' => $p_mData,
            'iCreateTime' => $this->_iTimeNow,
            'iExpireTime' => $this->_iTimeNow + $p_iLifeTime
        ];
    }

    /**
     * 保存session
     *
     * @return void
     */
    function save()
    {
        $oOrmSession = new ormsession();
        $oOrmSession->sGuid = $this->_sGuid;
        $oOrmSession->aData = $this->_aSessionData;
        $oOrmSession->sClientIp = $this->_sClientIp;
        $oOrmSession->sUserAgent = $this->_sUserAgent;
        $oOrmSession->addData(true);
    }
}
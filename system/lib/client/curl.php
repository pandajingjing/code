<?php
/**
 * curl
 *
 * curl客户端
 * @namespace panda\lib\client
 */
namespace panda\lib\client;

use panda\lib\sys\debugger;

/**
 * curl
 *
 * curl客户端
 */
class curl
{

    /**
     * 客户端连接
     *
     * @var object
     */
    private $_oResource = null;

    /**
     * CURL信息
     *
     * @var array
     */
    private $_aInfo = [];

    /**
     * 服务器返回的文本信息
     *
     * @var string
     */
    private $_sContent = '';

    /**
     * 最大重定向次数
     *
     * @var int
     */
    private $_iMaxRedirs = 5;

    /**
     * 尝试连接超时时间
     *
     * @var int
     */
    const CONNECT_TIME_OUT = 1000;

    /**
     * 执行超时时间
     *
     * @var int
     */
    const TIME_OUT = 1000;

    /**
     * 构造函数
     *
     * 设置客户端请求的url,捕获返回内容,建立连接和返回内容的超时时间
     *
     * @param string $p_sUrl            
     * @return void
     */
    function __construct($p_sUrl = '')
    {
        $this->_oResource = curl_init($p_sUrl);
        $this->setOption(CURLOPT_RETURNTRANSFER, true);
        $this->setOption(CURLOPT_FILETIME, true);
        $this->setOption(CURLOPT_MAXREDIRS, $this->_iMaxRedirs);
        $this->setOption(CURLOPT_SAFE_UPLOAD, true);
        $this->setOption(CURLOPT_FOLLOWLOCATION, true);
        $this->setConnectTimeOut();
        $this->setTimeOut();
        // $sCookieFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cookie';
        // $this->setOption(CURLOPT_COOKIEJAR, $sCookieFile);
        // $this->setOption(CURLOPT_COOKIEFILE, $sCookieFile);
    }

    /**
     * 析构函数
     *
     * 主动关闭连接
     *
     * @return void
     */
    function __destruct()
    {
        curl_close($this->_oResource);
    }

    /**
     * 设置客户端选项
     *
     * @param int $p_iName            
     * @param mix $p_mValue            
     * @return true|false
     */
    function setOption($p_iName, $p_mValue)
    {
        return curl_setopt($this->_oResource, $p_iName, $p_mValue);
    }

    /**
     * 设置要访问的URL
     *
     * @param string $p_sUrl            
     * @return true|false
     */
    function setUrl($p_sUrl)
    {
        return $this->setOption(CURLOPT_URL, $p_sUrl);
    }

    /**
     * 设置连接超时时间
     *
     * @param int $p_iTime            
     * @return true|false
     */
    function setConnectTimeOut($p_iTime = self::CONNECT_TIME_OUT)
    {
        return $this->setOption(CURLOPT_CONNECTTIMEOUT_MS, $p_iTime);
    }

    /**
     * 设置执行超时时间
     *
     * @param int $p_iTime            
     * @return true|false
     */
    function setTimeOut($p_iTime = self::TIME_OUT)
    {
        return $this->setOption(CURLOPT_TIMEOUT_MS, $p_iTime);
    }

    /**
     * 设置来源网页
     *
     * @param string $p_sReferer            
     * @return true|false
     */
    function setReferer($p_sReferer)
    {
        return $this->setOption(CURLOPT_REFERER, $p_sReferer);
    }

    /**
     * 让客户端使用post方式提交数据
     *
     * @param boolean $p_bPost            
     * @return true|false
     */
    function setPost($p_bPost = true)
    {
        return $this->setOption(CURLOPT_POST, $p_bPost);
    }

    /**
     * 设置Post参数
     *
     * @param array $p_aParam            
     * @return true|false
     */
    function setPostParams($p_aParam)
    {
        if (is_array($p_aParam)) {
            return $this->setOption(CURLOPT_POSTFIELDS, http_build_query($p_aParam));
        } else {
            return false;
        }
    }

    /**
     * 发送请求
     *
     * @return true|false
     */
    function executeUrl()
    {
        $mResult = curl_exec($this->_oResource);
        $sClassName = get_class($this);
        if (false === $mResult) {
            debugger::getInstance()->showMsg($sClassName . ': error message: ' . $this->getErrMsg());
            return false;
        } else {
            $this->_aInfo = curl_getinfo($this->_oResource);
            debugger::getInstance()->showMsg($sClassName . ': execution information: ' . var_export($this->_aInfo, true));
            $this->_sContent = $mResult;
            if (200 == $this->_aInfo['http_code']) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * 获取服务端返回信息
     *
     * @return string
     */
    function getContent()
    {
        return $this->_sContent;
    }

    /**
     * 得到版本信息
     *
     * @param int $p_iAge            
     * @return array
     */
    function getVersion($p_iAge = 0)
    {
        return curl_version($p_iAge);
    }

    /**
     * 得到CURL执行信息
     *
     * @return array
     */
    function getInfo()
    {
        if (empty($this->_aInfo)) {
            $this->_aInfo = curl_getinfo($this->_oResource);
        }
        return $this->_aInfo;
    }

    /**
     * 得到错误编号
     *
     * @return int
     */
    function getErrNo()
    {
        return curl_errno($this->_oResource);
    }

    /**
     * 得到错误信息
     *
     * @return string
     */
    function getErrMsg()
    {
        $sErr = curl_error($this->_oResource);
        return $sErr;
    }
}

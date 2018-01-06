<?php
/**
 * wechat
 *
 * @namespace api_service\bll
 */
namespace api_service\bll;

use panda\lib\sys\bll;
use panda\util\error;

/**
 * wechat
 */
class wechat extends bll
{
    // appid: wx409cd62da08d6c50
    // appsecret: 7b5da5c86bbf73a645b14d91dd90d0fa
    // EncodingAESKey: cCFX9C0rLb3IKtpitAAdUl0YRUKteTwiZcQ3AzSKj2x
    
    /**
     * 验证请求是否是微信发出
     *
     * @param string $p_sSignature            
     * @param int $p_iTimeStamp            
     * @param int $p_iNonce            
     * @return array
     */
    function verify($p_sSignature, $p_iTimeStamp, $p_iNonce)
    {
        $sToken = $this->getConfig('sToken', 'wechat');
        $aHash = [
            $sToken,
            $p_iTimeStamp,
            $p_iNonce
        ];
        sort($aHash, SORT_STRING);
        $sHash = sha1(implode($aHash));
        if ($sHash == $p_sSignature) {
            return $this->returnInfo('verify successed');
        } else {
            return $this->returnLogicError('sSignature', error::TYPE_INVALID, '', $p_sSignature);
        }
    }

    /**
     * 回复消息逻辑
     *
     * @param array $p_aMsg            
     * @param int $p_iCurrentTime            
     * @param boolean $p_bNeedDecrypt            
     * @return array
     */
    function replyMsg($p_aMsg, $p_iCurrentTime, $p_bNeedDecrypt = false)
    {
        $aReturn = [];
        $this->addLog('wechat message', var_export($p_aMsg, true), 'wechat');
        if ($p_bNeedDecrypt) { // @todo 解密
        }
        if (isset($p_aMsg['MsgType'])) {
            switch ($p_aMsg['MsgType']) {
                case 'text': // 文本消息
                    $aExample = [
                        'ToUserName' => 'gh_32715a16a974', // 公众号
                        'FromUserName' => 'o2b0n045KH6m0xXpbJOyAYt8VDR4', // openid
                        'CreateTime' => 1348831860,
                        'MsgType' => 'text',
                        'Content' => '这是一段测试的话',
                        'MsgId' => 6507377900608335110,
                        'Encrypt' => '49JMbKT2JhRJPiNOiYNuZrRGwIEHjYFB9mqJtoBxi7M+a9S4M9Odjyosf+Eo0YbpFqWKpaf7RbTHIsBpKfQJL6iFxVH6KyLdCJ6b0z1aPauiPnR07yAV1rBA62TNkt8YU5IaIgshX+EOMxegD1dlhQsb2GVHN9w+B0jlXBeuw++ov7oqlSWeUaZO3UfgDr1K9PDQTN2wyfvO/V290Xk7DfN7b7l2KwpVbpRobmEI5zQGfjEZ5HcLqFBpVhRiyxPyR2x7BVFNZT82J1vr22BNIVkuk0J/SJz63HgJZ7tQ82Mg1hbOw7TpW9X3ytOxWjivitsTKa/KzZXItj2NpZZ54Gp6xqAPihWPPGAlv6rriVxv5GH8AvQ4dliyYQTLF7EiqPkvLzVHrQD7APsQZcqUjgxvNf6yPUfUGGoabU1xKHSjmQUaapK107JWCGQCcHBpAgcHFlMnhlaQw/XXRrrfjQ=='
                    ];
                    return $this->returnRow($this->_handleTextMsg($p_aMsg['MsgId'], $p_aMsg['FromUserName'], $p_aMsg['ToUserName'], $p_aMsg['CreateTime'], $p_aMsg['Content'], $p_iCurrentTime));
                    break;
                case 'event': // 消息事件
                    $aExample = [
                        'ToUserName' => 'gh_32715a16a974',
                        'FromUserName' => 'o2b0n0wWft2Gmdio1ALf5vyW-8qM',
                        'CreateTime' => 1515144359,
                        'MsgType' => 'event',
                        'Event' => 'subscribe',
                        'EventKey' => '',
                        'Encrypt' => 'hBBguJvKldPm/Lf30+14Densem6wn6esTlqVyWO3eW21/s33RbUA5RN70gFkhMPG4kpxliSpTgIkw1WNZhFWuj6lDObS7ckV0sLdpJSfgTYJREQ0K9Bv78Fuf/hHHMk7czXWdTzw8CTtNoYBHXkN4LXA7Qdd2vH7S/2hby37yBi4PLhzNgdpneslT4I/JChrUiyc5UuvIsisXDCbRfbB4h9eC4V9znNZag9QbEPGfcL1W3AOqs0o8SbAK/CIHvzncc0VBLY1ehwoBi3lkeHTVgo2ng9rM8e6R+X1ENIm64Un3YK2lSIkz1aKY/griJjbqgI1I3Dy+bA9y1nU7bNBICiK60aJ6LzdqHsonXC9IvOAJoGZSeOLPlU0+WTlSkeIm3xQYPRNezeX5lqJQSAaZxXRRaM7ABAvp+2jRUSN+bo='
                    ];
                    return $this->returnRow($this->_handleEventMsg($p_aMsg['FromUserName'], $p_aMsg['ToUserName'], $p_aMsg['CreateTime'], $p_aMsg['Event'], $p_iCurrentTime));
                    break;
                case 'image': // 图片消息
                    $aExample = [
                        'ToUserName' => 'toUser',
                        'FromUserName' => 'fromUser',
                        'CreateTime' => 1348831860,
                        'MsgType' => 'image',
                        'PicUrl' => 'this is a url',
                        'MediaId' => 'media_id',
                        'MsgId' => 1234567890123456
                    ];
                case 'voice': // 语音消息
                    $aExample = [
                        'ToUserName' => 'toUser',
                        'FromUserName' => 'fromUser',
                        'CreateTime' => 1357290913,
                        'MsgType' => 'voice',
                        'MediaId' => 'media_id',
                        'Format' => 'Format',
                        'MsgId' => 1234567890123456
                    ];
                case 'video': // 视频消息
                case 'shortvideo': // 小视频消息
                case 'location': // 地理位置消息
                case 'link': // 链接消息
                default:
                    return $this->returnLogicError('MsgType', error::TYPE_INVALID, '', $p_aMsg['MsgType']);
                    break;
            }
        } else {
            return $this->returnLogicError('MsgType', error::TYPE_INVALID, '', '');
        }
    }

    /**
     * 处理文本消息
     *
     * @param int $p_iMsgId            
     * @param string $p_sFrom            
     * @param string $p_sTo            
     * @param int $p_iCreateTime            
     * @param string $p_sContent            
     * @param int $p_iCurrentTime            
     * @return array
     */
    private function _handleTextMsg($p_iMsgId, $p_sFrom, $p_sTo, $p_iCreateTime, $p_sContent, $p_iCurrentTime)
    {
        $aPattern = [
            [
                'sPattern' => '/熊童子/i',
                'sHandler' => 'createNewsReply',
                'aArgs' => [
                    $p_sTo,
                    $p_sFrom,
                    $p_iCurrentTime,
                    [
                        [
                            'sTitle' => '多肉图鉴：熊童子(附独家养护心得)',
                            'sDescription' => '多肉图鉴：熊童子(附独家养护心得)',
                            'sPicUrl' => 'http://mmbiz.qpic.cn/mmbiz_jpg/39jVTiaPWBPoWSs4nETbIf0eDw2TwZe74v8UuEVX7mrJhcMLjv7xoJR5MfmGsBsQmws7cN2pwg7iagRmGKR7kCfw/0?wx_fmt=jpeg',
                            'sUrl' => 'http://mp.weixin.qq.com/s?__biz=MzU0MjU5NzA4MQ==&mid=2247483705&idx=2&sn=f7ec6440fe64b13948aae6fed360b911&chksm=fb190b8fcc6e8299a246002cdcfae9f749f57800e16b5f74975d28b8e674511547f7781b5ac1#rd'
                        ]
                    ]
                ]
            ],
            [
                'sPattern' => '/桃蛋/i',
                'sHandler' => 'createNewsReply',
                'aArgs' => [
                    $p_sTo,
                    $p_sFrom,
                    $p_iCurrentTime,
                    [
                        [
                            'sTitle' => '多肉图鉴：桃蛋(附增肥心得)',
                            'sDescription' => '多肉图鉴：桃蛋(附增肥心得)',
                            'sPicUrl' => 'http://mmbiz.qpic.cn/mmbiz_jpg/39jVTiaPWBPr6uVwV0MHHCqIAxlTEhlbcElmmTZY2T7ogKBPrRZzTJoYHO5X7HkeC1TVvo57tSjkUKsKce6xhng/0?wx_fmt=jpeg',
                            'sUrl' => 'http://mp.weixin.qq.com/s?__biz=MzU0MjU5NzA4MQ==&mid=2247483784&idx=3&sn=bdb54554f9207530db6054181b62b5f7&chksm=fb190b3ecc6e822877f4dc3b4621d655e9e2b636355da7cb26e1de1ab8970b0295083551223c#rd'
                        ]
                    ]
                ]
            ],
            [
                'sPattern' => '/几岁/i',
                'sHandler' => 'createTextReply',
                'aArgs' => [
                    $p_sTo,
                    $p_sFrom,
                    $p_iCurrentTime,
                    '我今年4岁了，我还在努力的学习和成长中。如果我还有什么不会的，请您一定耐心告诉我，谢谢。'
                ]
            ],
            [
                'sPattern' => '/喜欢桃蛋/i',
                'sHandler' => 'createTextReply',
                'aArgs' => [
                    $p_sTo,
                    $p_sFrom,
                    $p_iCurrentTime,
                    '我也喜欢，矮肥矮肥的。我们家就属桃蛋养的最好了，有机会你来我们家看看吧。'
                ]
            ]
        ];
        usort($aPattern, [
            $this,
            'sortPattern'
        ]);
        foreach ($aPattern as $iIndex => $aHandler) {
            $aTmp = [];
            if (preg_match($aHandler['sPattern'], $p_sContent, $aTmp) > 0) {
                // print_r($aHandler);
                $oReflection = new \ReflectionClass(get_class($this));
                $oMethod = $oReflection->getMethod($aHandler['sHandler']);
                return $oMethod->invokeArgs(null, $aHandler['aArgs']);
            }
        }
        return self::createTextReply($p_sTo, $p_sFrom, $p_iCurrentTime, '您好，我是匠心多肉人工非智能客服溜溜，谢谢大家对我的喜爱。我现在还在学习阶段，很多东西都还不懂，如果我无法帮到您，请您直接与老板或老板娘联系，谢谢。');
    }

    /**
     * 将匹配模式转换成最大匹配
     *
     * @param array $p_aPatternA            
     * @param array $p_aPatterB            
     * @return int
     */
    protected function sortPattern($p_aPatternA, $p_aPatterB)
    {
        $iLenA = strlen($p_aPatternA['sPattern']);
        $iLenB = strlen($p_aPatterB['sPattern']);
        if ($iLenA > $iLenB) {
            return - 1;
        } elseif ($iLenA < $iLenB) {
            return 1;
        } else {
            return 1;
        }
    }

    /**
     * 处理事件消息
     *
     * @param string $p_sFrom            
     * @param string $p_sTo            
     * @param int $p_iCreateTime            
     * @param string $p_sEvent            
     * @param int $p_iCurrentTime            
     * @return array
     */
    private function _handleEventMsg($p_sFrom, $p_sTo, $p_iCreateTime, $p_sEvent, $p_iCurrentTime)
    {
        switch ($p_sEvent) {
            case 'subscribe': // 关注
                return $this->_handleSubscribe($p_sFrom, $p_sTo, $p_iCreateTime, $p_iCurrentTime);
                break;
            case 'unsubscribe': // 取消关注
            case 'VIEW': // 点击自定义菜单跳转链接
            case 'SCAN': // 扫描二维码
            case 'LOCATION': // 上报地理位置
            case 'CLICK': // 点击自定义菜单拉取消息
            default:
                return self::createTextReply($p_sTo, $p_sFrom, $p_iCurrentTime, ‘您好，您的意思我不是懂。’);
                break;
        }
    }

    /**
     * 处理订阅事件
     *
     * @param string $p_sFrom            
     * @param string $p_sTo            
     * @param int $p_iCreateTime            
     * @param int $p_iCurrentTime            
     * @return array
     */
    private function _handleSubscribe($p_sFrom, $p_sTo, $p_iCreateTime, $p_iCurrentTime)
    {
        $aAricles = [
            [
                'sTitle' => 'Hello, World',
                'sDescription' => 'Hello, World',
                'sPicUrl' => 'http://mmbiz.qpic.cn/mmbiz_jpg/39jVTiaPWBPrj44LYyU6tFyptibC7R5JonZGEhLRNiaIAKTMJRBbxQoEm0dv344ibtlpQ5xngeZKXrT1Lp5e8I5Yrg/0?wx_fmt=jpeg',
                'sUrl' => 'http://mp.weixin.qq.com/s?__biz=MzU0MjU5NzA4MQ==&mid=2247483689&idx=1&sn=7b3b49e160bf63d7b8fdbe9872c9b68d&chksm=fb190b9fcc6e828984d770696ae6e4ebe9dd80f3429e723b41ba5827ebbfd2a6251c4d834b15#rd'
            ],
            [
                'sTitle' => '关于匠心多肉',
                'sDescription' => '比胖,从来就没怂过！',
                'sPicUrl' => 'http://mmbiz.qpic.cn/mmbiz_jpg/39jVTiaPWBPolDv0U4ZJaf7SnMX9YTTZqJraCBdM04JWnm2bCUkMib9zJdfeX5GFUib0ibBxagU5HppnyvAA0V7bXA/0?wx_fmt=jpeg',
                'sUrl' => 'http://mp.weixin.qq.com/s?__biz=MzU0MjU5NzA4MQ==&mid=2247483689&idx=2&sn=9b7010eb92d8996bccb28c71b11d714c&chksm=fb190b9fcc6e8289c80cbbb2e85e3ba69f81b05d264ba5d8182676af2456514a6bb986635261#rd'
            ]
        ];
        return self::createNewsReply($p_sTo, $p_sFrom, $p_iCurrentTime, $aAricles);
    }

    /**
     * 生成文本消息
     *
     * @param string $p_sFrom            
     * @param string $p_sTo            
     * @param int $p_iCreateTime            
     * @param string $p_sContent            
     * @return array
     */
    static function createTextReply($p_sFrom, $p_sTo, $p_iCreateTime, $p_sContent)
    {
        $aExample = [
            'ToUserName' => 'toUser',
            'FromUserName' => 'fromUser',
            'CreateTime' => 12345678,
            'MsgType' => 'text',
            'Content' => '你好'
        ];
        return [
            'ToUserName' => $p_sTo,
            'FromUserName' => $p_sFrom,
            'CreateTime' => $p_iCreateTime,
            'MsgType' => 'text',
            'Content' => $p_sContent
        ];
    }

    /**
     * 创建图片消息
     */
    static function createImgReply()
    {}

    /**
     * 创建语音消息
     */
    static function createVoiceReply()
    {}

    /**
     * 创建视频消息
     */
    static function createVideoReply()
    {}

    /**
     * 创建音乐消息
     */
    static function createMusicReply()
    {}

    /**
     * 创建图文消息
     *
     * @param string $p_sFrom            
     * @param string $p_sTo            
     * @param int $p_iCreateTime            
     * @param array $p_aArticles            
     * @return array
     */
    static function createNewsReply($p_sFrom, $p_sTo, $p_iCreateTime, $p_aArticles)
    {
        $aExample = [
            'ToUserName' => 'toUser',
            'FromUserName' => 'fromUser',
            'CreateTime' => 12345678,
            'MsgType' => 'news',
            'ArticleCount' => 2,
            'Articles' => [
                'item' => [
                    'Title' => 'title1',
                    'Description' => 'description1',
                    'PicUrl' => 'picurl',
                    'Url' => 'url'
                ],
                'item' => [
                    'Title' => 'title',
                    'Description' => 'description',
                    'PicUrl' => 'picurl',
                    'Url' => 'url'
                ]
            ]
        ];
        $aItems = [];
        foreach ($p_aArticles as $aArticle) {
            $aItems[] = [
                'Title' => $aArticle['sTitle'],
                'Description' => $aArticle['sDescription'],
                'PicUrl' => $aArticle['sPicUrl'],
                'Url' => $aArticle['sUrl']
            ];
        }
        return [
            'ToUserName' => $p_sTo,
            'FromUserName' => $p_sFrom,
            'CreateTime' => $p_iCreateTime,
            'MsgType' => 'news',
            'ArticleCount' => count($p_aArticles),
            'Articles' => [
                'item' => $aItems
            ]
        ];
    }
}
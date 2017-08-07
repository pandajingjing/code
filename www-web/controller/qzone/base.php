<?php

/**
 * controller_qzone_base
 * @author jxu
 * @package www-web_controller_qzone
 */
/**
 * controller_qzone_base
 *
 * @author jxu
 */
abstract class controller_qzone_base extends controller_base
{

    /**
     * 访问qq空间的本地域名
     *
     * @var string
     */
    const QZONE_URL_DOMAIN = 'local.qq.com';

    /**
     * 在控制器开始时执行（调度使用）
     */
    function beforeRequest()
    {
        parent::beforeRequest();
        // print_r($_SERVER);
        if (self::QZONE_URL_DOMAIN != $this->getParam('HTTP_HOST', 'server')) {
            $this->createOutURL($p_sChannel, $p_sAlias);
            // $this->redirectURL($p_sURL);
        }
    }
}
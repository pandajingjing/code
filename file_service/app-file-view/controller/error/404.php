<?php
/**
 * 404 controller
 * @package system_kernel_controller_error
 */
load_lib('/app/controller');

/**
 * 404 controller
 *
 * @author jxu
 * @package system_kernel_controller_error
 */
class error_404controller extends app_controller
{

    /**
     * 入口方法
     *
     * @return string
     */
    function doRequest()
    {
        $this->setData('iCurrentTime', $this->getTime());
        $this->addLog(array(
            'sURL' => $this->getParam('DISPATCH_PARAM', 'server')
        ), '404log');
        $this->addHeader('HTTP/1.0 404 Not Found');
        return '/error/404';
    }
}
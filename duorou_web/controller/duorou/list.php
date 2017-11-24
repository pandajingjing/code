<?php

/**
 * controller_home_home
 * @author jxu
 * @package duorou_web_controller_home
 */
/**
 * controller_home_home
 *
 * @author jxu
 */
class controller_duorou_list extends lib_controller_web
{

    function doRequest()
    {
        $this->setData('test', 111);
        return 'duorou_list';
    }
}
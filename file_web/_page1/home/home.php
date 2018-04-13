<?php
/**
 * home page
 * @package app-file-view_page_home
 */
load_lib('/sys/page');

/**
 * home page
 *
 * @author jxu
 * @package app-file-view_page_home
 */
class home_homepage extends sys_page
{

    /**
     * 获取UI
     */
    function getView()
    {
        return 'home';
    }
}
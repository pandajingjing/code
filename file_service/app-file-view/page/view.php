<?php
/**
 * view page
 * @package app-file-view_page
 */
load_lib('/sys/page');

/**
 * view page
 *
 * @author jxu
 * @package app-file-view_page
 */
class viewpage extends sys_page
{

    function getView()
    {
        return 'view';
    }
}
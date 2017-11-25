<?php
namespace app\controller\home;

use panda\lib\controller\Web;

class miss extends Web
{

    function doRequest()
    {
        return 'web_404';
    }
}
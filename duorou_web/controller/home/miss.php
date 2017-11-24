<?php
namespace app\controller\home;

use panda\lib\controller\Web;

class Miss extends Web
{

    function doRequest()
    {
        return 'web_404';
    }
}
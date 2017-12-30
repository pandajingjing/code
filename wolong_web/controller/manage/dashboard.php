<?php
/**
 * dashboard
 * 
 * @namespace app\controller\manage
 */
namespace app\controller\manage;

/**
 * dashboard
 */
class dashboard extends base
{

    function doRequest()
    {
        $this->setBreadCrumb();
        return '/manage/dashboard';
    }

    /**
     * 设置面包屑
     */
    protected function setBreadCrumb()
    {
        $aCrumbUrls = [
            [
                '',
                $this->createInUrl(get_class($this))
            ]
        ];
        $this->setPageData('aCrumbUrls', $aCrumbUrls);
    }
}
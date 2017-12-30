<?php
/**
 * base
 * 
 * @namespace app\controller\manage
 */
namespace app\controller\manage;

use app\controller\base as webbase;

/**
 * base
 */
abstract class base extends webbase
{

    function beforeRequest()
    {
        parent::beforeRequest();
        $aLeftUrls = [
            'sContactList' => $this->createInUrl('\\app\\controller\\manage\\contact\\listing'),
            'sDocumentList' => $this->createInUrl('\\app\\controller\\manage\document\\listing'),
            'sPhotoList' => $this->createInUrl('\\app\\controller\\manage\\photo\\listing'),
            'sVideoList' => $this->createInUrl('\\app\\controller\\manage\video\\listing'),
            'sImport' => $this->createInUrl('\\app\\controller\\manage\\import')
        ];
        $this->setPageData('aLeftUrls', $aLeftUrls);
        $sBreadHomeUrl = $this->createInUrl('\\app\\controller\\manage\\dashboard');
        $this->setPageData('sBreadHomeUrl', $sBreadHomeUrl);
    }
}
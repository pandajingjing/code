<?php
$G_CONFIG['view']['aOriginal'] = array(
    '/ananzu\.com|pingan\.com|pingan\.com\.cn|anhouse\.cn|unknown|localhost|pinganfang\.com|ipo\.com|anhouse\.com|anhouse\.com\.cn|local\.com|pinganhaofang\.com/' => array(
        'banner',
        'document',
        'avatar',
        'secret',
        'ahb',
        'project',
        'export',
        'agreement'
    )
);

$G_CONFIG['view']['bCacheable'] = true;

/**
 * Option相关说明及业务逻辑
 * bThumbnail - 是否是缩略图
 * sMode - 操作规则,cut是剪切,zoom是缩放
 * sZoomMode - 缩放规则,fill通过填充来缩放,scale通过等比例来缩放
 * sZoomScaleMode - 等比例缩放的规则,width按照宽度为指定宽度来缩放高度,height按照高度为指定高度来缩放宽度,mix将整个图等比例缩放至不超过宽高的尺寸
 * 逻辑:
 * bThumbnail(true)-sMode(cut)将原图缩放到宽或者高中较小边与目标值一样后,居中裁剪另外一边
 * bThumbnail(true)-sMode(zoom)-sZoomMode(fill)把图片拉伸到指定尺寸
 * bThumbnail(true)-sMode(zoom)-sZoomMode(scale)-sZoomScaleMode(width)把图片拉伸到宽度满足指定宽度,高度等比例缩放
 * bThumbnail(true)-sMode(zoom)-sZoomMode(scale)-sZoomScaleMode(height)把图片拉伸到高度满足指定高度,宽度等比例缩放
 * bThumbnail(true)-sMode(zoom)-sZoomMode(scale)-sZoomScaleMode(mix)把图片拉伸到宽或高满足不超过指定宽度或高度,等比例缩放
 * bThumbnail(false)-sMode(cut)原图不缩放,从中心位置,取满足尺寸的区域
 * bThumbnail(false)-sMode(zoom)-sZoomMode(fill)把图片拉伸到指定尺寸
 * bThumbnail(false)-sMode(zoom)-sZoomMode(scale)-sZoomScaleMode(width)把图片拉伸到宽度满足指定宽度但不超过原始尺寸,高度等比例缩放
 * bThumbnail(false)-sMode(zoom)-sZoomMode(scale)-sZoomScaleMode(height)把图片拉伸到高度满足指定高度但不超过原始尺寸,宽度等比例缩放
 * bThumbnail(false)-sMode(zoom)-sZoomMode(scale)-sZoomScaleMode(mix)把图片拉伸到宽或高满足不超过指定宽度或高度但不超过原始尺寸,等比例缩放
 */
$G_CONFIG['view']['aResize']['/ipo\.com/'][''] = array();
$aThumbnailCfg = array(
    'mWatermark' => false,
    'bThumbnail' => true,
    'sMode' => 'cut'
);
$sWaterMark_120x45 = '/data1/www/other/mask_120x45.png';
$sWaterMark_140x50 = '/data1/www/other/mask_140x50.png';
$sWaterMark_140x50_ananzu = '/data1/www/other/mask_140x50_ananzu.png';
$sWaterMark_66x66_ananzu = '/data1/www/other/mask_66x66_ananzu.png';
// 楼盘尺寸
$G_CONFIG['view']['aResize']['/ipo\.com/'][''][] = array(
    'iWidth' => 67,
    'iHeight' => 50,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/'][''][] = array(
    'iWidth' => 96,
    'iHeight' => 72,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/'][''][] = array(
    'iWidth' => 120,
    'iHeight' => 120,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/'][''][] = array(
    'iWidth' => 134,
    'iHeight' => 100,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/'][''][] = array(
    'iWidth' => 160,
    'iHeight' => 120,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/'][''][] = array(
    'iWidth' => 200,
    'iHeight' => 150,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/'][''][] = array(
    'iWidth' => 220,
    'iHeight' => 165,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/'][''][] = array(
    'iWidth' => 240,
    'iHeight' => 240,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/'][''][] = array(
    'iWidth' => 260,
    'iHeight' => 195,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/'][''][] = array(
    'iWidth' => 300,
    'iHeight' => 225,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/'][''][] = array(
    'iWidth' => 470,
    'iHeight' => 354,
    'aOption' => array(
        'aDefault' => array(
            'mWatermark' => array(
                'sFilePath' => $sWaterMark_120x45,
                'aEdge' => array(
                    'iRight' => 40,
                    'iDown' => 30
                )
            ),
            'bThumbnail' => true,
            'sMode' => 'zoom', // cut
            'sZoomMode' => 'scale', // fill
            'sZoomScaleMode' => 'mix' // width,height
        )
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/'][''][] = array(
    'iWidth' => 528,
    'iHeight' => 297,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/'][''][] = array(
    'iWidth' => 600,
    'iHeight' => 450,
    'aOption' => array(
        'aDefault' => array(
            'mWatermark' => array(
                'sFilePath' => $sWaterMark_120x45,
                'aEdge' => array(
                    'iRight' => 20,
                    'iDown' => 20
                )
            ),
            'bThumbnail' => true,
            'sMode' => 'zoom', // cut
            'sZoomMode' => 'scale', // fill
            'sZoomScaleMode' => 'mix' // width,height
        )
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/'][''][] = array(
    'iWidth' => 640,
    'iHeight' => 360,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/'][''][] = array(
    'iWidth' => 900,
    'iHeight' => 675,
    'aOption' => array(
        'aDefault' => array(
            'mWatermark' => array(
                'sFilePath' => $sWaterMark_140x50,
                'aEdge' => array(
                    'iRight' => 40,
                    'iDown' => 30
                )
            ),
            'bThumbnail' => true,
            'sMode' => 'zoom', // cut
            'sZoomMode' => 'scale', // fill
            'sZoomScaleMode' => 'mix' // width,height
        )
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/'][''][] = array(
    'iWidth' => 800,
    'iHeight' => 532,
    'aOption' => array(
        'aDefault' => array(
            'mWatermark' => array(
                'sFilePath' => $sWaterMark_140x50,
                'aEdge' => array(
                    'iRight' => 40,
                    'iDown' => 30
                )
            ),
            'bThumbnail' => false,
            'sMode' => 'zoom', // cut
            'sZoomMode' => 'scale', // fill
            'sZoomScaleMode' => 'mix' // width,height
        )
    )
);
// 头像尺寸
$G_CONFIG['view']['aResize']['/ipo\.com/'][''][] = array(
    'iWidth' => 75,
    'iHeight' => 60,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/'][''][] = array(
    'iWidth' => 72,
    'iHeight' => 72,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/'][''][] = array(
    'iWidth' => 96,
    'iHeight' => 118,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/'][''][] = array(
    'iWidth' => 200,
    'iHeight' => 200,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/'][''][] = array(
    'iWidth' => 400,
    'iHeight' => 400,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
// 手机轮播图
$G_CONFIG['view']['aResize']['/ipo\.com/'][''][] = array(
    'iWidth' => 640,
    'iHeight' => 240,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
// 品房客新闻图片
$G_CONFIG['view']['aResize']['/ipo\.com/'][''][] = array(
    'iWidth' => 77,
    'iHeight' => 70,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
// 百盘大战需要尺寸
$G_CONFIG['view']['aResize']['/ipo\.com/'][''][] = array(
    'iWidth' => 248,
    'iHeight' => 186,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
// 分业务测试配置
$G_CONFIG['view']['aResize']['/ipo\.com/']['avatar'][] = array(
    'iWidth' => 398,
    'iHeight' => 259,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/']['avatar'][] = array(
    'iWidth' => 135,
    'iHeight' => 99,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/']['avatar'][] = array(
    'iWidth' => 60,
    'iHeight' => 80,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/']['avatar'][] = array(
    'iWidth' => 44,
    'iHeight' => 60,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/']['avatar'][] = array(
    'iWidth' => 100,
    'iHeight' => 134,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);

$G_CONFIG['view']['aResize']['/ipo\.com/']['avatar'][] = array(
    'iWidth' => 107,
    'iHeight' => 78,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/']['avatar'][] = array(
    'iWidth' => 75,
    'iHeight' => 60,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/']['avatar'][] = array(
    'iWidth' => 200,
    'iHeight' => 200,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/']['avatar'][] = array(
    'iWidth' => 220,
    'iHeight' => 165,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/']['avatar'][] = array(
    'iWidth' => 528,
    'iHeight' => 297,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/']['avatar'][] = array(
    'iWidth' => 600,
    'iHeight' => 450,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/']['avatar'][] = array(
    'iWidth' => 43,
    'iHeight' => 43,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);

$G_CONFIG['view']['aResize']['/ipo\.com/']['house'][] = array(
    'iWidth' => 90,
    'iHeight' => 90,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);

$G_CONFIG['view']['aResize']['/ipo\.com/']['house'][] = array(
    'iWidth' => 240,
    'iHeight' => 240,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);

$G_CONFIG['view']['aResize']['/ipo\.com/']['house'][] = array(
    'iWidth' => 96,
    'iHeight' => 72,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/']['house'][] = array(
    'iWidth' => 160,
    'iHeight' => 120,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/']['house'][] = array(
    'iWidth' => 200,
    'iHeight' => 150,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg,
        'ananzu' => array(
            'mWatermark' => array(
                'sFilePath' => $sWaterMark_66x66_ananzu,
                'aEdge' => array(
                    'bMiddle' => true
                )
            )
        )
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/']['house'][] = array(
    'iWidth' => 220,
    'iHeight' => 160,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/']['house'][] = array(
    'iWidth' => 260,
    'iHeight' => 195,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/']['house'][] = array(
    'iWidth' => 220,
    'iHeight' => 165,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/']['house'][] = array(
    'iWidth' => 470,
    'iHeight' => 354,
    'aOption' => array(
        'aDefault' => array(
            'mWatermark' => array(
                'sFilePath' => $sWaterMark_120x45,
                'aEdge' => array(
                    'iRight' => 40,
                    'iDown' => 30
                )
            ),
            'bThumbnail' => true,
            'sMode' => 'zoom', // cut
            'sZoomMode' => 'scale', // fill
            'sZoomScaleMode' => 'mix' // width,height
        )
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/']['house'][] = array(
    'iWidth' => 528,
    'iHeight' => 297,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/']['house'][] = array(
    'iWidth' => 640,
    'iHeight' => 360,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg,
        'ananzu' => array(
            'mWatermark' => array(
                'sFilePath' => $sWaterMark_140x50_ananzu,
                'aEdge' => array(
                    'bMiddle' => true
                )
            )
        ),
        'wm' => array(
            'mWatermark' => array(
                'sFilePath' => $sWaterMark_120x45,
                'aEdge' => array(
                    'iRight' => 40,
                    'iDown' => 30
                )
            )
        )
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/']['house'][] = array(
    'iWidth' => 900,
    'iHeight' => 675,
    'aOption' => array(
        'aDefault' => array(
            'mWatermark' => array(
                'sFilePath' => $sWaterMark_140x50,
                'aEdge' => array(
                    'iRight' => 40,
                    'iDown' => 30
                )
            ),
            'bThumbnail' => true,
            'sMode' => 'zoom', // cut
            'sZoomMode' => 'scale', // fill
            'sZoomScaleMode' => 'mix' // width,height
        ),
        'ananzu' => array(
            'mWatermark' => array(
                'sFilePath' => $sWaterMark_140x50_ananzu,
                'aEdge' => array(
                    'bMiddle' => true
                )
            ),
            'bThumbnail' => true,
            'sMode' => 'zoom', // cut
            'sZoomMode' => 'scale', // fill
            'sZoomScaleMode' => 'mix' // width,height
        ),
        'ananzupz' => array(
            'mWatermark' => false,
            'bThumbnail' => true,
            'sMode' => 'zoom', // cut
            'sZoomMode' => 'scale', // fill
            'sZoomScaleMode' => 'mix' // width,height
        )
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/']['house'][] = array(
    'iWidth' => 720,
    'iHeight' => 405,
    'aOption' => array(
        'aDefault' => array(
            'mWatermark' => array(
                'sFilePath' => $sWaterMark_120x45,
                'aEdge' => array(
                    'iRight' => 40,
                    'iDown' => 30
                )
            ),
            'bThumbnail' => true,
            'sMode' => 'zoom', // cut
            'sZoomMode' => 'scale', // fill
            'sZoomScaleMode' => 'mix' // width,height
        )
    )
);
// 身份证
$G_CONFIG['view']['aResize']['/ipo\.com/']['idcard'][] = array(
    'iWidth' => 220,
    'iHeight' => 165,
    'aOption' => array(
        'aDefault' => array(
            'mWatermark' => false,
            'bThumbnail' => true,
            'sMode' => 'zoom', // cut
            'sZoomMode' => 'scale', // fill
            'sZoomScaleMode' => 'mix' // width,height
        )
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/']['idcard'][] = array(
    'iWidth' => 600,
    'iHeight' => 450,
    'aOption' => array(
        'aDefault' => array(
            'mWatermark' => false,
            'bThumbnail' => true,
            'sMode' => 'zoom', // cut
            'sZoomMode' => 'scale', // fill
            'sZoomScaleMode' => 'mix' // width,height
        )
    )
);

$G_CONFIG['view']['aResize']['/ipo\.com/']['idcard'][] = array(
    'iWidth' => 1200,
    'iHeight' => 900,
    'aOption' => array(
        'aDefault' => array(
            'mWatermark' => array(
                'sFilePath' => $sWaterMark_140x50,
                'aEdge' => array(
                    'iRight' => 40,
                    'iDown' => 30
                )
            ),
            'bThumbnail' => true,
            'sMode' => 'zoom', // cut
            'sZoomMode' => 'scale', // fill
            'sZoomScaleMode' => 'mix' // width,height
        )
    )
);

// 与用户交互的通道
$G_CONFIG['view']['aResize']['/ipo\.com/']['interact'][] = array(
    'iWidth' => 96,
    'iHeight' => 72,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/']['interact'][] = array(
    'iWidth' => 220,
    'iHeight' => 165,
    'aOption' => array(
        'aDefault' => $aThumbnailCfg
    )
);
$G_CONFIG['view']['aResize']['/ipo\.com/']['interact'][] = array(
    'iWidth' => 900,
    'iHeight' => 675,
    'aOption' => array(
        'aDefault' => array(
            'mWatermark' => array(
                'sFilePath' => $sWaterMark_140x50,
                'aEdge' => array(
                    'iRight' => 40,
                    'iDown' => 30
                )
            ),
            'bThumbnail' => true,
            'sMode' => 'zoom', // cut
            'sZoomMode' => 'scale', // fill
            'sZoomScaleMode' => 'mix' // width,height
        )
    )
);
$G_CONFIG['view']['aResize']['/unknown|localhost/'] = array();
$G_CONFIG['view']['aResize']['/unknown|localhost/'] = $G_CONFIG['view']['aResize']['/ipo\.com/'];
$G_CONFIG['view']['aResize']['/ananzu\.com|pingan\.com|pingan\.com\.cn|anhouse\.cn|pinganfang\.com|anhouse\.com|anhouse\.com\.cn|local\.com|pinganhaofang\.com/'] = array();
$G_CONFIG['view']['aResize']['/ananzu\.com|pingan\.com|pingan\.com\.cn|anhouse\.cn|pinganfang\.com|anhouse\.com|anhouse\.com\.cn|local\.com|pinganhaofang\.com/'] = $G_CONFIG['view']['aResize']['/ipo\.com/'];

<?php
/**
 * image
 *
 * 缩放和裁剪图片
 * @namespace panda\util
 * @package util
 */
namespace panda\util;

/**
 * image
 *
 * 缩放和裁剪图片
 */
class image
{

    /**
     * 处理图片的类库
     *
     * 可以是imagick,gd或者完善其他的方法
     *
     * @var string
     */
    static $_eType = 'imagick';

    /**
     * 使用Imagick重新绘制图片
     *
     * @param string $p_sPath            
     * @param int $p_iWidth            
     * @param int $p_iHeight            
     * @param string $p_sExtension            
     * @param array $p_aOption            
     * @throws Exception
     * @return blob
     */
    private static function resizeImage_Imagick($p_sPath, $p_iWidth, $p_iHeight, $p_sExtension, $p_aOption = [])
    {
        $oImage = new \Imagick();
        $oImage->setResourceLimit(6, 1);
        $oImage->readImage($p_sPath);
        $iOWidth = $oImage->getImageWidth();
        $iOHeight = $oImage->getImageHeight();
        if ($iOWidth < $p_iWidth and $iOHeight < $p_iHeight) { // 不做拉伸图片处理
            $p_iWidth = $iOWidth;
            $p_iHeight = $iOHeight;
        }
        if (true === $p_aOption['bThumbnail']) {
            switch ($p_aOption['sMode']) {
                case 'cut': // 裁剪
                    $oImage->cropThumbnailImage($p_iWidth, $p_iHeight);
                    break;
                case 'zoom': // 缩放
                default:
                    switch ($p_aOption['sZoomMode']) {
                        case 'fill': // 填充
                            $oImage->thumbnailImage($p_iWidth, $p_iHeight);
                            break;
                        case 'scale': // 等比例缩放
                        default:
                            switch ($p_aOption['sZoomScaleMode']) {
                                case 'width':
                                    $oImage->thumbnailImage($p_iWidth, round($p_iWidth * $iOHeight / $iOWidth), true);
                                    break;
                                case 'height':
                                    $oImage->thumbnailImage(round($p_iHeight * $iOWidth / $iOHeight), $p_iHeight, true);
                                    break;
                                case 'mix':
                                default:
                                    $oImage->thumbnailImage($p_iWidth, $p_iHeight, true);
                                    break;
                            }
                            break;
                    }
                    break;
            }
        } else {
            switch ($p_aOption['sMode']) {
                case 'cut':
                    $oImage->cropImage($p_iWidth, $p_iHeight, round(($iOWidth - $p_iWidth) / 2), round(($iOHeight - $p_iHeight) / 2));
                    break;
                case 'zoom':
                default:
                    switch ($p_aOption['sZoomMode']) {
                        case 'fill':
                            $oImage->resizeImage($p_iWidth, $p_iHeight, \Imagick::FILTER_CATROM, 1);
                            break;
                        case 'scale':
                        default:
                            switch ($p_aOption['sZoomScaleMode']) {
                                case 'width':
                                    $oImage->resizeImage($p_iWidth, round($p_iWidth * $iOHeight / $iOWidth), \Imagick::FILTER_CATROM, 1, true);
                                    break;
                                case 'height':
                                    $oImage->resizeImage(round($p_iHeight * $iOWidth / $iOHeight), $p_iHeight, \Imagick::FILTER_CATROM, 1, true);
                                    break;
                                case 'mix':
                                default:
                                    if (($iOWidth / $iOHeight) > ($p_iWidth / $p_iHeight)) {
                                        $oImage->resizeImage($p_iWidth, round($p_iWidth * $iOHeight / $iOWidth), \Imagick::FILTER_CATROM, 1, false);
                                    } else {
                                        $oImage->resizeImage(round($p_iHeight * $iOWidth / $iOHeight), $p_iHeight, \Imagick::FILTER_CATROM, 1, false);
                                    }
                                    break;
                            }
                            break;
                    }
                    break;
            }
        }
        if (false !== $p_aOption['mWatermark']) { // 水印
            $aWatermark = $p_aOption['mWatermark'];
            if (file_exists($aWatermark['sFilePath'])) {
                $oWaterMark = new \Imagick();
                $oWaterMark->readImage($aWatermark['sFilePath']);
                $aEdge = $aWatermark['aEdge'];
                $iWatermarkWidth = $oWaterMark->getImageWidth();
                $iWatermarkHeight = $oWaterMark->getImageHeight();
                $iImageWidth = $oImage->getImageWidth();
                $iImageHeight = $oImage->getImageHeight();
                if (isset($aEdge['iLeft'])) {
                    $iPosX = $aEdge['iLeft'];
                } elseif (isset($aEdge['iRight'])) {
                    $iPosX = $iImageWidth - $iWatermarkWidth - $aEdge['iRight'];
                } elseif (isset($aEdge['bMiddle']) || isset($aEdge['bWidthMiddle'])) {
                    $iPosX = max(0, floor(($iImageWidth - $iWatermarkWidth) / 2));
                } else {
                    throw new \Exception(__CLASS__ . ': can not found configuration(resize_watermark_edge).');
                }
                if (isset($aEdge['iUp'])) {
                    $iPosY = $aEdge['iUp'];
                } elseif (isset($aEdge['iDown'])) {
                    $iPosY = $iImageHeight - $iWatermarkHeight - $aEdge['iDown'];
                } elseif (isset($aEdge['bMiddle']) || isset($aEdge['bHeightMiddle'])) {
                    $iPosY = max(0, floor(($iImageHeight - $iWatermarkHeight) / 2));
                } else {
                    throw new \Exception(__CLASS__ . ': can not found configuration(resize_watermark_edge)');
                }
                $oImage->compositeImage($oWaterMark, \Imagick::COMPOSITE_DEFAULT, $iPosX, $iPosY);
                $oWaterMark->clear();
                $oWaterMark->destroy();
            } else {
                throw new \Exception(__CLASS__ . ': can not found resize_watermark_path(' . $aWatermark['sFilePath'] . ')');
            }
        }
        
        $oImage->setImageFormat($p_sExtension);
        $oImage->setImageCompression(\Imagick::COMPRESSION_JPEG);
        $oImage->stripImage();
        $blImage = $oImage->getImageBlob();
        $oImage->clear();
        $oImage->destroy();
        return $blImage;
    }

    /**
     * 重新绘制图片
     *
     * @param string $p_sPath            
     * @param int $p_iWidth            
     * @param int $p_iHeight            
     * @param string $p_sExtension            
     * @param array $p_aOption            
     * @return blob
     */
    static function resizeImage($p_sPath, $p_iWidth, $p_iHeight, $p_sExtension, $p_aOption = [])
    {
        switch (self::$_eType) {
            case 'gd':
                return self::resizeImage_GD($p_sPath, $p_iWidth, $p_iHeight, $p_sExtension, $p_aOption);
                break;
            case 'imagick':
                return self::resizeImage_Imagick($p_sPath, $p_iWidth, $p_iHeight, $p_sExtension, $p_aOption);
                break;
        }
    }

    /**
     * 剪裁图片
     *
     * @param string $p_sPath            
     * @param int $p_iCutPointX            
     * @param int $p_iCutPointY            
     * @param int $p_iWidth            
     * @param int $p_iHeight            
     * @param string $p_sExtension            
     * @throws Exception
     * @return blob|false
     */
    static function cropImage($p_sPath, $p_iCutPointX, $p_iCutPointY, $p_iWidth, $p_iHeight, $p_sExtension)
    {
        // print_r(func_get_args());exit;
        $oImage = new \Imagick();
        $oImage->readImage($p_sPath);
        $iOWidth = $oImage->getImageWidth();
        $iOHeight = $oImage->getImageHeight();
        if ($p_iCutPointX > $iOWidth) {
            return false;
        }
        if ($p_iCutPointY > $iOHeight) {
            return false;
        }
        if ($p_iCutPointX + $p_iWidth > $iOWidth) {
            return false;
        }
        if ($p_iCutPointY + $p_iHeight > $iOHeight) {
            return false;
        }
        // 按照裁剪缩放的宽度
        $oImage->cropImage($p_iWidth, $p_iHeight, $p_iCutPointX, $p_iCutPointY);
        
        $oImage->setImageFormat($p_sExtension);
        $oImage->setImageCompression(\Imagick::COMPRESSION_JPEG);
        $oImage->stripImage();
        $blImage = $oImage->getImageBlob();
        $oImage->clear();
        $oImage->destroy();
        return $blImage;
    }

    /**
     * 使用GD重新绘制图片
     *
     * @param string $p_sPath            
     * @param int $p_iWidth            
     * @param int $p_iHeight            
     * @param string $p_sExtension            
     * @param array $p_aOption            
     * @return blob
     */
    private static function resizeImage_Gd($p_sPath, $p_iWidth, $p_iHeight, $p_sExtension, $p_aOption = array())
    {}

    /**
     * 生成验证码图片
     *
     * @param int $p_iWidth            
     * @param int $p_iHeight            
     * @param string $p_sStr            
     * @param string $p_sFontPath            
     * @param int $p_iFontSize            
     * @param int $p_iPointDensity            
     * @param int $p_iCircleDensity            
     * @param int $p_iFontAngle            
     * @return blob
     */
    static function createIdentifyCodeImage($p_iWidth, $p_iHeight, $p_sStr, $p_sFontPath, $p_iFontSize = 0, $p_iPointDensity = 0, $p_iCircleDensity = 0, $p_iFontAngle = 0)
    {
        // 获取各种默认值
        if (0 == $p_iFontSize) {
            $p_iFontSize = round($p_iHeight * 3 / 5);
        }
        if (0 == $p_iPointDensity) {
            $p_iPointDensity = round($p_iHeight * $p_iWidth / 100);
        }
        if (0 == $p_iCircleDensity) {
            $p_iCircleDensity = round($p_iHeight * $p_iWidth / 200);
        }
        // 生成画布
        $oImg = imagecreatetruecolor($p_iWidth, $p_iHeight);
        
        // 填充北京颜色 Edit by lost
        $bgc = imagecolorallocate($oImg, 255, 255, 255);
        imagefill($oImg, 0, 0, $bgc);
        
        // 获取字体范围大小
        $aTextSize = imagettfbbox($p_iFontSize, $p_iFontAngle, $p_sFontPath, $p_sStr);
        $iTextHeight = (max($aTextSize[1], $aTextSize[3]) - min($aTextSize[5], $aTextSize[7]));
        $iTextWidth = (max($aTextSize[4], $aTextSize[2]) - min($aTextSize[0], $aTextSize[6]));
        // 字体起始位置
        $iTextStartLeft = ($p_iWidth - $iTextWidth) / 2;
        $iTextStartHeight = $p_iHeight / 2 + $iTextHeight / 2;
        // 字体颜色
        $colors = [
            [
                0,
                10,
                210
            ],
            [
                24,
                157,
                10
            ],
            [
                177,
                70,
                20
            ]
        ];
        $colorsValue = $colors[array_rand($colors)];
        $oTextColor = imagecolorallocate($oImg, $colorsValue[0], $colorsValue[1], $colorsValue[2]);
        
        // 往画布上画字符串
        // imagettftext($oImg, $p_iFontSize, $p_iFontAngle, $iTextStartLeft, $iTextStartHeight, $oTextColor, $sTextFont, $p_sStr);
        
        $len = strlen($p_sStr);
        $_x = $len > 0 ? ($p_iWidth - 40) / $len : 0;
        
        for ($i = 0; $i < $len; $i ++) {
            $iTextStartLeft = $_x * $i + mt_rand(20, 25);
            imagettftext($oImg, $p_iFontSize, mt_rand(- 10, 10), $iTextStartLeft, $iTextStartHeight, $oTextColor, $p_sFontPath, $p_sStr[$i]);
        }
        
        for ($i = 0; $i < 100; $i ++) {
            $color = imagecolorallocate($oImg, rand(50, 220), rand(50, 220), rand(50, 220));
            imagesetpixel($oImg, rand(0, $p_iWidth), rand(0, $p_iHeight), $color);
        }
        
        // 往画布上画线条
        for ($i = 0; $i < 5; $i ++) {
            $color = imagecolorallocate($oImg, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
            imageline($oImg, mt_rand(0, $p_iWidth), mt_rand(0, $p_iHeight), mt_rand(0, $p_iWidth), mt_rand(0, $p_iHeight), $color);
        }
        
        // 往画布上画点
        for ($i = 0; $i < $p_iPointDensity; $i ++) {
            $iX = rand(0, $p_iWidth);
            $iY = rand(0, $p_iHeight);
            $iRed = rand(0, 255);
            $iGreen = rand(0, 255);
            $iBlue = rand(0, 255);
            $oPointColor = imagecolorallocate($oImg, $iRed, $iGreen, $iBlue);
            imagesetpixel($oImg, $iX, $iY, $oPointColor);
        }
        
        // 往画布上画圆
        for ($i = 0; $i < $p_iCircleDensity; $i ++) {
            $x = rand(0, $p_iWidth);
            $y = rand(0, $p_iHeight);
            $r = rand(1, $p_iFontSize / 4);
            $red = rand(0, 255);
            $green = rand(0, 255);
            $blue = rand(0, 255);
            $newcolor = imagecolorallocate($oImg, $red, $green, $blue);
            imagefilledellipse($oImg, $x, $y, $r, $r, $newcolor);
        }
        
        ob_start();
        imagegif($oImg);
        $blImage = ob_get_contents();
        ob_end_clean();
        imagedestroy($oImg);
        return $blImage;
    }
}

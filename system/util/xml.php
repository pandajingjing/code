<?php
/**
 * xml
 *
 * 提供框架解析xml和生成xml的能力
 * @namespace panda\util
 */
namespace panda\util;

/**
 * xml
 *
 * 提供框架解析xml和生成xml的能力
 */
class xml
{

    /**
     * 解析xml并转换为数组
     *
     * @param string $sRawData            
     * @return array
     */
    static function parseStr($sRawData)
    {
        if ($sRawData == '') {
            return [];
        } else {
            $mXml = simplexml_load_string($sRawData);
            if ($mXml == false) {
                return [];
            } else {
                $aData = self::_parseXml($mXml);
                return $aData['xml'];
            }
        }
    }

    /**
     * 根据数组转换为xml
     *
     * @param array $p_aData            
     * @return string
     */
    static function parseArr($p_aData)
    {
        if (is_array($p_aData)) {
            if (empty($p_aData)) {
                return '';
            } else {
                return '<xml>' . self::_parseArr($p_aData) . '</xml>';
            }
        } else {
            return '';
        }
    }

    /**
     * 解析数组
     *
     * @param array $p_aData            
     * @param string $p_sUpKey            
     * @param boolean $o_bIsList            
     * @return string
     */
    static private function _parseArr($p_aData, $p_sUpKey = 'xml', &$o_bIsList = false)
    {
        $sTmp = '';
        foreach ($p_aData as $sKey => $mVal) {
            if (is_numeric($sKey)) {
                $o_bIsList = true;
                $sKey = $p_sUpKey;
            } else {
                $o_bIsList = false;
            }
            if (is_array($mVal)) {
                $bIsList = false;
                $sChild = self::_parseArr($mVal, $sKey, $bIsList);
                if ($bIsList == true) {
                    $sTmp .= $sChild;
                } else {
                    $sTmp .= '<' . $sKey . '>' . $sChild . '</' . $sKey . '>';
                }
                unset($bIsList);
            } else {
                if (is_numeric($mVal)) {
                    $sTmp .= '<' . $sKey . '>' . $mVal . '</' . $sKey . '>';
                } else {
                    $sTmp .= '<' . $sKey . '><![CDATA[' . $mVal . ']]></' . $sKey . '>';
                }
            }
            // echo $sTmp, PHP_EOL;
        }
        return $sTmp;
    }

    /**
     * 把xml转换为数组
     *
     * @param object $oXml            
     * @return array
     */
    static private function _parseXml($oXml)
    {
        $sName = $oXml->getName();
        if ($oXml->count() > 0) {
            $oChildren = $oXml->children();
            $aChildren = [];
            foreach ($oChildren as $oChild) {
                $aChild = self::_parseXml($oChild);
                $aChildKeys = array_keys($aChild);
                foreach ($aChildKeys as $sKey) {
                    if (isset($aChildren[$sKey])) {
                        if (is_array($aChildren[$sKey])) { // 已经存放了数组
                            if (array_key_exists(0, $aChildren[$sKey])) { // 合法的xml的tag名称只能以下划线和字母开始，不能是纯数字。说明是个列表
                                $aChildren[$sKey][] = $aChild[$sKey];
                            } else { // 本身存得就是一个哈希，需要变成列表的一个元素
                                $mTmp = $aChildren[$sKey];
                                $aChildren[$sKey] = [
                                    $mTmp,
                                    $aChild[$sKey]
                                ];
                            }
                        } else { // 不是数组，需要变成数组
                            $mTmp = $aChildren[$sKey];
                            $aChildren[$sKey] = [
                                $mTmp,
                                $aChild[$sKey]
                            ];
                        }
                    } else {
                        $aChildren[$sKey] = $aChild[$sKey];
                    }
                }
            }
            return [
                $sName => $aChildren
            ];
        } else {
            return [
                $sName => trim($oXml)
            ];
        }
    }
}
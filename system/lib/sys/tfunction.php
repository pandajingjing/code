<?php
/**
 * 模板全局函数
 */
use panda\lib\sys\variable;

/**
 * 输出普通数据
 *
 * @param string $p_sStr            
 */
function panda($p_sStr)
{
    echo htmlspecialchars($p_sStr);
}

/**
 * 输出文本框数据
 *
 * @param string $p_sStr            
 */
function pandaText($p_sStr)
{
    echo str_replace([
        "\r",
        "\n\n",
        "\n"
    ], [
        "\n",
        "\n",
        '<br />'
    ], htmlspecialchars($p_sStr));
}

/**
 * 输出HTML代码
 *
 * @param string $p_sStr            
 */
function pandaHTML($p_sStr)
{
    echo $p_sStr;
}

/**
 * 获取静态资源路径
 *
 * @param string $p_sPath            
 * @param string $p_sSchemeDomainKey            
 * @return string
 */
function pandaRes($p_sPath, $p_sSchemeDomainKey = 'sCDNSchemeDomain')
{
    $sStaticSchemeDomain = variable::getInstance()->getConfig($p_sSchemeDomainKey, 'domain');
    return $sStaticSchemeDomain . $p_sPath;
}
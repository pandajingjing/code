<?php
/**
 * ip
 *
 * 全球 IPv4地址归属地数据库(17MON.CN 版)
 * @namespace panda\util
 * @package util
 */
namespace panda\util;

/**
 * ip
 *
 * 全球 IPv4 地址归属地数据库(17MON.CN 版)
 */
class ip
{

    /**
     * 数据文件句柄
     *
     * @var object
     */
    private static $_oFileHandle = null;

    /**
     * 偏移量
     *
     * @var array
     */
    private static $_aOffset = null;

    /**
     * 查询索引
     *
     * @var blob
     */
    private static $_blIndex = null;

    /**
     * 获取地址
     *
     * @param string $p_sIP            
     * @return string
     */
    static function findAddr($p_sIP)
    {
        if ('' == $p_sIP) {
            return 'N/A';
        }
        $p_aIP = explode('.', $p_sIP);
        if ($p_aIP[0] < 0 || $p_aIP[0] > 255 || count($p_aIP) !== 4) {
            return 'N/A';
        }
        if (self::$_oFileHandle === null) {
            self::init();
        }
        $nip2 = pack('N', ip2long($p_sIP));
        $tmp_offset = (int) $p_aIP[0] * 4;
        $start = unpack('Vlen', self::$_blIndex[$tmp_offset] . self::$_blIndex[$tmp_offset + 1] . self::$_blIndex[$tmp_offset + 2] . self::$_blIndex[$tmp_offset + 3]);
        $index_offset = $index_length = null;
        $max_comp_len = self::$_aOffset['len'] - 1024 - 4;
        for ($start = $start['len'] * 8 + 1024; $start < $max_comp_len; $start += 8) {
            if (self::$_blIndex{$start} . self::$_blIndex{$start + 1} . self::$_blIndex{$start + 2} . self::$_blIndex{$start + 3} >= $nip2) {
                $index_offset = unpack('Vlen', self::$_blIndex{$start + 4} . self::$_blIndex{$start + 5} . self::$_blIndex{$start + 6} . "\x0");
                $index_length = unpack('Clen', self::$_blIndex{$start + 7});
                break;
            }
        }
        if ($index_offset === null) {
            return 'N/A';
        }
        fseek(self::$_oFileHandle, self::$_aOffset['len'] + $index_offset['len'] - 1024);
        return explode("\t", fread(self::$_oFileHandle, $index_length['len']));
    }

    private static function init()
    {
        if (self::$_oFileHandle === null) {
            self::$_oFileHandle = fopen(__DIR__ . '/ipdb.dat', 'rb');
            if (self::$_oFileHandle === FALSE) {
                throw new \Exception('Invalid ipdb.dat file!');
            }
            self::$_aOffset = unpack('Nlen', fread(self::$_oFileHandle, 4));
            if (self::$_aOffset['len'] < 4) {
                throw new \Exception('Invalid ipdb.dat file!');
            }
            self::$_blIndex = fread(self::$_oFileHandle, self::$_aOffset['len'] - 4);
        }
    }
}
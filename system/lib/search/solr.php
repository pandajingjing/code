<?php
/**
 * solr
 *
 * 搜索
 * @namespace panda\lib\search
 * @package lib_search
 */
namespace panda\lib\search;

/**
 * solr
 *
 * 搜索
 */
class solr
{

    private static $_aCache;

    static function getInstance($sInstance)
    {
        if (! empty(self::$_aCache[$sInstance])) {
            return self::$_aCache[$sInstance];
        }
        
        // self::$_aCache[$sInstance] = new Solarium\Client(get_config($sInstance, 'solr'));
        return self::$_aCache[$sInstance];
    }
}

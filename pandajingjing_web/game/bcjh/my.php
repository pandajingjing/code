<?php
/**
 * my
 *
 * @namespace app\controller\game\bcjh
 */
namespace app\controller\game\bcjh;

use app\controller\base;
use app\bll\game\bcjh as BllBcjh;

/**
 * bcjh
 */
class my extends base
{

    function doRequest()
    {
        $oBll = new BllBcjh();
        $aICanUnlockRecipes = $oBll->getICanUnlockRecipes();
        $aIWantRunesChefs = $oBll->getIWantRunesChefs();
        $aIWantRuneNames = $oBll->getIWantRuneNames();
        $aIGetChefs = $oBll->getIGetChefs();
        $aIGetRecipes = $oBll->getIGetRecipes();
        $this->setPageData('aICanUnlockRecipes', $aICanUnlockRecipes);
        $this->setPageData('aIWantRunesChefs', $aIWantRunesChefs);
        $this->setPageData('aIWantRuneNames', $aIWantRuneNames);
        $this->setPageData('aIGetChefs', $aIGetChefs);
        $this->setPageData('aIGetRecipes', $aIGetRecipes);
        return '/game/bcjh/my';
    }
}
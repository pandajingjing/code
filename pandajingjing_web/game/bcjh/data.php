<?php
/**
 * data
 *
 * @namespace app\controller\game\bcjh
 */
namespace app\controller\game\bcjh;

use app\controller\base;
use app\bll\game\bcjh as BllBcjh;

/**
 * data
 */
class data extends base
{

    function doRequest()
    {
        $oBll = new BllBcjh();
        $aRecipes = $oBll->getRecipes();
        $aOrigins = $oBll->getOrigins();
        $aIngredients = $oBll->getIngredients();
        $aRunes = $oBll->getRunes();
        $aChefs = $oBll->getChefs();
        $aGuests = $oBll->getGuests();
        $this->setPageData('aRecipes', $aRecipes);
        $this->setPageData('aOrigins', $aOrigins);
        $this->setPageData('aIngredients', $aIngredients);
        $this->setPageData('aRunes', $aRunes);
        $this->setPageData('aChefs', $aChefs);
        $this->setPageData('aGuests', $aGuests);
        return '/game/bcjh/data';
    }
}
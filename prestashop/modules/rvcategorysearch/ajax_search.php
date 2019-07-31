<?php
/**
*  @author    RV Templates
*  @copyright 2015-2018 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
include_once(dirname(__FILE__).'/rvcategorysearch.php');

if (empty($_REQUEST['queryString'])) {
    $search_string = '';
} else {
    $search_string = Tools::replaceAccentedChars($_REQUEST['queryString']);
    $id_cat = Tools::replaceAccentedChars($_REQUEST['id_Cat']);
}

$rvcategorysearch = new RvCategorySearch();
$searchResults = $rvcategorysearch->getSearchProduct($id_cat, Context::getContext()->language->id, $search_string);

if ($searchResults['total'] > 0) {
    Context::getContext()->smarty->assign(array(
        'searchTotal' => $searchResults['total'],
        'searchResults' => $searchResults['result'],
        'searchImage' => Configuration::get('RVCATEGORYSEARCH_IMAGE'),
        'searchCategoryName' => Configuration::get('RVCATEGORYSEARCH_CATEGORYNAME'),
        'searchPrice' => Configuration::get('RVCATEGORYSEARCH_PRICE'),
        'searchLimit' => Configuration::get('RVCATEGORYSEARCH_MAX_ITEM'),
        'query' => $search_string,
        'link' => Context::getContext()->link
    ));
} else {
    Context::getContext()->smarty->assign(array(
        'searchTotal' => 0,
        'searchResults' => null,
        'searchLimit' => Configuration::get('RVCATEGORYSEARCH_MAX_ITEM'),
        'query' => $search_string,
        'link' => Context::getContext()->link
    ));
}

echo $rvcategorysearch->display(dirname(__FILE__).'/rvcategorysearch.php', 'search_result.tpl');

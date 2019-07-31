{*
*  @author    RV Templates
*  @copyright 2015-2018 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*}
<div id="_desktop_rvsearch" class="col-lg-10">
<div class="rvcategorysearch">
    <div id="category_search">
        <form id="searchbox" method="get" action="{$search_controller_url|escape:'html':'UTF-8'}">
            <div class="rvsearch-main">
            <input name="controller" value="search" type="hidden">
            <input name="orderby" value="position" type="hidden">
            <input name="orderway" value="desc" type="hidden">
            {if isset($searchCategoryList) && $searchCategoryList}
                <div class="searchboxform-control">
                    <select name="all_category" id="all_category">
                        <option value="all">{l s='All Categories' mod='rvcategorysearch'}</option>
                        {$all_category|escape:'quotes':'UTF-8' nofilter}
                    </select>
                </div>
            {/if}
        </div>
            <button type="submit" name="submit_search" class="btn btn-primary button-search">
                <span>Buscar</span>
            </button>
            <div class="input-wrapper">
                <input class="search_query form-control" type="text" name="search_query" placeholder="{l s='Search Our Catalog' mod='rvcategorysearch'}" value="{$search_query|escape:'htmlall':'UTF-8'|stripslashes}" autocomplete="off" />
            </div>
            <div id="rvajax_search" style="display:none">
                <input type="hidden" value="{$base_ssl|escape:'html':'UTF-8'}/ajax_search.php" class="ajaxUrl" />
            </div>
        </form>

    </div>
</div>
</div>

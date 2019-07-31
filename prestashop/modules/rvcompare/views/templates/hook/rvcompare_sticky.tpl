{*
	*  @author    RV Templates
	*  @copyright 2015-2018 RV Templates. All Rights Reserved.
	*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	*}
	{if $comparator_max_item}
		<div class="rvsticky-compare">
			<a class="bt_compare" href="{$compareUrl}" title="{l s='Compare' mod='rvcompare'}" rel="nofollow">
				<i class='material-icons'>&#xE043;</i>
				<span class="compare-title">{l s='Compare' mod='rvcompare'}</span>
				<span class="total-compare-val">({count($compared_products)})</span>
			</a>
			<input type="hidden" name="compare_product_count" class="compare_product_count" value="{count($compared_products)}" />
		</div>
	{/if}
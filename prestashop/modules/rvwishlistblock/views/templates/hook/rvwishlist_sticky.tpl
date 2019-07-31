{*
*  @author    RV Templates
*  @copyright 2015-2017 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*}

<div class="sticky-wishlist">
	<a class="wl_top_btn" href="{$link->getModuleLink('rvwishlistblock', 'mywishlist', array(), true)|escape:'html':'UTF-8'}" title="{l s='Wishlists' mod='rvwishlistblock'}" rel="nofollow">
		<i class="material-icons">favorite</i>
		<span class="wishlist-title">{l s='Wishlist' mod='rvwishlistblock'}</span>
		<span class="cart-wishlist-number">({$count_product})</span>
	</a>
</div>

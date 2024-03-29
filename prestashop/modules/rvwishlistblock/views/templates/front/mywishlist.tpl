{*
*  @author    RV Templates
*  @copyright 2015-2017 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*}
{extends file=$layout}

{block name='content'}
        <div id="mywishlist">
            <h1 class="h1">{l s='My wishlists' mod='rvwishlistblock'}</h1>
            {if isset($errors) && $errors}
                <div class="alert alert-danger">
                    {foreach from=$errors key=k item=error}
                        <div class="error">{$error}</div>
                    {/foreach}
                </div>
            {/if}

            {if $id_customer|intval neq 0}
                <form method="post" class="card card-block" id="form_wishlist">
                    <fieldset>
                        <h3 class="page-subheading">{l s='New wishlist' mod='rvwishlistblock'}</h3>
                        <div class="form-group">
                            <input type="hidden" name="token" value="{$token|escape:'html':'UTF-8'}" />
                            <label class="align_right" for="name">
                                {l s='Name' mod='rvwishlistblock'}
                            </label>
                            <input type="text" id="name" name="name" class="inputTxt form-control" value="{if isset($smarty.post.name) and $errors|@count > 0}{$smarty.post.name|escape:'html':'UTF-8'}{/if}" />
                        </div>
                        <p class="submit">
                            <button id="submitWishlist" class="btn btn-primary" type="submit" name="submitWishlist">
                                <span>{l s='Save' mod='rvwishlistblock'}</span>
                            </button>
                        </p>
                    </fieldset>
                </form>
                {if $wishlists}
                    <div id="block-history" class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="first_item">{l s='Name' mod='rvwishlistblock'}</th>
                                    <th class="item mywishlist_first">{l s='Qty' mod='rvwishlistblock'}</th>
                                    <th class="item mywishlist_first">{l s='Viewed' mod='rvwishlistblock'}</th>
                                    <th class="item mywishlist_second">{l s='Created' mod='rvwishlistblock'}</th>
                                    <th class="item mywishlist_second">{l s='Direct Link' mod='rvwishlistblock'}</th>
                                    <th class="item mywishlist_second">{l s='Default' mod='rvwishlistblock'}</th>
                                    <th class="last_item mywishlist_first">{l s='Delete' mod='rvwishlistblock'}</th>
                                </tr>
                            </thead>
                            <tbody>
                                {section name=i loop=$wishlists}
                                    <tr id="wishlist_{$wishlists[i].id_wishlist|intval}">
                                        <td style="width:200px;">
                                            <a href="#" onclick="javascript:event.preventDefault();WishlistManage('block-order-detail', '{$wishlists[i].id_wishlist|intval}');">
                                                {$wishlists[i].name|truncate:30:'...'|escape:'htmlall':'UTF-8'}
                                            </a>
                                        </td>
                                        <td class="bold align_center">
                                            {assign var=n value=0}
                                            {foreach from=$nbProducts item=nb name=i}
                                                {if $nb.id_wishlist eq $wishlists[i].id_wishlist}
                                                    {assign var=n value=$nb.nbProducts|intval}
                                                {/if}
                                            {/foreach}
                                            {if $n}
                                                {$n|intval}
                                            {else}
                                                0
                                            {/if}
                                        </td>
                                        <td>{$wishlists[i].counter|intval}</td>
                                        <td>{$wishlists[i].date_add|date_format:"%Y-%m-%d"}</td>
                                        <td>
                                            <a href="#" onclick="javascript:event.preventDefault();WishlistManage('block-order-detail', '{$wishlists[i].id_wishlist|intval}');">
                                                {l s='View' mod='rvwishlistblock'}
                                            </a>
                                        </td>
                                        <td class="wishlist_default">
                                            {if isset($wishlists[i].default) && $wishlists[i].default == 1}
                                                <p class="is_wish_list_default">
                                                    <i class="fa fa-check-square"></i>
                                                </p>
                                            {else}
                                                <a href="#" onclick="javascript:event.preventDefault();(WishlistDefault('wishlist_{$wishlists[i].id_wishlist|intval}', '{$wishlists[i].id_wishlist|intval}'));">
                                                    <i class="fa fa-square"></i>
                                                </a>
                                            {/if}
                                        </td>
                                        <td class="wishlist_delete">
                                            <a class="icon" href="#" onclick="javascript:event.preventDefault();return (WishlistDelete('wishlist_{$wishlists[i].id_wishlist|intval}', '{$wishlists[i].id_wishlist|intval}', '{l s='Do you really want to delete this wishlist ?' mod='rvwishlistblock' js=1}'));">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                {/section}
                            </tbody>
                        </table>
                    </div>
                    <div id="block-order-detail">&nbsp;</div>
                {/if}
            {/if}
            <div class="page-footer clearfix">
                {block name='my_account_links'}
                    <a href="{$urls.pages.my_account}" class="account-link">
                        <i class="fa fa-angle-left"></i>
                        <span>{l s='Back to your account' mod='rvwishlistblock'}</span>
                    </a>
                    <a href="{$urls.pages.index}" class="account-link">
                        <i class="fa fa-home"></i>
                        <span>{l s='Home' mod='rvwishlistblock'}</span>
                    </a>
                {/block}
            </div>
        </div>
{/block}

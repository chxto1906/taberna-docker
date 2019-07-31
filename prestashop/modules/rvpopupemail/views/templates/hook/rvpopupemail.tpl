{*
*  @author    RV Templates
*  @copyright 2015-2018 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*}

{if $DISPLAY_EMAILSUBCRIPTION == 1}
<div class="text-center animated" id="popup_email_subscription">
    <button type="button" class="close"> 
        <i class="material-icons">clear</i>
    </button> 
    {if $RV_BANNER_EMAILSUB}
        <img class="image-banner-emailsub" src="{$RV_BANNER_EMAILSUB}" />
    {/if}
    <div class="emailsub-text-wrapper">
        {if $RV_TITLE_EMAILSUB}
            <h3 class="title">{$RV_TITLE_EMAILSUB}</h3>
        {/if}
        
        {if $RV_DESCRIPTION_EMAILSUB}
            <div class="description">{$RV_DESCRIPTION_EMAILSUB}</div>
        {/if}

        <form  method="post" data-url="{$URL_AJAX_EMALISUBSCRIPTION}">
            <div class="input-group"> 
                <input type="email" id='rvemail' name="email" value="" class="form-control" placeholder="{l s='Your e-mail'}" />
                <input type="hidden" id='rvaction' name="action" value="0" />
            </div>
            <div class="input-group-btn">
                <button type="button" class="btn btn-primary" id="js_rvsubmitnewsletter">Subcribe</button>
            </div>
            <div class="result_email_subscription"></div>

            <div class="form-check">
                <label class="form-check-label">
                <input type="checkbox" id="rv_popup_hide"  class="form-check-input" value="1"> 
                <i class="custom-checkbox"></i>
                    Don't show this popup again  
                </label>
            </div>

        </form>
    </div>
    <div class="btn-close">
                    <button type="button" class="btn btn-primary close">Close</button>
                </div>
</div>
<div id="bg_popup_email_subscription"></div>
{/if}
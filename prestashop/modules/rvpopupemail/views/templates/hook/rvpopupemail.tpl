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
    

    <div class="row">
        <div class="col-lg-4 col-sm-3 col-xs-1"></div>
        <div class="col-lg-4 col-sm-6 col-xs-10 content-descuento">
            <div id="jssor_1" style="position:relative;top:0px;left:0px;width:380px;height:380px;overflow:hidden;">
                <div data-u="slides" style="position:absolute;top:0px;left:0px;width:380px;height:380px;overflow:hidden;">
                    <div data-b="2">
                        <img data-u="image" data-src2="../../modules/rvpopupemail/img/promos/JohnnieImperial.png">
                        <div data-ts="preserve-3d" style="position:absolute;top:325px;left:0px;width:328px;height:50px;">
                            <img data-t="30" style="position:absolute;top:0px;left:-763px;width:328px;height:50px;max-width:328px;" data-src2="../../modules/rvpopupemail/img/promos/textPromo1.png" />
                        </div>
                    </div>
                    <div data-b="2">
                        <img data-u="image" src="../../modules/rvpopupemail/img/promos/SandyMacImperial.png" />
                        <div data-ts="preserve-3d" style="position:absolute;top:325px;left:0px;width:328px;height:50px;">
                            <img data-t="30" style="position:absolute;top:0px;left:-763px;width:328px;height:50px;max-width:328px;" data-src2="../../modules/rvpopupemail/img/promos/textPromo2.png" />
                        </div>
                    </div>
                    <div data-b="2">
                        <img data-u="image" src="../../modules/rvpopupemail/img/promos/SmirnoffTonica.png" />
                        <div data-ts="preserve-3d" style="position:absolute;top:325px;left:0px;width:328px;height:50px;">
                            <img data-t="30" style="position:absolute;top:0px;left:-763px;width:328px;height:50px;max-width:328px;" data-src2="../../modules/rvpopupemail/img/promos/textPromo4.png" />
                        </div>
                    </div>
                    <div data-b="2">
                        <img data-u="image" src="../../modules/rvpopupemail/img/promos/TanquerayGingerAle.png" />
                        <div data-ts="preserve-3d" style="position:absolute;top:325px;left:0px;width:328px;height:50px;">
                            <img data-t="30" style="position:absolute;top:0px;left:-763px;width:328px;height:50px;max-width:328px;" data-src2="../../modules/rvpopupemail/img/promos/textPromo3.png" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-3 col-xs-1"></div>
    </div>






    {*

    {if $RV_BANNER_EMAILSUB}
        <img class="image-banner-emailsub" src="{$RV_BANNER_EMAILSUB}" />
    {/if}

    


    <div class="emailsub-text-wrapper">

        <div id='demo' style="min-height: 400px;height: 400px;">
            <div><a href='#'><img class="img-fluid" src="../../modules/rvpopupemail/img/promos/JohnnieImperial.png"></a></div>
            <div><a href='#'><img class="img-fluid" src="../../modules/rvpopupemail/img/promos/SandyMacImperial.png"></a></div>
            <div><a href='#'><img class="img-fluid" src="../../modules/rvpopupemail/img/promos/SmirnoffTonica.png"></a></div>
            <div><a href='#'><img class="img-fluid" src="../../modules/rvpopupemail/img/promos/TanquerayGingerAle.png"></a></div>
        </div>

*}


{*

        {if $RV_TITLE_EMAILSUB}
            <h3 class="title">{$RV_TITLE_EMAILSUB}</h3>
        {/if}
        
        {if $RV_DESCRIPTION_EMAILSUB}
            <div class="description">
                <h3>{$RV_DESCRIPTION_EMAILSUB}</h3>
            </div>
        {/if}

        <div class="input-group-btn">
            <a href="/index.php?id_category=123&controller=category" style="color: #000000;">
                <h4>Ver promociones >></h4>
            </a>
        </div>

*}

        <!--<form  method="post" data-url="{$URL_AJAX_EMALISUBSCRIPTION}">
            
            
            <div class="input-group"> 
                <input type="email" id='rvemail' name="email" value="" class="form-control" placeholder="{l s='Your e-mail'}" />
                <input type="hidden" id='rvaction' name="action" value="0" />
            </div>
            <div class="input-group-btn">
                <button type="button" class="btn btn-primary" id="js_rvsubmitnewsletter">Subcribe</button>
            </div>
            <div class="result_email_subscription"></div>
            -->


            

        <!--</form>-->
   {* </div> *}
    <div class="btn-close">
        <br>
        <div class="form-check">
            <label class="form-check-label">
            {*<input type="checkbox" id="rv_popup_hide1"  class="form-check-input" value="1">*} 
            <i class="custom-checkbox"></i>
                <a href="/index.php?id_category=123&controller=category" style="color: #000000;">
                <h5 class="text-promo-click">Adquirir ahora >></h5>
            </a>
            </label>
        </div>
    </div>
    
</div>
<div id="bg_popup_email_subscription"></div>
{/if}
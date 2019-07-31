{**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2017 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
<!doctype html>
<html lang="{$language.iso_code}">

  <head>
    {block name='head'}
      {include file='_partials/head.tpl'}
    {/block}
  </head>

  <body id="{$page.page_name}" class="{$page.body_classes|classnames}" {if Configuration::get('RVCUSTOMSETTING_ADD_CONTAINER')} style='{hook h="displayBackgroundBody"}' {/if}>

    {block name='hook_after_body_opening_tag'}
      {hook h='displayAfterBodyOpeningTag'}
    {/block}

    {if Configuration::get('RVFRONTSIDE_THEME_SETTING_SHOW')}
      <div class="rvtheme-control">
        <div class="rvcontrol-icon">
            <i class='material-icons'>&#xe8b8;</i>
        </div> 
          <div class="rvcontrol-wrapper">
            <div>
                <button class="rvcontrol-reset">reset</button>
            </div>
            <table>
              <tr class="rvselect-theme rvall-theme-content">
                <td>
                  <div class="rvselect-theme-name">Select Theme</div>
                </td>
                <td>
                  <select class="rvselect-theme-select" id="select_theme">
                    <option value="default_theme">Theme 1</option>
                    <option value="theme_custom">Custom</option>
                  </select>
                </td>  
              </tr>

              <tr class="rvtheme-color-one rvall-theme-content">
                <td>
                  <div class="rvcolor-theme-name">Custome Color 1</div>
                </td>
                <td>
                  <div class="rvtheme-color-box">
                    <input type="text" id="themecolor1" class="rvtheme-color-box-1" data-control="saturation" >
                  </div>
                </td>    
              </tr>

                 <tr class="rvtheme-color-two rvall-theme-content">  
                  <td>
                    <div class="rvcolor-theme-name">Custome Color 2</div>
                  </td>
                  <td>
                    <div class="rvtheme-color-box">
                      <input type="text" id="themecolor2" class="rvtheme-color-box-2" data-control="saturation">
                    </div>
                  </td>  
                </tr>

                <tr class="rvtheme-box-layout rvall-theme-content">
                  <td>
                      <div class="rvtheme-layout-name">Box-Layout</div>
                  </td>
                  <td>
                      <label class="checkbox-inline rvtheme-option rvtheme-box-layout-option">
                        <input type="checkbox" data-toggle="toggle">
                      </label>
                   </td>   
                </tr>
                <tr class="rvtheme-background-patten rvall-theme-content">
                  <td>
                      <div class="rvtheme-background-pattern-name"> Background Pattern</div>
                  </td>
                  <td>
                    <div class="rvtheme-all-pattern-wrapper">
                        <div class="rvtheme-all-pattern">
                            <div id="pattern1" class="rvtheme-pattern-image rvtheme-pattern-image1" style="background-image:url('{$urls.img_url}pattern/pattern1.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern2" class="rvtheme-pattern-image rvtheme-pattern-image2" style="background-image:url('{$urls.img_url}pattern/pattern2.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern3" class="rvtheme-pattern-image rvtheme-pattern-image3" style="background-image:url('{$urls.img_url}pattern/pattern3.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern4" class="rvtheme-pattern-image rvtheme-pattern-image4" style="background-image:url('{$urls.img_url}pattern/pattern4.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern5" class="rvtheme-pattern-image rvtheme-pattern-image5" style="background-image:url('{$urls.img_url}pattern/pattern5.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern6" class="rvtheme-pattern-image rvtheme-pattern-image6" style="background-image:url('{$urls.img_url}pattern/pattern6.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern7" class="rvtheme-pattern-image rvtheme-pattern-image7" style="background-image:url('{$urls.img_url}pattern/pattern7.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern8" class="rvtheme-pattern-image rvtheme-pattern-image8" style="background-image:url('{$urls.img_url}pattern/pattern8.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern9" class="rvtheme-pattern-image rvtheme-pattern-image9" style="background-image:url('{$urls.img_url}pattern/pattern9.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern10" class="rvtheme-pattern-image rvtheme-pattern-image10" style="background-image:url('{$urls.img_url}pattern/pattern10.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern11" class="rvtheme-pattern-image rvtheme-pattern-image11" style="background-image:url('{$urls.img_url}pattern/pattern11.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern12" class="rvtheme-pattern-image rvtheme-pattern-image12" style="background-image:url('{$urls.img_url}pattern/pattern12.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern13" class="rvtheme-pattern-image rvtheme-pattern-image13" style="background-image:url('{$urls.img_url}pattern/pattern13.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern14" class="rvtheme-pattern-image rvtheme-pattern-image14" style="background-image:url('{$urls.img_url}pattern/pattern14.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern15" class="rvtheme-pattern-image rvtheme-pattern-image15" style="background-image:url('{$urls.img_url}pattern/pattern15.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern16" class="rvtheme-pattern-image rvtheme-pattern-image16" style="background-image:url('{$urls.img_url}pattern/pattern16.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern17" class="rvtheme-pattern-image rvtheme-pattern-image17" style="background-image:url('{$urls.img_url}pattern/pattern17.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern18" class="rvtheme-pattern-image rvtheme-pattern-image18" style="background-image:url('{$urls.img_url}pattern/pattern18.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern19" class="rvtheme-pattern-image rvtheme-pattern-image19" style="background-image:url('{$urls.img_url}pattern/pattern19.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern20" class="rvtheme-pattern-image rvtheme-pattern-image20" style="background-image:url('{$urls.img_url}pattern/pattern20.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern21" class="rvtheme-pattern-image rvtheme-pattern-image21" style="background-image:url('{$urls.img_url}pattern/pattern21.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern22" class="rvtheme-pattern-image rvtheme-pattern-image22" style="background-image:url('{$urls.img_url}pattern/pattern22.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern23" class="rvtheme-pattern-image rvtheme-pattern-image23" style="background-image:url('{$urls.img_url}pattern/pattern23.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern24" class="rvtheme-pattern-image rvtheme-pattern-image24" style="background-image:url('{$urls.img_url}pattern/pattern24.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern25" class="rvtheme-pattern-image rvtheme-pattern-image25" style="background-image:url('{$urls.img_url}pattern/pattern25.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern26" class="rvtheme-pattern-image rvtheme-pattern-image26" style="background-image:url('{$urls.img_url}pattern/pattern26.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern27" class="rvtheme-pattern-image rvtheme-pattern-image27" style="background-image:url('{$urls.img_url}pattern/pattern27.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern28" class="rvtheme-pattern-image rvtheme-pattern-image28" style="background-image:url('{$urls.img_url}pattern/pattern28.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern29" class="rvtheme-pattern-image rvtheme-pattern-image29" style="background-image:url('{$urls.img_url}pattern/pattern29.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern30" class="rvtheme-pattern-image rvtheme-pattern-image30" style="background-image:url('{$urls.img_url}pattern/pattern30.png')"></div>
                        </div>


                        </div>
                        <p class="notice">custome background also available in admin.</p>
                   </td>   
                </tr>

                <tr class="rvtheme-background-color rvall-theme-content">  
                  <td>
                    <div class="rvbgcolor-theme-name">Background color</div>
                  </td>
                  <td>
                    <div class="rvtheme-bgcolor-box">
                      <input type="text" id="themebgcolor2" data-control="saturation" class="rvtheme-bgcolor-box-2">
                    </div>
                  </td>  
                </tr>


              <tr class="rvtheme-right-sticky rvall-theme-content">
                <td>
                    <div class="rvtheme-right-sticky-name">Right Stickt Enable</div>
                </td>
                <td>
                    <label class="checkbox-inline rvtheme-option rvtheme-right-sticky-option">
                      <input type="checkbox" checked data-toggle="toggle">
                    </label>
                 </td>   
              </tr>

            </table>
          </div>
      </div>
  {/if} 

    <main id="page" class="{if Configuration::get('RVCUSTOMSETTING_ADD_CONTAINER')}container rvbox-layout{/if}">
      {block name='product_activation'}
        {include file='catalog/_partials/product-activation.tpl'}
      {/block}

      <header id="header">
        {block name='header'}
          {include file='_partials/header.tpl'}
        {/block}
      </header>

      {block name='notifications'}
        {include file='_partials/notifications.tpl'}
      {/block}

       {if $page.page_name != 'index'}
        {block name='breadcrumb'}
          {include file='_partials/breadcrumb.tpl'}
        {/block}
      {/if}
      
      {if $page.page_name == 'index'}
      {capture name='displayTopColumn'}{hook h='displayTopColumn'}{/capture}
      {if $smarty.capture.displayTopColumn}
        <div id="top_column">
          {$smarty.capture.displayTopColumn nofilter}
        </div>
      {/if}
      {/if}

      {if $page.page_name == 'index'}
      {capture name='displayHomeTop'}{hook h='displayHomeTop'}{/capture}
      {if $smarty.capture.displayHomeTop}
        <div id="top_home">
          {$smarty.capture.displayHomeTop nofilter}
        </div>
      {/if}
      {/if}

      <section id="wrapper">
        {hook h="displayWrapperTop"}
        <div class="container">
          <div class="row">
          {block name="left_column"}

              {if $page.page_name != 'index'}
                  <div id="_desktop_left_column" class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
                    <div id="left-column">
                  {if $page.page_name == 'product'}
                    {hook h='displayLeftColumnProduct'}
                  {else}
                    {hook h="displayLeftColumn"}
                  {/if}
                    </div>
                  </div>
              {/if}
          {/block}

          {block name="content_wrapper"}
              <div id="content-wrapper" class="left-column right-column col-xs-12 col-sm-12 col-md-12 col-lg-6">
                {hook h="displayContentWrapperTop"}
                {block name="content"}
                  <p>Hello world! This is HTML5 Boilerplate.</p>
                {/block}
                {hook h="displayContentWrapperBottom"}
            </div>
          {/block}

          {block name="right_column"}
              <div id="_desktop_right_column" class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
                <div id="right-column">
              {if $page.page_name == 'product'}
                {hook h='displayRightColumnProduct'}
              {else}
                {hook h="displayRightColumn"}
              {/if}
            </div>
              </div>
          {/block}
        </div>
        </div>
        <div class="container">
          <div class="row">
        {hook h="displayWrapperBottom"}
          </div>
        </div>
      </section>

      {if $page.page_name == 'index'}
        {capture name='displayHomeBottom'}{hook h='displayHomeBottom'}{/capture}
        {if $smarty.capture.displayHomeBottom}
          <div id="bottom_home">
            {$smarty.capture.displayHomeBottom nofilter}
          </div>
        {/if}
      {/if}

      <div class="container">
        <div id="_mobile_left_column"></div>
        <div id="_mobile_right_column"></div>
        <div class="clearfix"></div>
      </div>

      <footer id="footer">
        {block name="footer"}
          {include file="_partials/footer.tpl"}
        {/block}
      </footer>

    </main>
    <a class="backtotop" href="#" title="Back to Top" style="display:none;">&nbsp;</a>
    {block name='javascript_bottom'}
      {include file="_partials/javascript.tpl" javascript=$javascript.bottom}
      {if Configuration::get('RVFRONTSIDE_THEME_SETTING_SHOW')}
        <!-- START THEME_CONTROL -->
        <script type="text/javascript" src="{$urls.js_url}jquery.minicolors.js"></script>
        <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
        <!-- END THEME_CONTROL -->
      {/if}
    {/block}

    {block name='hook_before_body_closing_tag'}
      {hook h='displayBeforeBodyClosingTag'}
    {/block}
  </body>

</html>

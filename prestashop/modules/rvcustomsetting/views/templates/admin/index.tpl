{*
*  @author    RV Templates
*  @copyright 2017-2018 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*}

<div class="rvadmincustom-setting">
	<div class="rvadmincustom-setting-all-tabs">
		<div tab-number='#fieldset_0' class="rvadmincustom-setting-tab {if $tab_number == '#fieldset_0'}rvadmincustom-setting-active{/if}">Theme Configuration</div>
		<div tab-number='#fieldset_1_1' class="rvadmincustom-setting-tab {if $tab_number == '#fieldset_1_1'}rvadmincustom-setting-active{/if}">App Link</div>
	</div>
</div>
<div>
	<input type="hidden" name="rvcustom_setting_tab_number" id='rvcustom-setting-tab-number' value="{$tab_number|escape:'htmlall':'UTF-8'}">
</div>
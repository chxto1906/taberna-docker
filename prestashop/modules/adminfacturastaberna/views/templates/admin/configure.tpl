{*
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="panel" id="panel-facturas" data-token="{$token}">
	<h3><i class="icon icon-credit-card"></i> {l s='Facturas Taberna' mod='adminfacturastaberna'}</h3>
	
	<div class="form-check">
	  <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="option1" checked>
	  <label class="form-check-label" for="exampleRadios1">
	    Último mes
	  </label>
	</div>
	<div class="form-check">
	  <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="option2">
	  <label class="form-check-label" for="exampleRadios2">
	    Buscar por fecha específica
	  </label>
	</div>

	<div class="form-check">
	  <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios3" value="option3">
	  <label class="form-check-label" for="exampleRadios3">
	    Todas
	  </label>
	</div>

	<table class="table table-striped">
	  <thead>
	    <tr>
	      <th scope="col">#</th>
	      <th scope="col">First</th>
	      <th scope="col">Last</th>
	      <th scope="col">Handle</th>
	    </tr>
	  </thead>
	  <tbody>
	    <tr>
	      <th scope="row">1</th>
	      <td>Mark</td>
	      <td>Otto</td>
	      <td>@mdo</td>
	    </tr>
	    <tr>
	      <th scope="row">2</th>
	      <td>Jacob</td>
	      <td>Thornton</td>
	      <td>@fat</td>
	    </tr>
	    <tr>
	      <th scope="row">3</th>
	      <td>Larry</td>
	      <td>the Bird</td>
	      <td>@twitter</td>
	    </tr>
	  </tbody>
	</table>


</div>


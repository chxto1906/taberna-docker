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

<div class="row" data-token="{$token}">
	<div class="col-sm-12">
		<form action="{$link->getAdminLink('Adminadminfacturastaberna')}" method="post" enctype="multipart/form-data">
			Hace: 
			<input type="number" name="months_ago" value="{$months}" min="1" max="12">
			mes/es
			<input type="submit" name="" value="Obtener">
			<hr>
			<div class="content container-fluid">
				<div class="table-responsive">
		  			<table class="table product mt-3">
					  <thead>
					    <tr>
					    	<th scope="col">ID</th>
						    <th scope="col"># Factura</th>
						    <th scope="col"># Factura SAP</th>
						    <th scope="col"># Documento Contable</th>
						    <th scope="col">Fecha</th>
						    <th scope="col">Valor</th>
						    <th scope="col">Método de pago</th>
						    <th scope="col">Promo Valor Tax Excluído</th>
						    <th scope="col">Promo Valor Tax Incluído</th>
						    <th scope="col">Numero Guía Delivery</th>
						    <th scope="col">PDF</th>
					    </tr>
					  </thead>
					  <tbody>
					  	{foreach from=$pedidos item=pedido}
							<tr>
								<th scope="row">{$pedido.id}</th>
						      	<th scope="row">{$pedido.num_fact}</th>
						      	<th>{$pedido.numero_factura_sap}</th>
						     	<th>{$pedido.numero_documento_contable}</th>
						      	<td>{$pedido.fecha_autorizacion}</td>
						      	<td>{$pedido.importe_total}</td>
						      	<td>{$pedido.payment}</td>

						      	<td>{$pedido.promo_tax_exc}</td>
						      	<td>{$pedido.promo_tax_inc}</td>
						      	<td>{$pedido.numero_guia_delivery}</td>
						      	<td><a href="{$pedido.url_pdf}" target="_blank">Ver >></a></td>
						    </tr>
						{/foreach}
					  </tbody>
					</table>
				</div>
			</div>

		</form>
	</div>
</div>


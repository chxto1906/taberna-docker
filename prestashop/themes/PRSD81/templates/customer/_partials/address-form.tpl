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

<script type="text/javascript">
   // JavaScript source code
  function inicializar() {
      //Opciones del mapa
      let $lat = $(".form-control[name='latitude']");
      let $lng = $(".form-control[name='longitude']");
      let latitude = $lat.val();
      let longitude = $lng.val();
      let address1 = $(".form-control[name='address1']").val();
      let marker = true;
      let Marcador = null;
      if (!latitude){
        latitude = -2.89162500;
        longitude = -79.02054500;
        marker = false;
      }

      var OpcionesMapa = {
          center: new google.maps.LatLng(latitude, longitude),
          mapTypeId: google.maps.MapTypeId.ROADMAP, //ROADMAP  SATELLITE HYBRID TERRAIN
          zoom: 16
      };
   
      var miMapa;
      //constructor
      miMapa = new google.maps.Map(document.getElementById('map-address'), OpcionesMapa);

      //Añadimos el marcador
      if (marker){
        Marcador = new google.maps.Marker({
          position: new google.maps.LatLng(latitude, longitude),
          map: miMapa,
          title:address1,
          draggable: true
        });
        google.maps.event.addListener(Marcador, 'dragend', function() {
          updatePosition(Marcador.getPosition());
        });
      }

      google.maps.event.addListener(miMapa, 'click', function(event) {
        if (Marcador) {
          Marcador.setPosition(event.latLng)
        } else {
          address1 = $(".form-control[name='address1']").val();
          Marcador = new google.maps.Marker({
            map: miMapa,
            position: event.latLng,
            title:address1,
            draggable: true
          });
        }
        updatePosition(event.latLng);
      });

  }

  function updatePosition(latLng){
    let lat = $(".form-control[name='latitude']");
    let lng = $(".form-control[name='longitude']");
    lat.val(latLng.lat());
    lng.val(latLng.lng());
  }
   
  function CargaScript() {
      var script = document.createElement('script');
      script.src = 'https://maps.googleapis.com/maps/api/js?sensor=false&callback=inicializar&key=AIzaSyBOQnXRSLyOJL6nmGR_p9_Lc_AZvYMlkXA';
      document.body.appendChild(script);  
      $(".form-control[name='latitude']").attr("readonly","readonly");
      $(".form-control[name='longitude']").attr("readonly","readonly");               
  }
   
  window.onload = CargaScript;

</script>


{block name="address_form"}
  <div class="js-address-form">
    {include file='_partials/form-errors.tpl' errors=$errors['']}

    {block name="address_form_url"}
    <form
      method="POST"
      action="{url entity='address' params=['id_address' => $id_address,'type_dni' => $type_dni]}"
      data-id-address="{$id_address}"
      data-refresh-url="{url entity='address' params=['ajax' => 1, 'action' => 'addressForm']}"
    >
    {/block}

      {block name="address_form_fields"}
        <section class="form-fields">
          {block name='form_fields'}
            {foreach key="key" from=$formFields item="field"}
              {if $key eq 'latitude'}
                <div class="form-group row ">
                  <label class="col-md-3 form-control-label">Localización</label>
                  <div class="col-md-6">
                    <div id="map-address"></div>
                  </div>
                  <div class="col-md-3 form-control-comment"></div>
                </div>
              {/if}
              {if $key eq 'type_dni'}
                {assign var="type" value=$field.value}
                <div class="form-group row ">
                  <label class="col-md-3 form-control-label">
                    Tipo de documento
                  </label>
                  <div class="col-md-6">
                    <select style="height: 34px !important;" class="form-control" id="type_dni" name="type_dni" maxlength="1" required="true">
                      <option {if $type eq 'Cédula'} selected {/if} value="Cédula">Cédula</option>
                      <option {if $type eq 'Ruc'} selected {/if} value="Ruc">RUC</option>
                      <option {if $type eq 'Pasaporte'} selected {/if} value="Pasaporte">Pasaporte</option>
                    </select>
                  </div>
                  <div class="col-md-3 "></div>
                </div>

              {/if}
              
              {*
              {if $key eq 'dni'}
                <div class="form-group row ">
                  <label class="col-md-3 ">
                    <select style="height: 34px !important;" class="form-control" id="type_dni" name="type_dni" maxlength="1" required="true">
                      <option {if $type eq 'Cédula'} selected {/if} value="Cédula">Cédula</option>
                      <option {if $type eq 'Ruc'} selected {/if} value="Ruc">RUC</option>
                      <option {if $type eq 'Pasaporte'} selected {/if} value="Pasaporte">Pasaporte</option>
                    </select>
                  </label>
                  <div class="col-md-6">
                    <input class="form-control" name="dni" type="text" value="{$field.value}" maxlength="13" required="">
                  </div>
                  <div class="col-md-3 "></div>
                </div>
              {else}

                {if $key ne 'type_dni'}
                  {block name='form_field'}
                    {form_field field=$field}
                  {/block}
                {/if}
              {/if} *}
              {if $key ne 'type_dni'}
                {block name='form_field'}
                  {form_field field=$field}
                {/block}
              {/if}

            {/foreach}
          {/block}
        </section>
      {/block}

      {block name="address_form_footer"}
      <footer class="form-footer clearfix">
        <input type="hidden" name="submitAddress" value="1">
        {block name='form_buttons'}
          <button class="btn btn-primary pull-xs-right" type="submit" class="form-control-submit">
            {l s='Save' d='Shop.Theme.Actions'}
          </button>
        {/block}
      </footer>
      {/block}

    </form>
  </div>
{/block}

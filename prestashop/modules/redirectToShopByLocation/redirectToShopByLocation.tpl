<div id="location-modal" class="modal fade" tabindex="-1" role="dialog" data-show="true" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-content-tienda">
      <div class="modal-header modal-header-tienda">
        <img class="img-pin-pop-up" src="{$module_dir}views/img/pin_map.png">
        <img class="img-responsive img-fluid" src="{$img_logo}">
        {*<h5 class="modal-title modal-title-tienda">{l s='La Taberna más cercana para ti:' d='redirectToShopByLocation'}</h5>*}

      </div>
      <div class="modal-body modal-body-tienda">
        <!-- 1 -->
        
        <h4 id="text-tienda-modal"></h4>
        <select id="tiendas_select_popup">
          {foreach from=$shops item=shop}
            <option value="http://{$shop.domain}/{$shop.virtual_uri}">{$shop.name}</option>
          {/foreach}
        </select>
        <h5 class="permitir-ubicacion-text">Ó permite al navegador obtener tu localización.</h5>
        {*<div id="store-localizated" class="loader loader--style1" title="0">
          <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
           width="40px" height="40px" viewBox="0 0 40 40" enable-background="new 0 0 40 40" xml:space="preserve">
          <path opacity="0.2" fill="#000" d="M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946
            s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634
            c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z"/>
          <path fill="#000" d="M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0
            C22.32,8.481,24.301,9.057,26.013,10.047z">
            <animateTransform attributeType="xml"
              attributeName="transform"
              type="rotate"
              from="0 20 20"
              to="360 20 20"
              dur="0.5s"
              repeatCount="indefinite"/>
            </path>
          </svg>
        </div>*}
        

      </div>
      <div class="modal-footer">
        <section class="section-pop-up">
          <input type="checkbox" class="form-checkbox" id="check-one"><label class="label-redirect" for="check-one">Confirmo que soy mayor de 18 años</label>
        </section>
        <br>

        <button id="btnCancelarModal" type="button" class="btn btn-secondary" data-dismiss="modal">{l s='Cancelar' d='redirectToShopByLocation'}</button>
        <button id="btnAceptarModalTienda" type="button" class="btn btn-primary disabled" disabled="disabled">{l s='Continuar' d='redirectToShopByLocation'}</button>
      </div>
    </div>
  </div>
</div>
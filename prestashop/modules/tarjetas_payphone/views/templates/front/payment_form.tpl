
<form action="{$action}" id="payment-form" method="POST" class="js-customer-form" style="padding: 10px;background: beige;">
  <section>  
      <input type="hidden" name="data" id="data">
    <div class="form-group row ">
      <label class="col-md-3 form-control-label required">
          Número de tarjeta
      </label>
      <div class="col-md-6">  
        <input class="form-control datos-tarj" id="cardNumber" autofocus type="text" size="20" autocomplete="off" required="true" >
      </div>
      <div class="col-md-3 form-control-comment">
                
      </div>
    </div> 

    <div class="form-group row " id="content-deferred">
      <label class="col-md-3 form-control-label required">
          Diferido
      </label>
      <div class="col-md-6">  

        <select class="form-control disabled" disabled="disabled" id="deferred">
          <option value="00000000">Corriente</option>
        </select>
        
      </div>

      <div class="col-md-3 form-control-comment">      
      </div>
    </div>

    <div class="form-group row ">
      <label class="col-md-3 form-control-label required">
          Nombre del titular
      </label>
      <div class="col-md-6">  
        <input class="form-control datos-tarj" id="holderName" type="text" autocomplete="off" required="">
      </div>
      <div class="col-md-3 form-control-comment">
                
      </div>
    </div>   
    <div class="form-group row ">
      <label class="col-md-3 form-control-label required">
          CVC
      </label>
      <div class="col-md-6">  
        <input style="width: 80px !important;" class="form-control datos-tarj" id="securityCode" size="3" maxlength="3" type="text" autocomplete="off" required="">
      </div>

      <div class="col-md-3 form-control-comment">      
      </div>
    </div>


    <div class="form-group row ">
      <label class="col-md-3 form-control-label required">
          Expiración (MM/AAAA)
      </label>
      <div class="col-md-6">  

        <select style="width: 80px !important;display: inline-block !important;" class="form-control datos-tarj" id="expirationMonth">
          {foreach from=$months item=month}
            <option value="{$month}">{$month}</option>
          {/foreach}
        </select>
        <span> / </span>
        <select style="width: 80px !important;display: inline-block !important;" class="form-control datos-tarj" id="expirationYear">
          {foreach from=$years item=year}
            <option value="{$year}">{$year}</option>
          {/foreach}
        </select>


      </div>

      <div class="col-md-3 form-control-comment">      
      </div>
    </div>

  </section>
</form>
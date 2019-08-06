
<form action="{$action}" id="payment-form" method="POST" class="js-customer-form">
  <section>  
      <input type="hidden" name="data" id="data">
    <div class="form-group row ">
      <label class="col-md-3 form-control-label required">
          {l s='Card number'}
      </label>
      <div class="col-md-6">  
        <input class="form-control datos-tarj" id="cardNumber" name="cardNumber" autofocus type="text" size="20" autocomplete="off" required="" >
      </div>
      <div class="col-md-3 form-control-comment">
                
      </div>
    </div> 

    <div class="form-group row " id="content-deferred">
      <label class="col-md-3 form-control-label required">
          {l s='Deferred'}
      </label>
      <div class="col-md-6">  

        <select class="form-control disabled" disabled="disabled" id="deferred" name="deferred">
          <option value="00000000">Corriente</option>
        </select>
        
      </div>

      <div class="col-md-3 form-control-comment">      
      </div>
    </div>

    <div class="form-group row ">
      <label class="col-md-3 form-control-label required">
          {l s='Holdername'}
      </label>
      <div class="col-md-6">  
        <input class="form-control datos-tarj" id="holderName" name="holderName" type="text" autocomplete="off" required="">
      </div>
      <div class="col-md-3 form-control-comment">
                
      </div>
    </div>   
    <div class="form-group row ">
      <label class="col-md-3 form-control-label required">
          {l s='CVC'}
      </label>
      <div class="col-md-6">  
        <input style="width: 80px !important;" class="form-control datos-tarj" id="securityCode" name="securityCode" size="3" maxlength="3" type="text" autocomplete="off" required="">
      </div>

      <div class="col-md-3 form-control-comment">      
      </div>
    </div>


    <div class="form-group row ">
      <label class="col-md-3 form-control-label required">
          {l s='Expiration (MM/AAAA)'}
      </label>
      <div class="col-md-6">  

        <select style="width: 80px !important;display: inline-block !important;" class="form-control datos-tarj" id="expirationMonth" name="expirationMonth">
          {foreach from=$months item=month}
            <option value="{$month}">{$month}</option>
          {/foreach}
        </select>
        <span> / </span>
        <select style="width: 80px !important;display: inline-block !important;" class="form-control datos-tarj" id="expirationYear" name="expirationYear">
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
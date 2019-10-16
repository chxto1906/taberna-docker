<div class="form-group row ">
  <h2>Selecciona una tarjeta:</h2>
  <table class="table table-hover">
    <thead>
      <tr style="background: beige;">
        <th scope="col" colspan="2" style="text-align: left !important;">Tus tarjetas</th>
        <th scope="col" style="text-align: left !important;">Nombre en la tarjeta</th>
        <th scope="col" style="text-align: left !important;">Vencimiento</th>
      </tr>
    </thead>
    <tbody>
      {foreach from=$cards item=card}
        <tr>
          <th scope="row">
            <input type="radio" class="card_id" name="card_id" value="{$card.id}" data-holder="{$card.description}" id="card-{$card.id}">
          </th>
          <td>{$card.type} que termina en {$card.lastDigits}</td>
          <td>{$card.description}</td>
          <td>{$card.dueDate}</td>
        </tr>
      {/foreach}
    </tbody>
  </table>
</div>

<ul>
  <li>
    <a id="new_target" href="#" style="color: blue;text-decoration: underline;">Utilizar una nueva Tarjeta</a>
  </li>
</ul>
<div id="content-form-pay" style="display: none;">
  <form action="{$action}" id="payment-form" method="POST" class="js-customer-form" style="padding: 10px;background: beige; ">
    <section>  
        <input type="hidden" name="data" id="data">
      <div class="form-group row ">
        <label class="col-md-3 form-control-label required">
            Número de tarjeta
        </label>
        <div class="col-md-6">  
          <input class="form-control" id="id_card" name="id_card" autofocus type="hidden" size="20" autocomplete="off" >
          <input class="form-control" id="cardHolder" name="cardHolder" autofocus type="hidden" autocomplete="off" >
          <input class="form-control datos-tarj" id="cardNumber" autofocus type="text"  autocomplete="off" required >
          <input class="form-control" id="lastDigits" name="lastDigits" autofocus type="hidden" size="4" autocomplete="off">
          <input class="form-control" id="type_card" name="type_card" autofocus type="hidden" autocomplete="off">
        </div>
        <div class="col-md-3 form-control-comment">
          <span id="ok-validation" style="font-size: 30px; color: green; display: none;">&#9745;</span>
          <span id="bad-validation" style="font-size: 30px; color: red; display: none;">&#9746;</span>
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
          <input class="form-control datos-tarj" name="holderName" id="holderName" type="text" autocomplete="off" required>
        </div>
        <div class="col-md-3 form-control-comment">
                  
        </div>
      </div>   
      <div class="form-group row ">
        <label class="col-md-3 form-control-label required">
            CVC
        </label>
        <div class="col-md-6">  
          <input style="width: 80px !important;" class="form-control datos-tarj" id="securityCode" size="3" maxlength="3" type="text" autocomplete="off" required>
        </div>

        <div class="col-md-3 form-control-comment">      
        </div>
      </div>


      <div class="form-group row ">
        <label class="col-md-3 form-control-label required">
            Expiración (MM/AAAA)
        </label>
        <div class="col-md-6">  

          <select style="width: 80px !important;display: inline-block !important;" class="form-control datos-tarj" id="expirationMonth" name="expirationMonth" required>
            {foreach from=$months item=month}
              <option value="{$month}">{$month}</option>
            {/foreach}
          </select>
          <span> / </span>
          <select style="width: 80px !important;display: inline-block !important;" class="form-control datos-tarj" id="expirationYear" name="expirationYear" required>
            {foreach from=$years item=year}
              <option value="{$year}">{$year}</option>
            {/foreach}
          </select>
        </div>

        <div class="col-md-3 form-control-comment">      
        </div>
      </div>
      <div class="form-group row">
        <label class="col-md-3 form-control-label required">
            Recordar esta tarjeta
        </label>
        <div class="col-md-6" style="padding: 13px;">
          <input class="form-control" type="checkbox" name="add_card" id="add_card" checked>
        </div>
      </div>
      
    </section>
  </form>
</div>
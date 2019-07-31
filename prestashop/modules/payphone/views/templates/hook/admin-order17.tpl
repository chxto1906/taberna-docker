<div class="row">
    <div class="col-lg-12">
        <div class="panel">
            <div class="panel-heading">
                {l s="PayPhone transaction detail" mod="payphone"}
            </div>        
            {if $status eq 'Approved' || $status eq 'Canceled'}
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width:250px"><span class="title_box ">{l s='Concept' mod="payphone"}</span></th>
                                <th><span class="title_box ">{l s='Result' mod="payphone"}</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{l s='Transaction number' mod="payphone"}</td>
                                <td>{$transaction_id}</td>
                            </tr>
                            {if $status eq 'Approved'}
                                <tr>
                                    <td>{l s='Authorization code' mod="payphone"}</td>
                                    <td>{$authorization_code}</td>
                                </tr>            
                            {/if}
                            <tr>
                                <td>{l s='Phone number' mod="payphone"}</td>
                                <td>{$phone_number}</td>
                            </tr>
                            <tr>
                                <td>{l s='Message' mod="payphone"}</td>
                                <td>
                                    <div class="green">
                                        <span class="payment-message">{$message}</span>
                                    </div>
                                </td>
                            </tr>      
                            {if $status eq 'Approved'}
                                <tr>
                                    <td>{l s='Card´s bin' mod="payphone"}</td>
                                    <td>{$bin}</td>
                                </tr>            
                                <tr>
                                    <td>{l s='Card´s brand' mod="payphone"}</td>
                                    <td>{$card_brand}</td>
                                </tr>            
                            {/if}
                        </tbody>
                    </table>
                </div>
            {elseif $status eq 'Pending'}
                <div class="alert alert-warning">
                    {l s='The payment with PayPhone is in state of ' mod="payphone"}<strong>{l s='pending' mod="payphone"}</strong>. {l s='This could happen if the browser is closed before returning the payment button response.' mod="payphone"}<br/>
                    {l s='To ascertain whether the payment has been approved or canceled, press ' mod="payphone"} 
                    <a class="btn btn-primary" href="{$check_payment_url}">{l s='Check payment' mod="payphone"}</a>
                </div>
            {else}
            {/if}
        </div>
    </div>
</div>
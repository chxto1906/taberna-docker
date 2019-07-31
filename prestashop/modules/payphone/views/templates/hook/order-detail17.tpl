<div class="box">
    {if $status eq 'Approved' || $status eq 'Canceled'}
        <h1 class="page-heading">{l s='Your PayPhone transaction' mod='payphone'}</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="width:250px">{l s='Concept' mod='payphone'}</th>
                    <th>{l s='Result' mod='payphone'}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{l s='Transaction number' mod='payphone'}</td>
                    <td>{$transaction_id}</td>
                </tr>
                {if $status eq 'Approved'}
                    <tr>
                        <td>{l s='Authorization code' mod='payphone'}</td>
                        <td>{$authorization_code}</td>
                    </tr>            
                {/if}
                <tr>
                    <td>{l s='Phone number' mod='payphone'}</td>
                    <td>{$phone_number}</td>
                </tr>
                <tr>
                    <td>{l s='Message' mod='payphone'}</td>
                    <td>
                        {if $status eq 'Approved'}
                            <div class="green">
                            {elseif $status eq 'Canceled'}
                                <div class="red">
                                {/if}                            
                                <span class="payment-message">{$message}</span>
                            </div>
                    </td>
                </tr>  
                {if $status eq 'Approved'}
                    <tr>
                        <td>{l s='Card bin' mod='payphone'}</td>
                        <td>{$bin}</td>
                    </tr>            
                    <tr>
                        <td>{l s='Card brand' mod='payphone'}</td>
                        <td>{$card_brand}</td>
                    </tr>            
                {/if}
            </tbody>
        </table>   
    {elseif $status eq 'Pending'}
        <div class="alert alert-warning">
            {l s='The payment with PayPhone is in state of ' mod='payphone'}<strong>{l s='pending' mod='payphone'}</strong>. {l s='This could happen if the browser is closed before returning the payment button response.' mod='payphone'}<br/>
            {l s='To ascertain whether the payment has been approved or canceled, press ' mod='payphone'} 
            <a id="check-payment" href="{$check_payment_url}" class="btn btn-primary">
                <span>
                    {l s='Check payment' mod='payphone'}<i class="icon-chevron-right right"></i>
                </span>
            </a>
        </div>
    {else}
    {/if}
</div>

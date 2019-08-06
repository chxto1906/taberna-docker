{*
<div class="confirmation-box">
    <h1 class="confirmation-title">{l s='The transaction #'  mod="payphone"}<b>{$transaction_id}</b>{l s=' with PayPhone has been '  mod="payphone"}
        {if $status eq 'Approved'}<b>{l s='approved'  mod="payphone"}</b>{/if}{if $status eq 'Canceled'}<b>{l s='canceled'  mod="payphone"}</b>{/if}
    </h1>
    <div class='payment-details'>
        <div>{l s='Date: '  mod="payphone"}<b>{$date}</b></div>
        <div>{l s='Amount: '  mod="payphone"}<b>{$amount}</b></div>
        {if $status eq 'Approved'}
        <div>{l s='Authorization code: '  mod="payphone"}<b>{$authorization_code}</b></div>
        {/if}
        <div>{l s='Phone number: '  mod="payphone"}<b>{$phone_number}</b></div>
    </div>
	{if $status eq 'Canceled'}
            <div class="red">
                <img class="status-image" src="{$module_dir}/payphone/views/images/canceled.png" />
                <span class="payment-message">{$message}</span>
            </div>
	{/if}
        {if $status eq 'Approved'}
            <div class="green">
                <img class='status-image' src="{$module_dir}/payphone/views/images/approved.png" />
                <span class="payment-message">{$message}</span>
            </div>            
	{/if}    
    </div>
</div>
*}
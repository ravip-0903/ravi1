{* process.tpl  *}
<div class="wysiwyg-content">
<h1>
{if $response.action eq 'grace'}
    {$lang.wait} {$response.action_time} {$lang.days}
{elseif  $response.action eq 'grace_error'}
    {$lang.cancel_grace_error}
{elseif $response.action eq 'error'}
    {$lang.authentication_error}
{/if}
</h1><br>
<div style="font-size:14px; color:#666;">
{if  $response.action eq 'grace'}
<div>{$lang.thankyou}  {$response.username}</div><br/>
<div style="margin-top:10px; line-height:18px;">
    
    {$lang.cancel_grace_first}
   {/if}
{if $response.action eq 'grace'}
    <span style="color:#048ccc; text-decoration: underline;">{$response.order_id}</span>{$lang.grace_end}
{elseif $response.action eq 'grace_error'}
   {$lang.cancel_grace_error_end}
{/if}
    
</div>
    </div>
</div>
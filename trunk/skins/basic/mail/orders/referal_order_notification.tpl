{$lang.f_name} : {$order_info.firstname}
<br>
{$lang.l_name}  : {$order_info.lastname}
<br>
{$lang.inv_amount}  : 
{if $config.invoice_status=='TRUE'}
    {$order_info.total}
    {else}
        0
    {/if}
<br>
{$lang.email_add} : {$order_info.email}
<br>
{$lang.order_time} : {$order_info.timestamp|date_format:"%e %B, %Y %H:%M:%S"}
<br>


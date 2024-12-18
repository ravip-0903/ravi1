{* $Id: status_d_a_notification.tpl 11527 2011-01-05 12:52:45Z 2tl $ *}

{include file="letter_header.tpl"}

{$lang.hello},<br /><br />

{$lang.text_company_status_changed|replace:"[company]":$company_data.company|replace:"[status]":$status}

<br /><br />

{if $reason}
{$lang.reason}: {$reason}
<br /><br />
{/if}

{include file="letter_footer.tpl"}
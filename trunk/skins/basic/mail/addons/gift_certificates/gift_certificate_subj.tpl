{* $Id: gift_certificate_subj.tpl 8658 2010-01-21 08:38:22Z lexa $ *}
{if $certificate_status.status=='A'}
  Congratulations! You have a ShopClues Gift Certificate from {$gift_cert_data.recipient}
{else}
{$settings.Company.company_name|unescape}: {$lang.gift_certificate} {$certificate_info.gift_cert_code} {$certificate_status.email_subj}
{/if}
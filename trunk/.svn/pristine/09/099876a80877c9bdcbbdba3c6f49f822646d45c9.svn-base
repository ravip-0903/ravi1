{* $Id: order_notification.tpl 9153 2010-03-25 10:02:32Z lexa $ *}

{include file="letter_header.tpl"}

{$lang.dear} {$order_info.firstname},<br /><br />

{if $order_status.staus == "H"}
	This email is confirm that your order {$lang.order}&nbsp;#{$order_info.order_id} has been Delivered.
<br /><br />

{$lang.email_customer_order_pre}
<br /><br />
{elseif $order_status.status == "P" || $order_status.status == "O"}
	Thank you for shopping with ShopClues. Here is confirmation of your {$lang.order}&nbsp;#{$order_info.order_id}.
<br /><br />

{$lang.email_customer_order_pre}
<br /><br />

{elseif $order_status.status == "A"}
	Exciting news. Your order {$lang.order}&nbsp;#{$order_info.order_id} has been shipped. You can log into your account on ShopClues.com to track your orders status.
<br /><br />

{$lang.email_customer_order_pre}
<br /><br />
{elseif $order_status.status == "F"}

Thank you for placing an order with ShopClues.com.  Unfortunately your Order #{$order_info.order_id} has failed to process. <br/><br/>
An order typically fails due to payment issues-  you might have entered incorrect information, or your bank might have declined transaction due to an error on that part. <br/><br/>

We encourage you to retry confirming your order by going to your #{$order_info.order_id} by going to <a href="http://shopclues.com/index.php?dispatch=orders.details&order_id={$order_info.order_id}"> Order detail</a> and clicking on "Pay Now" button at the bottom of this screen. <br/><br/>

Please note, if you are having trouble paying by Credit card or NetBanking, you can always opt for "Cash on Delivery".<br/><br/>

{else}
	This email is to confirm that status of your {$lang.order}&nbsp;#{$order_info.order_id} has been changed to {$order_status.description}.

<br /><br />

{$lang.email_customer_order_pre}
<br /><br />

{/if}

{*{$order_status.email_header|unescape}<br /><br />*}

{assign var="order_header" value=$lang.invoice}
{if $status_settings.appearance_type == "C" && $order_info.doc_ids[$status_settings.appearance_type]}
	{assign var="order_header" value=$lang.credit_memo}
{elseif $status_settings.appearance_type == "O"}
	{assign var="order_header" value=$lang.order_details}
{/if}

{*<b>{$order_header}:</b><br />*}

{include file="orders/invoice.tpl"}

{$lang.email_customer_order_post}
<br />


{include file="letter_footer.tpl"}

{if $order_status.status == "A"}
<br/><br/>
<b>For a Cash on Delivery orders, please note:</b><br/>
<p> Do ensure that you or an authorized person is available at the shipping address to receive the order.<br/>
Do ensure that you have the required cash to pay on delivery and preferably the exact change too.<br/>
Do ensure that the shipment received is in good condition. Do not accept the shipment if the packaging has been tampered with or the goods received are in broken condition or incomplete.<br/>
If you are getting it delivered at your office or your apartments has strict entry rules please inform the concerned administration person in your office or apartment about the expected order.<br/>
</p>
{/if}

{* $Id: search.tpl 12605 2011-06-02 12:38:41Z angel $ *}


{*{capture name="section"}
	{include file="views/orders/components/orders_search_form.tpl"}
{/capture}
{include file="common_templates/section.tpl" section_title=$lang.search section_content=$smarty.capture.section class="search-form"}*}

{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
{if $search.sort_order == "asc"}
{assign var="sort_sign" value="&nbsp;&nbsp;&#8595;"}
{else}
{assign var="sort_sign" value="&nbsp;&nbsp;&#8593;"}
{/if}
{if $settings.DHTML.customer_ajax_based_pagination == "Y"}
	{assign var="ajax_class" value="cm-ajax"}

{/if}

{literal}
 <style>
   .expand_box{ display:none; background: none repeat scroll 0 0 #F8F8F8;
    border: 1px solid #CCCCCC;
    border-radius: 5px 5px 5px 5px;
    box-shadow: 3px 3px 5px #C6EDFF;
    color: #666666;
    display: none;
    font: 11px/16px Verdana,Geneva,sans-serif;
    padding: 2px 10px;
    position: absolute;
    right: -24px;
    text-align: center;
    top: 12px;
    width: 150px;
    z-index: 10000;}
   .not_eligible{position:relative;}
   .not_eligible:hover .expand_box{ display:block}
   
 </style>
{/literal}
<!--user_saving-->

{assign var="user_saving"  value=$auth.user_id|fn_get_user_saving}
{if isset($user_saving) and !empty($user_saving)}
 {assign var="user_saved" value=$user_saving.user_saved|number_format}
 {if $user_saved != 0}
  <div class="user_saving">
   <span style="color:#333333; font-weight:bold; font-size:24px; float:right;"> {$lang.user_saving|replace:"[user_saving]":$user_saved}</span>
   <span style="clear:both; float:right; margin-top: -5px;">{$lang.you_saved_so_far}</span>
  </div>
 {/if}
{/if}
<!--end-->


<div class="box_headerTwo">
<h1 class="box_headingTwo">{$lang.my_orders}</h1>
</div>
<div class="clearboth height_ten"></div>
{include file="common_templates/pagination.tpl"}
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="table ord_his_mob" id="order_history_table">
<tr class="ord_his_pg_fr_tr">
   <th width="20%"><a class="{$ajax_class}" href="{"`$c_url`&amp;sort_by=date&amp;sort_order=`$search.sort_order`"|fn_url}" rev="pagination_contents">{$lang.oh_order_date}</a>{if $search.sort_by == "date"}{$sort_sign}{/if}</th>
	<th width="10%"><a class="{$ajax_class}" href="{"`$c_url`&amp;sort_by=order_id&amp;sort_order=`$search.sort_order`"|fn_url}" rev="pagination_contents">{$lang.oh_order_number}</a>{if $search.sort_by == "order_id"}{$sort_sign}{/if}</th>
    
	
	<th width="30%"><a class="{$ajax_class}">{$lang.oh_products}</a></th>
    
	
	<th width="10%" class="right"><a class="{$ajax_class}" href="{"`$c_url`&amp;sort_by=total&amp;sort_order=`$search.sort_order`"|fn_url}" rev="pagination_contents">{$lang.oh_total}</a>{if $search.sort_by == "total"}{$sort_sign}{/if}</th>
    <th width="15%"><a class="{$ajax_class}" href="{"`$c_url`&amp;sort_by=status&amp;sort_order=`$search.sort_order`"|fn_url}" rev="pagination_contents">{$lang.status}</a>{if $search.sort_by == "status"}{$sort_sign}{/if}</th>
    <th width="14%">
    {$lang.action}
    </th>
</tr>
{assign var="i" value=0}
 <pre>{*$orders|print_r*}</pre>
{foreach from=$orders item="o"}
<tr {cycle values=",class=\"table-row\""}>

    <td class="ord_his_dte" {if $o.parent_order_id !='0'}rowspan="2"{/if}><a href="{"orders.details?order_id=`$o.order_id`"|fn_url}">{$o.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</a></td>
	<td class="center ord_his_id"  alt="{$o.priority_level_name.priority_level_name}" title="{$o.priority_level_name.priority_level_name}" {if $o.ff_priority == 'Y'}style="background:url({$o.priority_level_name.icon_url}) left center no-repeat; padding:10px; background-size:24px;"{/if}><a href="{"orders.details?order_id=`$o.order_id`"|fn_url}"><strong>{$o.order_id}</strong></a>
</td>
	
	<td class="ord_his_name">
            <ul class="no-markers">
    {foreach from=$orders_item[$i] item='order_detail'}
		
			<li>{$order_detail.product}&nbsp;&nbsp;({$lang.order_qty}:{$order_detail.amount})</li>
    {/foreach}
    
            {if $o.pdd_edd}
                <li style="color: #333;">{$lang.edd} {$o.pdd_edd.edd1} {$lang.pdd_mid} {$o.pdd_edd.edd2}</li>
            {/if}
             </ul>
    {assign var="rma_status" value=$o.order_id|fn_get_return_status}
     {assign var="rma_id" value=$o.order_id|fn_get_return_id}
     {assign var="get_gift_certificate" value=$o.order_id|fn_get_purchased_gift_certificates}
     {if !empty($get_gift_certificate)}
        <ul class="no-markers">
       {foreach from=$get_gift_certificate item="gift_certificate"}
         <li>Gift Certificate{if $o.status !='N' && $o.status !='F'}({$gift_certificate.gift_cert_code}){/if}</li>         
         
       {/foreach}
         </ul>
     {/if}
	</td>
    
	
	<td class="no_tablet right no_mobile" {if $o.parent_order_id !='0'}rowspan="2"{/if}>{if $o.parent_order_id =='0'}{include file="common_templates/price.tpl" value=$o.total}{/if}</td>
    <td  class="ord_his_status" style="text-align:center" {if $o.parent_order_id !='0'}rowspan="2"{/if}>
    	{*{if $o.status == 'N'}
      		{$lang.incomple_order_message}
        {else}*}
        	{include file="common_templates/status.tpl" status=$o.status display="view"}
       {* {/if}*}
  	</td>
    <td class="no_tablet no_mobile" {if $o.parent_order_id !='0'}rowspan="2"{/if}>
    <a href="{"orders.reorder?order_id=`$o.order_id`"|fn_url}">{$lang.re_order}</a>
     
     {assign var="order_id" value=$o.order_id}
      {if $return_orders.$order_id=='Y'}
          <br/>
         <br/>
			<a href="{"rma.create_return?order_id=`$o.order_id`"|fn_url}">			             {$lang.return_registration}
            </a>
      {elseif $return_orders.$order_id=='E'}
        {if $lang.return_expired!=''}
          <br/>
          <br/>
          <div class="not_eligible" style="cursor:pointer">{$lang.return_expired}
            <div class="expand_box">{$lang.return_expired_order_text}</div>
          </div>
         {/if}
      {/if}
   
    {foreach from=$rma_id.returns item=rm_id key=id}
    {assign var="rm_id" value=$id}
    {/foreach}
    {*code by munish on 5 Nov 2013 - start
       Grace Button Code  - start *}
       
    {assign var="order_id" value=$o.order_id}
    
    {if $o.status == $config.OSLA_status1 || $o.status == $config.OSLA_status2}

    {literal}
        <script type="text/javascript">
            
    function grace(orderid,userid,gracetime)
    { 
          $.ajax({
                    type : 'GET', // Using GET method to sent data
                    url : 'index.php',
                    dataType : 'text',  //return data type is JavaScript Object Notation
                    data : {'dispatch' : 'orders.ajaxprocess','orderid' : orderid, 'userid' : userid, 'grace': gracetime },
                    success : function(response) 
                    { 	
                        if(response == 'success')
                            alert('{/literal}{$lang.ajax_thankyou}{literal}'+gracetime+'{/literal} {$lang.ajax_end}{literal}');
                        else if (response == 'error')
                            alert("{/literal}{$lang.ajax_error}{literal}");
                    },
                    complete : function()
                    {
                         jQuery.toggleStatusBox('hide');
                    }
               });          
    }       
        </script>

    {/literal}
    {if $grace.$order_id == 'Y'}
    <br/><br/>
    <a class="cm-ajax fdbk_click" style="margin-right:1px; display: inline; border: 0px none;border-radius: 5px; -moz-border-radius: 5px; -webkit-border-radius: 5px; overflow: visible; cursor: pointer;" href="#" onclick="grace('{$o.order_id}','{$o.user_id}','2');">{$lang.grace2}</a><br/><br/>

    <a class="cm-ajax fdbk_click" style="margin-right:1px; display: inline; border: 0px none; border-radius: 5px; -moz-border-radius: 5px; -webkit-border-radius: 5px; overflow: visible; cursor: pointer;" href="#" onclick="grace('{$o.order_id}','{$o.user_id}','5');">{$lang.grace5}</a>
    
    {/if}
    {/if}
    
    {*  Grace Button Code  - end 
    //code by munish on 5 Nov 2013 - end *}
    {if ! empty($rma_status)}
     <a href="{"rma.print_slip?return_id=`$rm_id`"|fn_url}">{$lang.print_shipping}</a>
     <a href="http://cdn.shopclues.com/images/banners/Return_Instructions.pdf" target="_blank" onClick="return false">{$lang.return_guidelines}</a>
    {/if}
      
    {if $o.status|in_array:$config.show_feedback_link_status}
    {assign var="feedback_status" value=$o.order_id|fn_get_feedback_posting_status}
    <br/>
     <br/> 
     {if $feedback_status}
      {$lang.feedback_posted|unescape}
     {else}
       	<a class="cm-ajax" href="index.php?dispatch=orders.show_feedback_form&order_id={$o.order_id}" onClick="return false" >{$lang.post_feedback}</a>
       
     {/if}
    {/if} 
    {if !empty($get_gift_certificate)}
    {else}
    {if $o.allow_cancelation=='Y'}
      <br/>
      <br/>
      <a href="index.php?dispatch=orders.get_cancel_content&order_id={$o.order_id}" class="cm-ajax" onClick="return false" >{$lang.cancelation_request}</a>
    {/if}
    {/if}
    </td>
    {assign var="i" value=$i+1}
</tr>
{if $o.parent_order_id !='0'}<tr>
<td colspan="2"><div id="parent_order">{if $o.parent_order_id !='0'}<span style="margin-right:5px;color:#000; font-size:13px;">{$lang.part_of_original_order}</span> <a href="{"orders.details?order_id=`$o.parent_order_id`"|fn_url}" style="font-size:13px; font-weight:bold;">{$o.parent_order_id}</a>{/if}</div>
<div class="foot_note_nl" style="padding:5px;">{$lang.parent_order_text}</div>
</td>
</tr>{/if}
{foreachelse}
<tr>
	<td colspan="7"><p class="no-items">{$lang.text_no_orders}</p></td>
</tr>
{/foreach}
<tr class="table-footer">
	<td colspan="6">&nbsp;</td>
</tr>
</table>

{include file="common_templates/pagination.tpl"}

{capture name="mainbox_title"}{$lang.orders}{/capture}

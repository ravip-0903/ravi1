{literal}
<style>
.abc td{color:#ff0000!important;}
</style>
{/literal}
{* $Id: userlog.tpl 10028 2010-07-09 11:17:28Z 2tl $ *}

{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
{if $sort_order == "asc"}
{assign var="sort_sign" value="&nbsp;&nbsp;&#8595;"}
{else}
{assign var="sort_sign" value="&nbsp;&nbsp;&#8593;"}
{/if}
{*{if $settings.DHTML.admin_ajax_based_pagination == "Y"}
	{assign var="ajax_class" value="cm-ajax"}

{/if}*}
 
	 
<!--added by sapna to to show expiration of clues bucks by 30 days or 60 days--> 
{assign var="cluesbucktotal" value=$auth.user_id|fn_get_clues_bucks_total}
{if !empty($cluesbucktotal) && $cluesbucktotal>0}

	 <div class="box_headerTwo">
<h1 class="box_headingTwo">{$lang.clues_bucks_description}</h1>
</div>
<div class="clues_bucks_graph">
<div class="cls_bcks_avil">
	<div id="cls_bcks_available" class="cls_bcks_graph" >{$cluesbucktotal}</div>
    <div class="cls_bcks_text">{$lang.clues_bucks_available}</div>
</div>

{assign var="cluesbuckthirty" value=$auth.user_id|fn_get_clues_bucks_thirty}
{foreach from=$cluesbuckthirty item=cluesbuckthirty}
{assign var=cluesbuckthirty value=$cluesbuckthirty.amount}

{/foreach}
{assign var="arest" value=$cluesbucktotal-$cluesbuckthirty}
 {math assign="rper" equation="(x*100)/(y)" x=$arest y=$cluesbucktotal}
 

 {math assign="eper" equation='(x*100)/(y)' x=$cluesbuckthirty y=$cluesbucktotal}
 

<div class="cls_bcks_ttd">
	

<div class="cls_bcks_exo" style="{if $eper>0 && $eper<=20}height:30px;line-height:30px;font-size:20px; {elseif  $rper>0 && $rper<=20 } height:100px;line-height:100px;font-size:{$eper/1.5}px; {else}height:{$eper*1.3}px; font-size:{$eper/1.5}px; line-height:{$eper*1.3}px;{/if}">
{$cluesbuckthirty}
</div>

   
   
   <div id="cls_bcks_aftr_thrty" class="cls_bcks_live" style="{if $rper>0 && $rper<=20}height:30px; line-height:30px;  font-size:20px;{elseif $eper>0 && $eper<=20} height:100px;line-height:100px;font-size:{$rper/1.5}px;{else}height:{$rper*1.3}px; font-size:{$rper/1.5}px; line-height:{$rper*1.3}px; {/if}">
   {$arest}</div>
    <div class="cls_bcks_text">{$lang.thirty_days_expire_cb}</div>
    </div>
   
  
    {assign var="cluesbucksixty" value=$auth.user_id|fn_get_clues_bucks_sixty}
  
    {foreach from=$cluesbucksixty item=cluesbucksixty}
    {assign var=cluesbucksixty value=$cluesbucksixty.amount}
    {/foreach}

     
      {assign var="expcluesbucksixty" value=$cluesbucksixty}
       {assign var="arests" value=$cluesbucktotal-$expcluesbucksixty}
     {* {math assign="rpers" equation="(x*100)/(y)" x=$arests y=$cluesbucktotal}
      {math assign="epers" equation='(x*100)/(y)' x=$clbksx y=$clbuckt}*}
       {math assign="new_exp" equation='(x*100)/(y)' x=$expcluesbucksixty y=$cluesbucktotal}
       {math assign="new_liv" equation='(x*100)/(y)' x=$arests y=$cluesbucktotal}

<div class="cls_bcks_ttd">

<div class="cls_bcks_exo" style="{if $new_exp>0 && $new_exp<=20}height:30px;line-height:30px;font-size:20px; {elseif  $new_liv>0 && $new_liv<=20 } height:100px;line-height:100px;font-size:{$new_exp/1.5}px; {else}height:{$new_exp*1.3}px; font-size:{$new_exp/1.5}px; line-height:{$new_exp*1.3}px;{/if}">
{$expcluesbucksixty}
</div>

   
   
   <div class="cls_bcks_live" style="{if $new_liv>0 && $new_liv<=20}height:30px; line-height:30px;  font-size:20px;{elseif $new_exp>0 && $new_exp<=20} height:100px;line-height:100px;font-size:{$new_liv/1.5}px;{else}height:{$new_liv*1.3}px; font-size:{$new_liv/1.5}px; line-height:{$new_liv*1.3}px; {/if}">
  {$arests}</div>
	<!--<div class="cls_bcks_exo" style=" {if $new_exp>0 && $new_exp<=20}height:30px;line-height:30px;font-size:20px;{elseif $new_exp>=80 && $new_exp<=99.99}height:100px;line-height:100px;font-size:65px;{elseif $new_exp>=20 && $new_exp<=79}height:{$new_exp*1.3}px;line-height:{$new_exp*1.3}px;font-size:{$new_exp/1.5}px;{elseif $new_exp==0 && $new_liv==100}display:none; {else}height:130px; line-height:130px; font-size:65px;{/if}">
	
{$expcluesbucksixty}

</div>
<div class="cls_bcks_live" style=" {if $new_exp>0 && $new_exp<=20}height:100px;line-height:100px;font-size:65px;{elseif $new_exp>=80 && $new_exp<=99.99}height:30px;line-height:30px;font-size:20px;{elseif $new_exp>=20 && $new_exp<=79}height:{$new_liv*1.3}px;line-height:{$new_liv*1.3}px;font-size:{$new_liv/1.5}px;{elseif $new_exp==100 && $new_liv==0}display:none; {else}height:130px; line-height:130px; font-size:65px;{/if}">{$arests}</div>

-->
   
   
    <div class="cls_bcks_text">{$lang.sixty_days_expire_cb}</div>
</div>


<div class="cb_desc_nl_spn">{$lang.cb_description}
</div>
{/if}
<div class="clr_bx_cntnr_blk" style="display:none;">
	<div class="clr_bx_cntnr">
	<div class="clr_bx yellow"></div>
    <div class="clr_bx_des">Clues bucks you have</div>
	</div>
    
	<div class="clr_bx_cntnr">
	<div class="clr_bx  blue"></div>
    <div class="clr_bx_des">expired in 30 days</div>
	</div>
	<div class="clr_bx_cntnr">
	<div class="clr_bx green"></div>
    <div class="clr_bx_des">expired in 60 days</div>
	</div>
	<div class="clr_bx_cntnr">
	<div class="clr_bx red"></div>
    <div class="clr_bx_des">expired in 90 days</div>
	</div>            
</div>

</div

{*include file="common_templates/pagination.tpl"*}
><div class="box_headerTwo">
<h1 class="box_headingTwo">{$lang.my_clues_buck}</h1>
</div>
<div class="clearboth height_ten"></div>

<span class="bold">{$lang.total_clues_buck}:<span id="total_bucks"></span></span>
<div class="clearboth height_ten"></div>


<table cellpadding="0" border="0" width="100%" class="table">
<tr>
	<th class="clues_issue_deduct_date" width="30%"><a class="{$ajax_class}" href="{"`$c_url`&amp;sort_by=timestamp&amp;sort_order=`$sort_order`"|fn_url}" rev="pagination_contents">{$lang.cb_date}</a>{if $sort_by == "timestamp"}{$sort_sign}{/if}</th>
	<th width="10%"><a class="{$ajax_class}" href="{"`$c_url`&amp;sort_by=amount&amp;sort_order=`$sort_order`"|fn_url}" rev="pagination_contents">{$lang.cb_info}</a>{if $sort_by == "amount"}{$sort_sign}{/if}</th>
	<th class="clues_issue_deduction_rsn" width="60%" ><strong>{$lang.cb_reason}</strong> <div class="mobile">{$lang.cb_expiration}</div></th>
     <th class="no_mobile"width="30%" ><a class="{$ajax_class}" href="{"`$c_url`&amp;sort_by=expire_on&amp;sort_order=`$sort_order`"|fn_url}" rev="pagination_contents">{$lang.cb_expiration}</a>{if $sort_by == "expire_on"}{$sort_sign}{/if}</th>
    
</tr>
{assign var="total_bucks" value="0"}
{foreach from=$userlog item="ul"}
{assign var="total_bucks" value=$total_bucks+$ul.amount}
{assign var="curr_date" value=$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}

{if $ul.expire_on < $curr_date && !empty($ul.expire_on) }
{assign var='Color' value='abc'}
{else}
{assign var='Color' value='white'}
{/if}
<tr class="{$Color} {cycle values="odd,table-row"}" id="clues_bucks_data">
  
	<td valign="top">{$ul.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</td>
	<td class="right"  valign="top">{$ul.amount}</td>
  
	<td  valign="top">
 		{assign var="indnot" value=$ul.reason|strpos:"refund on order"}
        {if $indnot > 0}
        {$ul.reason}
		{elseif $ul.action == $smarty.const.CHANGE_DUE_ORDER}
			{assign var="statuses" value=$smarty.const.STATUSES_ORDER|fn_get_statuses:true:true:true}
			{assign var="reason" value=$ul.reason|unescape|unserialize}
			{assign var="order_exist" value=$reason.order_id|fn_get_order_name}
			{$lang.order}&nbsp;{if $order_exist}<a href="{"orders.details?order_id=`$reason.order_id`"|fn_url}" class="underlined">{/if}<strong>{$reason.order_id}</strong>{if $order_exist}</a>{/if}:&nbsp;{$statuses[$reason.from]}&nbsp;&#8212;&#8250;&nbsp;{$statuses[$reason.to]}{if $reason.text}&nbsp;({$reason.text|fn_get_lang_var}){/if}
		{elseif $ul.action == $smarty.const.CHANGE_DUE_USE}
			{assign var="order_exist" value=$ul.reason|fn_get_order_name}
			{$lang.text_points_used_in_order}: {if $order_exist}<a href="{"orders.details?order_id=`$ul.reason`"|fn_url}">{/if}<strong>{$ul.reason}</strong>{if $order_exist}</a>{/if}
		{elseif $ul.action == $smarty.const.CHANGE_DUE_ORDER_DELETE}
			{assign var="reason" value=$ul.reason|unescape|unserialize}
			{$lang.order} <strong>{$reason.order_id}</strong>: {$lang.deleted}
		{elseif $ul.action == $smarty.const.CHANGE_DUE_ORDER_PLACE}
			{assign var="reason" value=$ul.reason|unescape|unserialize}
			{assign var="order_exist" value=$reason.order_id|fn_get_order_name}
			{$lang.order} {if $order_exist}<a href="{"orders.details?order_id=`$reason.order_id`"|fn_url}" class="underlined">{/if}<strong>{$reason.order_id}</strong>{if $order_exist}</a>{/if}: {$lang.placed}
		{else}
			{hook name="reward_points:userlog"}
			{$ul.reason}
			{/hook}
		{/if}

 {if $ul.expire_on=="0000-00-00 00:00:00" || $ul.expire_on==null || $ul.expire_on >="3000-01-01 00:00:00"}
 <div class="mobile">   {$lang.no_expiry} </div>
   
    
    {else}
<div class="mobile"> {$ul.expire_on|date_format:"`$settings.Appearance.date_format`"} </div>
{/if}
	</td>
    
   
    <td class="right no_mobile"  valign="top">

    {if $ul.expire_on=="0000-00-00 00:00:00" || $ul.expire_on==null || $ul.expire_on >="3000-01-01 00:00:00"}
    {$lang.no_expiry} 
   
    
    {else}
{$ul.expire_on|date_format:"`$settings.Appearance.date_format`"}
{/if}
    </td>
</tr>
{foreachelse}
<tr>
	<td colspan="3"><p class="no-items">{$lang.no_items}</p></td>
</tr>
{/foreach}
{if $total_bucks}
<tr>
	<td><strong>Total Clues Bucks</strong></td>
    <td align="right"><strong>{$total_bucks}</strong></td>
    <td>&nbsp;</td>
</tr>
{else}
<tr class="table-footer">
	<td colspan="3">&nbsp;</td>
</tr>
{/if}
</table>
{literal}

<script type="text/javascript">
$(document).ready(function(){
if($(window).width()<630)
{
$('.cls_bcks_exo').each(function(i,o){ if(parseInt($(o).css("height"))>0){$('#cls_bcks_available, #cls_bcks_aftr_thrty').css("cssText", "height: 70px !important;").css("cssText", "line-height: 70px !important;");}});
}
});
</script>

{/literal}
{*include file="common_templates/pagination.tpl"*}
{** / userlog description section **}

{capture name="mainbox_title"}{$lang.reward_points_log}{/capture}

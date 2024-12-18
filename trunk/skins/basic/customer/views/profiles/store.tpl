
<div class="box_headerTwo">
<h1 class="box_headingTwo">{$lang.my_stores}</h1>
{assign var="ret_url" value=$config.current_url|urlencode}
</div>
<div class="clearboth height_ten"></div>
{include file="common_templates/pagination.tpl"}

<table border="0" cellpadding="0" cellspacing="0" width="100%" class="table" id="my_fav_store">
 <tr class="no_mobile">
   <th width="150px" align="left">
    {$lang.store_name}
    <br/>
    <span style="font-size:9px; font-weight:normal">{$lang.click_to_text}</span>
   </th>
  <th width="150px" align="left">
    {$lang.store_data}
  </th>
     
   <th width="150px" align="left">
    {$lang.date_added}
   </th>
   <th width="30px" align="left">
    {$lang.remove}
   </th>

{foreach from=$my_stores item="ms"}
  <tr>
    
    <td class="store_nme">{if $ms.status=='A'}<a href="{"index.php?dispatch=companies.view&company_id=`$ms.company_id`"|fn_url}">{$ms.company}</a>
<span class="mobile_inline">({$ms.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"})</span>

    <br /><br/>
    <a class="no_mobile" href="{"products.search?company_id=`$ms.company_id`&search_performed=Y"|fn_url}">{$lang.store_products_page}</a>
    {else}{$ms.company}<br/><span>{$lang.comp_disabled}</span>{/if}
</td>
    <td class="store_rtng">
      {assign var="is_trm" value=$ms.company_id|fn_sdeep_is_trm}
      {assign var="rating" value=$ms.company_id|fn_sdeep_get_rating}
	{assign var="feedback" value=$ms.company_id|merchant_detail_rating}
    <div class="store_rtng_span" style="float:left; width:70%">
    {if $rating}
     {assign var="feedback_count" value=$feedback.count|default:0}
        {assign var="feedback_positive" value=$feedback.positive+$feedback.neutral|default:0}
        <a style="float:left;" href="{"index.php?dispatch=companies.view&company_id=`$ms.company_id`"|fn_url}#feedback_heading">{include file="addons/sdeep/components/stars.tpl" stars=$rating|fn_sdeep_get_stars}</a>
        <!--<div class="clearboth"></div>-->
        <span class="pj2_rating_text">
        {if $feedback_count} ({$feedback_count} {$lang.mer_rating}{if {$feedback_count > 1}s{/if}){/if}
        </span>
        <div class="clearboth"></div>
        <span style="font-size:12px;" class="">{if $feedback_positive}{$feedback_positive}% positive review{if {$feedback_count > 1}s{/if}{/if}</span>
   {/if}
   </div>
   <div style="float:left; width:29%">
    {if $is_trm}
    	<a class="trm_clk" ><img src="{$addons.sdeep.trm_icon_url}" width="30"/></a>
    {/if}
   </div> 
   <div class="clear"></div>
    </td>    
    <td class="no_mobile">{$ms.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</td>
    <td class="store_option_remove" align="center"><a rev="my_fav_store" style="color:red" href="{"index.php?dispatch=profiles.unlike_merchant&ret_url=`$ret_url`&c_id=`$ms.company_id`"|fn_url}" ><span class="no_mobile">X</span><span class="mobile_inline float_left">Remove</span></a></td>
  </tr>

{foreachelse}
<tr>
	<td colspan="7"><p class="no-items">{$lang.no_fav_store}</p></td>
</tr>
{/foreach}
</table>
{include file="common_templates/pagination.tpl"}


<div class="box_headerTwo">
<h1 class="box_headingTwo">{$lang.my_stores}</h1>
</div>
<div class="clearboth height_ten"></div>
{include file="common_templates/pagination.tpl"}
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="table">
 <tr>
   <th width="20%" align="left">
    {$lang.store_name}
    <br/>
    <span style="font-size:9px; font-weight:normal">{$lang.click_to_text}</span>
   </th>
  
   <th width="20%" align="left">
    {$lang.store_product_page}
   </th>
   <th width="20%" align="left">
    {$lang.date_added}
   </th>

{foreach from=$my_stores item="ms"}
  <tr>
    
    <td>{if $ms.status=='A'}<a href="{"index.php?dispatch=companies.view&company_id=`$ms.company_id`"|fn_url}">{$ms.company}</a>{else}{$ms.company}<br/><span>{$lang.comp_disabled}</span>{/if}</td>
    <td><a href="{"products.search?company_id=`$ms.company_id`&search_performed=Y"|fn_url}">{$lang.store_products_page}</a></td>
    <td>{$ms.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</td>
  </tr>
{foreachelse}
<tr>
	<td colspan="7"><p class="no-items">{$lang.no_fav_store}</p></td>
</tr>
{/foreach}
</table>
{include file="common_templates/pagination.tpl"}
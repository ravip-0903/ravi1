<div class="box_headerTwo">
<h1 class="box_headingTwo">{$lang.my_messages_view}</h1>
</div>
<div class="clearboth height_ten"></div>
{include file="common_templates/pagination.tpl"}
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="table">
 <tr>
   <th width="20%" align="left">
    <a href="index.php?dispatch=profiles.user_query&field=to&order={$order}">{$lang.to}</a>
    <br/>
    <!--<span style="font-size:9px; font-weight:normal">{$lang.click_to_text}</span> -->
   </th>
   
   <th width="20%" align="left">
    <a href="index.php?dispatch=profiles.user_query&field=from&order={$order}">{$lang.from}</a>
   </th>
   
   <th width="20%" align="left">
    <a href="index.php?dispatch=profiles.user_query&field=subject&order={$order}">{$lang.subject}</a>
   </th>
   <th width="20%" align="left">
    <a href="index.php?dispatch=profiles.user_query&field=date&order={$order}">{$lang.date}</a>
   </th>

{foreach from=$user_data item="user_value"}


<tr>
       
       <td><a href="index.php?dispatch=profiles.user_query_response&thread_id={$user_value.thread_id}">{$user_value.company}</a></td> 
       <td><a href="index.php?dispatch=profiles.user_query_response&thread_id={$user_value.thread_id}">{$user_name}</a></td>
       <td><a href="index.php?dispatch=profiles.user_query_response&thread_id={$user_value.thread_id}">{$user_value.subject}</a></td>
       <td><a href="index.php?dispatch=profiles.user_query_response&thread_id={$user_value.thread_id}">{$user_value.timestamp|date_format:"%d-%b-%Y %H:%M %p"}</a></td>
                             
    <!--<td>{if $ms.status=='A'}<a href="{"index.php?dispatch=companies.view&company_id=`$ms.company_id`"|fn_url}">{$ms.company}</a>{else}{$ms.company}<br/><span>{$lang.comp_disabled}</span>{/if}</td>
    <td><a href="{"products.search?company_id=`$ms.company_id`&search_performed=Y"|fn_url}">{$lang.store_products_page}</a></td>
    <td>{$ms.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</td>
  -->
    </tr>
 
{foreachelse}
<!--<tr>
	<td colspan="7"><p class="no-items">{$lang.no_fav_store}</p></td>
</tr>-->
{/foreach}
</table>
{include file="common_templates/pagination.tpl"}
{literal}
    <script>
    $(document).ready(function(){
        $('.central-column').css('width','81%');
});
    </script>
    {/literal}
{** block-description:sdeep_extra_block_solr_query **}
{assign var="extra_block_content" value=$product.product_id|fn_get_extra_block_solr_content:$block.properties.solr_query:$block.properties.product_count}
{if !empty($extra_block_content)}
{assign var="count" value=0}
{literal}
<style>
.extra_blk:hover{background:#f8f8f8;
}
</style>
{/literal}
<div class="produ_detai_right_b_mng pj2_top_border" style="border:1px solid #ccc; margin-bottom:14px">
<div style="padding:5px; color:#000; font-weight:bold;font-family:Arial, Helvetica, sans-serif; font-size:14px" >{$block.description}</div>
{foreach from=$extra_block_content item="extra_content"}
{assign var="count" value=$count+1}

 <table width="100%">
  <tr>
    <td class="extra_blk">
      <div style="float:left;width: 49px;border: 1px solid white;">
          <img src="http://cdn.shopclues.com/{$extra_content.image_url}" width="50" height="50" title="{$extra_content.product}"  />
      </div>
      <div style="float:left; width:160px; margin:1px 0px 0px 5px; font-size:11px">
         <a href="{$config.http_location}/{$extra_content.seo_name}.html" style="line-height:12px; font-size:12px; font-family:Verdana, Geneva, sans-serif">{$extra_content.product|truncate:22:'....'}</a>
         <div style="clear:both"></div>
         {assign var="average_rating" value=$extra_content.product_id|fn_get_average_rating:"P"}
         
        {if $average_rating}
            {include file="addons/discussion/views/discussion/components/stars.tpl" stars=$average_rating|fn_get_discussion_rating}
        {/if}
        <div style="clear:both"></div>
         <span class="list-price" style="font-size:12px">{$lang.price_value}:</span>{if $extra_content.price < $extra_content.list_price} <span class="list-price" style="font-size:12px"> <strike>{include file="common_templates/price.tpl" value=$extra_content.list_price}</strike></span>{/if} <span style="font-size:12px; font-family:trebuchet ms; color:#990000; font-weight:bold">&nbsp;{include file="common_templates/price.tpl" value=$extra_content.price}</span>
         
      </div> 
    </td>
   </tr>
 </table>
{/foreach}
</div>
{/if}
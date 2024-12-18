{** block-description:sdeep_extra_block **}
{ assign var="extra_block_content" value=$product.product_id|fn_get_extra_block_content:$block.properties.sql_id}
{if !empty($extra_block_content)}
{assign var="count" value=0}
{literal}
<style>
.extra_blk:hover{background:#f8f8f8;
}
</style>
{/literal}
<div class="produ_detai_right_b_mng pj2_top_border" style="border:1px solid #ccc; margin-top:14px">
<div style="padding:5px; color:#000; font-weight:bold;font-family:Arial, Helvetica, sans-serif; font-size:14px" >{$block.description}</div>
{foreach from=$extra_block_content item="extra_content"}
{assign var="count" value=$count+1}

 <table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td class="extra_blk">
      <div style="float:left">
        {assign var="pro_images" value=$extra_content.product_id|fn_get_image_pairs:'product':'M'}
{include file="common_templates/image.tpl" image_width="50" image_height="50" obj_id=$count images=$pro_images object_type="product" show_thumbnail="Y" alt_text=$extra_content.product}
      </div>
      <div style="float:left; width:160px; margin:1px 0px 0px 5px; font-size:11px">
         <a href="{"index.php?dispatch=products.view&product_id=`$extra_content.product_id`"|fn_url}" style="line-height:12px; font-size:12px; font-family:Verdana, Geneva, sans-serif" alt="{$extra_content.product}" title="{$extra_content.product}"  >{if $extra_content.product|strlen>40}{$extra_content.product|substr:0:37}...{else}{$extra_content.product}{/if}</a>
         <div style="clear:both"></div>
         {assign var="average_rating" value=$extra_content.product_id|fn_get_average_rating:"P"}

        {if $average_rating}
            {include file="addons/discussion/views/discussion/components/stars.tpl" stars=$average_rating|fn_get_discussion_rating}
        {/if}
        <div style="clear:both"></div>
         <span class="list-price" style="font-size:12px">{$lang.price_value}:</span>{if $extra_content.price < $extra_content.list_price} <span class="list-price" style="font-size:11px"> <strike>{include file="common_templates/price.tpl" value=$extra_content.list_price}</strike></span>{/if} <span style="font-size:12px; font-family:trebuchet ms; color:#990000; font-weight:bold">&nbsp;{include file="common_templates/price.tpl" value=$extra_content.price}</span>
         
      </div> 
    </td>
   </tr>
 </table>
{/foreach}
</div>
{/if}
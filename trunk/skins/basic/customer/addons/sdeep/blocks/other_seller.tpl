{** block-description:sdeep_other_seller **}
{assign var="other_seller" value=$product.product_id|fn_get_products_other_seller:$product.company_id:$product.product}
{if !empty($other_seller)}
<div class="produ_detai_right_b_mng pj2_top_border" style="margin-top:14px">
<div style="padding:5px; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; ">{$lang.other_seller}</div>
{foreach from=$other_seller item="other_sell"}
{assign var="vendor_info" value=$other_sell.company_id|fn_sdeep_get_vendor_info}
	{assign var="rating" value=$other_sell.company_id|fn_sdeep_get_rating}
	{assign var="feedback" value=$other_sell.company_id|merchant_detail_rating}
	{assign var="auth_dealer_info" value=$other_sell.company_id|fn_sdeep_get_auth_dealer_info}
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td{if $rating} {/if} style="border:0;">
                <div style="float:left">
                    <div class="" style="position: absolute; margin-left:185px; margin-top:-5px;">{include file="addons/sdeep/common_templates/vendor_icons.tpl" vendor_info=$vendor_info size="50"}</div>
                    <div class="clearboth"></div>
                    <span style="width:86%; float:left; font-size:12px"><a href="{"index.php?dispatch=companies.view&company_id=`$vendor_info.company_id`"|fn_url}" style="font-size:12px" class="pj2_vendor_name" title="{$vendor_info.company}">{$vendor_info.company}</a></span>
                    {if $rating}
                      {assign var="feedback_count" value=$feedback.count|default:0}
                        {assign var="feedback_positive" value=$feedback.positive+$feedback.neutral|default:0}
                        <a style="float:left;" href="{"index.php?dispatch=companies.view&company_id=`$vendor_info.company_id`"|fn_url}#feedback_heading">
                        {include file="addons/discussion/views/discussion/components/stars.tpl" stars=$rating|fn_sdeep_get_stars}      </a>
                        <!--<div class="clearboth"></div>-->
                        <span class="pj2_rating_text">
                        {if $feedback_count} ({$feedback_count} {$lang.mer_rating}{if {$feedback_count > 1}s{/if}){/if}
                        </span>
                        <div class="clearboth"></div>
                        <span style="font-size:12px;" class="">{if $feedback_positive}{$feedback_positive}% positive review{if {$feedback_count > 1}s{/if}{/if}</span>
                    {/if}
                    <div style="clear:both"></div>
                     <span > <a style="font-size:12px; font-family:trebuchet ms; color:#990000; font-weight:bold" href="{"index.php?dispatch=products.view&product_id=`$other_sell.product_id`"|fn_url}"> {$lang.selling_price}:&nbsp;{include file="common_templates/price.tpl" value=$other_sell.price}</a></span>
                 </div>                     
			</td>
		</tr>
       </table>      
{/foreach}
</div>
{/if}

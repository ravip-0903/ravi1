<h1 class="mainbox-title">Merchant Reviews</h1>

{*star rating dashboard done by Raj kumar on 04th April 2013*}

<div class="statistics-box overall" style="height:108px;">
	<div class="statistics-body" style="height:81px;">
                  {*$lang.merchant_statistics_title*}
                    <ul>
            <li class="nl_store_analytics_summary" style="width:13%;" > <span class="price"><a href="UniTechCity.php?dispatch=review_monitoring.merchant_review&review=&first_rate_value=1&second_rate_value=1.49&search=Search&type=O&period=A&time_from=&time_to=">{$all_star_rating.one_star|number_format}</a> </span>
                    <span class="block">{$lang.one_star} </span> </li>
            
             <li class="nl_store_analytics_summary" style="width:13%;" ><span class="price">{if empty($all_star_rating.two_star)} 0 {else} <a href="UniTechCity.php?dispatch=review_monitoring.merchant_review&review=&first_rate_value=1.5&second_rate_value=2.49&search=Search&type=O&period=A&time_from=&time_to=">{$all_star_rating.two_star|number_format} </a>{/if}</span>
                      <span class="block" style="word-wrap:break-word;">{$lang.two_star} </span></li>
                      
             <li class="nl_store_analytics_summary" style="width:13%;" ><span class="price">{if empty($all_star_rating.three_star)} 0 {else} <a href="UniTechCity.php?dispatch=review_monitoring.merchant_review&review=&first_rate_value=2.5&second_rate_value=3.49&search=Search&type=O&period=A&time_from=&time_to=">{$all_star_rating.three_star|number_format}</a> {/if}</span>
                      <span class="block">{$lang.three_star} </span></li>
                      
             
             <li class="nl_store_analytics_summary" style="width:13%;" ><span class="price">{if empty($all_star_rating.four_star)} 0 {else} <a href="UniTechCity.php?dispatch=review_monitoring.merchant_review&review=&first_rate_value=3.5&second_rate_value=4.49&search=Search&type=O&period=A&time_from=&time_to=">{$all_star_rating.four_star|number_format}</a> {/if}</span>
                     <span class="block">{$lang.four_star}</span> </li>
             
             <li class="nl_store_analytics_summary" style=" width:13%;" ><span class="price">{if empty($all_star_rating.five_star)} 0 {else} <a href="UniTechCity.php?dispatch=review_monitoring.merchant_review&review=&first_rate_value=4.49&second_rate_value=5&search=Search&type=O&period=A&time_from=&time_to=">{$all_star_rating.five_star|number_format} </a>{/if}</span>
                     <span class="block">{$lang.five_star}</span> </li>
             
             <li class="nl_store_analytics_summary" style=" border:0; width:13%;" ><span class="price">{if empty($total_review)} 0 {else} {$total_review|number_format} </a>{/if}</span>
                     <span class="block">{$lang.total_review_count}</span> </li>
             
             
             
</div>
                     </div>

{*star rating dashboard end*}

<form name="search_form" method="get" action="">
<input type="hidden" name="dispatch" value="review_monitoring.merchant_review" />
<div style="margin:5px; background-color:#F9F9F9; height:75px; padding:20px 0px 0px 5px">
<div>
 <div style="float:left;" class="order_search_mng"><label>Review Text</label><input type="text" class="input-text" name="review" style="margin-left:8px" value="{$smarty.request.review}" /></div>

<div style="float:left;" class="order_search_mng"><label>{$lang.star_rate_enter}</label><input type="text" class="input-text" name="first_rate_value" style="margin-left:8px" value="{$smarty.request.first_rate_value}" /> - <input type="text" class="input-text" name="second_rate_value" style="margin-left:8px" value="{$smarty.request.second_rate_value}" /></div>
 
  <div style="float:left; margin-left:5px;" class="order_search_mng">
     <span class="submit-button">
       <input type="submit" name="search" value="Search" />
     </span>  
 </div>
 <div class="clear"></div>
</div> 
 <br/>
 <div style="float:left; width:100%" class="order_search_mng">
 
 <label style="float:left; margin-right:8px">Period</label>
 {include file="common_templates/period_selector.tpl" period=$period form_name="search_form"}</div>
 

 <div class="clear"></div>
</div>
</form>
<div class="clear"></div>

<form name="review_monitor" method="post" action="">
{include file="common_templates/pagination.tpl" id="pagination_contents_comments"}




<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table">
  <tr>
    <th style="padding-left:7px;">
	
    </th>
  </tr>
{assign var="row_no" value=1}
{foreach from=$post item=posts}  
  <tr>
    <td  style="background:#fff !important;">
    <div class="box_reviewdetails">

<div class="box_reviewdetails_selection">
<input type="checkbox" value="{if $posts.status == "A"}D{else}A{/if}" class="checkbox cm-item" id="dis_approve_checkbox_{$posts.id}" name="posts[{$posts.id}][status]">
</div>

<div class="box_reviewdetails_productimage">
{assign var="pro_images" value='admin'|fn_get_manifest:CART_LANGUAGE:$posts.company_id}
{if $pro_images.Customer_logo.vendor}
   <img border="0" width="70" height="70" alt="{$pro_images.Customer_logo.alt}" src="{$config.images_path}{$pro_images.Customer_logo.filename}" />
{else}
    <img border="0" width="70" height="70" alt="{$pro_images.Customer_logo.alt}" src="{$config.no_image_path}" />
{/if}
</div>

<div class="box_reviewdetails_productdetails">
<div class="box_reviewdetails_productdetails_negativerating">
{assign var="negative_comment" value=''}
{assign var="negative_rate" value=$posts.id|fn_check_negative_rating_and_get_star_count:$posts.company_id}
{if $negative_rate.no_review_post==0 && $negative_rate.negative==1}
 {assign var="negative_comment" value=$posts.id|fn_get_negative_reting_comment_and_case_status:$posts.company_id}
 
<img src="images/admin_skin/icon_negativefeedback.gif" />
Negative Rating
{/if}
</div>
<div class="box_reviewdetails_productdetails_name">

<a href="UniTechCity.php?dispatch=companies.update&company_id={$posts.company_id}">{$posts.company}</a> - <span style="color:green;">{if $posts.status=='A'}
    {$lang.approved}
    
    {else}
    
    {$lang.notapproved}
    
    {/if}</span>
</div>
<div class="box_reviewdetails_productdetails_review">
 <div id="textdiv_{$posts.id}">
{$posts.review}
 </div>
 <div id="editdiv_{$posts.id}" style="display:none">
   <textarea style="margin-top:5px;" rows="2" cols="80" class="input-textarea-long" name="posts[{$posts.id}][message]">{$posts.review}</textarea>
   <input type="hidden" id="edttext_{$posts.id}" name="posts[{$posts.id}][edittext]" value="0" />
 </div>
</div>
<div class="box_reviewdetails_productdetails_postedinfo">
<input type="hidden" value="{$posts.name}" name="posts[{$posts.id}][name]" /> 
Posted By: {$posts.name}, {$posts.post_date|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}
</div>

<div class="box_reviewdetails_productdetails_functions submit-button">

<input name="" type="button" id="edit_{$posts.id}" value="Edit" onclick="show_edit_box({$posts.id})" />
{if !empty($negative_comment)}
 {if $negative_comment.negative_rating_case_resolved==0}
<input name="" type="button" id="add_admin_{$posts.id}" value="Admin Comment" onclick="show_admin_comment_box({$posts.id})" />
  {/if}
{/if}
<input name="" type="submit" id="save_{$posts.id}" value="Save" style="display:none" />
<input name="" type="button" id="cancel_{$posts.id}" value="Cancel" onclick="hide_edit_box({$posts.id})" style="display:none" />

</div>
{if !empty($negative_comment) && $negative_comment.negative_rating_case_resolved==0}

<div class="box_reviewdetails_productdetails_admincommentform" style="display:none" id="admin_comment_{$posts.id}">
<input type="hidden" name="posts[{$posts.id}][add_admin_comment]" id="add_admin_comment_{$posts.id}" value="0" />
<textarea name="posts[{$posts.id}][admin_comment]" cols="50" rows="1"></textarea>
<br />
<input name="" type="submit" value="Case Resolved" style="margin-top:5px;" />
<input type="button" id="close_button_{$posts.id}" value="Cancel" onclick="hide_admin_comment_box({$posts.id})" style="margin-top:5px;" />
</div>
{/if}
{if $posts.negative_rating_case_resolved==1}
<div class="box_reviewdetails_productdetails_admincomment">
{$posts.negative_rating_comment}
 <div class="box_reviewdetails_productdetails_postedinfo">
   Resolved By: {$posts.resolved_by|fn_hp_get_user_name_from_id}, {$posts.resolving_date|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}
 </div>
</div>
{/if}
<input type="hidden" name="posts[{$posts.id}][company_id]" value="{$posts.company_id}" />
</div>
{if $negative_rate.param==1 }
<div class="box_reviewdetails_starrating">
<div class="box_reviewdetails_starrating_heading">{$lang.detailed_merchant_rating}<span style="margin-right:8px; float:right;">Total Rating: {$negative_rate.avg_rate|number_format:2}</span></div>

<div class="box_negativerating">
<div class="box_negativerating_heading">Shipping Time :</div>
<div class="box_negativerating_stars">
{assign var="default_star" value=5}
{section name='stars' loop=$negative_rate.shipping_time}
<img src="images/rating/star-on.png" width="13" height="12" alt="*" />
{/section}
{assign var="off_star" value=$default_star-$negative_rate.shipping_time}
{section name='stars1' loop=$off_star}
<img src="images/rating/star-off.png" width="13" height="12" alt="" />
{/section}
</div>
</div>
<div class="box_negativerating">
<div class="box_negativerating_heading">Shipping Cost :</div>
<div class="box_negativerating_stars">
{section name='stars' loop=$negative_rate.shipping_cost}
<img src="images/rating/star-on.png" width="13" height="12" alt="*" />
{/section}
{assign var="off_star" value=$default_star-$negative_rate.shipping_cost}
{section name='stars1' loop=$off_star}
<img src="images/rating/star-off.png" width="13" height="12" alt="" />
{/section}
</div>
</div>
<div class="box_negativerating">
<div class="box_negativerating_heading">Product Quality :</div>
<div class="box_negativerating_stars">
{section name='stars' loop=$negative_rate.product_quality}
<img src="images/rating/star-on.png" width="13" height="12" alt="*" />
{/section}
{assign var="off_star" value=$default_star-$negative_rate.product_quality}
{section name='stars1' loop=$off_star }
<img src="images/rating/star-off.png" width="13" height="12" alt="" />
{/section}
</div>
</div>
<div class="box_negativerating">
<div class="box_negativerating_heading">Value For Money :</div>
<div class="box_negativerating_stars">

{section name='stars' loop=$negative_rate.value_for_money}
<img src="images/rating/star-on.png" width="13" height="12" alt="*" />
{/section}
{assign var="off_star" value=$default_star-$negative_rate.value_for_money}

{section name='stars1' loop=$off_star}
<img src="images/rating/star-off.png" width="13" height="12" alt="" />
{/section}
</div>
</div>


</div>

{/if}
</div>
	</td>    
  </tr>
  

{assign var="row_no" value=$row_no+1}
{/foreach}
  </table>



{include file="common_templates/pagination.tpl" id="pagination_contents_comments"}
<div class="buttons-container buttons-bg">
{include file="buttons/save.tpl" but_name="change_status" but_role="button_main"}
</div>
</form>

{literal}

<script>
function show_edit_box(id)
{
	$('#textdiv_'+id).hide();
	$('#editdiv_'+id).show();
	$('#save_'+id).show();
	$('#cancel_'+id).show();
	$('#edit_'+id).hide();
	$('#edttext_'+id).val(1);
}

function hide_edit_box(id)
{
	$('#textdiv_'+id).show();
	$('#editdiv_'+id).hide();
	$('#save_'+id).hide();
	$('#cancel_'+id).hide();
	$('#edit_'+id).show();
	$('#edttext_'+id).val(0);
}
function show_admin_comment_box(id)
{
	$('#admin_comment_'+id).show();
	$('#add_admin_'+id).hide();
	$('#add_admin_comment_'+id).val(1);
}
function hide_admin_comment_box(id)
{
	$('#admin_comment_'+id).hide();
	$('#add_admin_'+id).show();
	$('#add_admin_comment_'+id).val(0);
}
</script>
{/literal}














<!--<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table">
  <tr>
    <th style="width:5px;">&nbsp;</th>
    <th>Product Details</th>
    <th>Review Details</th>
    <th>Permission</th>
  </tr>
  
{assign var="row_no" value=1}
{foreach from=$post item=posts}
<tr>
    <td>&nbsp;</td>
    <td align="left" valign="top">
	<a href="UniTechCity.php?dispatch=companies.update&company_id={$posts.company_id}">{$posts.company}</a>
        <div style="font:12px trebuchet ms; color:#808080; margin-top:10px;">
        <strong>Posted Date:</strong> {$posts.post_date|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}
        </div>
        <div style="font:12px trebuchet ms; color:#666; margin-top:10px;">
        <input type="checkbox" value="{if $posts.status == "A"}D{else}A{/if}" class="checkbox cm-item" id="dis_approve_checkbox_{$posts.id}" name="posts[{$posts.id}][status]">
        <label for="dis_approve_checkbox_{$posts.id}">{if $posts.status=='A'}Disapprove{else}Approved{/if}</label>
        </div>
    </td>
    <td align="left" valign="top">
    <input type="text" class="input-text valign strong" size="40" value="{$posts.name}" name="posts[{$posts.id}][name]">
    <br />
    <textarea style="margin-top:5px;" rows="3" cols="80" class="input-textarea-long" name="posts[{$posts.id}][message]">{$posts.review}</textarea>
    </td>
    <td align="left" valign="middle">
    {if $posts.status=='A'}
    
    {$lang.approved}
    
    {else}
    
    {$lang.notapproved}
    
    {/if}
    </td>
  </tr>
       
{assign var="row_no" value=$row_no+1}
{/foreach}

</table>

{include file="common_templates/pagination.tpl" id="pagination_contents_comments"}
<div class="buttons-container buttons-bg">
		{include file="buttons/save.tpl" but_name="change_status" but_role="button_main"}
</div>
</form>-->


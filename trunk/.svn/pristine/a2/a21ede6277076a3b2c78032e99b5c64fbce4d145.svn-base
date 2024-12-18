<h1 class="mainbox-title">Product Reviews</h1>
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
    <td>
    <div class="box_reviewdetails">

<div class="box_reviewdetails_selection">
<input type="checkbox" value="{if $posts.status == "A"}D{else}A{/if}" class="checkbox cm-item" id="dis_approve_checkbox_{$posts.id}" name="posts[{$posts.id}][status]">
</div>

<div class="box_reviewdetails_productimage">
{assign var="pro_images" value=$posts.product_id|fn_get_image_pairs:'product':'M'}
{include file="common_templates/image.tpl" image_width="70" image_height="70" image_id=$row_no image=$pro_images.detailed  object_type=""}

</div>

<div class="box_reviewdetails_productdetails">
<div class="box_reviewdetails_productdetails_name">
<a class="name" href="UniTechCity.php?dispatch=products.update&product_id={$posts.product_id}">{$posts.product}</a> - <span style="color:green;">{if $posts.status=='A'}
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
<input name="" type="submit" id="save_{$posts.id}" value="Save" style="display:none" />
<input name="" type="button" id="cancel_{$posts.id}" value="Cancel" onclick="hide_edit_box({$posts.id})" style="display:none" />

</div>

</div>


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
</script>
{/literal}
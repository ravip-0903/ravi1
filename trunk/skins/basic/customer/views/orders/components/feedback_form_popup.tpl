{literal}
<style>
.dark_color_nl_popup{color:#333!important;}
.new_form_field_popup_nl{margin-top:5px!important; margin-left:0!important;}
.merchant_nl_box_border{border-right:1px solid #eee; padding-right:27px;}
.product-notification-container{top:26%!important;}
.box_functions input{cursor:pointer;}
.viewport textarea{width:285px!important; max-width:285px!important; height:60px; }
.box_RatingBig{margin-top:0; border-bottom:1px solid #eee; padding:0 10px;}
.box_merchantrating{margin-left:0; width:100%; padding:0px; border:0;}
.box_mer_rating_nl{margin-top:10px; float:left; color:#333;}
.box_prd_rating_nl{float:left; margin:10px 0 0px 0; padding-bottom:10px; border-bottom:1px dotted #D2D7D9}
.box_prd_rating_nl .form-field{margin-left:0; }
.box_prd_rating_nl .image_box_nl_popup {background:#f8f8f8; padding:5px; float:left;}
.box_prd_rating_nl .image_box_nl_popup label{margin-left:5px;; float:left; clear:none; width:230px; float:left; font-size:13px; color:#666; }
.box_prd_rating_nl .image_box_nl_popup .pro_image{width:auto; float:left;}
.box_RatingBig_heading label{color:#666!important;}
.label_text_cm{color:#333!important;}
</style>
{/literal}
{literal}
<script type="text/javascript">
	$('.star').raty({
				click: function(score, evt) {
					var id=$(this).attr('id');
					$('#rating_count_'+id).val(score);
									//$('#{$param_name}').val(score);
				}
	});
</script>
{/literal}
<form action="{""|fn_url}" method="post" id="my_form" name="sdeep_product_rating"  class="cm-form-highlight" enctype="multipart/form-data" onsubmit="return vaidate_merchant_rating()" >		
<input type="hidden" name="token" value="{$smarty.session.form_token_value}"/>
<div style="width:668px;">
{assign var="auth" value=$smarty.session.auth}
{assign var="show_info" value=$smarty.request.order_id|fn_get_order_feedback_info}
{assign var="merchant_name" value=$show_info.0.company_id|fn_get_company_name}
{assign var="merchant_rating_params" value=""|fn_sdeep_get_vendor_rating_params}
<div class="clearboth"></div>
<input type="hidden" name="company_id" value="{$show_info.0.company_id}"/>
<input type="hidden" name="order_id" value="{$smarty.request.order_id}"/>

<div id="scrollbar1" style="float:left; width:100%!important; height:325px;  ">
            <div class="scrollbar" style="margin:0;"><div class="track"><div class="thumb" style="400px"><div class="end"></div></div></div></div>
            <div class="viewport" style="width:97%; height:325px;">
            <div class="overview" style="top:0;">

<!--Box Rating -->
<div class="float_left width_fourtyfivepercent merchant_nl_box_border">

<div class="box_headerTwo">
<h2 class="box_headingTwo">{$lang.rate_merchant} ({$merchant_name})</h2>
</div>
<div style="float:left; margin-top:5px">
{$lang.info_merchant_rating|unescape}
</div>


<div class="box_mer_rating_nl">
<div>{$lang.rating_merchant_nl}</div>
{foreach from=$merchant_rating_params item="param" key="k"}
<div class="box_merchantrating">
<!--Box Rating Small -->
<div class="box_RatingBig">

<div class="box_RatingBig_heading">
<label for="param_rating_{$k}" id="rating_param_{$k}" style="text-transform:capitalize;">{$param.name}:</label>
{assign var="param_name" value=$param.name|replace:' ':'_'}
<input type="hidden" id="{$param_name}" name="{$param_name}" value="0" />
<input type="hidden" name="checked_field" id="checked_field_{$k}" value="0" />
</div>
<div class="box_RatingBig_star">

<script type="text/javascript">
                       //<![CDATA[
				
						
						$(function() {$ldelim}
							$('#click_{$k}').raty({$ldelim}
								click: function(score, evt) {$ldelim}
								     
								    $('#checked_field_{$k}').val(1);
									$('#{$param_name}').val(score);
									//$('#review_merchant_label').removeClass("label_text_cm");
									
									
									var i=0;
								var sum=0;
								var sele_val=0;
								var selected=0;
								while(document.getElementById('click_'+i))
								{$ldelim}
									sele_val=$('#click_'+i).find('input').val();
									if(sele_val!='')
									{$ldelim}
										selected++;
									{$rdelim}
									sum=Number(sum)+Number(sele_val);
									i++;
								{$rdelim}
									
									if(selected==i)
									{$ldelim}
										if(sum<=4)
										{$ldelim}
										  $('#review_merchant').css("border-color","red");
								          $('#review_merchant').css("color","red");
								          $('#review_merchant').val($('#neg_text').val());
										  $('#neg_feedback_text2').show();
										  $('#review_merchant_label').addClass("cm-required");
										{$rdelim}
										else
								        {$ldelim}
										   $('#review_merchant').css("border-color","#D2D7D9");
											$('#review_merchant').css("color","black");
											if($('#review_merchant').val()==$('#neg_text').val())
											{$ldelim}
										      $('#review_merchant').val('');
											  
											{$rdelim}
										   $('#neg_feedback_text2').hide();
										   $('#review_merchant_label').removeClass("cm-required");
								        {$rdelim}
									{$rdelim}
									
								{$rdelim}
							{$rdelim});
						{$rdelim});
						//]]>
               	</script>
                
<div id="click_{$k}"></div>
<div id="star_{$k}"></div>

</div>
<div style="color: #DD0000; float: right; font: 10px verdana; width:100%; padding-bottom:3px; display:none" id="required_flag_{$k}">
  <b>{$param.name|ucwords}</b> field is mandatory.
</div>
</div>
<!--End Box Rating Small -->
<div class="clearboth"></div>

</div>
{/foreach}
</div>
<div class="form_onecolumn"  style="margin-top:10px;">
<div class="form_onecolumn_row form-field new_form_field_popup_nl">
<input type="hidden" name="neg_text" id="neg_text" value="{$lang.neg_feedback_text}" />
<div class="form_onecolumn_row_fieldname"><label for="review_merchant" id="review_merchant_label" class="label_text_cm">{$lang.desc_merchant_nl}</label></div>

<div class="form_onecolumn_row_field new_form_field_popup_nl">
<textarea name="review_merchant" id="review_merchant" rows="5" cols="45" onclick="if(this.value==$('#neg_text').val())this.value=''; this.style.color='black';" class="form_onecolumn_row_field_textbox"></textarea>
</div>

</div>
</div>
{if $lang.neg_feedback_text2!=''}
<div id="neg_feedback_text2" style="display:none;" class="neg_feedback_text2_msg">
 {$lang.neg_feedback_text2}
</div>
{/if}
</div>
<!--End Box Rating -->

<!--Box Review -->
<div class="float_left width_fourtyfivepercent " style="margin-left:16px;">

<div class="box_headerTwo">
  <h2 class="box_headingTwo">{$lang.write_a_review_about_product}</h2>
</div>
<div style="float:left; margin-top:5px">{$lang.info_product_rating|unescape}</div>


<div class="box_merchantrating" style="width:100%; margin-left:0; padding-top:0; padding-bottom:0; border:0;">


<!--Box Rating -->
<div class="box_RatingBig" style="margin-left:0; border:0;">

{foreach from=$show_info item="product"}
<div class="box_prd_rating_nl">
<div class="form-field">
{assign var="pro_images" value=$product.product_id|fn_get_image_pairs:'product':'M'}
<div class="image_box_nl_popup">
   <div class="pro_image">
    {include file="common_templates/image.tpl" image_width="50" image_height="50" obj_id=$obj_id_prefix images=$pro_images object_type="product" show_thumbnail="Y"}
   </div>
   <label>{$product.product}</label>
</div>
<div class="clearboth"></div>
    <div class="box_RatingBig_heading"><label for="rating_count_{$product.product_id}" style="color:#333!important;" class="">{$lang.rating_product_nl}</label></div>
    <div class="box_RatingBig_star">
       <span class="input-helper">
            <input type="hidden" name="product_rating[{$product.product_id}][product_id]" value="{$product.product_id}"/>
            <input type="hidden" id="rating_count_{$product.product_id}" name="product_rating[{$product.product_id}][rating_count]" value="" class="product_rate">
            <div class="star" id="{$product.product_id}"></div>
            <label style="display:none;float: right;margin: 0; color:red; width:auto">{$lang.rate_required}</label>
      </span>
    </div>
</div>
<div class="form_onecolumn">


<div class="form_onecolumn_row form-field new_form_field_popup_nl">
<div class="form_onecolumn_row_fieldname"><label for="review_{$product.product_id}" class="cm-required dark_color_nl_popup">{$lang.desc_product_nl}</label></div>
<div class="form_onecolumn_row_field new_form_field_popup_nl">
<textarea name="product_rating[{$product.product_id}][review]" id="review_{$product.product_id}" rows="5" cols="45" class="form_onecolumn_row_field_textbox pro_review">{$review}</textarea>
</div>
</div>

</div>
</div>
{/foreach}
<div style="clear:both"></div>



</div>
<!--End Box Rating -->
</div>




</div>
<!--End Box Review -->


</div></div></div>

 {$lang.feed_back_msg|unescape}

<div class="box_functions" style="margin-right:25px; float:right; padding-bottom:10px; margin-top:0;" >

<input type="hidden" name="user_name" id="user_name" value="{$user_info.firstname}   {$user_info.lastname}" />

<!--<input type="submit" name="submit" value="submit" id="hid_button" style="display:none" />-->



<input type="submit" name="dispatch[orders.new_feedback_post]" value="{$lang.sub_feed}" class="box_functions_button" />
</form>
</div>

<div style="clear:both;"></div>
{literal}
<script type="text/javascript">


$(function() {
	$('#click').raty({
		click: function(score, evt) {
			$('#product_rating').val(score);
		}
	});
});

function vaidate_merchant_rating()
{
	var i=0;
	var valid=0;
	var j=0;
	var value1=$('#review_merchant').val();
	var value2=$('#neg_text').val();
	var notchecked_key=Array();
	while(document.getElementById('checked_field_'+i))
	{
		var value=$('#checked_field_'+i).val();
		$('#required_flag_'+i).hide();
		if(value==1)
		{
			valid++;
						
		}
		else
		{
			notchecked_key[j]=i;
			j++;
		}
		i++;
		
	}
	
	if(valid!=0)
	{
		for(var j=0; j<notchecked_key.length; j++)
		{
			$('#required_flag_'+notchecked_key[j]).show();
		}
		if(notchecked_key.length>0)
		{
			$('#scrollbar1').tinyscrollbar();
			return false;
		}
	
	}
	else if($('#review_merchant').val()!='' && valid==0)
	{
		for(var j=0; j<notchecked_key.length; j++)
		{
			$('#required_flag_'+notchecked_key[j]).show();
		}
		if(notchecked_key.length>0)
		{
			$('#scrollbar1').tinyscrollbar();
			return false;
		}
		
	}
   
   if(value1==value2)
	{
		$('#review_merchant').val('');
		$('#neg_feedback_text2').hide();
		$('#scrollbar1').tinyscrollbar();
		return false;
	}
	var ret=validate_product_review();
	if(ret)
	return true;
	else
	{
		$('#scrollbar1').tinyscrollbar();
		return false;
	}
	
	
	
}
function validate_product_review()
{
	var rate_not_valid=0;
	var review_not_valid=0;
	$('.product_rate').each(function() {
		obj=$(this).parent();
		obj.children('label').hide();
        if(this.value=='')
		{
			obj.children('label').show();
			rate_not_valid=1;
		}
		});
		
	$('.pro_review').each(function() {
		$(this).css('border','1px solid #D2D7D9');
        if(this.value=='')
		{
			$(this).css('border','1px solid red');
			review_not_valid=1;
		}
		});
	if(rate_not_valid==0 && review_not_valid==0)
	{
   	 	return true;
	}
	else
	{
		return false;
	}
}
</script>



{/literal}

{literal}
<script type="text/javascript">
		$(document).ready(function(){
			$('#scrollbar1').tinyscrollbar();	
		});
	</script>	
{/literal}
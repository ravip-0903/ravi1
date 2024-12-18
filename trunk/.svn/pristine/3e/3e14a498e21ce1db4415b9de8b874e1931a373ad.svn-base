{literal}
<style>
.width_fourtyfivepercent textarea{height:60px; max-height:60px;}
.box_merchantrating{padding:5px 0; margin-left:0;}
.box_merchantrating .box_RatingBig{margin-left:0;}
.box_rating_heading_hnl{color:#333!important;}
.box_RatingBig .form-field{margin-left:0;}
.box_functions_button{cursor:pointer;}
.box_RatingSmall_heading h2{background:none;}
</style>
{/literal}

{literal}
<script type="text/javascript">
	$('#star').raty({
				click: function(score, evt) {
					alert('ID: ' + $(this).attr('id') + '\nscore: ' + score + '\nevent: ' + evt);
				}
	});
</script>
{/literal}
<form action="{""|fn_url}" method="post" id="my_form" name="sdeep_product_rating"  class="cm-form-highlight" enctype="multipart/form-data" >		
<div class="myaccnt_moble_pndng_feedbck" style="width:773px;">

<!--Box Heading -->
<div class="box_header">
<h1 class="box_heading">{$lang.product_and_merchant_feedback}</h1>
</div>
<!--End Box Heading -->

<!--Box Review Product Details -->
<div class="box_ProductReviews">

<!--Review Prodcut Image -->
<div class="box_ProductReviews_image">
{assign var="auth" value=$smarty.session.auth}
   {assign var="product_info" value=$smarty.request.product_id|fn_get_basic_product_info}
   {assign var="pro_images" value=$smarty.request.product_id|fn_get_image_pairs:'product':'M'}
   {include file="common_templates/image.tpl" image_width="50" image_height="50" obj_id=$obj_id_prefix images=$pro_images object_type="product" show_thumbnail="Y"}
</div>
<!--End Review Prodcut Image -->
<!--Details -->
<div class="box_ProductReviews_detail">
<!--Review Product Details -->
<div class="box_ProductReviews_details" style="width:230px;">
<div class="box_ProductReviews_details_name">{$product_info.product}</div>

<div class="box_ProductReviews_details_row">
<div class="box_ProductReviews_details_resultname">{$lang.you_paid}:</div>
<div class="box_ProductReviews_details_result bold">{$order_info.subtotal}</div>
</div>

<div class="box_ProductReviews_details_row">
<div class="box_ProductReviews_details_resultname">{$lang.status}:</div>
<div class="box_ProductReviews_details_result bold">{assign var="status" value=$order_info.status|fn_get_status_data} {$status.description}</div>
</div>

<div class="box_ProductReviews_details_row">
<div class="box_ProductReviews_details_resultname">{$lang.sale_date}:</div>
<div class="box_ProductReviews_details_result">{$order_info.timestamp|date_format}</div>
</div>


</div>
<!--End Review Product Details -->





<div class="clearboth"></div>

<!--Box Rating Small -->
<div class="box_RatingSmall" style="margin-left:10px;">
<div class="box_RatingSmall_heading">{$lang.current_product_rating}</div>
<div class="box_RatingSmall_star">
{include file="addons/discussion/views/discussion/components/average_rating.tpl" object_id=$product_info.product_id object_type="P"}
</div>
</div>


<div class="box_RatingSmall float_right">
<div class="box_RatingSmall_heading">
{assign var="merchant_name" value=$product_info.company_id|fn_get_company_name}
{assign var="merchant_rating_params" value=""|fn_sdeep_get_vendor_rating_params}
{assign var="rating" value=$product_info.company_id|fn_sdeep_get_rating}

{include file="common_templates/subheader.tpl" title="$merchant_name"}
</div>
<div class="box_RatingSmall_star">
{include file="addons/sdeep/components/stars.tpl" stars=$rating|fn_sdeep_get_stars height=12}

</div>
</div>
<!--End Box Rating Small -->

</div>
<!--End Details -->

</div>
<!--End Box Review Product Details -->




<!--Box Rating -->
<div class="myaccnt_merchnt_feedbck float_left width_fourtyfivepercent margin_top_twenty">

<div class="box_headerTwo">
<h2 class="box_headingTwo">{$lang.rate_merchant} ({$merchant_name})</h2>

</div>
<div style="float:left; margin-top:5px">
{$lang.info_merchant_rating|unescape}
</div>
<div style="float:left; margin:10px 0 0 ;">{$lang.rating_merchant_nl}</div>
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
					    $('#star_{$k}').raty({$ldelim}
							click: function(score, evt) {$ldelim}
								alert('ID: ' + $(this).attr('id') + '\nscore: ' + score + '\nevent: ' + evt);
							{$rdelim}
                        {$rdelim});
						
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

</div>
<!--End Box Rating Small -->
<div class="clearboth"></div>
<div style="color: #DD0000; float: right; font: 10px verdana; width: 100%; display:none" id="required_flag_{$k}">
  <b>{$param.name|ucwords}</b> field is mandatory.
</div>
</div>

{/foreach}

<div class="form_onecolumn">
<div class="form_onecolumn_row form-field">
<input type="hidden" name="neg_text" id="neg_text" value="{$lang.neg_feedback_text}" />
<div class="form_onecolumn_row_fieldname"><label for="review_merchant" id="review_merchant_label" class="label_text_cm box_rating_heading_hnl">{$lang.desc_merchant_nl}</label></div>

<div class="form_onecolumn_row_field">
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
<div class="myaccnt_prdct_review float_right width_fourtyfivepercent margin_top_twenty">

<div class="box_headerTwo">
<h2 class="box_headingTwo">{$lang.write_a_review_about_product}</h2>

</div>
<div style="float:left; margin-top:5px">{$lang.info_product_rating|unescape}</div>
<div class="box_merchantrating">


<!--Box Rating -->
<div class="box_RatingBig">

<div class="form-field">

<div class="box_RatingBig_heading"><label for="product_rating" class="cm-required box_rating_heading_hnl" style="background:none;">{$lang.rating_product_nl}</label></div>
<div class="box_RatingBig_star">
 <span class="input-helper">
            	<input type="hidden" name="product_id" value="{$smarty.request.product_id}"/>
                <input type="hidden" name="company_id" value="{$product_info.company_id}"/>
                <input type="hidden" name="order_id" value="{$smarty.request.order_id}"/>

                <input type="hidden" id="product_rating" name="product_rating" value="" />
                <div id="click"></div>
                <div id="star"></div>
          	</span>
</div>
</div>

</div>
<!--End Box Rating -->
</div>


<div class="form_onecolumn">


<div class="form_onecolumn_row form-field">
<div class="form_onecolumn_row_fieldname"><label for="review" class="cm-required box_rating_heading_hnl">{$lang.desc_product_nl}</label></div>
<div class="form_onecolumn_row_field">
<textarea name="review" id="review"  style="width:95%;"  rows="5" cols="45" class="form_onecolumn_row_field_textbox">{$review}</textarea>
</div>
</div>

</div>

<div class="form_twocolumn">
<div class="box_rating_heading_hnl">{$lang.image_product_nl}</div>


<div class="form_twocolumn_row form-field" style="margin-top:10px;">
<div class="form_twocolumn_row_fieldname"><label>{$lang.images}:</label></div>
<div class="form_twocolumn_row_field">
<input type="file" name="review_img"  class="form_twocolumn_row_field_textbox" />
<ul class="form_twocolumn_row_aboutfield">
<li>{$lang.upload_image_instruction1}</li>
<li>{$lang.upload_image_instruction2}</li>
</ul>

</div>
</div>
<div class="form_twocolumn_row form-field">


  <div class="">
            
            <span class="input-helper">
            	
            </span>
        </div>


<div class="form_twocolumn_row_fieldname"><label>{$lang.video_link}:</label></div>
<div class="form_twocolumn_row_field">
<input type="text" name="video_url" class="form_twocolumn_row_field_textbox" />
<ul class="form_twocolumn_row_aboutfield">
<li>{$lang.video_instruction}</li>
</ul>

</div>
</div>
</div>


</div>
<!--End Box Review -->
{assign var="customerId" value=$smarty.session.auth.user_id}
<div style="float:left; clear:both">
 {$lang.feed_back_msg|unescape}
</div>
<div class="box_functions">
<input type="hidden" name="dispatch" value="rate_product.update" />
<input type="hidden" name="user_name" id="user_name" value="{$user_info.firstname}   {$user_info.lastname}" />
<input type="hidden" name="pb" id="pb" value="0" />
<input type="hidden" name="cb" id="cb" value="0" />
<input type="hidden" name="ipb" id="ipb" value="0" />
<input type="button" name="submit" value="Submit" class="box_functions_button" onclick="validate_review()"/>
<input type="submit" name="submit" value="submit" id="hid_button" style="display:none" />
</div>

<div style="clear:both;"></div>
</div>
</form>

{literal}
<script type="text/javascript">
function validate_review()
{
    var img = "<img src='http://api.targetingmantra.com/RecordEvent?mid=130915&eid=5&pid={/literal}{$smarty.request.product_id}{literal}&cid={/literal}{$customerId}{literal}' width='1' height='1'>";
    $("body").append(img);
	var pro_review=$('#review').val();
	var comp_review=$('#review_merchant').val();
	var name=$('#user_name').val();
	$.ajax({
      type: "POST",
      url: "index.php",
      data: { dispatch: 'rate_product.validate', name:name, pro_review:pro_review, comp_review:comp_review},
      success: function(msg) {
    	  if(msg=="IP_BLOCKED")
		  {
			  $('#ipb').val(1);
		  }
		  else
		  {
			  var msg=msg.split(',');
			  
			  for(i=0;i<msg.length;i++)
			  {
				  if(msg[i]!='' && msg[i]=='PRO_REVIEW_BLOCKED')
				  {
					  $('#pb').val(1);
				  }
				  else if(msg[i]!='' && msg[i]=='COMP_REVIEW_BLOCKED')
				  {
					  $('#cb').val(1);
				  }
			  }
		  }
		  
		  var ret=vaidate_merchant_rating();
		  
		  if(ret)
		  {
			  $('#hid_button').click();
			  //document.getElementById("sdeep_product_rating").submit();
		  }
	  }
	});
}

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
			return false;
		}
		
	}
   
   if(value1==value2)
	{
		$('#review_merchant').val('');
		$('#neg_feedback_text2').hide();
		return false;
	}
	return true;
	
}
</script>
{/literal}

{* $Id: myaccount.tpl 12544 2011-05-27 10:34:19Z bimib $ *}
<div class="mobile_pndng_feedbck" style="width:773px; margin:auto;">
<div class="box_header">
<h1 class="box_heading">{$lang.pend_feedback}</h1>
</div>
{if $pend_feedback_count>0}
{foreach from=$pend_feedback_order item="product"}
<div class="box_ProductReviews">

<!--Review Prodcut Image -->
<div class="box_ProductReviews_image">
  <a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="box_GridProduct_product">
{assign var="pro_images" value=$product.product_id|fn_get_image_pairs:'product':'M'}
{include file="common_templates/image.tpl" image_width="160" image_height="160" obj_id=$product images=$pro_images object_type="product" show_thumbnail="Y"}</a>
</div>
<!--End Review Prodcut Image -->
<!--Details -->
<div class="box_ProductReviews_detail">
<!--Review Product Details -->
<div class="box_ProductReviews_details">
<div class="box_ProductReviews_details_name"><a href="{"products.view?product_id=`$product.product_id`"|fn_url}">{$product.product_id|fn_get_product_name|truncate:50:"---"}</a></div>

<div class="box_ProductReviews_details_row">
<div class="box_ProductReviews_details_resultname">You Paid:</div>
<div class="box_ProductReviews_details_result bold">Rs.{$product.total}</div>
</div>


<div class="box_ProductReviews_details_row">
<div class="box_ProductReviews_details_resultname">Status:</div>
<div class="box_ProductReviews_details_result bold">{if $product.status=='C'}Completed{/if}</div>
</div>

<div class="box_ProductReviews_details_row">
<div class="box_ProductReviews_details_resultname">Order Id:</div>
<div class="box_ProductReviews_details_result bold">{$product.order_id}</div>
</div>

<div class="box_ProductReviews_details_row">
<div class="box_ProductReviews_details_resultname">Sale date:</div>
<div class="box_ProductReviews_details_result">{$product.sale_date}</div>
</div>


</div>
<!--End Review Product Details -->

<!--Hide clues bucks by Ajay-->

<!--Box communication -->
<!--<div class="box_ProductReviews_statuscommunication">
{if $product.reward_point!=''}{$lang.earn_buck}&nbsp;&nbsp;{$product.reward_point}{/if}
</div>-->
<!--End Box communication -->
<!--Box communication -->

<!-- End Hide clues bucks by Ajay-->

<!--Box Functions -->
<div class="box_ProductReviews_functions">

<input name="" type="button" class="box_ProductReviews_functions_button" value="Post Your Feedback" onclick="location.href='index.php?dispatch=rate_product.manage&order_id={$product.order_id}&product_id={$product.product_id}'" />

<a href="index.php?dispatch=orders.details&order_id={$product.order_id}">View order details</a>
<!--<a href="#">Contact seller</a>
<a href="#" class="border_none">Add note</a>-->
</div>
<!--End Box Functions -->

<div class="clearboth"></div>

<!--Box Rating Small -->
<!--<div class="box_RatingSmall">
<div class="box_RatingSmall_heading">Current product rating</div>
<div class="box_RatingSmall_star">
<img src="images/icon_starsmall.gif" width="14" height="14" />
<img src="images/icon_starsmall.gif" width="14" height="14" />
<img src="images/icon_starsmall.gif" width="14" height="14" />
<img src="images/icon_starsmall.gif" width="14" height="14" />
<img src="images/icon_starsmall_unselected.gif" width="14" height="14" />
</div>
</div>


<div class="box_RatingSmall float_right">
<div class="box_RatingSmall_heading">Current product rating</div>
<div class="box_RatingSmall_star">
<img src="images/icon_starsmall.gif" width="14" height="14" />
<img src="images/icon_starsmall.gif" width="14" height="14" />
<img src="images/icon_starsmall_unselected.gif" width="14" height="14" />
<img src="images/icon_starsmall_unselected.gif" width="14" height="14" />
<img src="images/icon_starsmall_unselected.gif" width="14" height="14" />
</div>
</div>-->
<!--End Box Rating Small -->

</div>
<!--End Details -->

</div>
{/foreach}
{else}
<div>{$lang.no_pend_feedback}</div>
{/if}
</div>

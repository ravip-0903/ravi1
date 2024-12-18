{* 
    @author:- Shashi kant
    @description:- Submitted Feedback on My Account page
    @created date:- 17/02/2014
*}
<div class="mobile_pndng_feedbck submitted_feedback">
<div class="box_header">
<h1 class="box_heading">{$lang.submitted_feedbacks}</h1>
</div>
{if $post_feedback_count>0}
{foreach from=$post_feedback_order  key="index" item="product"}

    {if $index == 0}
        <div class="box_ProductReviews">
            {else}
                <div class="box_ProductReviews dactive">
    {/if}

<div class="reviewed_date_sbmt">{$lang.submittion_review_date} : {$product.creation_date}</div>

<!--Review Prodcut Image -->
<div class="box_ProductReviews_image">
  <a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="box_GridProduct_product">
{assign var="pro_images" value=$product.product_id|fn_get_image_pairs:'product':'M'}
{include file="common_templates/image.tpl" image_width="160" image_height="160" obj_id=$product images=$pro_images object_type="product" show_thumbnail="Y"}</a>
<a class="order_id" href="index.php?dispatch=orders.details&order_id={$product.order_id}">{$product.order_id}</a>
<div class="prc">Rs.{$product.total}</div>
<div class="date">{$product.sale_date}</div>
</div>


<!--End Review Prodcut Image -->
<!--Details -->
<div class="box_ProductReviews_detail smart_rating">
<!--Review Product Details -->
<div class="box_ProductReviews_details">
<h1 class="box_heading">{$lang.merchant_reviews}({$product.company})</h1>

<div class="box_negativerating">
<div class="box_negativerating_heading">Shipping Time :</div>
<div class="box_negativerating_stars">
{assign var="default_star" value=5}
{section name='stars' loop=$product.shipping_time}
<img src="images/rating/star-on.png" width="13" height="12" alt="*" />
{/section}
{assign var="off_star" value=$default_star-$product.shipping_time}
{section name='stars1' loop=$off_star}
<img src="images/rating/star-off.png" width="13" height="12" alt="" />
{/section}
</div>
</div>

<div class="box_negativerating">
<div class="box_negativerating_heading">Shipping Cost :</div>
<div class="box_negativerating_stars">
{assign var="default_star" value=5}
{section name='stars' loop=$product.shipping_cost}
<img src="images/rating/star-on.png" width="13" height="12" alt="*" />
{/section}
{assign var="off_star" value=$default_star-$product.shipping_cost}
{section name='stars1' loop=$off_star}
<img src="images/rating/star-off.png" width="13" height="12" alt="" />
{/section}
</div>
</div>

<div class="box_negativerating">
<div class="box_negativerating_heading">Product Quality :</div>
<div class="box_negativerating_stars">
{assign var="default_star" value=5}
{section name='stars' loop=$product.product_quality}
<img src="images/rating/star-on.png" width="13" height="12" alt="*" />
{/section}
{assign var="off_star" value=$default_star-$product.product_quality}
{section name='stars1' loop=$off_star}
<img src="images/rating/star-off.png" width="13" height="12" alt="" />
{/section}
</div>
</div>

<div class="box_negativerating">
<div class="box_negativerating_heading">Value For Money :</div>
<div class="box_negativerating_stars">
{assign var="default_star" value=5}
{section name='stars' loop=$product.value_for_money}
<img src="images/rating/star-on.png" width="13" height="12" alt="*" />
{/section}
{assign var="off_star" value=$default_star-$product.value_for_money}
{section name='stars1' loop=$off_star}
<img src="images/rating/star-off.png" width="13" height="12" alt="" />
{/section}
</div>
</div>

<div class="box_ProductReviews_details_row">{$product.review_merchant}</div>

</div>

<div class="box_ProductReviews_details second_blk_rgt_sd">
    <div class="box_ProductReviews_details_name"><h1 class="box_heading"><a class="ahover_nl" href="{"products.view?product_id=`$product.product_id`"|fn_url}">{$product.product_id|fn_get_product_name|truncate:50:"---"}</a></h1></div>

<div class="box_negativerating">
<div class="box_negativerating_heading">Product Rating :</div>
<div class="box_negativerating_stars">
{assign var="default_star" value=5}
{section name='stars' loop=$product.product_rating}
<img src="images/rating/star-on.png" width="13" height="12" alt="*" />
{/section}
{assign var="off_star" value=$default_star-$product.product_rating}
{section name='stars1' loop=$off_star}
<img src="images/rating/star-off.png" width="13" height="12" alt="" />
{/section}
</div>
</div>
<div class="box_ProductReviews_details_row">{$product.review}</div>
<div class="more_less ahover_nl" id = "more_less_{$index}"> {if $index == 0}Less (-){else}More (+){/if}</div>
</div>

<!--<div class="box_ProductReviews_statuscommunication">
{if $product.reward_point!=''}{$lang.earn_buck}&nbsp;&nbsp;{$product.reward_point}{/if}
</div>-->


<div class="clearboth"></div>

</div>

</div>
{/foreach}
{else}
<div>{$lang.no_submitted_feedback}</div>
{/if}
</div>

{literal}
    <script>
        $('.box_ProductReviews').click(function(){
            $('.box_ProductReviews').addClass('dactive');
            $('.more_less').text('More (+)');
            $(this).removeClass('dactive');
            $(this).find('.more_less').text('Less (-)');
        });
        </script>
    {/literal}
{literal}
<style type="text/css">
.other_slr_blk{width:100%;float: left;margin-bottom: 14px; border-bottom:2px solid #048CCC;}
.other_slr_blk .other_slr_lst{float: left; width: 98%; border-bottom: 1px solid #eee;padding: 5px;display: block;background: #fff;border-top: 1px solid #fff; clear: both;color: #666;line-height: 28px;margin-bottom: 1px;}
.other_slr_blk .other_main_heading{border-left:0px none; border-right:0px none; background: none repeat scroll 0 0 #FFFFFF; border-bottom: 2px solid #048CCC; color: #333333; font-size: 14px;font-weight:bold;}
.other_slr_lst .other_slr_selr_name{float:left; width: 25%; padding-right:5px; line-height: auto;}
.other_slr_lst .other_slr_prd_nm{float:left; width: 30%; padding-right:5px; line-height: auto;}
.other_slr_lst .other_slr_prd_prc{float:left; width: 18%; padding-right:5px; line-height: auto;}
.other_slr_lst .other_slr_prd_cod{float:left; width: 5%; padding-right:5px; line-height: auto;}
.other_slr_lst .other_slr_prd_buynow{float:left; width: 16%; padding-right:5px; line-height: auto;}
.other_slr_prd_nm p{color:#808080;}
.other_slr_prd_nm p b{font-weight:normal; color:#808080;}
.sellerclass{float: left;
    position: relative;
    width: 100%;}
    .sellerclass a{
        background-color: #FFFFFF;
    border: 1px solid #EEEEEE;
    left: 50%;
    margin-left:-52px;
    margin-top: -9px;
    padding: 2px 5px;
    position: absolute;
    }
    .sellerpopup{
        display:none;
        position: absolute !important;
        left: 20% !important;
        top: 9.5% !important;
    }
</style>
{/literal}
{assign var="pid" value=$product.product_id}
{assign var="sameproducts" value=$product|fn_other_sellers_same_product}
{if !empty($sameproducts)}
    <div class="other_slr_blk">
    <div class="ml_pageheaderCateogry" style="margin-top:0px; border-bottom:0px none;">
        <h1 class="ml_pageheaderCateogry_heading"><a name="Description" style="cursor:pointer;">{$lang.other_sellers_of_same_product}</a></h1>
    </div>
    <div class="other_slr_lst other_main_heading">
        <span class="other_slr_selr_name">Merchant</span>
        <span class="other_slr_prd_nm">Product</span>
        <span class="other_slr_prd_prc">Price</span>
        <span class="other_slr_prd_cod">COD</span>
        <span class="other_slr_prd_buynow"></span>
    </div>
    <div class="sellerpopup pj2_popup_prd">
<p style="font:12px/18px trebuchet MS; color:#000; display:block; text-align:left; padding:0; font-weight:bold; margin:0px 0 0 5px;">What does Top Rated Merchant mean to me?</p>
<ul class="content">
    <li>
    Any time you see the TRM seal next to a merchant, rest assured that this merchant has been rated excellent on all possible parameters by our team and by the ShopClues community. Our team has verified that the merchant exhibits highest standards for customer service, return, pricing, brands/selection etc, and you will have an excellent shopping experience with this merchant.</li>
</ul>
</div>
    {foreach from=$sameproducts key="key" item="product" name=last_product}
       <div class="other_slr_lst" id="seller_{$key}" {if $key>2} style="display:none;" {/if} {if $smarty.foreach.last_product.last} style="border-bottom:0px;" {/if}>
    <span class="other_slr_selr_name">
        {*assign var="is_trm" value=$product.company_id|fn_sdeep_is_trm*}

        {assign var="is_trm" value=""}
        {assign var="sort_price" value=""}

        {assign var="is_trm" value=$product.is_trm}
        {assign var="sort_price" value=$product.sort_price}
        <a style="float:left;" href="{"companies.view?company_id=`$product.company_id`"|fn_url}" target="_blank">{$product.merchant}</a>{if $is_trm==Y} <a class="trm_clk" style="float:left; margin-left:5px;"><img width="20" src="http://cdn.shopclues.com/images/banners/shopclues_trm_icon.png"></a>{/if}

  <!--Merchant Rating --> 
{assign var="vendor_info" value=$product.company_id|fn_sdeep_get_vendor_info}
    {assign var="rating" value=$product.company_id|fn_sdeep_get_rating}
    {*={$rating}={$product.merchant_rating}*}
    {assign var="feedback" value=$product.company_id|merchant_detail_rating}
    {*assign var="auth_dealer_info" value=$product.company_id|fn_sdeep_get_auth_dealer_info*}
                {if $rating}
                  {assign var="feedback_count" value=$feedback.count|default:0}
                    {assign var="feedback_positive" value=$feedback.positive+$feedback.neutral|default:0}
                    <a style="float:left;" href="{"index.php?dispatch=companies.view&company_id=`$vendor_info.company_id`"|fn_url}#feedback_heading">
                    <div style="clear:both; float:left;">{include file="addons/discussion/views/discussion/components/stars.tpl" stars=$rating|fn_sdeep_get_stars}</div>      </a>
                    <!--<div class="clearboth"></div>-->
                    <span class="pj2_rating_text" style="float:left; padding:0px; margin:0px; line-height:0px; margin-top:10px;">
                    {if $feedback_count} ({$feedback_count} {$lang.mer_rating}{if {$feedback_count > 1}s{/if}){/if}
                    </span>
                    <div class="clearboth"></div>
                    <span style="font-size:12px;" class="">{if $feedback_positive}{$feedback_positive}% positive review{if {$feedback_count > 1}s{/if}{/if}</span>
                {/if} 
 <!--End Merchant Rating --> 
    </span>

    <span class="other_slr_prd_nm">
    <a href="{"products.view?product_id=`$product.product_id`"|fn_url}" target="_blank" alt="{$product.product}">{$product.product|truncate:30:"..."}</a>
    {if $product.product_shipping_estimation == ''}
        {assign var="estimation_id" value=$config.default_shipping_estimation}
    {else}
        {assign var="estimation_id" value=$product.product_shipping_estimation}
    {/if}
    {assign var="shipping_details" value=$estimation_id|fn_my_changes_get_shipping_estimation}
    <p style="margin:0px; padding:0px; line-height:0px; font-size:12px;">{$shipping_details.name}</p>

    </span>

    <span class="other_slr_prd_prc">
        {assign var="last_price" value=""}
        {if $product.third_price !=0}
            {assign var="last_price" value=$product.third_price}

        {else}
            {if $product.price !=0}
                {assign var="last_price" value=$product.price}

            {else}
              {assing var="last_price" value=$product.list_price}

            {/if}
        {/if}
      <span style="color:#808080;">  {$last_price|format_price:$currencies.$secondary_currency:""}</span>

        <p style="margin:0px; padding:0px; line-height:0; color:#808080; font-size:12px;">
            {if $product.free_shipping == 'Y'} 
                <span style="color:green;"> Free Shipping</span>
            {else}
                {if $product.shipping_freight == 0}
                    <span style="color:green;"> Free Shipping</span>
                {else}
                    + {$product.shipping_freight|format_price:$currencies.$secondary_currency:""} Shipping
                {/if}
            {/if}
        </p>
    <span style="font-weight:bold;">  {$sort_price|format_price:$currencies.$secondary_currency:""}</span>
    </span>

    <span class="other_slr_prd_cod">{if $product.is_cod == 'N'} NO {else} <span style="color:green;">YES</span> {/if}</span>

    <span class="other_slr_prd_buynow">
        <form class="cm-disable-empty-files" enctype="multipart/form-data" name="product_form_{$product.product_id}" method="post" action="{""|fn_url}">
        <input type="hidden" value="cart_status,wish_list" name="result_ids">
        <input type="hidden" value="index.php?dispatch=products.view&product_id={$pid}" name="redirect_url">
        <input type="hidden" value="{$product.product_id}" name="product_data[{$product.product_id}][product_id]">
            <span style="width:120px; float:left; " id="add_to_cart_update_{$product.product_id}" class="cm-reload-{$product.product_id} ">
                  <input type="hidden" value="1" name="appearance[show_add_to_cart]">
                  <input type="hidden" value="" name="appearance[separate_buttons]">
                  <input type="hidden" value="1" name="appearance[show_list_buttons]">
                  <input type="hidden" value="action" name="appearance[but_role]">
                   <span id="cart_add_block_{$product.product_id}">
                        <span class="pro_det_add_to_cart_butto">                                        
                            <span class="button-submit-action" style="float:none;margin:21px 0px 0px 21px;" id="wrap_button_cart_{$product.product_id}">
                               <input type="submit" value="Buy Now" name="dispatch[checkout.add..{$product.product_id}]" id="button_cart_{$product.product_id}">
                         </span>
                          </span>
                  </span>
            </span>
        </form>
    </span>

    </div>
    {/foreach}

    {if $sameproducts|count gt 3}
        <div id="showseller" class="sellerclass"><a href="javascript:void(0);" onclick="showseller();">{$lang.view_all_sellers}</a></div>
        <div id="hideseller" style="display:none;" class="sellerclass"><a href="javascript:void(0);" onclick="hideseller();">{$lang.view_less_sellers}</a></div>
    {/if}

    </div>
    {literal}
        <script>
        function showseller(){
            $('div[id^=seller]').css('display', 'block');
            
            $('div[id=showseller]').css('display', 'none');
            $('div[id=hideseller]').css('display', 'block');
        }
        function hideseller(){
            $('div[id^=seller]').css('display', 'none'); 

            $('div[id=seller_0]').css('display', 'block');
            $('div[id=seller_1]').css('display', 'block');
            $('div[id=seller_2]').css('display', 'block');

            $('div[id=showseller]').css('display', 'block');
            $('div[id=hideseller]').css('display', 'none');
            //$('.other_main_heading').focus(); 
             $('html, body').animate({scrollTop:528}, 800);
                           
        }

        $(".trm_clk").hover(function(){$(".sellerpopup").show();$(".sellerpopup").css("left",($(this).position().left+60));$(".sellerpopup").css("top",($(this).position().top-200));},function(){$(".sellerpopup").hide()});

        </script>
    {/literal}

{/if}

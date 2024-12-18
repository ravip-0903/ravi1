{literal}
    <script type="text/javascript">
        $(document).ready(function() {
            $('.jqzoom').live("hover", function() {
                $(this).jqzoom({
                    //          zoomType: 'standard',
                    lens: false,
                    preloadImages: true,
                    alwaysOn: false,
                    preloadText: 'Loading zoom',
                    zoomType: 'reverse',
                    zoomWidth: 440,
                    zoomHeight: 330,
                    lens:true,
                            title: false
                });
            });
        });
    </script>
{/literal}
{* $Id: default_template.tpl 12442 2011-05-12 12:45:40Z 2tl $ *}
{script src="js/exceptions.js"}
{if $product.tracking == "O"}
    {assign var="actual_amount" value=$product.inventory_amount}
{else}
    {assign var="actual_amount" value=$product.amount}
{/if}
{capture name="val_no_ajax"}{/capture}
{assign var="show_rating" value=true}
{assign var="show_old_price" value=true}
{assign var="show_price" value=true}
{assign var="show_list_discount" value=true}
{assign var="show_clean_price" value=true}
{assign var="show_discount_label" value=true}
{assign var="show_product_amount" value=true}
{assign var="show_product_options" value=true}
{assign var="show_qty" value=true}
{assign var="show_add_to_cart" value=true}
{assign var="show_list_buttons" value=false}
{assign var="but_role" value="action"}
{assign var="details_page" value=true}
{assign var="no_ajax" value=$smarty.capture.val_no_ajax}
{assign var="product_amount" value=$product.inventory_amount|default:$product.amount}
<input type="hidden" id="product_shipping" value={$product.shipping_freight}>
<input type="hidden" id="is_product_shipping" value={$product.free_shipping}>
<input type="hidden" id="product_amount" value={$product_amount}>
<div class="clk_view_prd_blk quickView">
    <div class="product-main-info ql_main_cntnr">
    <div onclick="quick_look_close({$product.product_id})" class="ql_popup_close"></div>
    {hook name="products:view_main_info"}
    {if $product}
        {assign var="after_apply_promotion" value=''}
        {if $product.promotion_id !=0}
            {assign var="after_apply_promotion" value=$product|fn_get_3rd_price}
        {/if}

        {assign var="obj_id" value=$product.product_id}
        {include file="common_templates/product_data.tpl" product=$product}

        {assign var="form_open" value="form_open_`$obj_id`"}
        {$smarty.capture.$form_open}
        <div class="clear margin_top_five">
            <!--Modified by clues dev to add new mark on product-->
            {assign var="is_new" value=$product|check_product_for_new}
            {if $is_new == 'new'}
                <div class="label_new_prodctdetails"></div> 
            {/if}
            {assign var="is_ngo" value="$product.company_id|fn_check_merchant_for_ngo"}
            {if $is_ngo == 'Y'}
                <div class="label_ngo_detail">{$lang.ngo_popup_hover}</div> 
            {/if}
            
            {assign var="discount_label" value="discount_label_`$obj_id`"}

            {if $show_discount_label && $smarty.capture.$discount_label|trim}
                <div class="float-left">
                    {$smarty.capture.$discount_label}
                </div>
            {/if}

            {assign var="image_cat_id" value=$product.category_ids|fn_get_category_image}
            {assign var="image_cat" value="-"|explode:$image_cat_id}
            {if $image_cat.1 !=''}
                {if $image_cat.0 > 0}
                    <div class="cate_icon_nl">
                        <img src="{$image_cat.1}">
                        <div class="label_cate_image" style="display:none;">{$lang.cate_image_hover}</div> </div>
                    {/if}
                {/if}
            <div class="ql_prd_img_left_blk">
                {if !$no_images}

                    <div class="prd-add-thm no_mobile image-border prew_mng float-left center cm-reload-{$product.product_id} img_cntr_new_div" id="product_images_{$product.product_id}_update">

                        {include file="views/products/components/product_images.tpl" product=$product show_detailed_link="Y"}
                    {/if}

            </div>
            <div class="mobile-prdct-cntnr" itemscope itemtype="http://schema.org/Product">
                <div class="product-info">
                    <h1 class="mainbox-title" itemprop="name" style="margin-left: 0px;">{$product.product|unescape}</h1>
                    <div class="clearboth"></div>
                    <div class="float_left">{assign var="rating" value="rating_`$obj_id`"}</div>
                    <div class="float_left" ></div>
                    <div class="float_left str_prd_rat_icon" style="font:12px/14px 'Trebuchet MS', Arial, Helvetica, sans-serif; color:#636566">{$smarty.capture.$rating}</div>            
                <div class="clearboth"></div>
                {assign var="old_price" value="old_price_`$obj_id`"}
                {assign var="price" value="price_`$obj_id`"}
                {assign var="clean_price" value="clean_price_`$obj_id`"}
                {assign var="list_discount" value="list_discount_`$obj_id`"}
                <div class="short_text">{$product.short_text|unescape}</div>                 
                <div class="{if $smarty.capture.$old_price|trim || $smarty.capture.$clean_price|trim || $smarty.capture.$list_discount|trim}prices-container {/if}clear">
                    <table class="mobile-prc-table" border="0" cellspacing="0" width="397" cellpadding="0">
                        <tr><td>
                           {if $smarty.capture.$old_price|trim || $smarty.capture.$clean_price|trim || $smarty.capture.$list_discount|trim}
                                    <div class="float-left product-prices">
                                        <!--Added by clues dev to show original price when catlog promotion is applied.-->
                                        {if $product.promotions}
                                            {if $product.list_price != $product.base_price}
                                                <span class="list-price float-left margin_right_five">{$lang.list_price}:
                                                    <strike>{include file="common_templates/price.tpl" value=$product.list_price span_id="list_price_`$obj_prefix``$obj_id`" class="list_price"}</strike>{/if}
                                                    {/if}
                                            </span>
                                            <div class="float_left" style="font:11px verdana #636566; margin-top:4px;">{if $smarty.capture.$old_price|trim}{$smarty.capture.$old_price}&nbsp;{/if}</div>

                                        {/if}
                                        <div class="clearboth"></div>

                                    {if !$smarty.capture.$old_price|trim || $details_page}<p>{/if}
                                        {$smarty.capture.$price}

                                        {if $after_apply_promotion != 0}
                                            <br><span class="price"><span class="lst_price_tit_nl {if $after_apply_promotion != 0}prc_third_app{/if}">{$lang.3rd_Price}:</span>{$after_apply_promotion|format_price:$currencies.$secondary_currency:""}</span>
                                        {/if}

                                    {if !$smarty.capture.$old_price|trim || $details_page}</p>{/if}

                                {if $smarty.capture.$old_price|trim || $smarty.capture.$clean_price|trim || $smarty.capture.$list_discount|trim}
                                    {$smarty.capture.$clean_price}

                                    {*{$smarty.capture.$list_discount}*}

                                </div>
                                 {/if}
                        </td>
                        <td valign="bottom">
                            <div class="float-left mobile-dscnt-prc" style="clear:both; float:right; width:215px; text-align:center; display:inline; font:12px/14px 'Trebuchet MS', Arial, Helvetica, sans-serif; color:#636566; margin-bottom:3px; color:#048ccc;" id="product_save">

                                {if $product.promotions}
                                    {assign var="disc_label" value=$product|calculate_discount_perc}
                                {else}
                                    {if $product.discount}
                                        {assign var="disc_label" value=$product.discount_prc}
                                    {else}
                                        {assign var="disc_label" value=$product.list_discount_prc}
                                    {/if}
                                {/if}
                                <!-- Added By Sudhir dt 09 octo 2012 to show third price percentage-->
                                {if $product.promotion_id !=0}
                                    {if $after_apply_promotion !=0}			
                                        {assign var="disc_label" value=$product|calculate_3rd_price_percentage:$after_apply_promotion}
                                    {/if}
                                {/if}

                            </div>
                        </td>
                    </tr></table>
                <div class="clearboth"></div>
                <div style="float:left; display:inline; font:12px/14px 'Trebuchet MS', Arial, Helvetica, sans-serif; color:#636566; margin-left:0px;">{$lang.price_includes_all_taxes}</div>
                <div class="clearboth"></div>
       
    </div>  
</div>

{if $capture_options_vs_qty}{capture name="product_options"}{/if}
            <div class="mobile-stck-dlvry">
            {assign var="product_amount" value="product_amount_`$obj_id`"}
            {$smarty.capture.$product_amount}
            </div>
            <div class="ql_qty_blk">
                <div class="ql_left_blk">{if $product.amount > 0 }
                    {assign var="product_options" value="product_options_`$obj_id`"}
                    {$smarty.capture.$product_options}
                    {/if}

                        {assign var="qty" value="qty_`$obj_id`"}
                        {$smarty.capture.$qty}

                        {assign var="advanced_options" value="advanced_options_`$obj_id`"}
                        {$smarty.capture.$advanced_options}
                        {if $capture_options_vs_qty}{/capture}{/if}

                        {assign var="min_qty" value="min_qty_`$obj_id`"}
                        {$smarty.capture.$min_qty} 
                       {if (($product.amount <= 0 || $product.amount < $product.min_qty))}
                        <div class="ql_sold_out">{$lang.sold_out_quick}</div>
                        {else}
                        {if $product.free_shipping=='Y'}
                        <div class="ql_free_shipping">{$lang.free_shipping_quick}</div>
                        {elseif $product.shipping_freight>0}
                            <div class="ql_shipping">{$lang.shipping_quick}<span class="list-price nowrap">{$product.shipping_freight|string_format:"%d"}</span></div>
                        {else}
                         <div class="ql_free_shipping">{$lang.free_shipping_quick}</div>
                        {/if}
                        {/if}
				</div>
				<div class="ql_right_blk">
                    <div class="ql_pin_blk">
                        <span>Enter Pincode:</span>
                        <input type="text" id="pincode{$product.product_id}" onkeyup="check_pin({$product.product_id},event);" {if isset($smarty.cookies.pincode)} value={$smarty.cookies.pincode} {else} placeholder="Enter Pincode"{/if} maxlength=6> 
                    </div>
                    <span class="ql_pincode" id="pincode"></span>
                    <span class="ql_cod_payment" id="cod_payment"></span>
                </div>
			</div>
                                    {assign var="product_edp" value="product_edp_`$obj_id`"}
                                    {$smarty.capture.$product_edp}
                                    {if $capture_buttons}{capture name="buttons"}{/if}
				<div class="buttons-container nowrap" style="{if $actual_amount == 0}{if ($product.out_of_stock_actions == "S") && ($product.tracking != "O")}margin-top:0px; {else}margin-top:25px;{/if}{else}margin-top:25px;{/if}">
                                                {assign var="add_to_cart" value="add_to_cart_`$obj_id`"}
                                                {$smarty.capture.$add_to_cart}
					
                                                {assign var="list_buttons" value="list_buttons_`$obj_id`"}
                                                {$smarty.capture.$list_buttons}
				</div>
                                                {if $capture_buttons}{/capture}{/if}
				<div class="ql_wishlist"> 
		        	<a class="wish_lst_lnk" id="ajaxified_wishlist" class="ajaxified_wishlist" onclick="ajaxifiedWishlist({$product.product_id})">Add to Wishlist</a>
				</div>
                <div class="ql_prd_full_view">
                <a href="{"products.view?product_id=`$product.product_id`"|fn_url}">{$lang.view_detail_quick_look}</a>
                </div>
            
		</div>
        
        </div>                            
                                                            {assign var="form_close" value="form_close_`$obj_id`"}
                                                            {$smarty.capture.$form_close}
                                                            {/if}
	
                                                                {/hook}                                                                                                         
</div>
<div class="sellerating">     
<div class="row"><span>Seller Details :</span>{$vendor_rating.vendor_info.company} </div>
{if $vendor_rating.rating}
<div class="box_GridProduct_starrating row">
	<span>Seller Ratings :</span>
    {include file="addons/discussion/views/discussion/components/top_banner_stars.tpl" stars=$vendor_rating.rating|fn_get_discussion_rating}
</div>
{/if}
<div class="row"><span>Location :</span>{$vendor_rating.state.city},{$vendor_rating.state.state} </div>
</div>

<div class="ql_footer">{$lang.ql_links}</div>
                                                                                                                                    {literal}
                                                                                                                                        <script>
                                                                                                                                            $('.contest_icon').click(function() {
                                                                                                                                                $('.contest_popup').show();
                                                                                                                                            });
                                                                                                                                            $('.img_close').click(function() {
                                                                                                                                                $('.contest_popup').hide();
                                                                                                                                            });
                                                                                                                                        </script>
                                                                                                                                        <script type="text/javascript">
                                                                                                                                            $('#product_thumbnails > li > a').click(function() {
                                                                                                                                                var img_id = this.id.slice(0, -5);
                                                                                                                                                $('#' + img_id).css('display', 'table-cell');
                                                                                                                                            });
                                                                                                                                            var optionsOwl_product = {
                                                                                                                                                navigation: false,
                                                                                                                                                slideSpeed: 300,
                                                                                                                                                paginationSpeed: 200,
                                                                                                                                                singleItem: true
                                                                                                                                            };

                                                                                                                                            if ($(window).width() < 630)
                                                                                                                                            {

                                                                                                                                                jQuery_1_10_2(".mobile-slider").owlCarousel(optionsOwl_product);
                                                                                                                                                // Custom Navigation Events
                                                                                                                                                jQuery_1_10_2(".mobile-slider-cntnr .jcarousel-next").click(function() {
                                                                                                                                                    var owl = jQuery_1_10_2(this).parent().find(".mobile-slider");
                                                                                                                                                    owl.trigger('owl.next');
                                                                                                                                                });
                                                                                                                                                jQuery_1_10_2(".mobile-slider-cntnr .jcarousel-prev").click(function() {
                                                                                                                                                    var owl = jQuery_1_10_2(this).parent().find(".mobile-slider");
                                                                                                                                                    owl.trigger('owl.prev');
                                                                                                                                                });

                                                                                                                                                $(".product-main-info .ml_pageheaderCateogry").click(function() {

                                                                                                                                                    if ($(this).hasClass("active"))
                                                                                                                                                    {
                                                                                                                                                        $(".mobile_cntnt_blks").slideUp("fast");
                                                                                                                                                        $(".product-main-info .ml_pageheaderCateogry").removeClass("active");
                                                                                                                                                    }
                                                                                                                                                    else
                                                                                                                                                    {
                                                                                                                                                        $(".mobile_cntnt_blks").slideUp("fast");
                                                                                                                                                        $(".product-main-info .ml_pageheaderCateogry").removeClass("active");
                                                                                                                                                        $(this).addClass("active");
                                                                                                                                                        $(this).next(".mobile_cntnt_blks").slideDown("fast");
                                                                                                                                                    }


                                                                                                                                                });
                                                                                                                                                $(".ml_pageheaderCateogry_discussion").click();
                                                                                                                                            }
                                                                                                                                        </script>
                                                                                                                                    {/literal}
                                                                                                                                    {literal}
                                                                                                                                        <script type="text/javascript">

                                                                                                                                            var h = $('.hour').html();
                                                                                                                                            var m = $('.minute').html();
                                                                                                                                            var s = $('.second').html();

                                                                                                                                            if (h == '00' && m == '00' && s == '00')
                                                                                                                                            {
                                                                                                                                                $(".special_offer_link").html();
                                                                                                                                            }

                                                                                                                                        </script>

                                                                                                                                    {/literal}
                                                                                                                                    {if $config.express_checkout}
                                                                                                                                        {literal}
                                                                                                                                            <script type="text/javascript">
                                                                                                                                                var product_id = {/literal}{$product.product_id}{literal};
                                                                                                                                                var function_name = 'fn_form_post_product_form_' + product_id;
                                                                                                                                                function setFunc(name) {
                                                                                                                                                    window[name] = function(result) {
                                                                                                                                                        $('.notification-x').css('display', 'none');
                                                                                                                                                        var ex = JSON.stringify(result);

                                                                                                                                                        var z = $.parseJSON(ex);

                                                                                                                                                        var y = z.notifications;

                                                                                                                                                        var u;
                                                                                                                                                        $.each(y, function(index) {
                                                                                                                                                            u = y[index];
                                                                                                                                                        });

                                                                                                                                                        var return_url;
                                                                                                                                                        var ext_msg = u.ext_msg;
                                                                                                                                                        var url = ext_msg;
                                                                                                                                                        var type = u.type;

                                                                                                                                                        if (url != '' && type == 'X' && url != null)
                                                                                                                                                        {

                                                                                                                                                            return_url = 'index.php?dispatch=' + url;
                                                                                                                                                            $(location).attr('href', return_url);
                                                                                                                                                        }

                                                                                                                                                    }
                                                                                                                                                }
                                                                                                                                                setFunc(function_name);

                                                                                                                                            </script>
                                                                                                                                        {/literal}
                                                                                                                                    {/if}
       

                                                                                                                                    {literal}
                                                                                                                                        <script type="text/javascript">
                                                                                                                                            $("body").append("<div class='mob_fullimg_overlay'></div>");
                                                                                                                                            $("body").append("<a class='mob_close_ovrlay'><span> × Close </span></a>");
                                                                                                                                            $(".mobile-slider-cntnr .mobile-slider .owl-item a img").click(function() {
                                                                                                                                                if ($(".mobile-slider-cntnr").hasClass("mob_overlay")) {
                                                                                                                                                    var src = $(this).parent().attr('href');
                                                                                                                                                    $(".mob_fullimg_overlay").append("<img src='" + src + "'/>");
                                                                                                                                                    $(".mob_fullimg_overlay").show();
                                                                                                                                                    $(".helper-container ").hide();
                                                                                                                                                } else {
                                                                                                                                                    $(".mobile-slider-cntnr").addClass("mob_overlay");
                                                                                                                                                    $(".mob_close_ovrlay").show();
                                                                                                                                                }
                                                                                                                                                return false;
                                                                                                                                            })
                                                                                                                                            $(".mob_close_ovrlay").click(function() {
                                                                                                                                                $(".mobile-slider-cntnr").removeClass("mob_overlay");
                                                                                                                                                $(".mob_fullimg_overlay img").remove();
                                                                                                                                                $(".mob_fullimg_overlay").hide();
                                                                                                                                                $(".mob_close_ovrlay").hide();
                                                                                                                                                $(".helper-container ").show();
                                                                                                                                            });

                                                                                                                                            $(".mob_fullimg_overlay").click(function() {
                                                                                                                                                $(".mob_fullimg_overlay img").remove();
                                                                                                                                                $(".mob_fullimg_overlay").hide();
                                                                                                                                                $(".helper-container ").show();
                                                                                                                                            });
                                                                                                                                            function  check_pin(product_id,e)
                                                                                                                                            {


                                                                                                                                                var pin = document.getElementById("pincode" + product_id).value;
                                                                                                                                               // var key = window.event ? e.keyCode : e.which;
                                                                                                                                               if(pin.length==6)
                                                                                                                                               { $.ajax({
                                                                                                                                                    url: 'nss.php', //This is the current doc
                                                                                                                                                    type: "GET",
                                                                                                                                                    data: {product_ids: product_id, pincode_no: pin},
                                                                                                                                                    dataType: 'text json',
                                                                                                                                                    success: function(data) {

                                                                                                                                                        if (data.pin_result == '4' || data.pin_result == '1')
                                                                                                                                                        {
                                                                                                                                                            $("#pincode").html("<span style='font-weight: lighter;'>Estimated delivery between <b>" + data.fdate + "</b> and <b>" + data.sdate+"</b><span>");
                                                                                                                                                            $('#cod_payment').html('{/literal}{$lang.both_cod_prepaid_quick_look}{literal}');
                                                                                                                                                            $('#cod_payment').show();

                                                                                                                                                        }
                                                                                                                                                        else if (data.pin_result == '3')
                                                                                                                                                        {
                                                                                                                                                            $('#pincode').html("<span style='font-weight: lighter;'>Estimated delivery between <b>" + data.fdate + "</b> and <b>" + data.sdate+"</b><span>");
                                                                                                                                                            $('#cod_payment').html('{/literal}{$lang.cod_quick_look}{literal}');
                                                                                                                                                            $('#cod_payment').show();
                                                                                                                                                        }
                                                                                                                                                        else
                                                                                                                                                        {
                                                                                                                                                            $("#pincode").html('{/literal}{$lang.try_another_location}{literal}');
                                                                                                                                                            $('#cod_payment').hide();
                                                                                                                                                        }

                                                                                                                                                    }
                                                                                                                                                });
                                                                                                                                                var sub_domain =  {/literal}'{$config.cookie_domain}'{literal};
                                                                                                                                                SetCookie('pincode',pin,365,sub_domain);
                                                                                                                                               }
                                                                                                                                            }
                                                                                                                                            $("#ajaxified_wishlist").click(function(){$(".clk_view_prd_blk").hide();})



                                                                                                                                        </script>
                                                                                                                                            <script>
                                                                                                                                                function ajaxifiedWishlist(product_id)
                                                                                                                                                 {
                                                                                                                                                     /* Showing ajax loading */
                                                                                                                                                    $("#ajax_loading_box").show();
                                                                                                                                                     /* ajax request */
                                                                                                                                                    $.ajax({
                                                                                                                                                        url:'index.php?dispatch=wishlist.add',
                                                                                                                                                        type:"POST",
                                                                                                                                                        data:{product_id:product_id,wishlist_ajaxified_pages:1},
                                                                                                                                                        success:function(responseData)
                                                                                                                                                        {
                                                                                                                                                            /* Parsing Json string */
                                                                                                                                                            data = JSON.parse(responseData);
                                                                                                                                                            var div_data = '<div class="product-notification-container cm-auto-hide" style="top: 134.5px;"><div class="popupbox-closer"><img src="/skins/basic/customer/images/icons/close_popupbox.png" class="cm-notification-close" title="Close" alt="Close"></div><div class="product-notification"><h1> ' +data.title+'</h1>'+ data.msg + '</div></div>';
                                                                                                                                                            /* Hiding ajax loading */
                                                                                                                                                            $("#ajax_loading_box").hide();
                                                                                                                                                            /* Appending div_data in popup */
                                                                                                                                                            $(".central-content").append(div_data);
                                                                                                                                                            /* Closing popup on cliking close image */
                                                                                                                                                            $(".popupbox-closer img").click(function(){
                                                                                                                                                                $(".product-notification-container").remove();
                                                                                                                                                            });
                                                                                                                                                            /* closing and opening new div if wishlist link is clicked */
                                                                                                                                                            $(".ajaxified_wishlist").click(function(){
                                                                                                                                                               $(".product-notification-container").remove();
                                                                                                                                                           });
                                                                                                                                                        }
                                                                                                                                                      });
                                                                                                                                                     }
/* Code for wishlist link on ajaxified pages */

                                                                                                                                                </script>
</div>

                                                                                                                                                {/literal}
                                                                                                                                     


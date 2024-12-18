{** block-description:you_may_like_hr_block **}
<div id ="you_may_like" style="width:998px;">
<div class="ml_pageheaderCateogry ml_pageheaderCateogry_reco">
    <h1 class="ml_pageheaderCateogry_heading" style="width:998px;">
        <a id="desc">{$lang.youmaylike_tm}</a>
    </h1>
</div>
<div class="bs_cntnr" id="bs_cntnr_you_like">
    <div class="bs_tm_left_arrow" id="bs_left_you_like"></div>
        <div class="bs_cntnr_ovr_blk" id="you_like_hr">&nbsp;</div>
     <div class="bs_tm_right_arrow" id="bs_right_you_like"></div>
</div>  
</div>  
{literal}
<script>
    function addCarousel_reco(){
        var sliderWidth = $("#you_like_hr .bs_cntnr_item").outerWidth();
        var slider = $('#bs_cntnr_you_like');
        var container = $('#you_like_hr');
        container.wrap( "<div class='carousel-wrap-small'></div>" );
        var sliderCount = $('.bs_cntnr_prd_name', slider).length;
        var delta = sliderCount * sliderWidth - slider.width();
        container.width((sliderCount * sliderWidth + 10));
        delta = (-1)*delta+13;
        var initialValue = container.position().left;

        $('#bs_left_you_like').click(function () {
            if(container.position().left< initialValue)//alert(container.position().left);
                container.animate({left: '+='+sliderWidth}, 100);
        });

        $('#bs_right_you_like').click(function () {//alert(container.position().left);
            if(container.position().left> delta+initialValue)
                container.animate({left: '-='+sliderWidth}, 100);
        });
    }
    $(document).ready(function(){
                {/literal}
                
                {foreach from=$order_info.items item="product" name="productlisting"}
                {if $product.product_id}{literal}
                var prod_id ={/literal} {$product.product_id};{else}{literal}
                var prod_id =" ";{/literal}{/if}
                 {/foreach}
               {literal}
        var limit = {/literal}{$config.TM_you_like}{literal};
        var path = {/literal}"{$config.website_protocol}"{literal};
        var url = path+"api.targetingmantra.com/TMWidgets?w=ppr&mid=130915&es="+prod_id+"&limit="+limit +"&json=true&callback=?";
        jQuery.getJSON(url,function(data){


            if(!data.ppr ||!data.ppr.recommendedItems || data.ppr.recommendedItems.length == 0){
                $(".ml_pageheaderCateogry_reco").hide();
                $("#bs_cntnr_you_like").hide();
            }
            else{
                var widgetTitle = data.ppr.WidgetTitle;
                $(".ml_pageheaderCateogry_reco").show();
                $("#bs_cntnr_you_like").show();



                data.ppr.recommendedItems.forEach(function(obj, index){
                    var prod_num=index+1;
                    var mrp = (function(){
                        if(parseFloat(obj.itemMRP) > parseFloat(obj.itemPrice)){
                            return parseFloat(obj.itemMRP).formatMoney(0,'.',',');
                        }
                        else{
                            return parseFloat(obj.itemPrice).formatMoney(0,'.',',');
                        }
                    })();
                    var img = document.createElement("img");
                    img.src = obj.itemImage;
                    img.setAttribute("width","160");
                    img = img.outerHTML;
                    var title = function(){if(obj.itemTitle.length >50){return obj.itemTitle.substr(0,46) + "...";}else{return obj.itemTitle;}};
                    var item = "<div class='bs_cntnr_item'><a onclick=\"_gaq.push(['_trackEvent', 'you_may_like_thank you', 'Click', 'Product_"+prod_num+"']);\" class='bs_cntnr_prd_name' href='"+ obj.itemURL +"'><div class='bs_cntnr_img'>"+img+"</div>" + "<span class='bs_prd_title_blk'>"+title()+"</span></a>"
                            +"<div id='you_like_hr_stars_"+index+"'></div><div class='bs_prc_out_blk'><span class='tm_mrp' id='you_like_hr_price_"+index+"'>MRP: Rs."+mrp+"</span><div class='bs_main_price'>Rs. " + parseFloat(obj.itemPrice).formatMoney(0,'.',',') +"</div></div></div></div>";

                    $("#you_like_hr").append(item);
                    if(parseInt(obj.itemRating) > 0){
                        $("#you_like_hr_stars_" + index).append("<span class='stars' id='you_like_hr_"+index+"'>"+obj.itemRating+"</span>");
                    }
                    $("#you_like_hr_" + index).makeStars();
                    if(parseFloat(obj.itemMRP) == parseFloat(obj.itemPrice)){
                        $("#you_like_hr_price_"+index).hide();
                    }
                });
                addCarousel_reco();
            }

        });
    });
</script>
{/literal}

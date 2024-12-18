{** block-description:best_seller_hr_block **}

<div class="ml_pageheaderCateogry ml_pageheaderCateogry_best">
    <h1 class="ml_pageheaderCateogry_heading" style="width:810px;">
        <a id="desc">{$lang.best_sellers_tm}</a>
            
            
    </h1>

  </div>  
<div class="bs_cntnr" id="bs_cntnr_best">
    <div class="bs_tm_left_arrow" id="bs_left_best"></div>
        <div class="bs_cntnr_ovr_blk" id="best_hr">
    </div>
     <div class="bs_tm_right_arrow" id="bs_right_best"></div>
</div>

{literal}
<script>
    function addCarousel_best(){
        var sliderWidth = $("#best_hr .bs_cntnr_item").outerWidth();
        var slider = $('#bs_cntnr_best');
        var container = $('#best_hr');
        container.wrap( "<div class='carousel-wrap-small'></div>" );
        var sliderCount = $('.bs_cntnr_prd_name', slider).length;
        var delta = sliderCount * sliderWidth - slider.width();
        container.width((sliderCount * sliderWidth + 10));
        delta = (-1)*delta;
        var initialValue = container.position().left;

        $('#bs_left_best').click(function () {
            if(container.position().left< initialValue)
                container.animate({left: '+='+sliderWidth}, 100);
        });

        $('#bs_right_best').click(function () {
            if(container.position().left> delta+initialValue)
                container.animate({left: '-='+sliderWidth}, 100);
        });
    }
    $(document).ready(function(){

                {/literal}{if $product.product_id}{literal}var prod_id ={/literal} {$product.product_id};{else}{literal}var prod_id =" ";{/literal}{/if}{literal}
        var limit = {/literal}{$config.TM_limit}{literal};
        var cat_id = {/literal}{$smarty.request.category_id}{literal};
        var url = "http://api.targetingmantra.com/TMWidgets?w=bs&mid=130915&pid="+prod_id+"&limit="+limit +"&json=true&catid="+cat_id+"&callback=?";
        jQuery.getJSON(url,function(data){

                if(data.bs && data.bs.bestSellerItems && data.bs.bestSellerItems.length != 0){
			
						var count=data.bs.bestSellerItems.length;
				}

            if(!data.bs || !data.bs.bestSellerItems || data.bs.bestSellerItems.length == 0 || count < limit){
                $(".ml_pageheaderCateogry_best").hide();
                $("#bs_cntnr_best").hide();
                $("#bs_left_best").hide();
                $("#bs_right_best").hide();
                
            }
            else{
                var widgetTitle = data.bs.widgetTitle;
                $(".ml_pageheaderCateogry_best").show();
                $("#bs_cntnr_best").show();



                data.bs.bestSellerItems.forEach(function(obj, index){
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
                    var item = "<div class='bs_cntnr_item'><a onclick=\"_gaq.push(['_trackEvent', 'Category_best_seller', 'Click', 'Product_"+prod_num+"']);\" class='bs_cntnr_prd_name' href='"+ obj.itemURL +"'><div class='bs_cntnr_img'>"+img+"</div>" + "<span class='bs_prd_title_blk'>"+title()+"</span></a>"
                            +"<div id='best_hr_stars_"+index+"'></div><div class='bs_prc_out_blk'><span class='tm_mrp' id='best_hr_price_"+index+"'>MRP: Rs."+mrp+"</span><div class='bs_cntnr_prc_blk'><div class='bs_main_price'>Rs. " + parseFloat(obj.itemPrice).formatMoney(0,'.',',') +"</div></div></div></div>";

                    $("#best_hr").append(item);
                    if(parseInt(obj.itemRating) > 0){
                        $("#best_hr_stars_" + index).append("<span class='stars' id='best_hr_"+index+"'>"+obj.itemRating+"</span>");
                    }
                    $("#best_hr_" + index).makeStars();
                    if(parseFloat(obj.itemMRP) == parseFloat(obj.itemPrice)){
                        $("#best_hr_price_"+index).hide();
                    }
                });
                addCarousel_best();
            }

        });
    });
</script>
{/literal}

{** block-description:recommendation_hr_block **}

<div class="ml_pageheaderCateogry ml_pageheaderCateogry_reco">
    <h1 class="ml_pageheaderCateogry_heading" style="width:810px;">
        <a id="desc">{$lang.recommendations}</a>
    </h1>
</div>
<div class="bs_cntnr" id="bs_cntnr_reco">
    <div class="bs_tm_left_arrow" id="bs_left_reco"></div>
        <div class="bs_cntnr_ovr_blk" id="reco_hr">&nbsp;</div>
     <div class="bs_tm_right_arrow" id="bs_right_reco"></div>
</div>    
{literal}
<script>
    function addCarousel_reco(){
        var sliderWidth = $("#reco_hr .bs_cntnr_item").outerWidth();
        var slider = $('#bs_cntnr_reco');
        var container = $('#reco_hr');
        container.wrap( "<div class='carousel-wrap-small'></div>" );
        var sliderCount = $('.bs_cntnr_prd_name', slider).length;
        var delta = sliderCount * sliderWidth - slider.width();
        container.width((sliderCount * sliderWidth + 10));
        delta = (-1)*delta;
        var initialValue = container.position().left;

        $('#bs_left_reco').click(function () {
            if(container.position().left< initialValue)
                container.animate({left: '+='+sliderWidth}, 100);
        });

        $('#bs_right_reco').click(function () {
            if(container.position().left> delta+initialValue)
                container.animate({left: '-='+sliderWidth}, 100);
        });
    }
    $(document).ready(function(){

                {/literal}{if $product.product_id}{literal}var prod_id ={/literal} {$product.product_id};{else}{literal}var prod_id =" ";{/literal}{/if}{literal}
        var limit = {/literal}{$config.TM_limit}{literal};
        var url = "http://api.targetingmantra.com/TMWidgets?w=rhf&mid=130915&pid="+prod_id+"&limit="+limit +"&json=true&callback=?";
        jQuery.getJSON(url,function(data){


            if(!data.rhf || !data.rhf.recommendations || !data.rhf.recommendations.recommendedItems || data.rhf.recommendations.recommendedItems.length == 0){
                $(".ml_pageheaderCateogry_reco").hide();
                $("#bs_cntnr_reco").hide();
            }
            else{
                var widgetTitle = data.rhf.recommendations.subWidgetTitle;
                $(".ml_pageheaderCateogry_reco").show();
                $("#bs_cntnr_reco").show();



                data.rhf.recommendations.recommendedItems.forEach(function(obj, index){
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
                    var item = "<div class='bs_cntnr_item'><a onclick=\"_gaq.push(['_trackEvent', 'Category_recommend', 'Click', 'Product_"+prod_num+"']);\" class='bs_cntnr_prd_name' href='"+ obj.itemURL +"'><div class='bs_cntnr_img'>"+img+"</div>" + "<span class='bs_prd_title_blk'>"+title()+"</span></a>"
                            +"<div id='reco_hr_stars_"+index+"'></div><div class='bs_prc_out_blk'><span class='tm_mrp' id='reco_hr_price_"+index+"'>MRP: Rs."+mrp+"</span><div class='bs_main_price'>Rs. " + parseFloat(obj.itemPrice).formatMoney(0,'.',',') +"</div></div></div></div>";

                    $("#reco_hr").append(item);
                    if(parseInt(obj.itemRating) > 0){
                        $("#reco_hr_stars_" + index).append("<span class='stars' id='reco_hr_"+index+"'>"+obj.itemRating+"</span>");
                    }
                    $("#reco_hr_" + index).makeStars();
                    if(parseFloat(obj.itemMRP) == parseFloat(obj.itemPrice)){
                        $("#reco_hr_price_"+index).hide();
                    }
                });
                addCarousel_reco();
            }

        });
    });
</script>
{/literal}

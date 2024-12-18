{** block-description:same_brand_block_vr **}

<div class="bs_cntnr_vr" id="bs_cntnr_vr_csims">
    <div class="bs_cntnr_vr_head">
        <a id="desc">{$lang.more_products_from_same_brand}</a>
    </div>
    <div class="bs_cntnr_ovr_blk_vr" id="csims">
    </div>
</div>


{literal}
<script>

    $(document).ready(function(){

        var prod_id = {/literal}{$product.product_id}{literal};
        var limit = {/literal}{$config.TM_limit_vertical}{literal};
        var url = "http://api.targetingmantra.com/TMWidgets?w=csims&mid=130915&pid="+prod_id+"&limit="+limit +"&json=true&callback=?";
        jQuery.getJSON(url,function(data){


            if(!data.csims || !data.csims.similarItems || data.csims.similarItems.length == 0){
                $("#bs_cntnr_vr_csims").hide();
            }
            else{
                //var widgetTitle = data.vsims.widgetTitle;
                $("#bs_cntnr_vr_csims").show();

                data.csims.similarItems.forEach(function(obj, index){
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
                    img.setAttribute("width","48");
                    img = img.outerHTML;
                    var title = function(){if(obj.itemTitle.length >50){return obj.itemTitle.substr(0,46) + "...";}else{return obj.itemTitle;}};
                    var item = "<div class='bs_cntnr_item_vr'><div class='bs_cntnr_img_vr'>"+img +"</div><div class='prd_info_left_blk'><a onclick=\"_gaq.push(['_trackEvent', 'Same_brand_product_also', 'Click', 'Product_"+prod_num+"']);\" class='bs_cntnr_prd_name_vr' href='"+ obj.itemURL +"'>" + title() +"</a>"
                            +"<div id='csims_stars_"+index+"'></div><span class='list-price' style='font-size: 12px;'>Price: </span><span class='list-price' style='font-size:11px' id='csims_price_"+index+"'><strike>Rs."+mrp+"</strike></span><div class='bs_cntnr_prc_blk_vr'><div class='bs_main_price_vr'>Rs. " + parseFloat(obj.itemPrice).formatMoney(0,'.',',') +"</div></div></div></div>";
                    $("#csims").append(item);
                    if(parseInt(obj.itemRating) > 0){
                        $("#csims_stars_" + index).append("<span class='stars' id='csims_"+index+"'>"+obj.itemRating+"</span>");
                    }
                    $("#csims_" + index).makeStars();
                    if(parseFloat(obj.itemMRP) == parseFloat(obj.itemPrice)){
                        $("#csims_price_"+index).hide();
                    }
                });
            }

        });
    });
</script>
{/literal}
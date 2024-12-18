{** block-description:same_brand_block_vr_single_Api **}
<div id="csims_org" style="display:none">
<div class="bs_cntnr_vr" id="bs_cntnr_vr_csims">
    <div class="bs_cntnr_vr_head">
        <a id="desc">{$lang.more_products_from_same_brand}</a>
    </div>
    <div class="bs_cntnr_ovr_blk_vr" id="csims">
    </div>
</div>
</div>

{literal}
<script>
function csims(){


                var data = JSON.parse(tm_array);
                var limit = {/literal}{$config.TM_limit_vertical}{literal};

            if(!data.csims || !data.csims.similarItems || data.csims.similarItems.length == 0){
                
                $("#csims_org").hide();
            }
            else{

                 $("#csims_org").show();
                
                data.csims.similarItems.forEach(function(obj, index){

                    if(index<limit){

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
                  }
                });
            }

        }

</script>
{/literal}

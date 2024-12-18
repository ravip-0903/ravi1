{** block-description:customer_viewed_block_vr_single_Api **}
<div id="vsims_org" style="display:none;">
<div class="bs_cntnr_vr" id="bs_cntnr_vr_vsims">
    <div class="bs_cntnr_vr_head">
        <a id="desc">{$lang.customer_who_viewed_also_viewed}</a>
    </div>
    <div class="bs_cntnr_ovr_blk_vr" id="vsims">
    </div>
</div>
</div>

{literal}
<script>

    function vsims(){

        
                var data = JSON.parse(tm_array);
                var limit = {/literal}{$config.TM_limit_vertical}{literal};

                
            if(!data.vsims || !data.vsims.similarItems || data.vsims.similarItems.length == 0){
                
                $("#vsims_org").hide();
            }
            else{
                
                  $("#vsims_org").show();

                data.vsims.similarItems.forEach(function(obj, index){


                    var prod_num=index+1;
                    if(index<limit){

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
                    var item = "<div class='bs_cntnr_item_vr'><div class='bs_cntnr_img_vr'>"+img +"</div><div class='prd_info_left_blk'><a onclick=\"_gaq.push(['_trackEvent', 'Product_customer_viewed_also', 'Click', 'Product_"+prod_num+"']);\" class='bs_cntnr_prd_name_vr' href='"+ obj.itemURL +"'>" + title() +"</a>"
                            +"<div id='vsims_stars_"+index+"'></div><span class='list-price' style='font-size: 12px;'>Price: </span><span class='list-price' style='font-size:11px' id='vsims_price_"+index+"'><strike>Rs."+mrp+"</strike></span><div class='bs_cntnr_prc_blk_vr'><div class='bs_main_price_vr'>Rs. " + parseFloat(obj.itemPrice).formatMoney(0,'.',',') +"</div></div></div></div>";
                    $("#vsims").append(item);
                    if(parseInt(obj.itemRating) > 0){
                        $("#vsims_stars_" + index).append("<span class='stars' id='vsims_"+index+"'>"+obj.itemRating+"</span>");
                    }
                    $("#vsims_" + index).makeStars();
                    if(parseFloat(obj.itemMRP) == parseFloat(obj.itemPrice)){
                        $("#vsims_price_"+index).hide();
                    }
                  }
                });
              
            }

        }
    
</script>
{/literal}

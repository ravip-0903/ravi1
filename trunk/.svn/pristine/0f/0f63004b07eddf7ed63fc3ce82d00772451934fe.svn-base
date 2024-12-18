{** block-description:recently_recommendation_hr_block_product_single_Api **}
<div id="club_block_prod" style="display:none;">
<div style="width:225px; float:left;clear:both;">
<div class="ml_pageheaderCateogry ml_pageheaderCateogry_rvi">
    <h1 class="ml_pageheaderCateogry_heading nl_recoman_blk">
        {$lang.recently_viewed_product_club_tm}
    </h1>
</div>
<div class="bs_cntnr_vr" id="bs_cntnr_vr_vsims_club" style="height:270px;">
        <div id="scrollbar1" class="scrl_cat_pg">
        <div class="scrollbar"><div class="track" style="background:none;"><div class="thumb"><div class="end"></div></div></div></div>
        <div class="viewport">
             <div class="overview">
                <div class="bs_cntnr_ovr_blk_vr" style="float:left; width:100%;" id="rvi_club"></div>
            </div>
        </div>
    </div>
</div>
</div>
{literal}
<script>
    function recent_recommend(){


                    var data = JSON.parse(tm_array);

                    var limit = {/literal}{$config.TM_limit}{literal};


//Recently code starts here

           if(!data.rhf || !data.rhf.recentHistory || !data.rhf.recentHistory.viewedItems || data.rhf.recentHistory.viewedItems.length == 0 || !data.rhf.recommendations || !data.rhf.recommendations.recommendedItems || data.rhf.recommendations.recommendedItems.length == 0){
                $("#club_block_prod").hide();   
                $(".ml_pageheaderCateogry_rvi").hide();
                $("#bs_cntnr_vr_vsims_club").hide();
            }
            else{
                var widgetTitle = data.rhf.recentHistory.subWidgetTitle;
                $("#club_block_prod").show(); 
                $(".ml_pageheaderCateogry_rvi").show();
                $("#bs_cntnr_vr_vsims_club").show();
                
                data.rhf.recentHistory.viewedItems.forEach(function(obj, index){

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
                    var item = "<div class='bs_cntnr_item_vr' style='margin:0;'><div class='bs_cntnr_img_vr'>"+ img+"</div><div class='prd_info_left_blk'><a onclick=\"_gaq.push(['_trackEvent', 'Product_recently_club', 'Click', 'Product_"+prod_num+"']);\" class='bs_cntnr_prd_name_vr' href='"+ obj.itemURL +"'>" + title() +"</a>"
                            +"<div id='rvi_club_stars_"+index+"'></div><span class='list-price' style='font-size: 11px;'>Price: </span><span class='list-price' style='font-size:11px' id='rvi_club_price_"+index+"' ><strike>Rs."+mrp+"</strike></span><div class='bs_cntnr_prc_blk_vr'><div class='bs_main_price_vr'>Rs. " + parseFloat(obj.itemPrice).formatMoney(0,'.',',') +"</div></div></div></div>";
                    $("#rvi_club").append(item);
                    if(parseInt(obj.itemRating) > 0){
                        $("#rvi_club_stars_" + index).append("<span class='stars' id='rvi_club_"+index+"'>"+obj.itemRating+"</span>");
                    }
                    $("#rvi_club_" + index).makeStars();
                    if(parseFloat(obj.itemMRP) == parseFloat(obj.itemPrice)){
                        $("#rvi_club_price_"+index).hide();
                    }
                  }
                });
            }
$('#scrollbar1').tinyscrollbar();

//Recommendation code starts here

if(!data.rhf || !data.rhf.recommendations || !data.rhf.recommendations.recommendedItems || data.rhf.recommendations.recommendedItems.length == 0 || !data.rhf || !data.rhf.recentHistory || !data.rhf.recentHistory.viewedItems || data.rhf.recentHistory.viewedItems.length == 0 ){
                $("#club_block_prod").hide();       
                $(".ml_pageheaderCateogry_reco").hide();
                $("#bs_cntnr_reco_club").hide();
            }
            else{
                var widgetTitle = data.rhf.recommendations.subWidgetTitle;
                $(".ml_pageheaderCateogry_reco").show();
                $("#bs_cntnr_reco_club").show();



                data.rhf.recommendations.recommendedItems.forEach(function(obj, index){
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
                    img.setAttribute("width","160");
                    img = img.outerHTML;
                    var title = function(){if(obj.itemTitle.length >50){return obj.itemTitle.substr(0,46) + "...";}else{return obj.itemTitle;}};
                    var item = "<div class='bs_cntnr_item' style='width:215px;'><a onclick=\"_gaq.push(['_trackEvent', 'Product_recommend_club', 'Click', 'Product_"+prod_num+"']);\" class='bs_cntnr_prd_name' href='"+ obj.itemURL +"'><div class='bs_cntnr_img prd_hp_bs_cntnr'>"+img+"</div>" + "<span class='bs_prd_title_blk'>"+title()+"</span></a>"
                            +"<div id='reco_hr_club_stars_"+index+"'></div><div class='bs_prc_out_blk hp_prd_blk_prc_cntr'><span class='tm_mrp' id='reco_hr_club_price_"+index+"' >MRP: Rs."+mrp+"</span><div class='bs_cntnr_prc_blk'><div class='bs_main_price'>Rs. " + parseFloat(obj.itemPrice).formatMoney(0,'.',',') +"</div></div></div></div>";

                    $("#reco_hr_club").append(item);
                    if(parseInt(obj.itemRating) > 0){
                        $("#reco_hr_club_stars_" + index).append("<span class='stars' id='reco_hr_club_"+index+"'>"+obj.itemRating+"</span>");
                    }
                    $("#reco_hr_club_" + index).makeStars();
                    if(parseFloat(obj.itemMRP) == parseFloat(obj.itemPrice)){
                        $("#reco_hr_club_price_"+index).hide();
                    }
                 }
                });
                addCarousel_reco_club();
            }
        }
    
</script>
{/literal}
    
{* --------------------------------------------------------------------------------*}    
<div style="width: 488px; float: left; margin-left: 35px;">
<div class="ml_pageheaderCateogry ml_pageheaderCateogry_reco">
    <h1 class="ml_pageheaderCateogry_heading nl_recoman_blk" style="width:810px;">
        {$lang.recommendations_product_club_tm}
    </h1>
</div>
<div class="bs_cntnr" id="bs_cntnr_reco_club">
    <div class="bs_tm_left_arrow" id="bs_left_reco_club"></div>
        <div class="bs_cntnr_ovr_blk" id="reco_hr_club">&nbsp;</div>
     <div class="bs_tm_right_arrow" id="bs_right_reco_club"></div>
</div>    
</div>
        </div>
{literal}
<script>
    function addCarousel_reco_club(){
        var sliderWidth = $("#reco_hr_club .bs_cntnr_item").outerWidth();
        var slider = $('#bs_cntnr_reco_club');
        var container = $('#reco_hr_club');
        container.wrap( "<div class='carousel-wrap-small'></div>" );
        var sliderCount = $('.bs_cntnr_prd_name', slider).length;
        var delta = sliderCount * sliderWidth - slider.width();
        container.width((sliderCount * sliderWidth + 10));
        delta = (-1)*delta;
        var initialValue = container.position().left;

        $('#bs_left_reco_club').click(function () {
            if(container.position().left< initialValue)
                container.animate({left: '+='+sliderWidth}, 100);
        });

        $('#bs_right_reco_club').click(function () {
            if(container.position().left> delta+initialValue)
                container.animate({left: '-='+sliderWidth}, 100);
        });
    }           
</script>
<style>
#scrollbar1{width:229px!important; float:left; width:97%; height:250px; }
#scrollbar1 .scrollbar{margin-right:2px;}
</style>
{/literal}

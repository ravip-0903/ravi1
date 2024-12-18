{** block-description:recently_recommendation_hr_block_homepage **}
{if !$config.isResponsive}
<div id="club_block_hp">
<div style="width:253px; float:left;clear:both;">
<div class="ml_pageheaderCateogry ml_pageheaderCateogry_rvi">
    <h1 class="ml_pageheaderCateogry_heading nl_recoman_blk">
        {$lang.recently_viewed_homepage_club_tm}
    </h1>
</div>
<div class="bs_cntnr_vr" id="bs_cntnr_vr_vsims_club" style="height:270px; width:251px;">
        <div id="scrollbar1" class="scrl_cat_pg scrl_cat_pg_hp" style="width:250px;">
        <div class="scrollbar"><div class="track" style="background:none;"><div class="thumb" style="400px"><div class="end"></div></div></div></div>
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

    $(document).ready(function(){

                {/literal}{if $product.product_id}{literal}var prod_id ={/literal} {$product.product_id};{else}{literal}var prod_id =" ";{/literal}{/if}{literal}
       var limit = {/literal}{$config.TM_limit}{literal};
      // var limit=0;
        var url = "http://api.targetingmantra.com/TMWidgets?w=rhf&mid=130915&pid="+prod_id+"&limit="+limit +"&json=true&callback=?";
        jQuery.getJSON(url,function(data){

//Recently Block starts here
            if(!data.rhf || !data.rhf.recentHistory || !data.rhf.recentHistory.viewedItems || data.rhf.recentHistory.viewedItems.length == 0){
                $("#club_block_hp").hide();
                $(".ml_pageheaderCateogry_rvi").hide();
                $("#bs_cntnr_vr_vsims_club").hide();
            }
            else{

                var widgetTitle = data.rhf.recentHistory.subWidgetTitle;
                $(".ml_pageheaderCateogry_rvi").show();
                $("#bs_cntnr_vr_vsims_club").show();
                
                data.rhf.recentHistory.viewedItems.forEach(function(obj, index){
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
                    var item = "<div class='bs_cntnr_item_vr' style='margin:0;'><div class='bs_cntnr_img_vr'>"+ img+"</div><div class='prd_info_left_blk' style='width:170px;'><a onclick=\"_gaq.push(['_trackEvent', 'Homepage_recently_club', 'Click', 'Product_"+prod_num+"']);\" class='bs_cntnr_prd_name_vr' href='"+ obj.itemURL +"'>" + title() +"</a>"
                            +"<div id='rvi_club_stars_"+index+"'></div><span class='list-price' style='font-size: 11px;'>Price: </span><span class='list-price' style='font-size:11px' id='rvi_club_price_"+index+"'><strike>Rs."+mrp+"</strike></span><div class='bs_cntnr_prc_blk_vr'><div class='bs_main_price_vr'>Rs. " + parseFloat(obj.itemPrice).formatMoney(0,'.',',') +"</div></div></div></div>";
                    $("#rvi_club").append(item);
                    if(parseInt(obj.itemRating) > 0){
                        $("#rvi_club_stars_" + index).append("<span class='stars' id='rvi_club_"+index+"'>"+obj.itemRating+"</span>");
                    }
                    $("#rvi_club_" + index).makeStars();
                    if(parseFloat(obj.itemMRP) == parseFloat(obj.itemPrice)){
                        $("#rvi_club_price_"+index).hide();
                    }
                });
            }
$('#scrollbar1').tinyscrollbar();

//Recomendation block starts here

            if(!data.rhf || !data.rhf.recommendations || !data.rhf.recommendations.recommendedItems || data.rhf.recommendations.recommendedItems.length == 0 ){
                $("#club_block_hp").hide();   
                $(".ml_pageheaderCateogry_reco").hide();
                $("#bs_cntnr_reco_club").hide();
  
            }
            else{
                var widgetTitle = data.rhf.recommendations.subWidgetTitle;
                $(".ml_pageheaderCateogry_reco").show();
                $("#bs_cntnr_reco_club").show();
    



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
                    var item = "<div class='bs_cntnr_item' style='width:162px; padding:13px 33px;'><a onclick=\"_gaq.push(['_trackEvent', 'Homepage_recommend_club', 'Click','Product_"+prod_num+"']);\" class='bs_cntnr_prd_name' href='"+ obj.itemURL +"'><div class='bs_cntnr_img'>"+img+"</div>" + "<span class='bs_prd_title_blk'>"+title()+"</span></a>"
                            +"<div id='reco_hr_club_stars_"+index+"'></div><div class='bs_prc_out_blk' style='left:74px;'><span class='tm_mrp' id='reco_hr_club_price_"+index+"' >MRP: Rs."+mrp+"</span><div class='bs_cntnr_prc_blk'><div class='bs_main_price'>Rs. " + parseFloat(obj.itemPrice).formatMoney(0,'.',',') +"</div></div></div></div>";

                    $("#reco_hr_club").append(item);
                    if(parseInt(obj.itemRating) > 0){
                        $("#reco_hr_club_stars_" + index).append("<span class='stars' id='reco_hr_club_"+index+"'>"+obj.itemRating+"</span>");
                    }
                    $("#reco_hr_club_" + index).makeStars();
                    if(parseFloat(obj.itemMRP) == parseFloat(obj.itemPrice)){
                        $("#reco_hr_club_price_"+index).hide();
                    }
                });
                addCarousel_reco_club();
            }

        });
    });
</script>
{/literal}
    
{* --------------------------------------------------------------------------------*}    
<div style="width: 710px; float: left; margin-left: 35px;">
<div class="ml_pageheaderCateogry ml_pageheaderCateogry_reco">
    <h1 class="ml_pageheaderCateogry_heading nl_recoman_blk" style="width:810px;">
        {$lang.recommendations_homepage_club_tm}
    </h1>
</div>
<div class="bs_cntnr" id="bs_cntnr_reco_club">
    <div class="bs_tm_left_hp_arrow_disabled" id="bs_left_reco_club" ></div>
        <div class="bs_cntnr_ovr_blk" id="reco_hr_club">&nbsp;</div>
     <div class="bs_tm_hp_right_arrow" id="bs_right_reco_club"></div>
</div>    
</div>
        </div>
{literal}
<script>
    function addCarousel_reco_club(){
        var sliderWidth = $("#reco_hr_club .bs_cntnr_item").outerWidth();
        var slider = $('#bs_cntnr_reco_club');
        var container = $('#reco_hr_club');
        container.wrap( "<div class='carousel-wrap-small'></div>" );//alert(sliderWidth);//width per slider 228
        var sliderCount = $('.bs_cntnr_prd_name', slider).length; //alert(sliderCount); //count of slider 6
        var delta = sliderCount * sliderWidth - slider.width(); //alert(delta);alert(slider.width()); //658
        container.width((sliderCount * sliderWidth + 10));
        delta = (-1)*delta;//alert(delta);//-658
        var initialValue = container.position().left;

      //  var limit = {/literal}{$config.TM_limit}{literal};
        $('#bs_left_reco_club').click(function () {
            if(container.position().left< initialValue)
               {
                container.animate({left: '+='+sliderWidth}, 100,function(){
              if(container.position().left == initialValue){
                   $('#bs_left_reco_club').addClass('bs_tm_left_hp_arrow_disabled');
                   $('#bs_left_reco_club').removeClass('bs_tm_left_hp_arrow');                    
               } 
               if(container.position().left < initialValue){
                   $('#bs_left_reco_club').removeClass('bs_tm_left_hp_arrow_disabled');
                   $('#bs_left_reco_club').addClass('bs_tm_left_hp_arrow');                    
                   $('#bs_right_reco_club').removeClass('bs_tm_hp_right_arrow_disabled');
                   $('#bs_right_reco_club').addClass('bs_tm_hp_right_arrow');
                }
                
                }); 
                }
        });
        
        $('#bs_right_reco_club').click(function () {
            if(container.position().left> delta+initialValue){   
                container.animate({left: '-='+sliderWidth}, 100,function(){ 
                if(container.position().left< delta + initialValue)
                {
                   $('#bs_right_reco_club').addClass('bs_tm_hp_right_arrow_disabled');
                   $('#bs_right_reco_club').removeClass('bs_tm_hp_right_arrow'); 
                }
                else{
                 if(container.position().left <= initialValue){
                   $('#bs_left_reco_club').removeClass('bs_tm_left_hp_arrow_disabled');
                   $('#bs_left_reco_club').addClass('bs_tm_left_hp_arrow');
                }
            else {
                   $('#bs_left_reco_club').removeClass('bs_tm_left_hp_arrow');
                   $('#bs_left_reco_club').addClass('bs_tm_left_hp_arrow_disabled');
                }
                //alert(click_next);  alert(click_count);
                }
                });
            }
        });
    }			
</script>
<style>
#scrollbar1{width:253px!important; float:left; width:97%; height:250px; }
#scrollbar1 .scrollbar{margin-right:2px;}
</style>
{/literal}

{else}
    {if $config.show_TM_on_mobile}
        <div id="club_block_hp">

            <div class="recommendations_mobile">
                <div class="ml_pageheaderCateogry ml_pageheaderCateogry_rvi_homepage_mobile">
                    <h1 class="ml_pageheaderCateogry_heading nl_recoman_blk">
                        {$lang.recommendations_homepage_club_tm}
                    </h1>
                </div>

                <div class="bs_cntnr_vr_mobile" id="mob_reco_blk">
                    <div class="bs_cntnr_ovr_blk_vr" style="float:left;" id="mob_reco_blk_club"></div>
                </div>

            </div>

            <div class="rcntly_viewed_mobile">
                <div class="ml_pageheaderCateogry ml_pageheaderCateogry_rvi_homepage_mobile">
                    <h1 class="ml_pageheaderCateogry_heading nl_recoman_blk">
                        {$lang.recently_viewed_homepage_club_tm}
                    </h1>
                </div>

                <div class="bs_cntnr_vr_mobile" id="bs_cntnr_vr_vsims_club">
                    <div class="bs_cntnr_ovr_blk_vr" style="float:left;" id="rvi_club"></div>
                </div>

            </div>

        </div>
    {literal}
        <script>

            $(document).ready(function() {

                        {/literal}{if $product.product_id}{literal}var prod_id ={/literal} {$product.product_id};{else}{literal}
                var prod_id = " ";{/literal}{/if}{literal}
                var limit = {/literal}{$config.TM_limit}{literal};
                // var limit=0;
                var url = "http://api.targetingmantra.com/TMWidgets?w=rhf&mid=130915&pid=" + prod_id + "&limit=" + limit + "&json=true&callback=?";
                jQuery.getJSON(url, function(data) {

                    //Recently Block starts here
                    if (!data.rhf || !data.rhf.recentHistory || !data.rhf.recentHistory.viewedItems || data.rhf.recentHistory.viewedItems.length == 0) {
                        $(".rcntly_viewed_mobile").hide();
                    }
                    else {

                        var widgetTitle = data.rhf.recentHistory.subWidgetTitle;
                        $(".rcntly_viewed_mobile").show();

                        data.rhf.recentHistory.viewedItems.forEach(function(obj, index) {
                            var prod_num = index + 1;
                            var mrp = (function() {
                                if (parseFloat(obj.itemMRP) > parseFloat(obj.itemPrice)) {
                                    return parseFloat(obj.itemMRP).formatMoney(0, '.', ',');
                                }
                                else {
                                    return parseFloat(obj.itemPrice).formatMoney(0, '.', ',');
                                }
                            })();
                            var img = document.createElement("img");
                            img.src = obj.itemImage;
                            //img.setAttribute("width", "48");
                            img = img.outerHTML;
                            var title = function() {
                                if (obj.itemTitle.length > 50) {
                                    return obj.itemTitle.substr(0, 46) + "...";
                                } else {
                                    return obj.itemTitle;
                                }
                            };
                            var item = "<div class='bs_cntnr_item_vr_mobile'><div class='bs_cntnr_img_vr_mobile'>" + img + "</div><div class='prd_info_left_blk_mob'><a onclick=\"_gaq.push(['_trackEvent', 'Homepage_recently_club', 'Click', 'Product_" + prod_num + "']);\" class='bs_cntnr_prd_name_vr' href='" + obj.itemURL + "'>" + title() + "</a>"
                                    + "<div class='starRating' id='rvi_club_stars_" + index + "'></div><span class='list-price' style='font-size: 11px;'>Price: </span><span class='list-price' style='font-size:11px' id='rvi_club_price_" + index + "'><strike>Rs." + mrp + "</strike></span><div class='bs_cntnr_prc_blk_vr'><div class='bs_main_price_vr'>Rs. " + parseFloat(obj.itemPrice).formatMoney(0, '.', ',') + "</div></div></div></div>";
                            $("#rvi_club").append(item);
                            if (parseInt(obj.itemRating) > 0) {
                                $("#rvi_club_stars_" + index).append("<span class='stars' id='rvi_club_" + index + "'>" + obj.itemRating + "</span>");
                            }
                            $("#rvi_club_" + index).makeStars();
                            if (parseFloat(obj.itemMRP) == parseFloat(obj.itemPrice)) {
                                $("#rvi_club_price_" + index).hide();
                            }
                        });
                    }

                    // recommendation block here
                    if(!data.rhf || !data.rhf.recommendations || !data.rhf.recommendations.recommendedItems || data.rhf.recommendations.recommendedItems.length == 0 ){
                        $(".recommendations_mobile").hide();

                    }
                    else{
                        var widgetTitle = data.rhf.recommendations.subWidgetTitle;
                        $(".recommendations_mobile").show();

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
                            img = img.outerHTML;
                            var title = function(){if(obj.itemTitle.length >50){return obj.itemTitle.substr(0,46) + "...";}else{return obj.itemTitle;}};
                            var item = "<div class='bs_cntnr_item_vr_mobile'><div class='bs_cntnr_img_vr_mobile'>" + img + "</div><div class='prd_info_left_blk_mob'><a onclick=\"_gaq.push(['_trackEvent', 'Homepage_recently_club', 'Click', 'Product_" + prod_num + "']);\" class='bs_cntnr_prd_name_vr' href='" + obj.itemURL + "'>" + title() + "</a>"
                                    + "<div class='starRating' id='reco_hr_club_stars_" + index + "'></div><span class='list-price' style='font-size: 11px;'>Price: </span><span class='list-price' style='font-size:11px' id='reco_hr_club_price_" + index + "'><strike>Rs." + mrp + "</strike></span><div class='bs_cntnr_prc_blk_vr'><div class='bs_main_price_vr'>Rs. " + parseFloat(obj.itemPrice).formatMoney(0, '.', ',') + "</div></div></div></div>";

                            $("#mob_reco_blk_club").append(item);
                            if(parseInt(obj.itemRating) > 0){
                                $("#reco_hr_club_stars_" + index).append("<span class='stars' id='reco_hr_club_"+index+"'>"+obj.itemRating+"</span>");
                            }
                            $("#reco_hr_club_" + index).makeStars();
                            if(parseFloat(obj.itemMRP) == parseFloat(obj.itemPrice)){
                                $("#reco_hr_club_price_"+index).hide();
                            }
                        });
                    }
                    if($(window).width()<630){
                        jQuery_1_10_2(".bs_cntnr_ovr_blk_vr").owlCarousel({
                            items : 4, //4 items above 1000px browser width
                            itemsDesktop : [1000,4], //4 items between 1000px and 901px
                            itemsDesktopSmall : [900,3], // betweem 900px and 601px
                            itemsTablet: [600,3], //2 items between 600 and 400
                            itemsMobile : [400,2] // 1 items between 400 and 0
                        });
                    }
                });
            });
        </script>
    {/literal}
    {/if}
{/if}

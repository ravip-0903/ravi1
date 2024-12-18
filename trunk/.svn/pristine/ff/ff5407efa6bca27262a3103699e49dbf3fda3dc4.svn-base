{* Added by shashi kant to show recently viewed products in full horizontal view *}
{** block-description:recently_viewed_full_horizontal_block_homepage **}

<div class="recently_viewed_full_horizontal_view" style="height:340px;">
<div class="ml_pageheaderCateogry_rvi">
    <div class="ml_pageheaderCateogry ml_pageheaderCateogry_reco">
    <h1 class="ml_pageheaderCateogry_heading" style="width:810px;">
        <a id="desc">{$lang.recently_viewed_homepage_full_horizontal_view}</a>
    </h1>
</div>
<div class="bs_cntnr" id="bs_cntnr_rvi_viewed" style="width:1000px;">
    <ul class="bs_cntnr_ovr_blk jcarousel-skin-tango" id="rvi_hr_viewed">
    </ul>
    
</div>
    
</div>
        </div>
     
{literal}
    
<script>
    var cdn_path = {/literal}"{$config.ext_images_host}"{literal};
    
    $(document).ready(function(){
    var prod_id = ReadCookie('rvph');

    $.ajax({
          type: "GET",
          url: " ",
          cache:false,
          data: {dispatch:'products.recently_viewed_data','product_id':prod_id},
          error:function (data, textStatus, jqXHR) {
                $(".recently_viewed_full_horizontal_view").hide();
                $(".ml_pageheaderCateogry_heading").hide();
                $(".ml_pageheaderCateogry_rvi").hide();
                $("#bbs_cntnr_rvi_viewed").hide();
          }
      }).done(function(recent_data){

           recent_data = recent_data.trim();

           if(recent_data == 'null' ){
                $(".recently_viewed_full_horizontal_view").hide();
                $(".ml_pageheaderCateogry_heading").hide();
                $(".ml_pageheaderCateogry_rvi").hide();
                $("#bs_cntnr_rvi_viewed").hide();
            }
            else{
                recent_data = jQuery.parseJSON(recent_data);
                $(".recently_viewed_full_horizontal_view").show();
                $(".ml_pageheaderCateogry_heading").show();
                $(".ml_pageheaderCateogry_rvi").show();
                $("#bs_cntnr_rvi_viewed").show();

                var arr_len=recent_data.length;

                recent_data.forEach(function(data ,index){
                    var prod_num=index+1;
                    var mrp = (function(){
                    if(parseFloat(data.list_price) > parseFloat(data.price))
                    {
                        return parseFloat(data.list_price).formatMoney(0,'.',',');
                    }
                    else
                    {
                        return parseFloat(data.price).formatMoney(0,'.',',');
                    }
                    })();
                    var img = document.createElement("img");
                    img.src = cdn_path+'/'+data.image_url;

                    img.setAttribute("width","160");
                    img.setAttribute("height","160");
                    img = img.outerHTML;
                    var title1 = function(){if(data.seo_name.length >50){return data.seo_name.substr(0,46) + "...";}else{return data.seo_name;}};
                    var item = "<li id = 'product_list_"+index+"' class='bs_cntnr_item_hp new_hor_blk_home_page' style='width:197px; padding:13px 15px 13px 0;'><a onclick=\"_gaq.push(['_trackEvent', 'Homepage_recently_club', 'Click', 'Product_"+prod_num+"']);\" class='bs_cntnr_prd_name bs_cntnr_prd_name_hp ' href='"+ data.seo_name+".html" +"'><div class='bs_cntnr_img'>"+img+"</div><span class='bs_prd_title_blk bs_prd_title_blk_nl'>" + title1() +"</span></a>"
                            +"<div id='reco_hr_club_stars_"+index+"'></div>"+"<div class='bs_prc_out_blk new_hor_blk_recently'><span class='tm_mrp' id='reco_hr_club_price_"+index+"' >MRP: Rs."+mrp+"</span><div class='bs_main_price bs_main_price_hp'>Rs. " + parseFloat(data.price).formatMoney(0,'.',',') +"</div></div></li>";
                    $("#rvi_hr_viewed").append(item);
                    
                    if(parseInt(data.rating) > 0)
                   {
                         $("#reco_hr_club_stars_" + index).append("<span class='stars' id='reco_club_id_view_"+index+"'>"+data.rating+"</span>");
                   }

                   if(index==(arr_len-1))
                   {
                       $("#product_list_"+index).css("border", "none"); 
                   }
                     
             $("#reco_club_id_view_" + index).makeStars();
             if(parseFloat(data.list_price) == parseFloat(data.price))
                   {
                         $("#reco_hr_club_price_"+index).hide();
                   }
                });
         
                $('#rvi_hr_viewed').jcarousel();
                addCarousel_rvi();
            }

    });

});

    function addCarousel_rvi(){
        var sliderWidth = $("#rvi_hr_viewed .bs_cntnr_item_hp").outerWidth(true);
        var slider = $('#bs_cntnr_rvi_viewed');
        var container = $('#rvi_hr_viewed');
        var sliderCount = $('.bs_cntnr_prd_name', slider).length;
        var delta = sliderCount * sliderWidth - slider.width();
        container.width((sliderCount * sliderWidth + 10));
        delta = (-1)*delta;
        var initialValue = container.position().left;

        if(container.position().left> delta+initialValue){
            if($('#bs_cntnr_rvi_viewed .jcarousel-next').hasClass("jcarousel-next-disabled jcarousel-next-disabled-horizontal")){
                $('#bs_cntnr_rvi_viewed .jcarousel-next').removeClass("jcarousel-next-disabled jcarousel-next-disabled-horizontal");
            }
        }
        $('#bs_cntnr_rvi_viewed .jcarousel-prev').click(function () {
            if(container.position().left< initialValue){
                container.animate({left: '+='+sliderWidth}, 100, function(){
                    if(container.position().left>= initialValue){
                        if(!$('#bs_cntnr_rvi_viewed .jcarousel-prev').hasClass("jcarousel-prev-disabled jcarousel-prev-disabled-horizontal")){
                            $('#bs_cntnr_rvi_viewed .jcarousel-prev').addClass("jcarousel-prev-disabled jcarousel-prev-disabled-horizontal");
                        }
                    }
                    if(container.position().left> delta+initialValue){
                        if($('#bs_cntnr_rvi_viewed .jcarousel-next').hasClass("jcarousel-next-disabled jcarousel-next-disabled-horizontal")){
                            $('#bs_cntnr_rvi_viewed .jcarousel-next').removeClass("jcarousel-next-disabled jcarousel-next-disabled-horizontal");
                        }
                    }
                });
            }

        });

        $('#bs_cntnr_rvi_viewed .jcarousel-next').click(function () {
            if(container.position().left> delta+initialValue){
                container.animate({left: '-='+sliderWidth}, 100, function(){
                    if(container.position().left< delta+initialValue){
                        if(!$('#bs_cntnr_rvi_viewed .jcarousel-next').hasClass("jcarousel-next-disabled jcarousel-next-disabled-horizontal")){
                            $('#bs_cntnr_rvi_viewed .jcarousel-next').addClass("jcarousel-next-disabled jcarousel-next-disabled-horizontal");
                        }
                    }
                    if(container.position().left< initialValue){
                        if($('#bs_cntnr_rvi_viewed .jcarousel-prev').hasClass("jcarousel-prev-disabled jcarousel-prev-disabled-horizontal")){
                            $('#bs_cntnr_rvi_viewed .jcarousel-prev').removeClass("jcarousel-prev-disabled jcarousel-prev-disabled-horizontal");
                        }
                    }
                });
            }

        });
    }

</script>
{/literal}
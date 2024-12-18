{*Added by shashikant to show recently_viewed_history_half_horizontal_view*}
{** block-description:recently_viewed_product_history **}

<div class="recently_viewed_product_data_history_record" style="width: 710px; float: left; margin-left: 35px;">
<div class="ml_pageheaderCateogry ml_pageheaderCateogry_reco">
    <h1 class="ml_pageheaderCateogry_heading nl_recoman_blk" style="width:810px;">
        {$lang.recently_viewed_history_horizontal_view}
    </h1>
</div>
<div class="bs_cntnr" id="product_data_div_history">
    <div class="bs_tm_left_hp_arrow_disabled" id="bs_left_reco_club" ></div>
        <div class="bs_cntnr_ovr_blk" id="recently_view_listing_history_horizontally">&nbsp;</div>
     <div  id="bs_right_reco_club"></div>
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
                 $(".recently_viewed_product_data_history_record").hide(); 
                 $(".ml_pageheaderCateogry_rvi").hide();
                 $("#product_data_div_history").hide();
          }
      }).done(function(recent_data){
    
           recent_data = recent_data.trim();

    if(recent_data == 'null' )
            {
                 $(".recently_viewed_product_data_history_record").hide(); 
                 $(".ml_pageheaderCateogry_rvi").hide();
                 $("#product_data_div_history").hide();
            }
    else { 
            recent_data = jQuery.parseJSON(recent_data);
            $(".recently_viewed_product_data_history_record").show();
            $(".ml_pageheaderCateogry_reco").show();
            $("#product_data_div_history").show();

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
             img = img.outerHTML;

             var title = function(){if(data.seo_name.length >50){return data.seo_name.substr(0,46) + "...";}else{return data.seo_name;}};
             var item = "<div class='bs_cntnr_item' style='width:162px; padding:13px 33px;'><a onclick=\"_gaq.push(['_trackEvent', 'Homepage_recently_club', 'Click', 'Product_"+prod_num+"']);\" class='bs_cntnr_prd_name' href='"+ data.seo_name+".html" +"'><div class='bs_cntnr_img'>"+img+"</div>" + "<span class='bs_prd_title_blk'>"+title()+"</span></a>"
                            +"<div id='reco_hr_club_stars_view_"+index+"'></div><div class='bs_prc_out_blk' style='left:74px;'><span class='tm_mrp' id='reco_hr_club_price_view_"+index+"' >MRP: Rs."+mrp+"</span><div class='bs_cntnr_prc_blk'><div class='bs_main_price'>Rs. " + parseFloat(data.price).formatMoney(0,'.',',') +"</div></div></div></div>";

             $("#recently_view_listing_history_horizontally").append(item);
             if(parseInt(data.rating) > 0)
                   {
                         $("#reco_hr_club_stars_view_" + index).append("<span class='stars' id='rvi_club_vertival_view_"+index+"'>"+data.rating+"</span>");
                   }
             $("#rvi_club_vertival_view_" + index).makeStars();
             if(parseFloat(data.list_price) == parseFloat(data.price))
                   {
                         $("#reco_hr_club_price_view_"+index).hide();
                   }
            });
                addCarousel_reco_club();
            }
    
 });
           }); 
        
  </script>
{/literal}   

{literal}
<script>
    function addCarousel_reco_club(){
        var sliderWidth = $("#recently_view_listing_history_horizontally .bs_cntnr_item").outerWidth();
        var slider = $('#product_data_div_history');
        var container = $('#recently_view_listing_history_horizontally');
        container.wrap( "<div class='carousel-wrap-small'></div>" );
        var sliderCount = $('.bs_cntnr_prd_name', slider).length;
        var delta = sliderCount * sliderWidth - slider.width(); 
        container.width((sliderCount * sliderWidth + 10));
        delta = (-1)*delta;
        var initialValue = container.position().left;

        if(container.position().left< delta + initialValue)
                {
                   $('#bs_right_reco_club').addClass('bs_tm_hp_right_arrow_disabled');
 
                }else
                {
                    $('#bs_right_reco_club').addClass('bs_tm_hp_right_arrow');
                }
      
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

                }
                });
            }
        });
    }			
</script>

{/literal}    
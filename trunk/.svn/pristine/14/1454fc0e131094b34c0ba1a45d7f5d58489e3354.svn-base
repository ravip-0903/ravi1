{*Added by shashikant to show recently_viewed_history_vertical_view*}
{** block-description:recently_viewed_product_history **}

<div class="recently_viewed_product_data_history" style="width:253px; float:left;clear:both;">
<div class="ml_pageheaderCateogry ml_pageheaderCateogry_rvi">
    <h1 class="ml_pageheaderCateogry_heading nl_recoman_blk">
        {$lang.recently_viewed_history_homepage}
    </h1>
</div>
<div class="bs_cntnr_vr" id="product_data_div" style="height:270px; width:251px;">
        <div id="scrollbar2" class="scrl_cat_pg scrl_cat_pg_hp" style="width:250px !important;">
        <div class="scrollbar" style="margin:0;"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
		<div class="viewport">
			 <div class="overview">
		        <div class="bs_cntnr_ovr_blk_vr" style="float:left; width:auto;" id="recently_view_listing"></div>
            </div>
        </div>
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
            $(".recently_viewed_product_data_history").hide();  
            $(".ml_pageheaderCateogry_rvi").hide();
            $("#product_data_div").hide();
             
          }
      }).done(function(recent_data){

     recent_data = recent_data.trim();
     if(recent_data == 'null')
          {
                 $(".recently_viewed_product_data_history").hide();
                 $(".ml_pageheaderCateogry_rvi").hide();
                 $("#product_data_div").hide();
          }
          else
          {
            recent_data = jQuery.parseJSON(recent_data);  
            $(".recently_viewed_product_data_history").show();  
            $(".ml_pageheaderCateogry_rvi").show();
            $("#product_data_div").show();
             
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
             img.setAttribute("width","48");
             img = img.outerHTML;
             
             var title = function(){if(data.seo_name.length >50){return data.seo_name.substr(0,46) + "...";}else{return data.seo_name;}};
             var item = "<div class='bs_cntnr_item_vr' style='margin:0;'><div class='bs_cntnr_img_vr'>"+img+"</div><div class='prd_info_left_blk' style='width:170px;'><a onclick=\"_gaq.push(['_trackEvent', 'Homepage_recently', 'Click', 'Product_"+prod_num+"']);\" class='bs_cntnr_prd_name_vr' href='"+ data.seo_name+".html" +"'>" + title() +"</a>"
                        +"<div id='rvi_club_stars_vertival_view_"+index+"'></div><span class='list-price' style='font-size: 11px;'>Price: </span><span class='list-price' style='font-size:11px' id='rvi_club_price_vertival_view_"+index+"'><strike>Rs."+mrp+"</strike></span><div class='bs_cntnr_prc_blk_vr'><div class='bs_main_price_vr'>Rs. " + parseFloat(data.price).formatMoney(0,'.',',') +"</div></div></div></div>";

             $("#recently_view_listing").append(item);
             if(parseInt(data.rating) > 0)
                   {
                         $("#rvi_club_stars_vertival_view_" + index).append("<span class='stars' id='rvi_club_vertival_view_"+index+"'>"+data.rating+"</span>");
                   }
             $("#rvi_club_vertival_view_" + index).makeStars();
             if(parseFloat(data.list_price) == parseFloat(data.price))
                   {
                         $("#rvi_club_price_vertival_view_"+index).hide();
                   }
            });      
    }

             $('#scrollbar2').tinyscrollbar();
    
 });
           }); 
        
  </script>
{/literal}

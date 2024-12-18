{** block-description:recommendation_page_block_tm **}
<div class="box_manualdeals" style="width:100%; margin-top: 10px;">
<div  id="recommend_main" class="slider_manualdeals jcarousel-skin-tango">
<div id="loading_tm" style="margin-left:436px;">
    <p>Please Wait...</p>
    <img src="images/progress-bar.gif">
</div>
</div>
</div>

{if $config.isResponsive}
    {literal}
        <style>
            .block-packs-title {font-size: 14px;}
            .owl-item .box_GridProduct a img {width: 100%; height:auto;}
            .box_GridProduct .box_GridProduct_link, .box_GridProduct .box_GridProduct_link:hover {white-space: normal;width: 70% !important;margin-left: 15%;}
            .box_GridProduct .box_GridProduct_price {width: 90%;}
            .owl-theme .owl-controls .owl-page span{display: none;}
            .jcarousel-skin-ie7 .jcarousel-next-disabled-horizontal:hover, .jcarousel-skin-tango .jcarousel-next-horizontal:hover {background: url('http://cdn.shopclues.com/images/skin/mc_next.png') left top no-repeat;}
            .jcarousel-skin-tango .jcarousel-prev-horizontal:hover, .jcarousel-skin-ie7 .jcarousel-prev-disabled-horizontal:hover {background: url('http://cdn.shopclues.com/images/skin/mc_pre.png') left top no-repeat;}
        </style>
    {/literal}
{/if}

{literal}
<script type="text/javascript">

    $(document).ready(function(){
    	
       var limit = {/literal}{$config.TM_recommend_limit}{literal};
      
       var url = "http://api.targetingmantra.com/TMWidgets?w=rp&mid=130915&limit="+limit+"&json=true&callback=?";

        jQuery.getJSON(url,function(data){
        	
            data.rp.widgets.forEach(function(obj, index){

	                    var rec_num=index;
	                    var main='';
	                    var main="<h1 class='block-packs-title'><span class='float_left'>"+obj.subWidgetTitle+"</span></h1><div class='clearboth'></div> <ul id='recommend_"+rec_num+"'></ul>";

	                    $("#loading_tm").hide();
	                    $("#recommend_main").append(main);

                        if({/literal}{$config.isResponsive}{literal}){
                            var end_string = '<div class=" mobile-arrow mobile-arrow-prev-'+rec_num+' jcarousel-prev jcarousel-prev-horizontal jcarousel-prev-disabled jcarousel-prev-disabled-horizontal" disabled="true" style="display: block !important;"></div><div class=" mobile-arrow mobile-arrow-next-'+rec_num+' jcarousel-next jcarousel-next-horizontal jcarousel-next-disabled jcarousel-next-disabled-horizontal" disabled="true" style="display: block !important;"></div><div class="clearboth height_ten"></div>';
                            $("#recommend_main").addClass("jcarousel-skin-ie7");
                            $("#recommend_main").append(end_string);
                        }
                        else{
                            var end_string = "<div class='clearboth height_ten'></div>";
                            $("#recommend_main").append(end_string);
                        }
	                    
	                    var prod='';
	            obj.recommendedItems.forEach(function(obj_rec, index){

                    	var prod_num=index;
                    	var arr_len=obj.recommendedItems.length;
                    	
                    	var title = function(){if(obj_rec.itemTitle.length >50){return obj_rec.itemTitle.substr(0,50) + "...";}else{return obj_rec.itemTitle;}};

	                    var prod="<li id='recommend_li_"+rec_num+"_"+prod_num+"'><div class='box_GridProduct' style='margin-left:0px; margin-top:15px;'><a href='"+obj_rec.itemURL+"' class='box_GridProduct_product'><img src='"+obj_rec.itemImage+"' height='160' width='160' border='0' alt='"+obj_rec.itemTitle+"' title='"+obj_rec.itemTitle+"'></a><a href='"+obj_rec.itemURL+"' class='box_GridProduct_link'>"+title()+"</a><div id='main_star_"+rec_num+"_"+prod_num+"' class='box_GridProduct_starrating'></div><div class='box_GridProduct_pricing'><span id='price_"+rec_num+"_"+prod_num+"' class='box_GridProduct_price'>"+obj_rec.itemMRP+"</span><span class='box_GridProduct_priceoffer'>"+obj_rec.itemPrice+"</span></div></li>";
	                    
	     				$("#recommend_"+rec_num).append(prod);

	     				if(parseInt(obj_rec.itemRating) > 0){
	                        $("#main_star_" +rec_num+"_"+prod_num).append("<span class='stars' style='margin: auto;' id='star_"+rec_num+"_"+prod_num+"'>"+obj_rec.itemRating+"</span>");
	                    }

	                    $("#star_"+rec_num+"_"+prod_num).makeStars();

	                    if(prod_num ==(arr_len-1))
		                   {
		                       $("#recommend_li_"+rec_num+"_"+prod_num).css("border", "none"); 
		                   }

	                   if(parseFloat(obj_rec.itemMRP) == parseFloat(obj_rec.itemPrice)){
	                        $("#price_"+rec_num+"_"+prod_num).hide();
	                    }

	              });

                if({/literal}{$config.isResponsive}{literal}){
                    jQuery_1_10_2("#recommend_"+rec_num).owlCarousel({
                        items : 4, //4 items above 1000px browser width
                        itemsDesktop : [1000,4], //4 items between 1000px and 901px
                        itemsDesktopSmall : [900,3], // betweem 900px and 601px
                        itemsTablet: [600,3], //2 items between 600 and 400
                        itemsMobile : [400,2] // 1 items between 400 and 0
                    });
                    jQuery_1_10_2(".mobile-arrow-next-"+rec_num).click(function(){
                        var owl = jQuery_1_10_2(this).parent().find("#recommend_"+rec_num);
                        owl.trigger('owl.next');
                    });
                    jQuery_1_10_2(".mobile-arrow-prev-"+rec_num).click(function(){
                        var owl = jQuery_1_10_2(this).parent().find("#recommend_"+rec_num);
                        owl.trigger('owl.prev');
                    });
                }
                else{
                    $("#recommend_"+rec_num).jcarousel({
                        size: obj.recommendedItems.length,
                        scroll:1,
                        item_count: obj.recommendedItems.length
                    });
                }
            });
			
        });
		
		
    });
</script>
{/literal}
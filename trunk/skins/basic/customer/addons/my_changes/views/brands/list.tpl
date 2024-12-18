{* $Id: list.tpl 2013-01-07 chandan $ *}
{literal}
<style type="text/css">
.pj2_return_policy{width:810px; float:left; font: 11px/20px verdana; line-height:25px;}
.pj2_return_policy h2.main_heading{font-size:22px; color:#EE811D; border-bottom:1px solid #ddd; font-weight:normal; padding:0 0 0 70px; margin:0 0 15px; background:url(images/skin/pj2_emi_option.gif) no-repeat; line-height:70px; font-weight:bold;}
h2.heading{font-size:16px; color:#048ccc; border-bottom:1px solid #ddd; font-weight:bold;  margin:20px 0 5px; padding:0px 0 5px 5px; clear:both;}
.pj2_return_policy .policy_points{background:url(images/skin/pj2_return_policy_icon.gif) 175px 205px no-repeat; width:270px; float:right; margin:0 8px 0 15px; height:auto;}
.pj2_return_policy .policy_points ul.points{float:left; display:inline; width:90%; padding:0px; margin:0px; list-style-type:none; margin-left:5px; padding-bottom:10px;}
.pj2_return_policy .policy_points ul.points li{width:100%; display:inline;  clear:both;padding:0px; margin:0px; list-style-type:none; line-height:normal; background: url(images/skin/bullet_right_green.gif) left 4px no-repeat; padding-left:20px; margin-top:5px; line-height:20px; }
.pj2_return_policy .policy_points ul.points li.show_list{cursor:pointer; float:left;}
span.blue_points{background: url(images/skin/point_bg.png) 0 0 no-repeat; width: 25px; line-height: 25px; margin: 0 5px 0 0; text-align: center; float: left; color:#000; text-indent: -3px;}
.pj2_return_policy span.red{color:#ff0000;}
.pj2_return_policy span.blue{color:#048ccc;}
.pj2_return_policy a.red{color:#ff0000; text-decoration:none;}
.pj2_return_policy a.red:hover{text-decoration:underline;}
.pj2_return_policy a.blue{color:#048ccc; text-decoration:none;}
.pj2_return_policy a.blue:hover{text-decoration:underline;}
.pj2_return_policy p{padding:0 10px; margin:0px 0 0px 0; text-align:justify; font:11px/20px Verdana;}
.pj2_return_policy ul.num_points{float:left; padding:0 10px; width:auto; margin:0;}
.pj2_return_policy ul.num_points li{float:left; list-style:none; margin:0 0 10px 0px; line-height:25px; clear:both; color:#048ccc;}
.pj2_return_policy ul.num_points li a{color:#048ccc; text-decoration:none;}
.pj2_return_policy ul.num_points li a:hover{text-decoration:underline;}

.pj2_return_policy ul.num_points_ans{float:left; padding:40px 10px 10px; width:auto; margin:0;}
.pj2_return_policy ul.num_points_ans li{float:left; list-style:decimal; margin:0 0 30px 20px; line-height:normal; clear:both; color:#434343;}
.pj2_return_policy img.pj2_return_plc_img{margin:40px 0 30px 35px;}
ul.pj2_product_return{margin:0 0 0 30px;}
ul.pj2_product_return li{ text-transform:capitalize; list-style:disc;}
ul.pj2_product_return li.noncap{ text-transform:none; list-style:disc;}


.brand_row_nl_new{width:100%; display:block;}
.brand_row_nl_box_new{width:150px; display:inline-block; height:164px; border:1px solid #ccc; margin:10px 3px; position:relative;}
.brand_row_nl_box_new:hover{border:1px solid #048ccc; box-shadow:0 0 5px #aaa; -moz-box-shadow:0 0 5px #aaa; }
.brand_row_nl_box_new:hover .brnd_nl_name_new{clear:both; text-align:center; background:#048ccc; color:#fff;}
.brand_row_nl_box_new .img_brnd_nl_new{height:118px; text-align:center; width:162px; line-height:118px; display:table-cell; vertical-align:middle;}
.brand_row_nl_box_new .img_brnd_nl_new img{max-width:140px; max-height:118px;}
.brand_row_nl_box_new .brnd_nl_name_new{clear:both; text-align:center; background:#eeeeee; color:#048ccc; height: 25px; line-height: 25px; position:absolute; width:100%; bottom:0;}
.box_GridProduct_newlabel_new{background: url("http://cdn.shopclues.com/images/skin/new.png") no-repeat scroll left top transparent; height: 35px; left: 0; position: absolute; top: 0; width: 35px;}
.box_GridProduct_ngolabel_new{background: url("http://cdn.shopclues.com/images/skin/bg_sprite.png") no-repeat scroll -73px top transparent;  height: 10px; left: 50px; position: absolute; top: 2px; width: 24px;}



.brand_row_nl{width:100%; display:block;}
.brand_row_nl_box{width:150px; display:inline-block; height:164px; border:1px solid #ccc; margin:10px 3px; position:relative;}
.brand_row_nl_box:hover{border:1px solid #048ccc; box-shadow:0 0 5px #aaa; -moz-box-shadow:0 0 5px #aaa; }
.brand_row_nl_box .brnd_nl_name{clear:both; text-align:center; background:#eeeeee; color:#048ccc; height: 25px; line-height: 25px; position:absolute; width:100%; bottom:0;}
.brand_row_nl_box:hover .brnd_nl_name{clear:both; text-align:center; background:#048ccc; color:#fff;}
.brand_row_nl_box .img_brnd_nl{height:118px; text-align:center; width:162px; line-height:118px; display:table-cell; vertical-align:middle;}
.brand_row_nl_box .img_brnd_nl img{max-width:140px; max-height:118px;}
.box_GridProduct_newlabel{background: url("http://cdn.shopclues.com/images/skin/new.png") no-repeat scroll left top transparent; height: 35px; left: 0; position: absolute; top: 0; width: 35px;}
.box_GridProduct_ngolabel{background: url("http://cdn.shopclues.com/images/skin/bg_sprite.png") no-repeat scroll -73px top transparent;  height: 10px; left: 50px; position: absolute; top: 2px; width: 24px;}



.bg_like_nl{height:25px; width:26px; float:right; margin:-5px 0 0 ; position:relative; cursor:pointer}
    .nl_tool_tip{border-radius:5px; -moz-border-radius:5px; background:#f8f8f8; border:1px solid #ccc; display:none; position:absolute; z-index:10000; width:150px; top: 23px;
right: -24px; color:#666; padding:2px 10px; text-align:center; box-shadow:3px 3px 5px #c6edff; -moz-box-shadow:3px 3px 5px #c6edff; font:11px/16px Verdana, Geneva, sans-serif;}
	.bg_like_nl.store_liked{background:url('http://cdn.shopclues.com/images/skin/shopclues_seller_like_new_look.png') no-repeat -27px 0;}
    .bg_like_nl:hover{background-position: -27px 0px}
    .bg_like_nl.store_not_liked{background:url('http://cdn.shopclues.com/images/skin/shopclues_seller_like_new_look.png');background-position: -55px 0}
	.bg_like_nl.store_not_liked:hover{background-position: -27px 0}
    .bg_like_nl:hover .nl_tool_tip{display:block!important}
	
	
	/*.bg_like_nl{display:none;}*/
	.brand_row_nl_box:hover .bg_like_nl{display:block; }
</style>{/literal}

{$lang.brand_store_title}
{$lang.brand_store_description}

{assign var="fav_stores" value=","|explode:$smarty.cookies.scfavstore}
{if $is_new == 'Y'}
<div class="brand_row_nl_new">
<h2 class="heading">{$lang.new_stores}</h2>
{foreach from=$brands item="brand" key="k"}
	{foreach from=$brand item="b"}
        {if $b.is_new == "Y"}
            {if $b.brand_id == "" || $b.brand_id == "0"}
                <a class="brand_row_nl_box_new" id="{$b.company_id}_new" href="{"products.search&category_id=`$b.category_id`&company_id=`$b.company_id`&subcats=Y&search_performed=Y"|fn_url}">
            {else}
                <a class="brand_row_nl_box_new" id="{$b.company_id}_new" href="{"products.search&category_id=`$b.category_id`&company_id=`$b.company_id`&subcats=Y&features_hash[]=9.`$b.brand_id`"|fn_url}">
            {/if}
            {*{if $b.is_new == "Y"}
                <div class="box_GridProduct_newlabel_new">
                </div>
            {/if}*}
            {if $b.is_ngo == "Y"}
            <div class="box_GridProduct_ngolabel_new">
            </div>
             {/if}   
            <div class="bg_like_nl {if $b.company_id|in_array:$fav_stores}store_liked{/if}" id="{$b.company_id}_like_unlike_icon" style="margin-top:0px; right:0px;">
                 <div  id="{$b.company_id}_fav_like_tool_tip" class="{if $b.company_id|in_array:$fav_stores}nl_tool_tip{/if}" >  
                 {if $b.company_id|in_array:$fav_stores}{$lang.already_liked|escape}{/if}
                </div>        
            </div>
            
            <div class="img_brnd_nl_new">
                <img src="{$b.logo_url}"  />
            </div>
            {if $b.new_product == "Y"}
                <span class="nl_red_icon_spl_offer_tag_new" style="margin-left:1px; margin-top:7px; width:69px; text-align:center; float:left;	">
                    <label class="nl_prc_red_icon_spl_new" style="font:10px/12px verdana" >{$lang.new_product}</label>
                </span>
            {/if}
            {if $b.offers == "Y"}
                <span class="top_home_blue_deal_nl_new" style="margin-left:0; margin-right:1px;  margin-top:7px; float:right; text-align:center; width:38px;">
                    <label style="color:#fff!important;">Offers</label>
                </span>
            {/if}
            <div class="brnd_nl_name_new">
            {if $b.brand_id == "" || $b.brand_id == "0"}
                {if $b.custom_brand_name != ""}
                    {$b.custom_brand_name|truncate:25:""}
                {else}
                    {$b.company_id|fn_get_company_name|truncate:25:""}
                {/if}
            {else}
                {$b.brand_name|truncate:25:""}
            {/if}
            </div>
            </a>
        {/if}
    {/foreach}
    
{/foreach}
</div>
{/if}





<div class="brand_row_nl">
{foreach from=$brands item="brand" key="k"}

	<h2 class="heading">{$k|fn_get_category_name}</h2>
    
    {foreach from=$brand item="b"}
        {if $b.brand_id == "" || $b.brand_id == "0"}
            {if $b.custom_brand_name != ""}                
                {assign var="titlebrandname" value=$b.custom_brand_name}
            {else}
                {assign var="titlebrandname" value=$b.company_id|fn_get_company_name}
            {/if}
        {else}
            {assign var="titlebrandname" value=$b.brand_name}
        {/if}

        {if $b.brand_id == "" || $b.brand_id == "0"}
        	<a class="brand_row_nl_box" id="{$b.company_id}" href="{"products.search&category_id=`$b.category_id`&company_id=`$b.company_id`&subcats=Y&search_performed=Y"|fn_url}" alt="{$titlebrandname}" title="{$titlebrandname}">
        {else}
        	<a class="brand_row_nl_box" id="{$b.company_id}" href="{"products.search&category_id=`$b.category_id`&company_id=`$b.company_id`&subcats=Y&features_hash[]=9.`$b.brand_id`"|fn_url}" alt="{$titlebrandname}" title="{$titlebrandname}">
        {/if}
        {if $b.is_new == "Y"}
            <div class="box_GridProduct_newlabel">
            </div>
        {/if}
        {if $b.is_ngo == "Y"}
        <div class="box_GridProduct_ngolabel">
        </div>
         {/if}   
        <div class="bg_like_nl {if $b.company_id|in_array:$fav_stores}store_liked{/if}" id="{$b.company_id}_like_unlike_icon" style="margin-top:0px; right:0px;">
             <div  id="{$b.company_id}_fav_like_tool_tip" class="{if $b.company_id|in_array:$fav_stores}nl_tool_tip{/if}" >  
             {if $b.company_id|in_array:$fav_stores}{$lang.already_liked|escape}{/if}
            </div>        
        </div>
        
        <div class="img_brnd_nl">
            <img src="{$b.logo_url}"  />
        </div>
        {if $b.new_product == "Y"}
        	<span class="nl_red_icon_spl_offer_tag" style="margin-left:1px; margin-top:7px; width:69px; text-align:center; float:left;">
            	<label class="nl_prc_red_icon_spl" style="font:10px/12px verdana;">{$lang.new_product}</label>
            </span>
        {/if}
        {if $b.offers == "Y"}
            <span class="top_home_blue_deal_nl" style="margin-left:0; margin-right:1px;  margin-top:7px; float:right; text-align:center; width:38px;">
                <label style="color:#fff!important;">Offers</label>
            </span>
        {/if}
        <div class="brnd_nl_name">
        {$titlebrandname}
        </div>
        </a>
    {/foreach}
    
{/foreach}
</div>


{literal}
  <script type="text/javascript">
	/*$(function() {
		$('.brand_row_nl_box').hover(function(){
			var company_id = this.id;
			var store_fav_id = $(this).children(".bg_like_nl").attr('id');
			var store_fav_tt_id = $(this).children(".bg_like_nl").children().attr('id');
			if(ReadCookie('scfavstore')!='')
			{
				var favcookie=ReadCookie('scfavstore').split(",");
				var found=0;
				for(i=0;i<favcookie.length;i++){
					if(favcookie[i]== company_id){
						found=1;
					}
				}						
				if(found==1){
					$('#'+store_fav_id).removeClass('store_not_liked');
					$('#'+store_fav_id).addClass('store_liked');
					$('#'+store_fav_id).css('right','0px');
					$('#'+store_fav_tt_id).css('right','-43px');
					$('#'+store_fav_tt_id).html('{/literal}{$lang.already_liked|escape}{literal}');
					$('#'+store_fav_tt_id).addClass('nl_tool_tip');
				}else{
						
				}
			}
		});
    });*/
	
	
  </script>
  
{/literal}

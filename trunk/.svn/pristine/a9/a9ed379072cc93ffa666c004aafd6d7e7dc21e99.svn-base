 {if !empty($bazooka_arr)} 
{literal}
    <script type="text/javascript">
        $(document).ready(function(){
            $(".allProductLink").click(function(){
                //$("{$order_id}").removeClass('margin_bottom_twenty');
                //$("{$order_id}").css('border','none');
               // $(".allProductLink").css('display','none');
                //$(".allProductLink_active").css('display','inline');
            });
            $(".allProductLink_active").click(function(){
                //$("{$order_id}").addClass('margin_bottom_twenty');
                //$("{$order_id}").css('border',';');
                //$(".allProductLink").css('display','inline');
                //$(".allProductLink_active").css('display','none');
            });
            
           
        });
         function showBox(id, orderid, color){
        
              $("#"+orderid+"_"+color).mouseover(function(){
               $("#"+orderid+"_hover") .css('visibility','visible');
               if(id==1)
               {
                   $("#"+orderid+"_arrow").css('margin-left','50px');
                   $("#"+orderid+"_details").css('margin-left','40px')
               }
               else if (id==2)
                {
                    $("#"+orderid+"_arrow").css('margin-left','160px');
                    $("#"+orderid+"_details").css({
                        'width':'338px',
                        'text-align':'center'
                    });
                    
                }
                else if(id==3)
                {
                    $("#"+orderid+"_arrow").css('margin-left','300px');
                    $("#"+orderid+"_details").css({
                         'width':'90%',
                        'text-align':'center'
                    });
                }
                else if(id==4)
                {
                    $("#"+orderid+"_arrow").css('margin-left','450px');
                    $("#"+orderid+"_details").css({
                        'float':'right',
                        'text-align':'center',
                        'width':'465px'
                    });
                }
                else
                {
                    $("#"+orderid+"_arrow").css('margin-left','605px');
                    $("#"+orderid+"_details").css({
                        'width':'150px',
                        'float':'right',
                        'text-align':'center'
                    });
                }
              });
               
               $("#"+orderid+"_"+color).mouseleave(function(){
               $("#"+orderid+"_hover") .css('visibility','hidden');  
              });
            }
        
        function showChild(orderid){
            $('#' + orderid).removeClass('margin_bottom_twenty');
            $('#' + orderid).css('border','none');
            $('#' + orderid + '_box_childproducts').slideDown();
            $(".allProductLink").css('display','none');
            $(".allProductLink_active").css('display','inline');
        };
        
        function hideChild(orderid){
            $('#' + orderid).addClass('margin_bottom_twenty');
            $('#' + orderid).css('border','');
            $('#' + orderid + '_box_childproducts').slideUp();
            $(".allProductLink").css('display','inline');
            $(".allProductLink_active").css('display','none');
        };
            
        
        
        
      
    </script>
{/literal}  
{foreach from=$bazooka_arr key="order_id" item="bazooka"}

<div id="{$order_id}" class="productStatus margin_bottom_twenty{if $bazooka.m_product==1} no_mobile {/if}" style="border:;{if $bazooka.fetch_images==0}margin-top:-30px;{/if}">
{if $bazooka.fetch_images==1}
<div class="aside_left">
 
<a href="{if $bazooka.m_product!=0}#" onclick='return false;'{else}{"products.view?product_id=`$bazooka.product_id`"|fn_url}"{/if} class="image"><img src="{if $bazooka.m_product=='0'}{$bazooka.image_url}{else if $bazooka.m_product=='1'}{$bazooka.image_url_for_multiple}{/if}"  height="100" width="100"/></a>

{if $bazooka.m_product==1}
<div class="clearboth"></div>
<a  onclick="showChild({$order_id})" class="allProductLink" style="display:;">{$lang.view_all_product_message}</a>
<a  onclick="hideChild({$order_id})" class="allProductLink_active" style="display:none;" >{$lang.all_products_message}</a>
{/if}
</div>
{/if}
<div class="aside_right" {if $bazooka.fetch_images==0}style="width:730px;"{/if}>
{if $bazooka.fetch_images==1}
<div class="heading">{$bazooka.product_name}</div>
<div class="otherDetails">
<span><label>Order ID : </label>{$order_id}</span>
{if $bazooka.m_product!=1}
<span><label>{$lang.qty} : </label> {$bazooka.quantity}</span>
{else}
 <span><label>{$lang.no_of_product} : </label> {$bazooka.count_products}</span>  
 {/if}
</div>
{/if}
<div class="statusDetails no_mobile">
    <div style="float:right; margin-right:60px; {if $bazooka.fetch_images==0} margin-top: 20px;{/if}" >  {$lang.ProductStatusBar_info} </div> 
  {if $bazooka.fetch_images==1} 
    <div class="clearboth"></div>
{/if}
<div class="overallStatus" {if $bazooka.fetch_images==0}style="margin-left:10px;"{/if}>{$bazooka.status}</div>

<!--Status Bar-->
<div id="box_OrderStatusBar" {if $bazooka.fetch_images==0}style="clear:both;"{/if}>

<!--Status Bar-->
<ul class="box_StatusBar">

<li id='{$order_id}_{$bazooka.color_bucket.1}' class="bg_{$bazooka.color_bucket.1}" {if $bazooka.color_bucket.1=="red"|| $bazooka.color_bucket.1=="blue"}onmouseover="showBox(1, {$order_id},'{$bazooka.color_bucket.1}');"{/if}>
<div class="status">Confirmed</div>
<div class="date">{if $bazooka.dates.1!=''}{$bazooka.dates.1}{else}&nbsp;{/if}</div>
</li>

<li class="divider_{$bazooka.color_bucket.1}_{$bazooka.color_bucket.2}"></li>

<li id='{$order_id}_{$bazooka.color_bucket.2}' class="bg_{$bazooka.color_bucket.2}" {if $bazooka.color_bucket.2=="red"|| $bazooka.color_bucket.2=="blue"}onmouseover="showBox(2, {$order_id},'{$bazooka.color_bucket.2}');"{/if}>
<div class="status">Processing</div>
<div class="date">{if $bazooka.dates.2!=''}{$bazooka.dates.2}{else}&nbsp;{/if}</div>
</li>

<li class="divider_{$bazooka.color_bucket.2}_{$bazooka.color_bucket.3}"></li>

<li id='{$order_id}_{$bazooka.color_bucket.3}' class="bg_{$bazooka.color_bucket.3}" {if $bazooka.color_bucket.3=="red"|| $bazooka.color_bucket.3=="blue"}onmouseover="showBox(3, {$order_id},'{$bazooka.color_bucket.3}');"{/if}>
<div class="status">Quality Check</div>
<div class="date">{if $bazooka.dates.3!=''}{$bazooka.dates.3}{else}&nbsp;{/if}</div>
</li>

<li class="divider_{$bazooka.color_bucket.3}_{$bazooka.color_bucket.4}"></li>

<li id='{$order_id}_{$bazooka.color_bucket.4}' class="bg_{$bazooka.color_bucket.4}" {if $bazooka.color_bucket.4=="red"|| $bazooka.color_bucket.4=="blue"}onmouseover="showBox(4, {$order_id},'{$bazooka.color_bucket.4}');"{/if}>
<div class="status">Dispatched</div>{if $bazooka.dates.4!=''} {if ($bazooka.color_bucket.4=="blue")&&($bazooka.ship_info!='')}<div href="#" class="whatisthis"><div>{$bazooka.ship_info}</div></div>{/if}{/if}
<div class="date">{if $bazooka.dates.4!=''}{$bazooka.dates.4}{else}&nbsp;{/if}</div>
</li>

<li class="divider_{$bazooka.color_bucket.4}_{$bazooka.color_bucket.5}"></li>

<li id='{$order_id}_{$bazooka.color_bucket.5}' class="bg_{$bazooka.color_bucket.5}" {if $bazooka.color_bucket.5=="red"|| $bazooka.color_bucket.5=="blue"}onmouseover="showBox(5, {$order_id},'{$bazooka.color_bucket.5}');"{/if}>
{if $bazooka.color_bucket.5=='green'}
    <div class="icon_complete">
 {else}
    <div class="icon_uncomplete">
  {/if}
<div class="status">Delivered</div>
{if $bazooka.dates.5!='' and $bazooka.color_bucket.5=='gray'}
<div href="#" class="whatisthis"><div style="margin-top:-70px;">{$bazooka.complete_description}</div></div>
{/if}
<div class="date">{if $bazooka.dates.5!=''}{$bazooka.dates.5}{else}&nbsp;{/if}&nbsp;</div>
</div>
</li>

</ul>
<!--End Status Bar-->

<!--Hover Details-->
<div id="{$order_id}_hover" class="box_hoverdetails " style="visibility: hidden;">
<div class="box_hoverdetails_top"></div>
<div id="{$order_id}_arrow" class="pointingarrow" style="margin-left:160px;"></div>
<div id="{$order_id}_details" class="details">{$bazooka.hover_text} <span class="date"></span></div>
</div>
<!--End Hover Details-->
{*{literal}
    
    <script type="text/javascript">
        var order_id = $order_id;
        
    </script>
{/literal} *}
</div>
<!--End Status Bar-->
</div>

</div>



</div>

<!--Child Product-->
{if $bazooka.m_product=='1'}
    <ul id="{$order_id}_box_childproducts" class="box_childproducts" >  
    {foreach from=$bazooka.product item="product_details"}
        

<li>
    <div class="productStatus">

<div class="aside_left">
<!--<a href="#" class="image"><img src="images/detailed/76/ot-890_d1.png" /></a>-->
<a href="{"products.view?product_id=`$product_details.product_id`"|fn_url}" class="image"><img src="{$product_details.image_url}" height="100" width="100"/></a>
</div>

<div class="aside_right">

<div class="heading">{$product_details.product_name}</div>
<div class="otherDetails">
<span><label>Order ID : </label>{$order_id}</span>
<span><label>Qty : </label> {$product_details.quantity}</span>
</div>
</div>

</div>
</li>

{/foreach}
</ul>
{/if}
{/foreach}
{/if}

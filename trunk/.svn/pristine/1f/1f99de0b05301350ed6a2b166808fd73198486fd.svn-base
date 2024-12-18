{** block-description:recently_viewed_hr_block_homepage **}

{if $controller == "index"}
{if !$config.mobile_perf_optimization}
<div class="ml_pageheaderCateogry_rvi">
    <h1 class="ml_pageheaderCateogry_heading" style="width:810px;">
        <a id="desc">{$lang.recently_viewed_homepage}</a>
    </h1>
</div>
<div class="bs_cntnr" id="bs_cntnr_rvi" style="width:1000px;">
    <ul class="bs_cntnr_ovr_blk jcarousel-skin-tango" id="rvi_hr">
    </ul>
</div>


{literal}
<script>
    function addCarousel_rvi(){
        var sliderWidth = $("#rvi_hr .bs_cntnr_item_hp").outerWidth(true);
        var slider = $('#bs_cntnr_rvi');
        var container = $('#rvi_hr');
        var sliderCount = $('.bs_cntnr_prd_name', slider).length;
        var delta = sliderCount * sliderWidth - slider.width();
        container.width((sliderCount * sliderWidth + 10));
        delta = (-1)*delta;
        var initialValue = container.position().left;

        if(container.position().left> delta+initialValue){
            if($('#bs_cntnr_rvi .jcarousel-next').hasClass("jcarousel-next-disabled jcarousel-next-disabled-horizontal")){
                $('#bs_cntnr_rvi .jcarousel-next').removeClass("jcarousel-next-disabled jcarousel-next-disabled-horizontal");
            }
        }
        $('#bs_cntnr_rvi .jcarousel-prev').click(function () {
            if(container.position().left< initialValue){
                container.animate({left: '+='+sliderWidth}, 100, function(){
                    if(container.position().left>= initialValue){
                        if(!$('#bs_cntnr_rvi .jcarousel-prev').hasClass("jcarousel-prev-disabled jcarousel-prev-disabled-horizontal")){
                            $('#bs_cntnr_rvi .jcarousel-prev').addClass("jcarousel-prev-disabled jcarousel-prev-disabled-horizontal");
                        }
                    }
                    if(container.position().left> delta+initialValue){
                        if($('#bs_cntnr_rvi .jcarousel-next').hasClass("jcarousel-next-disabled jcarousel-next-disabled-horizontal")){
                            $('#bs_cntnr_rvi .jcarousel-next').removeClass("jcarousel-next-disabled jcarousel-next-disabled-horizontal");
                        }
                    }
                });
            }

            //
        });

        $('#bs_cntnr_rvi .jcarousel-next').click(function () {
            if(container.position().left> delta+initialValue){
                container.animate({left: '-='+sliderWidth}, 100, function(){
                    if(container.position().left< delta+initialValue){
                        if(!$('#bs_cntnr_rvi .jcarousel-next').hasClass("jcarousel-next-disabled jcarousel-next-disabled-horizontal")){
                            $('#bs_cntnr_rvi .jcarousel-next').addClass("jcarousel-next-disabled jcarousel-next-disabled-horizontal");
                        }
                    }
                    if(container.position().left< initialValue){
                        if($('#bs_cntnr_rvi .jcarousel-prev').hasClass("jcarousel-prev-disabled jcarousel-prev-disabled-horizontal")){
                            $('#bs_cntnr_rvi .jcarousel-prev').removeClass("jcarousel-prev-disabled jcarousel-prev-disabled-horizontal");
                        }
                    }
                });
            }

            //
        });
    }


    $(document).ready(function(){

        var limit = {/literal}{$config.TM_limit}{literal};
        var url = "http://api.targetingmantra.com/TMWidgets?w=rvi&mid=130915&limit="+limit +"&json=true&callback=?";
        jQuery.getJSON(url,function(data){


            if(!data.rvi || !data.rvi.viewedItems || data.rvi.viewedItems.length == 0){
                $(".ml_pageheaderCateogry_rvi").hide();
                $("#bs_cntnr_rvi").hide();
            }
            else{
                var widgetTitle = data.rvi.widgetTitle;
                $(".ml_pageheaderCateogry_rvi").show();
                $("#bs_cntnr_rvi").show();
                data.rvi.viewedItems.forEach(function(obj){
                    var img = document.createElement("img");
                    img.src = obj.itemImage;
                    img.setAttribute("width","160");
                    img.setAttribute("height","160");
                    img = img.outerHTML;
                    var title = function(){if(obj.itemTitle.length >50){return obj.itemTitle.substr(0,46) + "...";}else{return obj.itemTitle;}};
                    var item = "<li class='bs_cntnr_item_hp'><a class='bs_cntnr_prd_name bs_cntnr_prd_name_hp' href='"+ obj.itemURL +"'><div class='bs_cntnr_img'>"+img+"</div>" + title() +"</a>"
                            +"<div class='bs_cntnr_prc_blk'><div class='bs_main_price bs_main_price_hp'>Rs. " + parseFloat(obj.itemPrice).formatMoney(0,'.',',') +"</div></div></li>";
                    $("#rvi_hr").append(item);
                });
                //
                $('#rvi_hr').jcarousel();
                addCarousel_rvi();
            }

        });
    });
</script>
{/literal}
{else}
{/if}

{else}
<div class="ml_pageheaderCateogry_rvi">
    <h1 class="ml_pageheaderCateogry_heading" style="width:810px;">
        <a id="desc">{$lang.recently_viewed_homepage}</a>
    </h1>
</div>
<div class="bs_cntnr" id="bs_cntnr_rvi" style="width:1000px;">
    <ul class="bs_cntnr_ovr_blk jcarousel-skin-tango" id="rvi_hr">
    </ul>
</div>


{literal}
<script>
    function addCarousel_rvi(){
        var sliderWidth = $("#rvi_hr .bs_cntnr_item_hp").outerWidth(true);
        var slider = $('#bs_cntnr_rvi');
        var container = $('#rvi_hr');
        var sliderCount = $('.bs_cntnr_prd_name', slider).length;
        var delta = sliderCount * sliderWidth - slider.width();
        container.width((sliderCount * sliderWidth + 10));
        delta = (-1)*delta;
        var initialValue = container.position().left;

        if(container.position().left> delta+initialValue){
            if($('#bs_cntnr_rvi .jcarousel-next').hasClass("jcarousel-next-disabled jcarousel-next-disabled-horizontal")){
                $('#bs_cntnr_rvi .jcarousel-next').removeClass("jcarousel-next-disabled jcarousel-next-disabled-horizontal");
            }
        }
        $('#bs_cntnr_rvi .jcarousel-prev').click(function () {
            if(container.position().left< initialValue){
                container.animate({left: '+='+sliderWidth}, 100, function(){
                    if(container.position().left>= initialValue){
                        if(!$('#bs_cntnr_rvi .jcarousel-prev').hasClass("jcarousel-prev-disabled jcarousel-prev-disabled-horizontal")){
                            $('#bs_cntnr_rvi .jcarousel-prev').addClass("jcarousel-prev-disabled jcarousel-prev-disabled-horizontal");
                        }
                    }
                    if(container.position().left> delta+initialValue){
                        if($('#bs_cntnr_rvi .jcarousel-next').hasClass("jcarousel-next-disabled jcarousel-next-disabled-horizontal")){
                            $('#bs_cntnr_rvi .jcarousel-next').removeClass("jcarousel-next-disabled jcarousel-next-disabled-horizontal");
                        }
                    }
                });
            }

            //
        });

        $('#bs_cntnr_rvi .jcarousel-next').click(function () {
            if(container.position().left> delta+initialValue){
                container.animate({left: '-='+sliderWidth}, 100, function(){
                    if(container.position().left< delta+initialValue){
                        if(!$('#bs_cntnr_rvi .jcarousel-next').hasClass("jcarousel-next-disabled jcarousel-next-disabled-horizontal")){
                            $('#bs_cntnr_rvi .jcarousel-next').addClass("jcarousel-next-disabled jcarousel-next-disabled-horizontal");
                        }
                    }
                    if(container.position().left< initialValue){
                        if($('#bs_cntnr_rvi .jcarousel-prev').hasClass("jcarousel-prev-disabled jcarousel-prev-disabled-horizontal")){
                            $('#bs_cntnr_rvi .jcarousel-prev').removeClass("jcarousel-prev-disabled jcarousel-prev-disabled-horizontal");
                        }
                    }
                });
            }

            //
        });
    }


    $(document).ready(function(){

        var limit = {/literal}{$config.TM_limit}{literal};
        var url = "http://api.targetingmantra.com/TMWidgets?w=rvi&mid=130915&limit="+limit +"&json=true&callback=?";
        jQuery.getJSON(url,function(data){


            if(!data.rvi || !data.rvi.viewedItems || data.rvi.viewedItems.length == 0){
                $(".ml_pageheaderCateogry_rvi").hide();
                $("#bs_cntnr_rvi").hide();
            }
            else{
                var widgetTitle = data.rvi.widgetTitle;
                $(".ml_pageheaderCateogry_rvi").show();
                $("#bs_cntnr_rvi").show();
                data.rvi.viewedItems.forEach(function(obj){
                    var img = document.createElement("img");
                    img.src = obj.itemImage;
                    img.setAttribute("width","160");
                    img.setAttribute("height","160");
                    img = img.outerHTML;
                    var title = function(){if(obj.itemTitle.length >50){return obj.itemTitle.substr(0,46) + "...";}else{return obj.itemTitle;}};
                    var item = "<li class='bs_cntnr_item_hp'><a class='bs_cntnr_prd_name bs_cntnr_prd_name_hp' href='"+ obj.itemURL +"'><div class='bs_cntnr_img'>"+img+"</div>" + title() +"</a>"
                            +"<div class='bs_cntnr_prc_blk'><div class='bs_main_price bs_main_price_hp'>Rs. " + parseFloat(obj.itemPrice).formatMoney(0,'.',',') +"</div></div></li>";
                    $("#rvi_hr").append(item);
                });
                //
                $('#rvi_hr').jcarousel();
                addCarousel_rvi();
            }

        });
    });
</script>
{/literal}
{/if}
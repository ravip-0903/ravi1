{* $Id: show.tpl 2014-04-28 $  Show scrapbook images*}

{literal}
    <script>
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId={/literal}{$config.shopclues_app_id}{literal}";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
{/literal}

{if !$config.isResponsive && $config.enable_scrapbook}
    <div class="all_scrapbook">
        <div id="page_1">
{foreach from=$scrapbook_data item="scrapbook"}
<div class="box_metacategory box_GridProduct inner_scrapbook" >
<div  class="box_metacategory_image ">
          <img class="" id="det_img_2618652" src="{$config.cdn_scrapbook_url}{$scrapbook.image_name}" width="320" border="0" alt="scrapbook image" title="scrapbook image">
</div>
 <div class="bottom_pannel">
     <div class="fb_content_value">
         <div><span>{$scrapbook.fb_like}</span>  Likes</div>
         <div></div>
         <div><span>{$scrapbook.fb_share}</span>  Share</div>
    </div>
     <div class="fb_button">
<div id="fb-root"></div>

<div class="fb-like" data-href="{$config.cdn_scrapbook_url}{$scrapbook.image_name}" data-layout="button" data-action="like" data-show-faces="true" data-share="true"></div>
    </div>
 </div>
</div>
{/foreach}
            <div class="topBar"><span class="pageNum">Page 2</span><span class="loader"><img src="{$config.ext_images_host}/images/skin/ajax-loader.gif"><span>Loading more results.....</span></span></div>
            </div>

</div>
{literal}

    <script type="text/javascript">
        var count=1;
        var throwAjax = true;

        function getScrapbooks(){

            count = count+1;
            throwAjax=false;

            var data = "dispatch=scrapbook.show&scrapbook_ajax=1&page="+ count;
            $.ajax({
                url:"index.php",
                data:data,
                success:function(data){
                    var scrapbooks = JSON.parse(data);
                    if(scrapbooks.length >0){
                        var page = '<div id="page_'+count+'"></div>';
                        $(".all_scrapbook").append(page);
                        scrapbooks.forEach(function(scrapbook){
                            var book = '<div class="box_metacategory box_GridProduct inner_scrapbook" ><div class="box_metacategory_image "><img id="det_img_2618652" src="{/literal}{$config.cdn_scrapbook_url}{literal}'+scrapbook.image_name+'" width="320" border="0" alt="scrapbook image" title="scrapbook image"></div><div class="bottom_pannel"><div class="fb_content_value"><div><span>'+scrapbook.fb_like+'</span>  Likes</div><div></div><div><span>'+scrapbook.fb_share+'</span>  Share</div></div><div class="fb_button"><div id="fb-root"></div><div class="fb-like" data-href="{/literal}{$config.cdn_scrapbook_url}{literal}'+scrapbook.image_name+'" data-layout="button" data-action="like" data-show-faces="true" data-share="true"></div></div></div></div>';
                            $(".all_scrapbook #page_" + count).append(book);
                        });
                        $(".all_scrapbook #page_" + count).append('<div class="topBar"><span class="pageNum"> Page '+(count+1)+'</span><span class="loader"><img src="{/literal}{$config.ext_images_host}{literal}/images/skin/ajax-loader.gif"><span>Loading more results.....</span></span></div>');
                        throwAjax=true;
                        try{
                            FB.XFBML.parse();
                        }catch(ex){}
                    }
                    else{
                        $("#page_" + (count-1) + " .topBar").hide();
                        throwAjax=false;
                    }
                }
            });

        }

        $(window).scroll(function(){
            if($(window).scrollTop()> $(document).height()/2 && throwAjax){
                getScrapbooks();
            }
        });

    </script>
{/literal}

{else}
    This feature is no longer available
 {/if}

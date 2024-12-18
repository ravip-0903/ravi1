{* $Id: view.tpl 2014-04-25 $ *}
{if !$config.isResponsive && $config.enable_scrapbook}
    <div class="ie-msg">{$lang.ie_message}</div>
    <div>{$lang.scrapbook_instructions}</div>
    <div class="main_scrapbook_part"style="min-height: 500px;">
        <div class="float-left">
            <div class="scrapbook_url">
                <a href="{$config.fashion_week_url}">{$lang.fashion_week}</a>
                <a class="right" href="{'index.php?dispatch=scrapbook.show'|fn_url}">{$lang.all_created_scrapbooks}</a>
            </div>
            <div id=scrapPad>
                <div class="textEditor">
                    <label for="te-inp-text" style="margin-left:5px">Text:</label>
                    <input type="text" class="inp-text" value="Your text here...">
                    <label for="te-font-size" style="margin-left:5px">Size:</label>
                    <input type="text" class="font-size" value="14">
                    <label for="te-font-size" style="margin-left:5px">Color:</label>
                    <div class="text-color-option">
                        <input type="text" class="color" style="display: none;"/>
                    </div>
                    <label for="te-font-family" style="margin-left:5px">Font:</label>
                    <select id="font-family">
                      <option value="'Helvetica Neue'">Helvetica</option>
                      <option value="courier">Courier</option>
                      <option value="Georgia">Georgia</option>
                      <option value="Arial">Arial</option>
                    </select>
                    <span class="close-editor">x</span>
                </div>
                <div id="scrapbookContainer" class="container selectDisable">
                    <div class="sb-logo">
                        <img src="{$config.scrapbook_logo}">
                    </div>
                    <div class="author"></div>
                </div>
                <div class="action_btns">
                    <div class="button save"><span></span><label>Save</label></div>
                    <div class="button share"><span><div id="fb-root"></div>{literal}
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId={/literal}{$config.shopclues_app_id}{literal}";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>{/literal}</span><label>Share</label></div>
                    <div class="button text"><span></span><label>Add <br>Text</label></div>
                    <div class="button clear"><span></span><label>Clear</label></div>
                </div>

                <div class="color-panel"><input type="text" class="color" style="display: none;"/>Background Color</div>
            </div>
        </div>

        <div class="float-right">
            <div class="sb_cat_blk">
                <div class="sb_cat active" id="home">Home</div>
            </div>
            <div class="sb_cat_box">
                <h3 class="select_cat">Select Category</h3>
                <div class="cat_block" id="home_panel" >
                    {foreach from=$cat_data item="category"}
                        <div class="all_category" >
                            <a class="category_image" id="{$category.category_id}" title="{$category.category}" itemid="{$category.id}">
                                <div class="sb_img">
                                    <img src="{$category.icon_path}"  />
                                </div>
                                <div class="sb_cat_name">{$category.category}</div>
                            </a>
                        </div>
                    {/foreach}
                </div>
            </div>
        </div>


    </div>
    {literal}
        <script type="text/javascript">
        var IE_Version = (function() {
            if (document.documentMode) {
                return document.documentMode;
            } else {
                for (var i = 7; i > 0; i--) {
                    var div = document.createElement("div");

                    div.innerHTML = "<!--[if IE " + i + "]><span></span><![endif]-->";

                    if (div.getElementsByTagName("span").length) {
                        return i;
                    }
                }
            }

            return undefined;
            })();

        var  cat_image_json = JSON.parse({/literal}'{$cat_image_data|json_encode}'{literal});
        var $presentSelectedTextArea;

    $(document).ready(function(){
        if(IE_Version<9){$(".ie-msg").show();}
        $('.category_image').bind('click',makenewtab);
        $('.sb_cat').bind('click',movetab);
        // handling all scrapbook action button actions here starts

        // save
        $("#scrapPad .action_btns .save").click(function(){
            trimAllHelpers();
            if($("#scrapPad .container .sb_img").length >0 && ($("#scrapbookContainer .sb_img").length - $("#scrapbookContainer .textArea").length) >=3){
                html2canvas(document.getElementById("scrapbookContainer"), {
                    useCORS:true,
                    onrendered: function(canvas) {
                        var dataURL = canvas.toDataURL();
                        //send image to server
                        $.ajax({
                            type: "POST",
                            url: "index.php?dispatch=scrapbook.upload_image",
                            data: {
                            image: dataURL
                        }
                        }).done(function(o) {
                            alert("ScrapBook Saved Successfully!");
                        });
                    }
                });
            }
            else{
                alert("{/literal}{$lang.scrapbook_limit_alert}{literal}");
            }
        });

        // share
        $("#scrapPad .action_btns .share").click(function(){
        trimAllHelpers();
        if($("#scrapPad .container .sb_img").length >0 && ($("#scrapbookContainer .sb_img").length - $("#scrapbookContainer .textArea").length) >=3){
            html2canvas(document.getElementById("scrapbookContainer"), {
                useCORS:true,
                onrendered: function(canvas) {
                    var dataURL = canvas.toDataURL();
                    //send image to server
                    $.ajax({
                        type: "POST",
                        url: "index.php?dispatch=scrapbook.share_image",
                        data: {
                            image: dataURL
                        }
                    }).done(function(o) {
                        FB.ui({
                            method: 'feed',
                            name: '{/literal}{$lang.share_scrapbook}{literal}',
                            link: '{/literal}{$config.cdn_scrapbook_url}{literal}'+o,
                            picture: '',
                            caption: '',
                            description: ''
                        },
                        function(response) {

                        });
                    });
                }
            });
            }
            else{
                alert("{/literal}{$lang.scrapbook_limit_alert}{literal}");
            }
        });

        // Add Text
        $("#scrapPad .action_btns .text").click(function(){
            var textArea = "<div class='sb_img textArea'><span class='txt'>'Your text here...'</span></div>";
            putInScrapbook($(textArea), $("#scrapPad .container"),false);
        });

        // Clear
    $("#scrapPad .action_btns .clear").click(function(){
        var r=confirm("{/literal}{$lang.scrapbook_clear_message}{literal}");
        if (r==true)
          {
            $("#scrapPad .container .sb_img").remove();
          }
    });
    // handling all scrapbook action button actions here ends
    var un = ReadCookie('scun');
    if(un != ""){
        un = JSON.parse(window.atob(un));
        un = un.replace('+',' ');
        $('.author').html('By '+un);
    }
    else
        {
            $('.author').html('By Guest');
        } 
    });
    
    function OnColorChanged(selectedColor, colorPickerIndex) {
        if(colorPickerIndex == 0){
            $presentSelectedTextArea.css("color", selectedColor);
        }
        else{
            $("#scrapPad .container").css("background-color", selectedColor);
        }
    }

    function rotateImage($rotateTarget){
    var dragging = false;
    var target = $rotateTarget.find(".rotate_image");
    var mainTarget = $rotateTarget;
    var rad = mainTarget.width()/2;
    var elOfs = mainTarget.offset();
    var elPos = {
    x: elOfs.left,
    y: elOfs.top
    };
    target.mousedown(function() {
    dragging = true;
    });
    $(document).mouseup(function() {
    dragging = false;
    });
    $(document).mousemove(function(e) {
    var mPos = {
    x: e.pageX-elPos.x,
    y: e.pageY-elPos.y
    };
    var getAtan = Math.atan2(mPos.x-rad, mPos.y-rad);
    var getDeg = -getAtan/(Math.PI/180) + 135;  //135 = (180deg-45deg)

    if (dragging) {
    mainTarget.css({transform: 'rotate(' + getDeg + 'deg)'});
    mainTarget.css({'-ms-transform': 'rotate(' + getDeg + 'deg)'});
    mainTarget.css({'-webkit-transform': 'rotate(' + getDeg + 'deg)'});
    }
    });
    }

    function trimAllHelpers(){
        $("#scrapbookContainer .sb_img").find(".ui-resizable-handle").hide();
        $("#scrapbookContainer .sb_img").find(".rotate_image").hide();
    }

    function putInScrapbook($item, $container,maintainAspectRatio){
    var $newItem = $item.clone();
    //var itemMargin = parseFloat($item.css("marginLeft").replace('px', '')) + parseFloat($item.css("marginRight").replace('px', ''));
    //var maxWidth = $container.width() - itemMargin;
    $container.append($newItem);
    if(maintainAspectRatio){
        $newItem.resizable({ aspectRatio:true,maxWidth:$container.width()-27,handles: "ne, se, sw, nw" }).draggable({containment: $container, scroll: true});
    }
    else{
        $newItem.resizable({ maxWidth:$container.width()-27,handles: "ne, se, sw, nw" }).draggable({containment: $container, scroll: true});
    }
    $newItem.append("<span class='rotate_image'></span>");
    $newItem.append("<span class='close_image'></span>");
    $newItem.addClass('selectDisable');
    $newItem.mousedown(function(){
        $container.find(".sb_img").css("z-index",6);
        $(this).css("z-index",10);
    });
    $newItem.hover(function(){
        $(this).addClass("addBorder");
        $(this).find(".ui-resizable-handle").show();
        $(this).find(".rotate_image").show();
        $(this).find(".close_image").show();
    },function() {
        $(this).removeClass("addBorder");
        $(this).find(".ui-resizable-handle").hide();
        $(this).find(".close_image").hide();
    });
    $newItem.find(".rotate_image").mousedown(function(event){
        rotateImage($newItem);
        event.stopPropagation();
    });
    $newItem.find(".close_image").click(function(event){
        $newItem.remove();
    });
    if($newItem.hasClass("textArea")){
        $newItem.click(function(){
            $(".textEditor").show();
            $(".textEditor .close-editor").click(function(){
                $(".textEditor").hide();
            });
            $presentSelectedTextArea = $(this);
            $(".textEditor .inp-text").keyup(function(){
                $presentSelectedTextArea.find(".txt").html($(this).val());
            });
            $(".textEditor .font-size").keyup(function(){
                $presentSelectedTextArea.find(".txt").css("font-size", $(this).val() + "px");
            });
            $("#font-family").change(function(){
                $presentSelectedTextArea.find(".txt").css("font-family", $(this).val());
            })
        });
    }

    }

    function makenewtab()
    {
    var id = $(this).attr('itemid');
    var active = "#tab"+id;
    if($(active).length>0)
    {
    $(active).css('display','inline-block');
    $(".sb_cat_block").css('display','none');
    $(".cat_block").css('display','none');
    $(".select_cat").css('display','none');
    $(".sb_cat").removeClass("active");
    var activepanel = active+"_panel";
    $(activepanel).css('display','block');
    $(active).addClass("active");
    }
    else
    {
    var title_tab = "<div class='sb_cat' id='tab"+id+"'>"+this.title+"<span class='close_tab'> x </span></div>";
    $(".sb_cat_blk").append(title_tab);
    var tab_data = "<div class='sb_cat_block' id='tab"+id+"_panel' >";

    cat_image_json[this.id].forEach(function(index){

    tab_data +="<div class='all_category sub_images' ><a class='category_image'><div class='sb_img'><img src='"+index+"'  /></div></a></div>";

    });
    tab_data +="</div>";
    $(".sb_cat_box").append(tab_data);
    $( ".sb_img", ".sb_cat_block" ).draggable({
    cancel: "a.ui-icon", // clicking an icon won't initiate dragging
    revert: "invalid", // when not dropped, the item will revert back to its initial position
    containment: "document",
    helper: "clone",
    cursor: "move"
    });
    $("#scrapPad .container").droppable({
    accept: ".sb_cat_block .sb_img",
    activeClass: "ui-state-highlight",
    drop: function( event, ui ) {
    putInScrapbook(ui.draggable, $("#scrapPad .container"),true);
    }
    });

    $("#home").removeClass("active");
    $("#home_panel").css('display','none');
    $(".select_cat").css('display','none');
    $(active).addClass("active");
    $(active).bind('click',movetab);
    $('.close_tab').bind('click',closetab);
    }
    }

    function movetab()
    {
    if(this.id == "home")
    {
    $(".select_cat").css('display','block');
    $(".cat_block").css('display','block');
    }
    else
    {
    $(".select_cat").css('display','none');
    $(".cat_block").css('display','none');
    }

    $(".sb_cat_block").css('display','none');
    $(".sb_cat").removeClass("active");
    var active = "#"+this.id+"_panel";
    $(active).css('display','block');
    $(this).addClass("active");

    }

    function closetab(event)
    {

    if($(this).parent().attr('id') ==$('.active').attr('id') )
    {
    $(".sb_cat_block").css('display','none');
    $(".sb_cat").removeClass("active");
    var tab = '#'+$(this).parent().attr('id');
    var panel = tab+'_panel';
    $(tab).css('display','none');
    $(panel).css('display','none');
    $(".select_cat").css('display','block');
    $('#home').css('display','inline-block');
    $('#home_panel').css('display','block');
    $('#home').addClass("active");
    }
    else
    {
    var tab = '#'+$(this).parent().attr('id');
    var panel = tab+'_panel';
    $(tab).css('display','none');
    $(panel).css('display','none');
    }
    event.stopPropagation();
    }
    
    
    window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
      appId      : '{/literal}{$config.shopclues_app_id}{literal}', // App ID from the App Dashboard
      channelUrl : '//'+window.location.hostname+'/', // Channel File for x-domain communication
      status     : true, // check the login status upon init?
      cookie     : true, // set sessions cookies to allow your server to access the session?
      xfbml      : true  // parse XFBML tags on this page?
    });

	FB.Event.subscribe('edge.create',function(response) {
		//download();
	});
			
	FB.Event.subscribe('auth.authResponseChange', function(response) {

    });
			
			
  };
  
  // Load the SDK's source Asynchronously
  (function(d, debug){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all" + (debug ? "/debug" : "") + ".js";
     ref.parentNode.insertBefore(js, ref);
   }(document, /*debug*/ false));
    
     </script>
{/literal}
{else}
    This feature is no longer available
{/if}

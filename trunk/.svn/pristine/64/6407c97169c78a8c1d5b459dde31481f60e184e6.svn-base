{* $Id: stars.tpl 9910 2010-06-30 08:22:42Z angel $ *}

<div class="nowrap stars" style="margin:auto; width:83px; text-align:center; padding:0px;">

{if $controller == "products" && $mode == "view"}
<a onclick="$('#block_discussion').click(); return false;">
{/if}

{section name="full_star" loop=$stars.full}
<div style="background:url(images/skin/sprite_jpeg_icon.jpg) -216px -0px;" class="sprite_new_star_icon_home_page" ></div>
{/section}


    {if $stars.part==1}
    <div style="background:url(images/skin/sprite_jpeg_icon.jpg) -153px -0px;" class="sprite_new_star_icon_home_page"></div>
    {elseif $stars.part==2}
    <div style="background:url(images/skin/sprite_jpeg_icon.jpg) -174px -0px;" class="sprite_new_star_icon_home_page" ></div>
    {elseif $stars.part==3}
    <div style="background:url(images/skin/sprite_jpeg_icon.jpg) -195px -0px;" class="sprite_new_star_icon_home_page" ></div>
    {elseif $stars.part==4}
    <div style="background:url(images/skin/sprite_jpeg_icon.jpg) -132px -0px;" class="sprite_new_star_icon_home_page" ></div>
    {/if}

{section name="full_star" loop=$stars.empty}
<div style="background:url(images/skin/sprite_jpeg_icon.jpg) -112px -0px;" class="sprite_new_star_icon_home_page" ></div>
{/section}

{if $controller == "products" && $mode == "view"}
</a>
{/if}

</div>
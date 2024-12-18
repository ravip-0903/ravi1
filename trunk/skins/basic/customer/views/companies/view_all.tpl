{* $Id: view_all.tpl*}
{literal}
<style>
.dir_nl_aj_db{font:11px/14px Verdana, Geneva, sans-serif;}
.dir_nl_aj_db td{ vertical-align:top;}
.dir_nl_heading{background:url(images/skin/directory_bg_new.jpg) repeat-x; height:38px; font:bold 14px/38px Arial, Helvetica, sans-serif ; padding-left: 15px; width:250px; border:1px solid #ccc; color:#323232; }
.dr_nl_bg_points{list-style:none; margin:4px 0 4px 15px; position:relative; z-index:1; color:#fff; font-size:10px;}
.dr_nl_bg_points a{font-size:11px;}
.dr_nl_bg_point a:hover{text-decoration:underline;}
.dr_nl_bg_point{background:url(images/skin/directory_arrow.png)  0 1px no-repeat; padding:0 0 0 15px; margin:8px 0 0 4px }
.dr_nl_bg_points .points{background:#777; border-radius:10px; position:absolute; left:-26px; top:0px; z-index:-1; width:20px; height:20px;}
.dr_nl_ul_list{margin:0; padding:0; float:left; border:1px solid #ccc; border-top:0; width:265px; border-radius: 5px; border-top-left-radius: 0; border-top-right-radius: 0; border-bottom-left-radius:0; border-bottom-right-radius:0; -moz-border-radius: 5px; -moz-border-top-left-radius: 0; -moz-border-top-right-radius: 0;}
.dir_nl_aj_db .view_all{float:right; margin-right:5px;}
.dir_nl_aj_db .view_all:hover{text-decoration:underline;}
.view_all_companies{float:right;background: url(images/skin/directory_arrow.png) 0 0px no-repeat; padding: 0 0 5px 16px;}
.view_all_companies:hover{text-decoration:underline;}

</style>
{/literal}
<h1 class="mainbox-title ">{$lang.directory_heading|unescape}</h1>

{foreach from=$result item="company" key="k"}
<table class="dir_nl_aj_db" cellpadding="2" cellspacing="2" border="0">
<tr>
	<td><span style="font:18px trebuchet ms; color:#000;">{$k}</span></td>
</tr>

<tr >
<td valign="top" colspan="3">
 
  <ul class="dr_nl_ul_list all_comp" id="{$root_categories.$k.category_id}_all_seller" style="width: 799px; margin: 0 0 0; border: 1px solid #CCC; border-bottom:none ;padding: 0 0 0px 5px;">
  {assign var="count" value=0}
	{foreach from=$company item="companies_all" name="companies_all" key="k"}
    {if $count%3==0 && $count!=0}
      </ul>
      <ul class="dr_nl_ul_list all_comp" id="{$companies_all.company_id}_all_seller" style="width: 799px; margin: 0 0 0; border: 1px solid #CCC; border-top:none !important; border-bottom:none ;padding: 0 0 0px 5px;">
     {assign var="count" value=0} 
    {/if}
		<li style="float:left;  font-size:11px;{if $count==0 || $count==1} width:31%;{else} width:27%; {/if}" class="dr_nl_bg_point" ><a href="{"index.php?dispatch=companies.view&company_id=`$companies_all.company_id`"|fn_url}" {if $companies_all.is_trm == 'Y'} style="float:left; margin-top:-1px"{/if}>{if $count==2 && $companies_all.company|strlen > 27}{$companies_all.company|substr:0:24}...{elseif ($count==1 ||$count==0 ) && $companies_all.company|strlen > 40}{$companies_all.company|substr:0:35}...{else}{$companies_all.company}{/if}</a>
        {if $companies_all.is_trm == 'Y'}
         <img width="15" src="{$addons.sdeep.trm_icon_url}" alt="Top Rated Merchant" title="Top Rated Merchant" style="float:left; margin-left:5px">
		{/if}
        </li>
     {assign var="count" value=$count+1}
   {/foreach}
  </ul> 
</td>
</tr>
</table>
{/foreach}

{literal}
<script type="text/javascript">
$('.dir_nl_aj_db td ul:last-child').css({'border-bottom-left-radius':'5px','border-bottom-right-radius':'5px','border-bottom': '1px solid #CCC','padding-bottom':'10px','margin-bottom':'15px'});
		/*$(document).ready(function(){
			$('#scrollbar1').tinyscrollbar();	
		});*/
		
	</script>	
{/literal}
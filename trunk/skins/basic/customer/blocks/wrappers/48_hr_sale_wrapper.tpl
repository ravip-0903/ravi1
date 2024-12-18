{* $Id: sidebox_general.tpl 11786 2011-02-08 09:44:48Z 2tl $ *}
<div>
 {$content|default:"&nbsp;"}
</div>
{if !$config.isResponsive}
{literal}	
<script type="text/javascript">
	//<![CDATA[	
	//$(document).ready(function() {$ldelim}
		// create a new date and insert it
		var EndDate = new Date({/literal}{$lang.48hrsale_end_datetime}{literal});
		//$.countdown('#timer', EndDate);
		$.countdown('#timer3', EndDate);
		$('#categ_descr').html('<a href="{"index.php?dispatch=categories.view&category_id=`$lang.48hrsale_category_id`"|fn_url}" class="catname">{$catg.description|truncate:40:"---"}</a>');
		var url='{"index.php?dispatch=categories.view&category_id=`$lang.48hrsale_category_id`"|fn_url}';
		url=url.replace(/&amp;/g, '&');
		$('#48_hr_url').attr("href",url);
	//{$rdelim});	
	//]]>
</script>
{/literal}
{/if}
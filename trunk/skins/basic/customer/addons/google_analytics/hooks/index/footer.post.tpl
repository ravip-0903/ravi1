{* $Id: footer.post.tpl 11786 2011-02-08 09:44:48Z 2tl $ *}

<script type="text/javascript">
//<![CDATA[
	var _gaq = _gaq || [];
	_gaq.push(["_setAccount", "{$addons.google_analytics.tracking_code}"]);
	_gaq.push(["_trackPageview"]);
	
	(function() {$ldelim}
		var ga = document.createElement("script");
		{if $config.google_analytics_new_code == 1}
		ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + "stats.g.doubleclick.net/dc.js";
		{else}
		ga.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";
		{/if}
		ga.setAttribute("async", "true");
		document.documentElement.firstChild.appendChild(ga);
	{$rdelim})();
//]]>
</script>
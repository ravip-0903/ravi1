{* $Id: one_page.tpl 12143 2011-03-31 12:02:30Z subkey $ *}
<div class="search-result">
	<span>{$page.result_number}.</span> <a href="{"pages.update?page_id=`$page.page_id`"|fn_url}">{$page.page|unescape}</a>
	{assign var="more_link" value="pages.update?page_id=`$page.page_id`"|fn_url}
	<p>{$page.description|unescape|strip_tags|truncate:280:"<a href=\"`$more_link`\" >`$lang.more_link`</a>"}</p>
</div>
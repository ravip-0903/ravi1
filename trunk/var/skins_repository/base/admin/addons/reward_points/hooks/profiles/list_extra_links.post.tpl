{* $Id: list_extra_links.post.tpl 11786 2011-02-08 09:44:48Z 2tl $ *}

{if $user.user_type == "C"}
	<li><a href="{"reward_points.userlog?user_id=`$user.user_id`"|fn_url}">{$lang.points} ({if $user.points}{$user.points|@unserialize}{else}0{/if})</a></li>
{/if}
{* $Id: update.tpl 12544 2011-05-27 10:34:19Z bimib $ *}

{include file="views/profiles/components/profiles_scripts.tpl"}

{* capture name="tabsbox" *}
<div class="box_headerTwo">
<h1 class="box_headingTwo">{$lang.my_profile}</h1>
</div>
<div class="clearboth height_ten"></div>
	<div id="content_general">
		<form name="profile_form" action="{""|fn_url}" method="post">
		<input id="selected_section" type="hidden" value="general" name="selected_section"/>
        <input type="hidden" name="token" value="{$smarty.session.form_token_value}"/>
		<input id="default_card_id" type="hidden" value="" name="default_cc"/>
		<input type="hidden" name="profile_id" value="" />
		{if $smarty.request.return_url != ''}
		<input type="hidden" name="return_url" value="{$smarty.request.return_url}" />
		{/if}
		{capture name="group"}
		{include file="views/profiles/components/profiles_account.tpl"}
                <input type="hidden" name="copy_address" value="" />


		{if $mode == "add" && $settings.Image_verification.use_for_register == "Y"}
			{include file="common_templates/image_verification.tpl" id="register" align="center"}
		{/if}

		{/capture}
		{include file="common_templates/group.tpl" content=$smarty.capture.group}
        <div>
        <div class="clear"></div>
        </div>
        <div class="clearboth"></div>
        <div>
        <span class="float_left margin_left_fifteen">{$lang.text_mandatory_fields}</span>
		<div class="buttons-container center" style="margin-top:10px;">
			{if $action}
				{assign var="_action" value="$action"}
			{/if}
			{if $mode == "update"}
				{include file="buttons/save.tpl" but_name="dispatch[profiles.update.$_action]" but_id="save_profile_but"}
			{else}
				{include file="buttons/register_profile.tpl" but_name="dispatch[profiles.add.$_action]"}
			{/if}
		</div>
        </div>
		</form>
	</div>
	
	{if $mode == "update"}
	{if $usergroups && $user_data.user_type != "A"}
	<div id="content_usergroups">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" class="table">
		<tr>
			<th width="30%">{$lang.usergroup}</th>
			<th width="30%">{$lang.status}</th>
			{if $settings.General.allow_usergroup_signup == "Y"}
			<th width="40%">{$lang.action}</th>
			{/if}
		</tr>
		{foreach from=$usergroups item=usergroup}
		{if $user_data.usergroups[$usergroup.usergroup_id]}
			{assign var="ug_status" value=$user_data.usergroups[$usergroup.usergroup_id].status}
		{else}
			{assign var="ug_status" value="F"}
		{/if}
		{if $settings.General.allow_usergroup_signup == "Y" || $settings.General.allow_usergroup_signup != "Y" && $ug_status == "A"}
		<tr {cycle values=",class=\"table-row\""}>
			<td>{$usergroup.usergroup}</td>
			<td class="center">
				{if $ug_status == "A"}
					{$lang.active}
					{assign var="_link_text" value=$lang.remove}
				{elseif $ug_status == "F"}
					{$lang.available}
					{assign var="_link_text" value=$lang.join}
				{elseif $ug_status == "D"}
					{$lang.declined}
					{assign var="_link_text" value=$lang.join}
				{elseif $ug_status == "P"}
					{$lang.pending}
					{assign var="_link_text" value=$lang.cancel}
				{/if}
			</td>
			{if $settings.General.allow_usergroup_signup == "Y"}
			<td>
				<a class="cm-ajax" rev="content_usergroups" href="{"profiles.request_usergroup?usergroup_id=`$usergroup.usergroup_id`&amp;status=`$ug_status`"|fn_url}">{$_link_text}</a>
			</td>
			{/if}
		</tr>
		{/if}
		{/foreach}
		<tr class="table-footer">
			<td colspan="{if $settings.General.allow_usergroup_signup == "Y"}3{else}2{/if}">&nbsp;</td>
		</tr>
		</table>
	<!--content_usergroups--></div>
	{/if}
	{if $settings.General.user_store_cc == "Y"}
	<div id="content_credit_cards">
		{include file="views/profiles/components/credit_cards.tpl"}
	</div>
	{/if}
	{/if}
{*/capture*}
{*include file="common_templates/tabsbox.tpl" content=$smarty.capture.tabsbox active_tab=$smarty.request.selected_section track=true*}

{capture name="mainbox_title"}{$lang.profile_details}{/capture}

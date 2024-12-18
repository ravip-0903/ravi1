{* $Id: update.tpl 12544 2011-05-27 10:34:19Z bimib $ *}

{include file="views/profiles/components/profiles_scripts.tpl"}

{* capture name="tabsbox" *}
<div class="box_headerTwo">
<h1 class="box_headingTwo">{$lang.my_address}</h1>
</div>
<div class="clearboth height_ten"></div>
	<div id="content_general">
		<form name="profile_form" action="{""|fn_url}" method="post">
		<input id="selected_section" type="hidden" value="general" name="selected_section"/>
		<input id="default_card_id" type="hidden" value="" name="default_cc"/>
		<input type="hidden" name="profile_id" value="{$user_data.profile_id}" />
		{if $smarty.request.return_url != ''}
		<input type="hidden" name="return_url" value="{$smarty.request.return_url}" />
		{/if}
		{capture name="group"}
		
		{include file="views/profiles/components/addressbook_fields.tpl" section="C" title=$lang.contact_information}

		{if $profile_fields.B || $profile_fields.S}
			{if $settings.General.user_multiple_profiles == "Y" && $mode == "update_addressbook"}
				<!--<p>{$lang.text_multiprofile_notice}</p>-->
				{include file="views/profiles/components/multiple_addressbook.tpl" profile_id=$user_data.profile_id}	
			{/if}
			
			
				{assign var="first_section" value="B"}
				{assign var="first_section_text" value=$lang.billing_address}
				{assign var="sec_section" value="S"}
				{assign var="sec_section_text" value=$lang.shipping_address}
				{assign var="body_id" value="sa"}
			
                        
			{include file="views/profiles/components/addressbook_fields.tpl" section=$sec_section body_id=$body_id ship_to_another="Y" title=$sec_section_text }
			
		{/if}


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
        {*<span class="float_left margin_left_fifteen">{$lang.text_mandatory_fields}</span>*}
		<div class="buttons-container center" style="margin-top:10px;">
			{if $action}
				{assign var="_action" value="$action"}
			{/if}
			{if $mode == "update_addressbook"}                                    
                            <div style="float:left;margin-left:450px" >
                            {include file="buttons/save.tpl" but_name="dispatch[profiles.update_addressbook.$_action]" but_id="save_profile_but" }
                            </div>
                            {include file="buttons/cancel.tpl" but_href="profiles.manage_addressbook" }
			{else}
				{include file="buttons/register_profile.tpl" but_name="dispatch[profiles.add.$_action]"}
			{/if}
		</div>
        </div>
		</form>
	</div>

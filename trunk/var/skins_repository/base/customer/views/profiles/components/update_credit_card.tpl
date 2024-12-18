{* $Id: update_credit_card.tpl 12724 2011-06-21 12:48:57Z zeke $ *}

{assign var="capture_name" value="card_picker_`$card_id`"}
{capture name=$capture_name}
	<form name="{$id}_form" action="{""|fn_url}" method="post">
		<input type="hidden" name="profile_id" value="{$pid}" />
		<input type="hidden" name="user_id" value="{$uid}" />
		{if $card_id}
		<input type="hidden" name="card_id" value="{$card_id}" />
		<input type="hidden" name="default_cc" value="{if $card_data.default}1{/if}" />
		{/if}
		<input type="hidden" value="credit_cards" name="selected_section"/>
		<input type="hidden" value="do" name="dispatch[profiles.update_cards]" />
		
		{include file="views/orders/components/payments/cc.tpl" card_id=$card_id card_data=$card_data}

		<div class="buttons-container">
			{if $card_id}
				{assign var="_but_text" value=$lang.update}
			{else}
				{assign var="_but_text" value=$lang.add}
			{/if}
			{include file="buttons/add_close.tpl" is_js=false but_close_text=$_but_text}
		</div>
	</form>
{/capture}

{include file="common_templates/popupbox.tpl" id=$id link_text=$link_text text=$title content=$smarty.capture.$capture_name edit_picker=true link_meta=$link_meta}
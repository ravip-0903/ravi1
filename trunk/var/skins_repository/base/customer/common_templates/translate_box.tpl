{* $Id: translate_box.tpl 12544 2011-05-27 10:34:19Z bimib $ *}

<div id="translate_link" class="hidden">
	<a class="edit-link" onclick="fn_show_translate_box();">{$lang.edit}</a>
</div>
<div id="translate_box" class="hidden">

	<div id="translate_box_menu_language_selector" class="float-right">
		<div class="inline" id="translate_box_language_selector">
		{include file="common_templates/select_object.tpl" style="graphic" link_tpl="design_mode.get_langvar"|fn_link_attach:"lang_code=" items=$languages selected_id=$smarty.const.CART_LANGUAGE key_name="name" suffix="translate_box" display_icons=true}
		</div>
	</div>

	<input id="tbox_descr_sl" type="hidden" name="descr_sl" value="" />
	<input id="trans_val" class="input-text" type="text" value="" size="37" onkeyup="fn_change_phrase();"/>
	<p></p>
	<div id="orig_phrase" class="clear-both"></div>

	<div class="buttons-container">
		{include file="buttons/button.tpl" but_text=$lang.save_translation but_onclick="fn_save_phrase();"}
		&nbsp;&nbsp;&nbsp;{$lang.or}&nbsp;&nbsp;&nbsp;
		<a class="cm-dialog-closer">{$lang.cancel}</a>
	</div>

</div>
{script src="js/design_mode.js"}

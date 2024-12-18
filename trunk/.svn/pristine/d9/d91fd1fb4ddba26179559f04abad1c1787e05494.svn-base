{* $Id: manage.tpl 12165 2011-04-05 10:19:11Z subkey $ *}

{script src="js/tabs.js"}

{capture name="mainbox"}

{include file="views/product_features/components/product_features_search_form.tpl" dispatch="product_features.manage"}

{literal}
<script type="text/javascript">
//<![CDATA[
function fn_check_product_feature_type(value, id)
{
	var w = $('#warning_feature_change_' + id);
	($('#tab_variants_' + id).is(':visible') && !(value == 'S' || value == 'M' || value == 'N' || value == 'E')) ? w.show() : w.hide();

	var t = $('#content_tab_variants_' + id);
	$('#tab_variants_' + id).toggleBy(!(value == 'S' || value == 'M' || value == 'N' || value == 'E'));
	// display/hide images
	$('.cm-extended-feature', t).toggleBy(value != 'E');
	if (value != 'E') {
		$('tr[id^=extra_feature_]', t).hide();
		$('img[id^=off_extra_feature_]', t).hide();
		$('img[id^=on_extra_feature_]', t).show();
		$('img[id^=off_st_]', t).hide();
		$('img[id^=on_st_]', t).show();
	}

	if (value == 'N') {
		$('.cm-feature-value', t).addClass('cm-value-integer');
	} else {
		$('.cm-feature-value', t).removeClass('cm-value-integer');
	}
}
//]]>
</script>
{/literal}

{include file="common_templates/pagination.tpl"}

{assign var="r_url" value=$config.current_url|escape:url}

<div class="items-container{if ""|fn_check_form_permissions} cm-hide-inputs{/if}" id="update_features_list">
{if $features}

	{if $has_ungroupped}
		<div class="object-group clear no-hover">
			<div class="float-left object-name">
				{$lang.ungroupped_features}
			</div>
		</div>
		
		{foreach from=$features item="p_feature"}
			{if $p_feature.feature_type != "G"}
				{include file="common_templates/object_group.tpl" id=$p_feature.feature_id details=$p_feature.feature_description|unescape text=$p_feature.description status=$p_feature.status hidden=true href="product_features.update?feature_id=`$p_feature.feature_id`&amp;return_url=$r_url" object_id_name="feature_id" table="product_features" href_delete="product_features.delete?feature_id=`$p_feature.feature_id`" rev_delete="pagination_contents" header_text="`$lang.editing_product_feature`:&nbsp;`$p_feature.description`" element="-elements"}
			{/if}
		{/foreach}
	{/if}
	
	{foreach from=$features item="gr_feature"}
		{if $gr_feature.feature_type == "G"}
			{include file="common_templates/object_group.tpl" id=$gr_feature.feature_id details=$gr_feature.feature_description|unescape text=$gr_feature.description status=$gr_feature.status hidden=true href="product_features.update?feature_id=`$gr_feature.feature_id`&amp;return_url=$r_url" object_id_name="feature_id" table="product_features" href_delete="product_features.delete?feature_id=`$gr_feature.feature_id`" rev_delete="pagination_contents" header_text="`$lang.editing_group`:&nbsp;`$gr_feature.description`"}
	
			{if $gr_feature.subfeatures}
				{foreach from=$gr_feature.subfeatures item="subfeature"}
					{include file="common_templates/object_group.tpl" id=$subfeature.feature_id details=$subfeature.feature_description|unescape text=$subfeature.description status=$subfeature.status hidden=true href="product_features.update?feature_id=`$subfeature.feature_id`&amp;return_url=$r_url" object_id_name="feature_id" table="product_features" href_delete="product_features.delete?feature_id=`$subfeature.feature_id`" rev_delete="pagination_contents" header_text="`$lang.editing_product_feature`:&nbsp;`$subfeature.description`" element="-elements"}
				{/foreach}
			{/if}
	
		{/if}
	{/foreach}
{else}
	<p class="no-items">{$lang.no_data}</p>
{/if}
<!--update_features_list--></div>

{include file="common_templates/pagination.tpl"}

	{capture name="tools"}
		{capture name="add_new_picker"}
			{include file="views/product_features/update.tpl" feature="" mode="add" is_group=true}
		{/capture}
		{include file="common_templates/popupbox.tpl" id="add_new_group" text=$lang.add_new_group content=$smarty.capture.add_new_picker link_text=$lang.add_group act="general"}

		{capture name="add_new_picker_2"}
			{include file="views/product_features/update.tpl" feature="" mode="add"}
		{/capture}
		{include file="common_templates/popupbox.tpl" id="add_new_feature" text=$lang.add_new_feature content=$smarty.capture.add_new_picker_2 link_text=$lang.add_feature act="general"}
	{/capture}

{/capture}
{include file="common_templates/mainbox.tpl" title=$lang.product_features content=$smarty.capture.mainbox tools=$smarty.capture.tools title_extra=$smarty.capture.title_extra select_languages=true}

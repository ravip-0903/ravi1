{script src="js/tabs.js"}
{literal}
<script type="text/javascript">
//<![CDATA[
function fn_check_option_type(value, tag_id)
{
	var id = tag_id.replace('option_type_', '');
	$('#tab_option_variants_' + id).toggleBy(!(value == 'S' || value == 'R' || value == 'C'));
	$('#required_options_' + id).toggleBy(!(value == 'I' || value == 'T' || value == 'F'));
	$('#extra_options_' + id).toggleBy(!(value == 'I' || value == 'T'));
	$('#file_options_' + id).toggleBy(!(value == 'F'));
	
	if (value == 'C') {
		var t = $('table', '#content_tab_option_variants_' + id);
		$('.cm-non-cb', t).switchAvailability(true); // hide obsolete columns
		$('tbody:gt(1)', t).switchAvailability(true); // hide obsolete rows

	} else if (value == 'S' || value == 'R') {
		var t = $('table', '#content_tab_option_variants_' + id);
		$('.cm-non-cb', t).switchAvailability(false); // show all columns
		$('tbody', t).switchAvailability(false); // show all rows
		$('#box_add_variant_' + id).show(); // show "add new variants" box
		
	} else if (value == 'I' || value == 'T') {
		$('#extra_options_' + id).show(); // show "add new variants" box
	}
}
//]]>
</script>
{/literal}

{capture name="mainbox"}

{if $object == "global"}
	{assign var="select_languages" value=true}
	{assign var="rev_delete_id" value="pagination_contents"}
{else}
	{assign var="rev_delete_id" value="product_options_list"}
	{assign var="query_product_id" value="&product_id=`$product_id`"}
{/if}

{include file="common_templates/pagination.tpl"}

<div class="items-container" id="product_options_list">

{foreach from=$product_options item="po"}
	{if $object == "product" && !$po.product_id}
		{assign var="details" value="(`$lang.global`)"}
	{else}
		{assign var="details" value=""}
	{/if}
	
	{assign var="skip_delete" value="product_options"|fn_check_company_id:"option_id":$po.option_id}
	{assign var="skip_delete" value=$skip_delete|fn_is_empty}
	{if $po.company_id}
	{assign var="po_company_name" value=$po.company_id|fn_get_company_name"}
	{assign var="po_name" value="`$po.option_name` (`$lang.vendor`: `$po_company_name`)"}
	{else}
	{assign var="po_name" value=$po.option_name}
	{/if}
	{if "COMPANY_ID"|defined && $po.company_id == $smarty.const.COMPANY_ID}
		{assign var="link_text" value=$lang.edit}
		{assign var="additional_class" value="cm-no-hide-input"}
	{elseif "COMPANY_ID"|defined}
		{assign var="link_text" value=$lang.view}
		{assign var="additional_class" value=""}
	{/if}
	{assign var="link_class" value="text-button-edit"}
	{if "COMPANY_ID"|defined && $smarty.const.COMPANY_ID != $po.company_id}
		{assign var="hide_for_vendor" value=true}
	{/if}
   
	{include file="common_templates/object_group.tpl" id=$po.option_id id_prefix="_product_option_" details=$details text=$po_name hide_for_vendor=$hide_for_vendor status=$po.status table="product_options" object_id_name="option_id" href="product_options.update?option_id=`$po.option_id``$query_product_id`" href_delete="product_options.delete?option_id=`$po.option_id``$query_product_id`" rev_delete=$rev_delete_id header_text="`$lang.editing_option`:&nbsp;`$po.option_name`" skip_delete=$skip_delete additional_class=$additional_class option_description=$po.staff_notes}

{foreachelse}

	<p class="no-items">{$lang.no_items}</p>

{/foreach}
<!--product_options_list--></div>

{include file="common_templates/pagination.tpl"}

<div class="buttons-container">
	{capture name="tools"}
		{capture name="add_new_picker"}
			{include file="views/product_options/update.tpl" mode="add" option_id="0"}
		{/capture}
        <!--change by ankur not to show create option button to merchant-->
        {if !"COMPANY_ID"|defined}
		{include file="common_templates/popupbox.tpl" id="add_new_option" text=$lang.new_option link_text=$lang.add_option act="general" content=$smarty.capture.add_new_picker}
        {/if}
        <!--code end-->
	{/capture}
	{if $object == "global"} 
		{include file="common_templates/popupbox.tpl" id="add_new_option" text=$lang.new_option link_text=$lang.add_option act="general"} 
	{else} 
		{$smarty.capture.tools} 
	{/if}
	{if $product_options && $object == "global"}
		{include file="buttons/button.tpl" but_text=$lang.apply_to_products but_role="text" but_href="product_options.apply"}
	{/if}

	{$extra}
</div>

{/capture}

{if $object == "product"}
	{$smarty.capture.mainbox}
{else}
	{include file="common_templates/mainbox.tpl" title=$lang.global_options content=$smarty.capture.mainbox tools=$smarty.capture.tools select_language=$select_language}
{/if}
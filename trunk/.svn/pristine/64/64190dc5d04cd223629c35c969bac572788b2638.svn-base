{* $Id: select_status.tpl 10893 2010-10-14 04:53:40Z 2tl $ *}

{if $display == "select"}
<select name="{$input_name}" {if $input_id}id="{$input_id}"{/if}>
	<option value="A" {if $obj.status == "A"}selected="selected"{/if}>{$lang.active}</option>
	{if $hidden}
	<option value="H" {if $obj.status == "H"}selected="selected"{/if}>{$lang.hidden}</option>
	{/if}
	<option value="D" {if $obj.status == "D"}selected="selected"{/if}>{$lang.disabled}</option>
	<option value="P" {if $obj.status == "P"}selected="selected"{/if}>{$lang.pending}</option>
</select>
{else}
<div class="form-field">
	<label class="cm-required">{$lang.status}:</label>
	<div class="select-field">
		{if $items_status}
			{if !$items_status|is_array}
				{assign var="items_status" value=$items_status|yaml_unserialize}
			{/if}
			{foreach from=$items_status item="val" key="st" name="status_cycle"}
			<input type="radio" name="{$input_name}" id="{$id}_{$obj_id|default:0}_{$st|lower}" {if $obj.status == $st || (!$obj.status && $smarty.foreach.status_cycle.first)}checked="checked"{/if} value="{$st}" class="radio" /><label for="{$id}_{$obj_id|default:0}_{$st|lower}">{$val}</label>
			{/foreach}
		{else}

	{if !"COMPANY_ID"|defined}
		{if $pr == 'product'}
			<input type="radio" name="{$input_name}" id="{$id}_{$obj_id|default:0}_a" {if $obj.status == "A"}checked="checked"{/if} value="A" class="radio" onClick="approve_product('Y');" /><label for="{$id}_{$obj_id|default:0}_a">{$lang.active}</label>
		{else}
			<input type="radio" name="{$input_name}" id="{$id}_{$obj_id|default:0}_a" {if $obj.status == "A"}checked="checked"{/if} value="A" class="radio" /><label for="{$id}_{$obj_id|default:0}_a">{$lang.active}</label>
		{/if}
	{else}

		{if $product_data.is_approved == "N"}
			<input type="radio" name="{$input_name}" id="{$id}_{$obj_id|default:0}_a" {if $obj.status == "A"}checked="checked"{/if} disabled value="A" class="radio" /><label for="{$id}_{$obj_id|default:0}_a">{$lang.active}</label>
		{else}
			<input type="radio" name="{$input_name}" id="{$id}_{$obj_id|default:0}_a" {if $obj.status == "A"}checked="checked"{/if}value="A" class="radio" /><label for="{$id}_{$obj_id|default:0}_a">{$lang.active}</label>
		{/if}
	{/if}
		{if $hidden}
		<input type="radio" name="{$input_name}" id="{$id}_{$obj_id|default:0}_h" {if $obj.status == "H"}checked="checked"{/if} value="H" class="radio" /><label for="{$id}_{$obj_id|default:0}_h">{$lang.hidden}</label>
		{/if}

		<input type="radio" name="{$input_name}" id="{$id}_{$obj_id|default:0}_d" {if $obj.status == "D"}checked="checked"{/if} value="D" class="radio" /><label for="{$id}_{$obj_id|default:0}_d">{$lang.disabled}</label>
		{if $pr != 'product'}		
			<input type="radio" name="{$input_name}" id="{$id}_{$obj_id|default:0}_d" {if $obj.status == "P"}checked="checked"{/if} value="P" class="radio" /><label for="{$id}_{$obj_id|default:0}_d">{$lang.pending}</label>
		{/if}
{if $pr == 'product'}
	{if !"COMPANY_ID"|defined}
		<input type="radio" name="{$input_name}" id="{$id}_{$obj_id|default:0}_p" {if $obj.status == "P" || !$obj.status}checked="checked"{/if} value="P" class="radio" /><label for="{$id}_{$obj_id|default:0}_p">{$lang.pending}</label>
	{else}
		<input type="radio" name="{$input_name}" id="{$id}_{$obj_id|default:0}_p" {if $obj.status == "P" || !$obj.status}checked="checked"{/if} value="P" class="radio" /><label for="{$id}_{$obj_id|default:0}_p">{$lang.requestapproval}</label>

	{/if}
{/if}
		{/if}
	</div>
</div>
{if $pr == 'product'}
<div class="form-field">
	<label class="cm-required">{$lang.is_approved}:</label>
	<div class="select-field">
	{if !"COMPANY_ID"|defined}
		<input type="radio" name="product_data[is_approved]" value="Y" {if $product_data.is_approved == "Y"}checked="checked"{/if} id="is_approved"/>
		 <label for="{$id}_{$obj_id|default:0}_d">{$lang.yes}</label>
		<input type="radio" name="product_data[is_approved]" value="N" {if $product_data.is_approved == "N"}checked="checked"{/if} /> <label for="{$id}_{$obj_id|default:0}_d">{$lang.no}</label>
	{else}
		<input type="radio" name="product_data[is_approved]" value="Y" {if $product_data.is_approved == "Y"}checked="checked"{/if} disabled />
		 <label for="{$id}_{$obj_id|default:0}_d">{$lang.yes}</label>
		<input type="radio" name="product_data[is_approved]" value="N" {if $product_data.is_approved == "N"}checked="checked"{/if} disabled /> <label for="{$id}_{$obj_id|default:0}_d">{$lang.no}</label>
	{/if}
	</div>
</div>
{/if}

{/if}

<script type="text/javascript">
//<![CDATA[
{literal}
function approve_product(obj){
	document.getElementById("is_approved").checked = true;
}
{/literal}
//]]>
</script>


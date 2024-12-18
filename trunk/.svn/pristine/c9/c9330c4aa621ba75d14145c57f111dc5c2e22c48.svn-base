{* $Id: manifest_list.tpl 12178 2011-04-06 12:14:37Z bimib $ *}

{capture name="mainbox"}

{include file="addons/my_changes/views/manifest_search/components/manifest_search_form.tpl" dispatch="manifest_search.manifest_list"}
<div id="content_manage_manifests">

{include file="common_templates/pagination.tpl" save_current_page=true save_current_url=true div_id=$smarty.request.content_id}

{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}

{if $settings.DHTML.admin_ajax_based_pagination == "Y"}
	{assign var="ajax_class" value="cm-ajax cm-history"}
{/if}

{assign var="rev" value=$smarty.request.content_id|default:"pagination_contents"}

<table border="0" cellpadding="0" cellspacing="0" width="100%" class="table sortable">
<tr>
	<!--<th width="1%" class="center">
		<input type="checkbox" name="check_all" value="Y" title="{$lang.check_uncheck_all}" class="checkbox cm-check-items" /></th> -->
	<th width="5%"><a class="{$ajax_class}{if $search.sort_by == "manifest_id"} sort-link-{$search.sort_order}{/if}" href="{"`$c_url`&amp;sort_by=manifest_id&amp;sort_order=`$search.sort_order`"|fn_url}" rev={$rev}>{$lang.id}</a></th>
	<th width="10%"><a class="{$ajax_class}{if $search.sort_by == "dispatch_date"} sort-link-{$search.sort_order}{/if}" href="{"`$c_url`&amp;sort_by=dispatch_date&amp;sort_order=`$search.sort_order`"|fn_url}" rev={$rev}>{$lang.dispatch_date}</a></th>
	<th width="10%"><a class="{$ajax_class}{if $search.sort_by == "order_cnt"} sort-link-{$search.sort_order}{/if}" href="{"`$c_url`&amp;sort_by=order_cnt&amp;sort_order=`$search.sort_order`"|fn_url}" rev={$rev}>{$lang.order_count}</a></th>
	<th width="10%"><a class="{$ajax_class}{if $search.sort_by == "manifest_type"} sort-link-{$search.sort_order}{/if}" href="{"`$c_url`&amp;sort_by=manifest_type&amp;sort_order=`$search.sort_order`"|fn_url}" rev={$rev}>{$lang.manifest_type}</a></th>
    <th width="10%"><a class="{$ajax_class}{if $search.sort_by == "carrier_name"} sort-link-{$search.sort_order}{/if}" href="{"`$c_url`&amp;sort_by=carrier_name&amp;sort_order=`$search.sort_order`"|fn_url}" rev={$rev}>{$lang.carrier_name}</a></th>
    <th width="20%"><a class="{$ajax_class}{if $search.sort_by == "pickup_location"} sort-link-{$search.sort_order}{/if}" href="{"`$c_url`&amp;sort_by=pickup_location&amp;sort_order=`$search.sort_order`"|fn_url}" rev={$rev}>{$lang.pickup_location}</a></th>
	<th width="20%"><a class="{$ajax_class}{if $search.sort_by == "notes"} sort-link-{$search.sort_order}{/if}" href="{"`$c_url`&amp;sort_by=notes&amp;sort_order=`$search.sort_order`"|fn_url}" rev={$rev}>{$lang.notes}</a></th>
</tr>
{foreach from=$list_email_data item="o"}
{hook name="orders:order_row"}
<tr {cycle values="class=\"table-row\", "}>
	<!--<td class="center">
		<input type="checkbox" name="order_ids[]" value="{$o.id}" class="checkbox cm-item" /></td> -->
	<td>
		<a href="{"manifest_create.manifest_list_detail&manifest_id=`$o.manifest_id`"|fn_url}" class="underlined">&nbsp;#{$o.manifest_id}</a>
	</td>
	<td>
		{$o.dispatch_date}
	</td>
	<td>
    	{*assign var="order_cnt" value=$o.manifest_id|get_manifest_order_cnt*}
    	{$o.order_cnt}
    </td>
	<td>
		{$o.manifest_type_name}
	</td>
    <td>
    	{assign var="carrier_name" value=$o.carrier_name|get_single_carrier_name}
		{$carrier_name}
     </td>
  <td>
		{$o.pickup_location}
    </td>
	<td >
		{$o.notes}
    </td>
</tr>
{/hook}
{foreachelse}
<tr class="no-items">
	<td colspan="9"><p>{$lang.no_data}</p></td>
</tr>
{/foreach}
</table>

{include file="common_templates/pagination.tpl" div_id=$smarty.request.content_id}


</div>

{/capture}

{include file="common_templates/mainbox.tpl" title='Manifest Search' content=$smarty.capture.mainbox }<!--title_extra=$smarty.capture.title_extra tools=$smarty.capture.tools extra_tools=$smarty.capture.extra_tools-->

{* $Id: product_filters.tpl 12029 2011-03-14 08:12:03Z klerik $ *}
{** block-description:original **}

{if $smarty.request.category_id}
	{assign var="curr_url" value="categories.view"}
{else}
	{assign var="curr_url" value="products.search"}
{/if}

<form method="get" action="{$curr_url|fn_url}" name="newsearch" id="newsearch" />
{if $items && !$smarty.request.advanced_filter}

{if $smarty.server.QUERY_STRING|strpos:"dispatch=" !== false}
	{assign var="curl" value=$config.current_url}
	{assign var="filter_qstring" value=$curl|fn_query_remove:"result_ids":"filter_id":"view_all":"req_range_id":"advanced_filter":"features_hash":"subcats":"page"}
{else}
	{assign var="filter_qstring" value="products.search"}
{/if}

{assign var="reset_qstring" value="products.search"}

{if $smarty.request.q}
	<input type="hidden" name="subcats" value="{$smarty.request.subcats}" />
	<input type="hidden" name="status" value="{$smarty.request.status}" />
	<input type="hidden" name="pname" value="{$smarty.request.pname}" />
	<input type="hidden" name="product_code" value={$smarty.request.product_code} />
	<input type="hidden" name="match" value="{$smarty.request.match}" />
	<input type="hidden" name="pkeywords" value="{$smarty.request.pkeywords}" />
	<input type="hidden" name="search_performed" value="{$smarty.request.search_performed}" />
	<input type="hidden" name="cid" value="{$smarty.request.cid}" />
	<input type="hidden" name="q" value="{$smarty.request.q}" />
{/if}
{if $smarty.request.sort_by}
	<input type="hidden" name="sort_by" value={$smarty.request.sort_by} />
	<input type="hidden" name="sort_order" value={$smarty.request.sort_order} />
{/if}
{if $smarty.request.company_id}
	<input type="hidden" name="company_id" value={$smarty.request.company_id} />
{/if}
{if $smarty.request.search_performed}
	<input type="hidden" name="search_performed" value={$smarty.request.search_performed} />
{/if}
{if $smarty.request.category_id}
	<input type="hidden" name="category_id" value={$smarty.request.category_id} />
	{assign var="filter_qstring" value=$filter_qstring|fn_link_attach:"subcats=Y"}

	{assign var="reset_qstring" value=$reset_qstring|fn_link_attach:"subcats=Y"}
{/if}

{assign var="has_selected" value=false}

{foreach from=$items item="filter" name="filters"}
	<h4 {if $smarty.foreach.filters.iteration != "1" }class="margin_top_ten"{/if}>{$filter.filter}</h4>
	<div class="clearboth"></div>
	<ul class="product-filters" id="content_product_more_filters_{$block.block_id}_{$filter.filter_id}">
	{if $smarty.request.features_hash|is_array}
			{foreach from=$filter.ranges name="ranges" item="range"}
				{if $controller == "index"}
					<li {if $smarty.foreach.ranges.iteration > $smarty.const.FILTERS_RANGES_COUNT}class="hidden"{/if}>
				{else}
					<li>
				{/if}
				
				<p class="a_hover_cursor">
				{if $range.filter_id == '1'}
					<input type="checkbox" id="sh_option{$range.range_id}" style="float:left; margin:0px 4px 0 0px" name="features_hash[]" value="P{$range.range_id}" onclick="return new_search(this);" {if 'P'|cat:$range.range_id != $last_search} {if 'P'|cat:$range.range_id|in_array:$smarty.request.features_hash} checked {/if}{/if} />
				{else}
					<input type="checkbox" id="sh_option{$range.range_id}" style="float:left; margin:0px 4px 0 0px" name="features_hash[]" value="{$range.filter_id}.{$range.range_id}" onclick="return new_search(this);" {if $range.filter_id|cat:"."|cat:$range.range_id != $last_search}{if $range.filter_id|cat:"."|cat:$range.range_id|in_array:$smarty.request.features_hash} checked {/if} {/if} />
				{/if}
					<span  onclick="chk_sh_option({$range.range_id});">{$range.range_name|fn_text_placeholders}</span>
					<span class="details">&nbsp;({$range.products})</span>
				</p></li>
			{/foreach}

	{else}

	{foreach from=$filter.ranges name="ranges" item="range"}
		{if $config.solr && $filter.filter_id == '1'}
			{if $smarty.foreach.ranges.iteration == "1"}
			{assign var="allfilter" value=''|fn_assign_filtertoitem}
		 <ul class="price_fct" id="price_fct">    
		       {foreach from=$allfilter.price name="pricename" key="pricekey" item="priceval" }
				{assign var="shkey" value=$priceval.key|replace:' ':''} 
				{assign var="shkey1" value=$priceval.key|fn_assign_pricekey:"val"}
				{assign var="shval" value=$shkey1|fn_showprice}
				<li><p class="a_hover_cursor"><input type="checkbox" {if $priceval.val eq "0"} disabled="disabled"{/if} id="sh_option{$shkey1}" style="float:left; margin:0px 4px 0 0px" name="fq[]" rev="price" value={$shkey1} onclick="return new_search(this);" {if $shkey1|in_array:$smarty.request.fq} checked {/if} /><span onclick="return chk_sh_option('{$shkey1}');">{$shval} </span></p></li>
			{/foreach}
		     </ul>  
			{/if}
		{else}
				{if $controller == "index"}
					<li {if $smarty.foreach.ranges.iteration > $smarty.const.FILTERS_RANGES_COUNT}class="hidden"{/if}>
				{else}
					<li>
				{/if}
			{strip}
			{if $range.selected == true}
				{assign var="fh" value=$smarty.request.features_hash|fn_delete_range_from_url:$range:$filter.field_type}
				{if $fh}
					{assign var="attach_query" value="features_hash=`$fh`"}
				{/if}
				{if $filter.feature_type == "E" && $range.range_id == $smarty.request.variant_id}
					{assign var="reset_lnk" value=$reset_qstring}
				{else}
					{assign var="reset_lnk" value=$filter_qstring}
				{/if}
				{assign var="has_selected" value=true}
			
		    <p>
		    {$filter.prefix}
		    {$range.range_name|fn_text_placeholders}
		    {$filter.suffix}
		    <a class="extra-link filter-delete" href="{if $fh}{$reset_lnk|fn_link_attach:$attach_query|fn_url}{else}{$reset_lnk|fn_url}{/if}" rel="nofollow" title="{$lang.remove}"><img src="{$images_dir}/icons/delete_icon.gif" width="12" height="11" border="0" alt="{$lang.remove}" align="bottom" /></a>
		    </p>
				{if $filter.other_variants}
					<ul id="other_variants_{$block.block_id}_{$filter.filter_id}" class="hidden">
					{foreach from=$filter.other_variants item="r"}
					<li>
						{assign var="filter_query_elm" value=$fh|fn_add_range_to_url_hash:$r:$filter.field_type}
						{if $fh}
							{assign var="cur_features_hash" value="&amp;features_hash=`$fh`"}
						{/if}
						<a href="{if $r.feature_type == "E" && !$r.simple_link && $controller == "product_features"}{"product_features.view?variant_id=`$r.range_id``$cur_features_hash`"|fn_url}{else}{$filter_qstring|fn_link_attach:"features_hash=`$filter_query_elm`"|fn_url}{/if}" rel="nofollow">{$filter.prefix}{$r.range_name|fn_text_placeholders}{$filter.suffix}

						{if $controller != "index"}
						<span class="details">&nbsp;({$r.products})</span>
						{/if}
						</a>
				
					</li>
					{/foreach}
					</ul>
						<a id="sw_other_variants_{$block.block_id}_{$filter.filter_id}" class="extra-link cm-combination">{$lang.choose_other}</a>
				{/if}
				{else}
					{assign var="filter_query_elm" value=$smarty.request.features_hash|fn_add_range_to_url_hash:$range:$filter.field_type}
					{if $smarty.request.features_hash}
						{assign var="cur_features_hash" value="&amp;features_hash=`$smarty.request.features_hash`"}
					{/if}

					<p class="a_hover_cursor">
					{if $filter.filter_id == '1'}

							<input type="checkbox" id="sh_option{$range.range_id}" style="float:left; margin:0px 4px 0 4px" name="features_hash[]" value="{$filter_query_elm}" onclick="return new_search(this);" />


					{else}
						{if $config.solr}
							<input type="checkbox" id="sh_option{$range.range_id}" style="float:left; margin:0px 4px 0 0px" name="br[]" value="{$range.range_id}" rev="brand"  onclick="return new_search(this);" />
						{else}
							<input type="checkbox" id="sh_option{$range.range_id}" style="float:left; margin:0px 4px 0 4px" name="features_hash[]" value="{$filter.filter_id}.{$range.range_id}" onclick="return new_search(this);" />
						{/if}
					{/if}

					<span onclick="chk_sh_option({$range.range_id});">{$range.range_name|fn_text_placeholders}</span>

					{if $controller != "index"}
					    	<span class="details">
						({$range.products})</span>
					{/if}</p>
				{/if}
			{/strip}
		{/if}
	{/foreach}
{/if}
	{if $filter.more_cut}
		{if $filter.filter == $lang.filter_by_brands}
	
		{else}
			<li id="view_all_{$block.block_id}_{$filter.filter_id}" class="right hidden">
			{assign var="capture_q" value=$smarty.capture.q|escape:url}
				<a href="{"product_features.view_all?q=`$capture_q`"|fn_url}" rel="nofollow" class="extra-link">{$lang.view_all}</a>
		</li>
		{/if}
	{/if}
	</ul>
	{if $controller == "index"}
		{if $smarty.foreach.ranges.iteration > $smarty.const.FILTERS_RANGES_COUNT}
			{if $config.solr && $smarty.foreach.filters.iteration == '2'}
				<div class="float_left margin_left_five">
					<a onclick="$('#content_product_more_filters_{$block.block_id}_{$filter.filter_id} li').show(); $('#view_all_{$block.block_id}_{$filter.filter_id}').show(); $(this).hide(); return false;" class="link-left link_custom extra-link"> {$lang.more}</a>
				</div>
			{/if}
		{/if}
	{/if}

{/foreach}


<div class="clear filters-tools float_right margin_right_five float_right">
	<!--<div class="float-right"><a {if "FILTER_CUSTOM_ADVANCED"|defined}href="{"products.search?advanced_filter=Y"|fn_url}"{else}href="{$filter_qstring|fn_link_attach:"advanced_filter=Y"|fn_url}"{/if} rel="nofollow" class="secondary-link lowercase">{$lang.advanced}</a></div>-->
	{if $has_selected}
	<a href="{if $smarty.request.category_id}{"categories.view?category_id=`$smarty.request.category_id`"|fn_url}{else}{$index_script|fn_url}{/if}" rel="nofollow" class="reset-filters">{$lang.reset}</a>
	{/if}

     {if $smarty.request.features_hash|is_array && $smarty.request.features_hash|count > 1}
		<a href="javascript:;" onclick="return deselect_all(this);">{$lang.deselect}</a>
     {else}
		<a href="brands" rel="nofollow" class="link_custom extra-link" >{$lang.all_brands}</a>
     {/if}
</div>
		<input type="submit" name="dispatch" value="{$curr_url}" id="newsearch_filter" style="display:none;" />
{/if}
</form>
<div class="clearboth"></div>

{literal}
<script>
function new_search(obj){
	document.getElementById('newsearch_filter').click();
}
function deselect_all(obj){
	$('#option_unchk').attr('checked','checked');
	$('input:checkbox').removeAttr('checked');
	document.getElementById('newsearch_filter').click();
}
function chk_sh_option(obj){
	if($('#sh_option'+obj+':checkbox:checked').length == "1"){
		$('#sh_option'+obj).removeAttr("checked");
        } else {
                $('#sh_option'+obj).attr('checked','checked');
        }
	document.getElementById('newsearch_filter').click();
}
</script>
<style>
.a_hover_cursor:hover{cursor:pointer;}
</style>
{/literal}

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

{if $smarty.request.market_id}
	<input type="hidden" name="market_id" value={$smarty.request.market_id} />
{/if}

{assign var="has_selected" value=false}
{if $controller == "index"}
{foreach from=$items item="filter" name="filters"}
	<h4 {if $smarty.foreach.filters.iteration != "1" }class="margin_top_ten"{/if}>{$filter.filter}</h4>

	{if $config.category_solr}
		<ul class="product-filters" id="content_product_more_filters_{$block.block_id}_0{$smarty.foreach.filters.iteration}" {if $filter.feature_id == '53'} style="overflow-y:scroll; max-height: 130px; overflow-x: hidden; min-height: 30px;" {/if}>
	{else}
		<ul class="product-filters" id="content_product_more_filters_{$block.block_id}_{$filter.filter_id}">
	{/if}
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

							<input type="checkbox" id="sh_option{$range.range_id}" style="float:left; margin:0px 4px 0 4px; height: 18px;" name="features_hash[]" value="{$filter_query_elm}" onclick="return new_search(this);" />


					{else}
						{if $config.solr}
							<input type="checkbox" id="sh_option{$range.range_id}" style="float:left; margin:0px 4px 0 0px; height: 18px;" name="br[]" value="{$range.range_id}" rev="brand"  onclick="return new_search(this);" />
						{else}
							<input type="checkbox" id="sh_option{$range.range_id}" style="float:left; margin:0px 4px 0 4px; height: 18px;" name="features_hash[]" value="{$filter.filter_id}.{$range.range_id}" onclick="return new_search(this);" />
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
{elseif ($controller == "categories") || (!$config.solr && $controller == "products")}
{if $config.category_solr}
{assign var="items" value=$_REQUEST|fn_get_solr_categories:0}
<div>
<h4 id="brands_fct_2" style="margin-top:1px; cursor: pointer;" class="margin_top_ten  slide_toggle_fct box_expand">By Price {if !empty($smarty.request.fq)}<span class="clear_filter">Clear</span>{/if}</h4>
 <ul class="price_fct" id="price_fct" style="clear:both;">    
       {foreach from=$items.price name="pricename" key="pricekey" item="priceval" }
		{assign var="shkey" value=$priceval.key|replace:' ':''} 
		{assign var="shkey1" value=$priceval.key|fn_assign_pricekey:"val"}
		{assign var="shval" value=$shkey1|fn_showprice}
             <li><p class="a_hover_cursor"><input type="checkbox" {if $priceval.val eq "0"} disabled="disabled"{/if} id="sh_option{$shkey1}" style="float:left; margin:0px 4px 0 0px" name="fq[]" rev="price" value={$shkey1} onclick="return new_search(this);" {if $shkey1|in_array:$smarty.request.fq} checked {/if} /><span {if $priceval.val == "0"} style="color:#999;" {/if} {if $priceval.val != "0"} onclick="return chk_sh_option('{$shkey1}');" {/if}>{$shval} {if $priceval.val !=0}<span class="count">({$priceval.val})</span>{/if}</span></p></li>
        {/foreach}
</ul>
</div>

<div>
<h4 id="brands_fct_1" style="margin-top:1px; cursor: pointer;" class="margin_top_ten  slide_toggle_fct box_expand">By Brands {if !empty($smarty.request.br)}<span class="clear_filter">Clear</span>{/if}</h4>
<input name="brand_search" id="brand_search" placeholder="Search by brands" style="padding:4px;margin:4px;width:89%;" />
<ul id="brands_fct" class="brands_fct" {if $items.brand|count > 11} style="max-height: 300px; overflow: scroll; overflow-x: hidden; min-height: 30px;" {else} style="max-height: 300px; overflow-x: hidden; min-height: 30px;" {/if}>

    {foreach from=$items.brand key="brandkey" item="brand" }
    	{if $brand.count > 0 || !empty($smarty.request.fsrc)}
         <li><p class="a_hover_cursor"><input type="checkbox" {if $brand.count eq "0"} disabled="disabled"{/if} id="sh_option{$brand.id}" style="float:left; margin:0px 4px 0 0px" name="br[]" value="{$brand.id}" rev="brand"  onclick="return new_search(this);" {if $brand.id|in_array:$smarty.request.br} checked {/if} /><span {if $brand.count != "0"} onclick="return chk_sh_option('{$brand.id}');" {/if}>
        	<span {if $brand.count == "0"} style="color:#999;" {/if} class="pclass">{$brand.name} </span>  {if $brand.count !=0} <span class="count">({$brand.count})</span> {/if}</span></p></li>
      {/if}
    {/foreach}
</ul>
</div>
{if !empty($items.is_trm)}
  
{foreach from=$items.is_trm name="ranges" key="rangekey" item="range"}
{if $range.name==y}
<div>
<h4 id="brands_fct_5" style="margin-top:1px; cursor: pointer;" class="margin_top_ten  slide_toggle_fct box_expand">{$lang.trm_filter} {if !empty($smarty.request.is_trm)}<span class="clear_filter">Clear</span>{/if}</h4>
<ul class="trm_fct search_dynamic_filter" id="trm_fct" style="clear:both;">
 <li><p class="a_hover_cursor"><input type="checkbox" {if $range.count eq "0"} disabled="disabled"{/if} id="sh_option{$range.id}" style="float:left; margin:0px 4px 0 0px" name="is_trm[]" value="{$range.id}" rev="is_trm"  onclick="return new_search(this);" {if $range.id|in_array:$smarty.request.is_trm} checked {/if} /><span {if $range.count == "0"} style="color:#999;" {/if} {if $range.count != "0"} onclick="return chk_sh_option('{$range.id}');" {/if}>{$lang.trm_filter_value} {if $range.count !=0} <span class="count">({$range.count})</span> {/if}</span></p></li>
</ul>
</div>
{/if}
{/foreach}

{/if}

{if !empty($items.is_cod)}
{foreach from=$items.is_cod name="ranges" key="rangekey" item="range"}
{if $range.name=="y" && $range.count != 0}
<div>
<h4 id="brands_fct_5" style="margin-top:1px; cursor: pointer;" class="margin_top_ten  slide_toggle_fct box_expand">{$lang.solr_cod_filter} {if !empty($smarty.request.is_cod)}<span class="clear_filter">Clear</span>{/if}</h4>
<ul class="cod_fct search_dynamic_filter" id="cod_fct" style="clear:both;">
 <li><p class="a_hover_cursor"><input type="checkbox" {if $range.count eq "0"} disabled="disabled"{/if} id="sh_option{$range.id}" style="float:left; margin:0px 4px 0 0px" name="is_cod[]" value="{$range.id}" rev="is_cod"  onclick="return new_search(this);" {if $range.id|in_array:$smarty.request.is_cod} checked {/if} /><span {if $range.count == "0"} style="color:#999;" {/if} {if $range.count != "0"} onclick="return chk_sh_option('{$range.id}');" {/if}>{$lang.solr_cod_filter_value} {if $range.count !=0} <span class="count">({$range.count})</span> {/if}</span></p></li>
</ul>
</div>
{/if}
{/foreach}

{/if}

{foreach from=$items name="filters" key="filterkey" item="filter"}
          {if !empty($filter) && !in_array($filterkey, $config.solr_exclude_filters)}
{*$smarty.request.$filterkey|print_r*}
<div>
		{assign var="filtername" value=$filterkey|fn_show_solr_filterkey}
		{assign var="is_global" value=$filtername|strstr:"O "}
		{if !empty($is_global)}
			{assign var="showname" value=$filtername|substr:2}
		{else}
			{assign var="showname" value=$filterkey|fn_show_solr_filterkey}
		{/if}

		<h4 id="{$filterkey}" {if $smarty.foreach.filters.iteration != "1" }class="slide_toggle_fct box_expand margin_top_ten"{/if}>
			{if $filterkey =='show_merchant'}{$lang.show_merchant}{else}{$showname}{/if}{if !empty($smarty.request.$filterkey)}<span class="clear_filter">Clear</span>{/if}</h4>

		<div class="clearboth"></div>
		<ul class="product-filters" id="content_product_more_filters_{$block.block_id}_0{$smarty.foreach.filters.iteration}" rel="{$filterkey}" style="overflow-y:scroll; max-height: 280px; overflow-x: hidden; min-height: 30px;" >
		{foreach from=$filter name="ranges" key="rangekey" item="range"}
		{if $range.count !=0}
			{if empty($is_global)}
	 			<li><p class="a_hover_cursor"><input  type="checkbox" {if $range.count eq "0"} disabled="disabled"{/if} id="sh_option{$range.id}" style="float:left; margin:0px 4px 0 0px;" name="{$filterkey}[]" value="{$range.id}" rev="{$filterkey}"  onclick="return new_search(this);" {if $range.id|in_array:$smarty.request.$filterkey} checked {/if} /><span {if $range.count == "0"} style="color:#999;" {/if} {if $range.count != "0"} onclick="return chk_sh_option('{$range.id}');" {/if}>{$range.name} {if $range.count !=0} {if empty($is_global)}<span class="count">({$range.count})</span> {/if} {/if}</span></p></li>
	 		{else}
				{if $filtername|strstr:"Color"}
	 				<li style="background-color:{$range.name};float: left; width: auto; height:25px; width:25px;border-bottom:1px solid #eee !important;"><input  type="checkbox" {if $range.count eq "0"} disabled="disabled"{/if} id="sh_option{$range.id}" style="float:left; margin:0px 4px 0 0px; display: none;" name="{$filterkey}[]" value="{$range.id}" rev="{$filterkey}"  onclick="return new_search(this);" {if $range.id|in_array:$smarty.request.$filterkey} checked {/if} />
					<span class="img_colorHover_check {if $range.id|in_array:$smarty.request.$filterkey} select {/if}" alt="{$range.name}" title="{$range.name}" {if $range.count != "0"} onclick="return chk_sh_option('{$range.id}');" {/if}>&nbsp;</span></li>
				{else}					
					<li style="border-bottom: 1px solid #ccc !important;" class="globalOptions {if $range.id|in_array:$smarty.request.$filterkey}select{/if}"><input  type="checkbox" {if $range.count eq "0"} disabled="disabled"{/if} id="sh_option{$range.id}" style="float:left; margin:0px 4px 0 0px; display: none;" name="{$filterkey}[]" value="{$range.id}" rev="{$filterkey}"  onclick="return new_search(this);" {if $range.id|in_array:$smarty.request.$filterkey} checked {/if} />
					<span {if $range.count != "0"} onclick="return chk_sh_option('{$range.id}');" {/if}>{$range.name} {if $range.count !=0} {if empty($is_global)}<span class="count">({$range.count})</span> {/if} {/if}</span></li>
				{/if}					
			{/if}
 		{/if}
		{/foreach}
		</ul>
</div>
 	{/if}
{/foreach}

<div>
<h4 id="brands_fct_3" style="margin-top:1px; cursor: pointer;" class="margin_top_ten  slide_toggle_fct box_expand">By Discount {if !empty($smarty.request.df)}<span class="clear_filter">Clear</span>{/if}</h4>
 <ul class="discount_fct" id="discount_fct" style="clear:both;">    
       {foreach from=$items.discount_percentage name="pricename" key="pricekey" item="priceval" }
		{assign var="shkey" value=$priceval.key|replace:' ':''} 
		{assign var="shkey1" value=$priceval.key|fn_assign_discountkey:"val"}
		{assign var="shval" value=$shkey1|fn_showdiscount}
             <li><p class="a_hover_cursor"><input type="checkbox" {if $priceval.val eq "0"} disabled="disabled"{/if} id="sh_option{$shkey1}" style="float:left; margin:0px 4px 0 0px" name="df[]" rev="discount_percentage" value={$shkey1} onclick="return new_search(this);" {if $shkey1|in_array:$smarty.request.df} checked {/if} /><span {if $priceval.val == "0"} style="color:#999;" {/if} {if $priceval.val != "0"} onclick="return chk_sh_option('{$shkey1}');" {/if}>{$shval} {if $priceval.val !=0} <span class="count">({$priceval.val})</span> {/if}</span></p></li>
        {/foreach}
</ul>
</div>

<div>
<h4 id="brands_fct_4" style="margin-top:1px; cursor: pointer;" class="margin_top_ten  slide_toggle_fct box_expand">Availability {if !empty($smarty.request.product_amount_available)}<span class="clear_filter">Clear</span>{/if}</h4>
<ul class="percentage_fct" id="percentage_fct" style="clear:both;">
{foreach from=$items.product_amount_available name="ranges" key="rangekey" item="range"}
{if $range.name==1}
		<li><p class="a_hover_cursor"><input type="checkbox" {if $range.count eq "0"} disabled="disabled"{/if} id="sh_option{$range.id}" style="float:left; margin:0px 4px 0 0px" name="product_amount_available[]" value="{$range.id}" rev="product_amount_available"  onclick="return new_search(this);" {if $range.id|in_array:$smarty.request.product_amount_available} checked {/if} /><span {if $range.count == "0"} style="color:#999;" {/if} {if $range.count != "0"} onclick="return chk_sh_option('{$range.id}');" {/if}>Exclude Out of stock</span></p></li>
{/if}
{/foreach}
</ul>
</div>

{else}			


{foreach from=$items item="filter" name="filters"}
<div>
	<h4 {if $smarty.foreach.filters.iteration != "1" }class="slide_toggle_fct box_expand margin_top_ten"{/if}>{$filter.filter}</h4>
	
{if $config.category_solr}
	<ul class="product-filters" id="content_product_more_filters_{$block.block_id}_0{$smarty.foreach.filters.iteration}" {if $filter.feature_id == '53'} style="overflow-y:scroll; max-height: 280px; overflow-x: hidden; min-height: 30px;" {/if}>
{else}
	<ul class="product-filters" id="content_product_more_filters_{$block.block_id}_{$filter.filter_id}">
{/if}
{if $config.category_solr && $filter.filter_id == '1'}
	{assign var="allfilter" value=''|fn_assign_filtertoitem}
       {foreach from=$allfilter.price name="pricename" key="pricekey" item="priceval" }
		{assign var="shkey" value=$priceval.key|replace:' ':''} 
		{assign var="shkey1" value=$priceval.key|fn_assign_pricekey:"val"}
		{assign var="shval" value=$shkey1|fn_showprice}
		<li><p class="a_hover_cursor"><input type="checkbox" {if $priceval.val eq "0"} disabled="disabled"{/if} id="sh_option{$shkey1}" style="float:left; margin:0px 4px 0 0px" name="fq[]" rev="price" value={$shkey1} onclick="return new_search(this);" {if $shkey1|in_array:$smarty.request.fq} checked {/if} /><span {if $priceval.val == "0"} style="color:#999;" {/if} onclick="return chk_sh_option('{$shkey1}');">{$shval}</span></p></li>
	{/foreach} 
{/if}		
	{if $smarty.request.features_hash|is_array}
			{foreach from=$filter.ranges name="ranges" item="range"}
				{if $controller == "index"}
					<li {if $smarty.foreach.ranges.iteration > $smarty.const.FILTERS_RANGES_COUNT}class="hidden"{/if}>
				{else}
					<li>
				{/if}
				
				<p class="a_hover_cursor">
				{if $range.filter_id == '1'}
					<input type="checkbox" id="sh_option{$range.range_id}" style="float:left; margin:0px 4px 0 0px; height: 18px;" name="features_hash[]" value="P{$range.range_id}" onclick="return new_search(this);" {if 'P'|cat:$range.range_id != $last_search} {if 'P'|cat:$range.range_id|in_array:$smarty.request.features_hash} checked {/if}{/if} />
				{else}
					<input type="checkbox" id="sh_option{$range.range_id}" style="float:left; margin:0px 4px 0 0px; height: 18px;" name="features_hash[]" value="{$range.filter_id}.{$range.range_id}" onclick="return new_search(this);" {if $range.filter_id|cat:"."|cat:$range.range_id != $last_search}{if $range.filter_id|cat:"."|cat:$range.range_id|in_array:$smarty.request.features_hash} checked {/if} {/if} />
				{/if}
					<span  onclick="chk_sh_option({$range.range_id});">{$range.range_name|fn_text_placeholders}</span>
					{*if $controller != "index" && $controller != "products" && !$smarty.request.features_hash|is_array*}
					<span class="details">&nbsp;({$range.products})</span>
					{*/if*}

				</p></li>
			{/foreach}

	{else}

	{foreach from=$filter.ranges name="ranges" item="range"}
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
					<input type="checkbox" id="sh_option{$range.range_id}" style="float:left; margin:0px 4px 0 4px; height: 18px;" name="features_hash[]" value="{$filter_query_elm}" onclick="return new_search(this);" />
{else}
					<input type="checkbox" id="sh_option{$range.range_id}" style="float:left; margin:0px 4px 0 4px; height: 18px;" name="features_hash[]" value="{$filter.filter_id}.{$range.range_id}" onclick="return new_search(this);" />
{/if}
				{*<a href="{$filter_qstring|fn_link_attach:"features_hash=`$filter_query_elm`"|fn_url}"{if $filter.feature_type != "E"} rel="nofollow"{/if}>

	{$filter.prefix}*} 
				<span onclick="chk_sh_option({$range.range_id});">{$range.range_name|fn_text_placeholders}</span>
	{*{$filter.suffix}*}

			{if $controller != "index"}
			    	<span class="details">
				({$range.products})</span>
			{/if}</p>
		    {*</a>*}
		    
		    {*<a href="{if $filter.feature_type == "E" && !$filter.simple_link}{"product_features.view?variant_id=`$range.range_id``$cur_features_hash`"|fn_url}{else}{$filter_qstring|fn_link_attach:"features_hash=`$filter_query_elm`"|fn_url}{/if}"{if $filter.feature_type != "E"} rel="nofollow"{/if}>{$filter.prefix}{$range.range_name|fn_text_placeholders}{$filter.suffix}</a>
		    <span class="details">&nbsp;({$range.products})</span>*}
			{/if}
			{/strip}
	{/foreach}
</div>
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
		<div class="float_left margin_left_five">
		<a onclick="$('#content_product_more_filters_{$block.block_id}_{$filter.filter_id} li').show(); $('#view_all_{$block.block_id}_{$filter.filter_id}').show(); $(this).hide(); return false;" class="link-left link_custom extra-link"> {$lang.more}</a>
		</div>
		{/if}
	{/if}
		{*{if $smarty.foreach.ranges.iteration > $smarty.const.FILTERS_RANGES_COUNT}
		<div class="float_left margin_left_five">
		<a onclick="$('#content_product_more_filters_{$block.block_id}_{$filter.filter_id} li').show(); $('#view_all_{$block.block_id}_{$filter.filter_id}').show(); $(this).hide(); return false;" class="link-left link_custom extra-link"> {$lang.more}</a>
		</div>
		<div class="float_right margin_right_five">
				{if $filter.filter == $lang.filter_by_brands}			         
				    {if $filter.more_cut}
					{capture name="q"}{$filter_qstring|unescape}&filter_id={$filter.filter_id}&{if $smarty.request.features_hash}&features_hash={$smarty.request.features_hash|fn_delete_range_from_url:$range:$filter.field_type}{/if}{/capture}
					{assign var="capture_q" value=$smarty.capture.q|escape:url}
					<a href="brands" rel="nofollow" class="link_custom extra-link">{$lang.all_brands}</a>
					{/if}
				{/if}
		</div>
		{/if}*}

{/foreach}


{/if}

{/if}

<div class="clear filters-tools float_right margin_right_five float_right">
	<!--div class="float-right"><a {if "FILTER_CUSTOM_ADVANCED"|defined}href="{"products.search?advanced_filter=Y"|fn_url}"{else}href="{$filter_qstring|fn_link_attach:"advanced_filter=Y"|fn_url}"{/if} rel="nofollow" class="secondary-link lowercase">{$lang.advanced}</a></div-->
	{if $has_selected}
	<a href="{if $smarty.request.category_id}{"categories.view?category_id=`$smarty.request.category_id`"|fn_url}{else}{$index_script|fn_url}{/if}" rel="nofollow" class="reset-filters">{$lang.reset}</a>
	{/if}

     {if $smarty.request.features_hash|is_array && $smarty.request.features_hash|count > 1}
		<a href="javascript:;" onclick="return deselect_all(this);">{$lang.deselect}</a>
     {else}
		<a href="brands" rel="nofollow" class="link_custom extra-link" style="display:none;">{$lang.all_brands}</a>
     {/if}
</div>
<input type="hidden" id="fsrc" name="fsrc" value="" />
<input type="hidden" id="features_hash" name="features_hash[]" value="" />
		<input type="submit" name="dispatch" value="{$curr_url}" id="newsearch_filter" style="display:none;" />
{/if}
</form>
<div class="clearboth"></div>

{literal}
<script>

$(document).ready(function(){
$('.clear_filter').click(function(event){
	event.stopPropagation();
		$(this).parent().parent().find('li').find('input:checkbox').removeAttr('checked');
		document.getElementById('newsearch_filter').click();
	});

if($(window).width()<630)
	{
$('.box_expand').each(function(i, obj) {
$(this).addClass("box_collapse").removeClass("box_expand");
});
	}
});

function new_search(obj){
    var fsrc = $(obj).attr('rev');
    $('#fsrc').val(fsrc+':'+$(obj).val());
    //$('#features_hash').val(fsrc+':'+$(obj).val());
    document.getElementById('newsearch_filter').click();
}

function deselect_all(obj){
	$('#option_unchk').attr('checked','checked');
	$('input:checkbox').removeAttr('checked');
	document.getElementById('newsearch_filter').click();
}
function chk_sh_option(obj){
        var fsrc = $('#sh_option'+obj).attr('rev');
        $('#fsrc').val(fsrc+':'+$('#sh_option'+obj).val());
	if($('#sh_option'+obj+':checkbox:checked').length == "1"){
		$('#sh_option'+obj).removeAttr("checked");
        } else {
                $('#sh_option'+obj).attr('checked','checked');
        }
	document.getElementById('newsearch_filter').click();
}
    
    $('.slide_toggle_fct').click(function(){
        if($(this).next().hasClass('clearboth') && $(this).next().attr('id') == "" && $(this).next().next().is("ul")){
            var target_id = $(this).next().next().attr('id');
        }else{
            var target_id = $(this).next().attr('id');
        }
        $('#'+target_id).slideToggle();
        if($('#'+this.id).attr('id')=="brands_fct_1") { $('#brands_fct').slideToggle(); }

        if($('#'+this.id).hasClass('box_expand')){
           $('#'+this.id).removeClass('box_expand'); 
           $('#'+this.id).addClass('box_collapse');
        }else{
           $('#'+this.id).removeClass('box_collapse');
           $('#'+this.id).addClass('box_expand');        
        }
    });
        

	var controller = "{/literal}{$controller}{literal}";
	if(controller != 'index') {
		$("ul[id^='content_product_more_filters_20_']").each(function() {
		  var id = this.id.replace("content_product_more_filters_20_", "");
		  
		  if(id!='') {
			  var ulHeight = $("#content_product_more_filters_20_" + id).height();
			  if(ulHeight < 280) {
			  	$("#content_product_more_filters_20_" + id).removeAttr( 'style' );
			  }

			  // hide ul if li is empty
			  if($("#content_product_more_filters_20_" + id).find("li").length == 0) {
			    var relid = $("#content_product_more_filters_20_" + id).attr( 'rel' );
			    if(relid!='') $('#'+relid).hide();
			  }			  
		  }
		});
	}

$('input[name="brand_search"]').keyup(function (element) {
	var wordToSearch = $(element.target).val();
	$("#brands_fct li p .pclass").each(function(index, obj) {
	var s = new RegExp("^"+wordToSearch, "gi");
	 if($(obj).html().match(s)){
	  $(obj).parent().parent().show();
	 }
	 else{
	  $(obj).parent().parent().hide();
	 }
	});
});

</script>
<style>
.a_hover_cursor:hover{cursor:pointer;}
.count {color: #888;font-size: 10px;}
.clear_filter { font-size: 11px; cursor: pointer;font-weight: normal;font-variant: normal;float:right;}
.clear_filter:hover{ text-decoration: underline;}
</style>
{/literal}

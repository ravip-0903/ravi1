{script src="addons/block_packs/js/block_packs.js"}
{script src="addons/block_packs/js/jquery.block_packs.js"}

<script type="text/javascript">
var items{$block.block_id} = [];
</script>

<div class="block_packs-main">
	<div class="block_packs-container-{$block.block_id}">
		<!-- <div class="pagination cm-block_packs-pagination-list right"></div> -->
		<div class="clear"></div>
		<div class="hot-block_packs-skin-tango">
			<div class="hot-block_packs-container clear">
				<div class="hot-block_packs-prev cm-block_packs-left"></div>
				<div class="hot-block_packs-next cm-block_packs-right"></div>
				<div class="hot-block_packs-centered">
    				<div class="hot-block_packs-list">
    					{section name="index" loop=$params.num_rows}
    						<div class="hot-block_packs-item cm-block_packs-item-{$smarty.section.index.index}"></div>
    					{/section}
    				</div>
				</div>
			</div>
		</div>
		
		<div>	
			{foreach name="products" from=$items item="product"}
				{if $product.product}
					{foreach from=$product.category_ids key=cat_id item=cat_main}
						{if $cat_main == "M"}
							{include file="common_templates/image.tpl" image_width=115 image_height=115 obj_id="`$block.block_id`_`$product.product_id`" images=$product.main_pair object_type="product" capture_image=true show_thumbnail="Y"}
							<script type="text/javascript">
								//<![CDATA[
								items{$block.block_id}[{$smarty.foreach.products.iteration-1}] = {$ldelim}name: '{$product.product|html_entity_decode|strip_tags|truncate:50:"...":true}', link: '{"products.view?product_id=`$product.product_id`"|fn_url}', image: '{$smarty.capture.icon_image_path}', cat_id: {$cat_id}, width: 115, height: 115, price:{$product.price}, list_price:{$product.list_price}{$rdelim};
								//]]>
							</script>
						{/if}
					{/foreach}
				{/if}
			{/foreach}
			
			{foreach name="currencies" from=$currencies item="currency"}
				{if $currency.is_primary=='Y'}
				<script type="text/javascript">
					//<![CDATA[
						currencySymbol = "{$currency.symbol}";
					//]]>
				</script>
				{/if}
			{/foreach}
		</div>
	</div>
</div>

<script type="text/javascript">
jQuery(document).ready(function()
{$ldelim}
	parent_elm = jQuery('.block_packs-container-{$block.block_id}');
	var block_packs{$block.block_id} = new Block_packs(items{$block.block_id}, parent_elm, {$block.block_id}, false, {$params.num_rows});
{$rdelim});
</script>

<!-- 
<div class="block_pack_content">
<div id="block-packs-btn"><img onclick="fn_block_packs_prev({$block.block_id})" src="{$images_dir}/icons/calendar_previous.gif" /></div>

    {foreach name="products" from=$items item="product"}
    <div class="block-packs-item">
    	<div class="block-packs-image">
    		<a href="?dispatch=products.view&product_id={$product.product_id}">{include file="common_templates/image.tpl" image_height=115 obj_id="`$block.block_id`_`$product.product_id`" images=$product.main_pair object_type="product" capture_image=false show_thumbnail="Y"}</a>
    	</div>
    	<div class="block-packs-label">
    		{$product.product}
    	</div>
    </div>
    {/foreach}

<div id="block-packs-btn"><img onclick="fn_block_packs_next({$block.block_id})" src="{$images_dir}/icons/calendar_next.gif" /></div>

</div> -->
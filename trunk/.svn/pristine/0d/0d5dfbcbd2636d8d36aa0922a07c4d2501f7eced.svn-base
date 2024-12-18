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
			{*$product|var_dump*}
				{if $product.product}
					{foreach from=$product.category_ids key=cat_id item=cat_main}
						{if $cat_main == "M"}
							<!--Modified by clues dev to add new mark on product-->
                {assign var="is_new" value=$product|check_product_for_new}
                {assign var="is_ngo" value=""}
                {assign var="is_ngo" value=$product.company_id|fn_check_merchant_for_ngo}
                
              <!--Modified by clues dev to add new mark on product--> 
              {include file="common_templates/image.tpl" image_width=200 image_height=200 obj_id="`$block.block_id`_`$product.product_id`" images=$product.main_pair object_type="product" capture_image=true show_thumbnail="Y"}
							
                            <!--Modified by clues dev-->
                                 	{assign var="disc_perc" value="0"}
                                    {if $product.discount_prc && $product.discount_prc!=''}
                                      {assign var="disc_perc" value=$product.discount_prc}
                                    {elseif $product.list_discount_prc && $product.list_discount_prc!=''}
                                      {assign var="disc_perc" value=$product.list_discount_prc}
                                    {else}
                                      {assign var="disc_perc" value="0"}
                                    {/if}
                                    {if $disc_perc>=50}
                                     {assign var="styles" value="label_discount_fullwidth_first"}
                                    {elseif $disc_perc>=0 and $disc_perc<=49}
                                     {assign var="styles" value="label_discount_fullwidth_second"}
                                  	{/if}
                                
                                <!-- Modified by clues dev -->
							<script type="text/javascript">
								//<![CDATA[
								items{$block.block_id}.push({$ldelim}name: '{$product.product|html_entity_decode|strip_tags|truncate:50:"...":true}', link: '{"products.view?product_id=`$product.product_id`"|fn_url}', image: '{$smarty.capture.icon_image_path}', pro_discount: '{$disc_perc}', style: '{$styles}', is_new: '{$is_new}', is_ngo: '{$is_ngo}', cat_id: {$cat_id}, width: 160, height: 160, price:{$product.price}, list_price:{$product.list_price}{$rdelim});
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
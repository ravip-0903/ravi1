{* $Id: view.tpl 12544 2011-05-27 10:34:19Z bimib $ *}

{hook name="categories:view"}
{if !$category_data.category_id|in_array:$config.special_sale_category_id}

	{if $smarty.request.advanced_filter}
		{include file="views/products/components/product_filters_advanced_form.tpl" separate_form=true}
	{/if}

        
        {if $category_data.is_nrh == "Y"}
            {include file="views/categories/sub_category.tpl}
        {else}
            {if $products && empty($products_similar) && ($category_data.show_product_listing == 'Y' || isset($smarty.request.features_hash))}
                    {assign var="layouts" value=""|fn_get_products_views:false:0}
                    {if $category_data.product_columns}
                            {assign var="product_columns" value=$category_data.product_columns}
                    {else}
                            {assign var="product_columns" value=$settings.Appearance.columns_in_products_list}
                    {/if}

                    {if $layouts.$selected_layout.template}
                            {include file="`$layouts.$selected_layout.template`" columns=`$product_columns`}
                    {/if}

            {elseif !$subcategories && ($category_data.show_product_listing == 'Y' || isset($smarty.request.features_hash)) && !$search}
                    <p class="no-items">{$lang.text_no_products}</p>
            {elseif empty($products) && $category_data.show_product_listing == 'Y'  && $under_nrh == 'Y'}
                <p>{$lang.text_no_products_nrh}</p>
            {/if}
        {/if}
{else}
	{if $category_data.description && $category_data.description != ""}
       
		<script type="text/javascript">
		//<![CDATA[	
		//$(document).ready(function() {$ldelim}
			// create a new date and insert it
			var EndDate = new Date({$lang.sale_end_datetime});
			$.countdown('#box_timersale1', EndDate);
		//{$rdelim});	
		//]]>
		</script>
		
		<script type="text/javascript">
		//<![CDATA[	
		//$(document).ready(function() {$ldelim}
			// create a new date and insert it
			var EndDate = new Date({$lang.sale_end_datetime});
			$.countdown('#box_timersale2', EndDate);
		//{$rdelim});	
		//]]>
		</script>
	{/if}
{/if}
{if $search}
	{if $products_similar}
		{*<p class="no-items">{$lang.text_no_matching_products_found}</p>*}
		{if $last_search}
			{assign var="option_selected" value=$last_search|shopping_option_name}
			{$lang.matching_records|replace:'[option_selected]':$option_selected}
		{else}
			<p class="no-items">{$lang.text_no_matching_products_found}</p>
		{/if}
		{if $products && ($category_data.show_product_listing == 'Y' || isset($smarty.request.features_hash))}
			{assign var="layouts" value=""|fn_get_products_views:false:0}
			{if $category_data.product_columns}
				{assign var="product_columns" value=$category_data.product_columns}
			{else}
				{assign var="product_columns" value=$settings.Appearance.columns_in_products_list}
			{/if}

			{if $layouts.$selected_layout.template}
				{include file="`$layouts.$selected_layout.template`" columns=`$product_columns`}
			{/if}
		{/if}
	{/if}
{/if}
{capture name="mainbox_title"}{$category_data.category}{/capture}
{/hook}
{literal}
<script type="text/javascript">
  var piwik_switch="{/literal}{$config.piwik_switch}{literal}";
  if(piwik_switch){
	   var _paq = _paq || [];
	   _paq.push(["setCookieDomain", "*.shopclues.com"]);
	   var dispatch = "{/literal}{$smarty.request.dispatch}{literal}";
	   var category_id = "{/literal}{$smarty.request.category_id}{literal}";
	   var count="{/literal}{$product_count}{literal}";
	   var user_id="{/literal}{$smarty.session.auth.user_id}{literal}";
	   if(user_id ==0){
	   var user_id="logged out";
	   }
	   _paq.push(['setCustomVariable',1,"user id",user_id,scope="page"]); 
	   if(dispatch =="categories.view"){
	   _paq.push(['setCustomVariable',3,"category",category_id,scope="page"]);
	   }
	  
  }
 //Added by lijo for TM Single Api call Category Page starts here

  $(document).ready(function(){
  	

  	function get_tm_cat_data(){

           var limit = {/literal}{$config.TM_limit_single_api}{literal};
           var cat_tm_blocks = {/literal}"{$config.cat_tm_blocks}"{literal};
           var cat_id = {/literal}{$smarty.request.category_id}{literal};
           var tm_url = "http://api.targetingmantra.com/TMWidgets?w="+cat_tm_blocks+"&mid=130915&limit="+limit +"&json=true&catid="+cat_id+"&callback=?";
				   
		   $.getJSON(tm_url,function(data){

							
							window.tm_array = JSON.stringify(data);
							try{
								best_seller();	
							   }
							catch(err){

							}
							try{
								new_arrival();	
							   }
							catch(err){
								
							}
							try{
								recent_recommend_category();
							   }
							catch(err){
								
							}
							
							
					 })

						.fail(function() {
							  return true;
							});


            
        }

		var single_api_tm = {/literal}{$config.single_api_tm}{literal}; 

			if(single_api_tm) {

				get_tm_cat_data(); 

			}           
  });
  
//Added by lijo for TM Single Api call Category Page ends here

 </script> 


{/literal}

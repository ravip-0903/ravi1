{* $Id: view.tpl 10654 2010-09-16 10:54:41Z klerik $ *}

{capture name="val_hide_form"}{/capture}
{capture name="val_capture_options_vs_qty"}{/capture}
{capture name="val_capture_buttons"}{/capture}
{capture name="val_separate_buttons"}{/capture}
{capture name="val_no_ajax"}{/capture}

{hook name="products:layout_content"}
{include file=$product.product_id|fn_get_product_details_layout product=$product show_sku=true show_rating=true show_old_price=true show_price=true show_list_discount=true show_clean_price=true details_page=true show_discount_label=true show_product_amount=true show_product_options=true hide_form=$smarty.capture.val_hide_form show_qty=true min_qty=true show_edp=true show_add_to_cart=true show_list_buttons=true but_role="action" capture_buttons=$smarty.capture.val_capture_buttons capture_options_vs_qty=$smarty.capture.val_capture_options_vs_qty separate_buttons=$smarty.capture.val_separate_buttons show_add_to_cart=true show_list_buttons=true but_role="action" block_width=true no_ajax=$smarty.capture.val_no_ajax}
{/hook}
{assign var="productId" value= $smarty.request.product_id}
{assign var="customerId" value=$smarty.session.auth.user_id}
{if $product.amount == 0}
{assign var="in_stock_qty" value= 0}
{else}
{assign var="in_stock_qty" value= 1}
{/if}
{assign var="targeting_mantra_price" value=''}
{if $product.promotion_id !=0}
    {assign var="targeting_mantra_price" value=$product|fn_get_3rd_price}
{else}
    {assign var="targeting_mantra_price" value=$product.price}
{/if}

{if $targeting_mantra_price ==''}
    {assign var="targeting_mantra_price" value=$product.price}
{/if}
<img src='http://api.targetingmantra.com/RecordEvent?mid=130915&eid=1&pid={$productId}&cid={$customerId}&stk={$in_stock_qty}&prc={$targeting_mantra_price}' width='1' height='1'>

{literal}
<script type="text/javascript">
  
  var piwik_switch="{/literal}{$config.piwik_switch}{literal}";
  
  if(piwik_switch){
	   var _paq = _paq || [];
	   _paq.push(["setCookieDomain", "*.shopclues.com"]);
	   var dispatch = "{/literal}{$smarty.request.dispatch}{literal}";
	   var product_id = "{/literal}{$smarty.request.product_id}{literal}";
	   var count="{/literal}{$product_count}{literal}";
	   var user_id="{/literal}{$smarty.session.auth.user_id}{literal}";
	   if(user_id ==0){
	   var user_id="logged out";
	   }
	   
	   _paq.push(['setCustomVariable',1,"user id",user_id,scope="page"]); 
	   if(dispatch =="products.view"){
	    _paq.push(['setCustomVariable',4,"product id",product_id,scope="page"]);
	   }
	   
  }
 </script> 
{/literal}
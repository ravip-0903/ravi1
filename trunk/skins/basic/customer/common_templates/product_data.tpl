{if $controller=='products' and $mode=='view'}
{literal}<script>
    var product_price_arr = '{/literal}{$product_price_arr}{literal}';
    var temp_var = document.createElement('textarea');
    temp_var.innerHTML = product_price_arr;
    product_price_arr=JSON.parse(temp_var.value);
    </script>{/literal}
{/if}

{if ($product.price|floatval || $product.zero_price_action == "P" || $product.zero_price_action == "A" || (!$product.price|floatval && $product.zero_price_action == "R")) && !($settings.General.allow_anonymous_shopping == "P" && !$auth.user_id)}
  {assign var="show_price_values" value=true}
{else}
  {assign var="show_price_values" value=false}
{/if}
{capture name="show_price_values"}{$show_price_values}{/capture}

{assign var="cart_button_exists" value=false}
{assign var="obj_id" value=$obj_id|default:$product.product_id}
{assign var="product_amount" value=$product.inventory_amount|default:$product.amount}

{capture name="form_open_`$obj_id`"}
{if !$hide_form}
<form {if $controller =='product_quick_view'} id="cart_form"{/if} action="{""|fn_url}" method="post" name="product_form_{$obj_prefix}{$obj_id}" {if !$hide_enctype} enctype="multipart/form-data" {/if} class="cm-disable-empty-files{if $settings.DHTML.ajax_add_to_cart == "Y" && !$no_ajax && !$config.isResponsive} cm-ajax{/if}">
<input type="hidden" name="result_ids" value="cart_status,wish_list" />
{if !$stay_in_cart}
<input id="product_quick_redirect" type="hidden" name="redirect_url" value="{$config.current_url}" />
{/if}
<input type="hidden" name="product_data[{$obj_id}][product_id]" value="{$product.product_id}" />
{/if}
{/capture}
{if $no_capture}
  {assign var="capture_name" value="form_open_`$obj_id`"}
  {$smarty.capture.$capture_name}
{/if}

{capture name="name_`$obj_id`"}
  {if $show_name}
    {* [andyye]: free-shipping class was added *}
              {if $controller=='products' and $mode=='search'}
    {if $hide_links}<strong>{else}<a href="{"products.view?product_id=`$product.product_id`"|fn_url}{$url}" class="product-title{if $product.free_shipping == 'Y'} free-shipping{/if}" onclick="productCookie('{$product.product_id}','{$key}','{$last_price}');">{/if}{$product.product|unescape}{if $hide_links}</strong>{else}</a>{/if}
        {else}
               {if $hide_links}<strong>{else}<a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="product-title{if $product.free_shipping == 'Y'} free-shipping{/if}" onclick="productCookie('{$product.product_id}','{$key}','{$last_price}');">{/if}{$product.product|unescape}{if $hide_links}</strong>{else}</a>{/if}
              {/if}
        {elseif $show_trunc_name}
             {if $controller=='products' and $mode=='search'}
    {if $hide_links}<strong>{else}<a href="{"products.view?product_id=`$product.product_id`"|fn_url}{$url}" class="product-title{if $product.free_shipping == 'Y'} free-shipping{/if}" title="{$product.product|strip_tags}" onclick="productCookie('{$product.product_id}','{$key+1}','{$last_price}');">{/if}{$product.product|unescape|truncate:45:"...":true}{if $hide_links}</strong>{else}</a>{/if}
             {else}
                {if $hide_links}<strong>{else}<a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="product-title{if $product.free_shipping == 'Y'} free-shipping{/if}" title="{$product.product|strip_tags}" onclick="productCookie('{$product.product_id}','{$key+1}','{$last_price}');">{/if}{$product.product|unescape|truncate:45:"...":true}{if $hide_links}</strong>{else}</a>{/if}
            {/if}
        {/if}
{/capture}
{if $no_capture}
  {assign var="capture_name" value="name_`$obj_id`"}
  {$smarty.capture.$capture_name}
{/if}

{capture name="sku_`$obj_id`"}
  {if $show_sku}
    {if !$product.product_code} 
          &nbsp;
        {else}
        <p class="sku{if !$product.product_code} hidden{/if}">
      <span class="cm-reload-{$obj_prefix}{$obj_id}" id="sku_update_{$obj_prefix}{$obj_id}">
        <input type="hidden" name="appearance[show_sku]" value="{$show_sku}" />
        <span id="sku_{$obj_prefix}{$obj_id}">{$lang.sku}: <span id="product_code_{$obj_prefix}{$obj_id}">{$product.product_code}</span></span>
      <!--sku_update_{$obj_prefix}{$obj_id}--></span>
    </p>
        {/if}
  {/if}
    
{/capture}
{if $no_capture}
  {assign var="capture_name" value="sku_`$obj_id`"}
  {$smarty.capture.$capture_name}
{/if}

{capture name="rating_`$obj_id`"}
  {hook name="products:data_block"}
  {/hook}
{/capture}
{if $no_capture}
  {assign var="capture_name" value="rating_`$obj_id`"}
  {$smarty.capture.$capture_name}
{/if}

{capture name="add_to_cart_`$obj_id`"}
{if $show_add_to_cart}
<div>
    <div class="mob_add_to_cart cm-reload-{$obj_prefix}{$obj_id} {if $actual_amount == 0}{if ($product.out_of_stock_actions == "S") && ($product.tracking != "O")}prd_notify_blk_cntnr{/if}{/if}" id="add_to_cart_update_{$obj_prefix}{$obj_id}" {if $details_page} style="width:120px; float:left; " {/if}>
      <input type="hidden" name="appearance[show_add_to_cart]" value="{$show_add_to_cart}" />
      <input type="hidden" name="appearance[separate_buttons]" value="{$separate_buttons}" />
      <input type="hidden" name="appearance[show_list_buttons]" value="{$show_list_buttons}" />
      <input type="hidden" name="appearance[but_role]" value="{$but_role}" />
      {if $one_day_sale}
      <input type="hidden" name="one_day_sale" value=true />
      {/if}
      {if isset($smarty.request.layout)}
          <input type="hidden" name="appearance[layout]" value="{$smarty.request.layout}" />
      {/if}
      {hook name="products:buttons_block"}
      {if !($product.zero_price_action == "R" && $product.price == 0) && !($settings.General.inventory_tracking == "Y" && $settings.General.allow_negative_amount != "Y" && (($product_amount <= 0 || $product_amount < $product.min_qty) && $product.tracking != "D") && $product.is_edp != "Y")}
      <{if $separate_buttons}div class="buttons-container"{else}span{/if} id="cart_add_block_{$obj_prefix}{$obj_id}">
      {if $product.avail_since <= $smarty.const.TIME || ($product.avail_since > $smarty.const.TIME && $product.out_of_stock_actions == "B")}
      <div class="pro_det_add_to_cart_butto"> {hook name="products:add_to_cart"}
        {if $product.has_options && !$show_product_options && !$details_page}
            <a class="button-action_option" href="{"products.view?product_id=`$product.product_id`"|fn_url}">            {*$lang.new_buy_now*}&nbsp;</a>
       
       
       
       {*include file="buttons/button.tpl" but_id="button_cart_`$obj_prefix``$obj_id`" but_text=$lang.select_options but_href="products.view?product_id=`$product.product_id`" but_role="action" but_name=""*}
       
        {else}
        {if $extra_button}{$extra_button}&nbsp;{/if}
        {if $config.isResponsive}{assign var="but_text" value="Buy Now"}{else}{assign var="but_text" value="Buy Now"}{/if}
        {include file="buttons/add_to_cart.tpl" but_id="button_cart_`$obj_prefix``$obj_id`" but_name="dispatch[checkout.add..`$obj_id`]" but_role=$but_role block_width=$block_width obj_id=$obj_id product=$product but_text=$but_text}
        {assign var="cart_button_exists" value=true}
        {/if}
        {/hook}
       </div>
      {/if}
      </{if $separate_buttons}div{else}span{/if}>
      
      {elseif ($settings.General.inventory_tracking == "Y" && $settings.General.allow_negative_amount != "Y" && (($product_amount <= 0 || $product_amount < $product.min_qty) && $product.tracking != "D") && $product.is_edp != "Y")}
      {if !$details_page}
          {if (($product_amount <= 0 || $product_amount < $product.min_qty) && ($product.avail_since > $smarty.const.TIME))}
              {include file="common_templates/coming_soon_notice.tpl" avail_date=$product.avail_since add_to_cart=$product.out_of_stock_actions}
          {else} 
              <span class="strong out-of-stock" id="out_of_stock_info_{$obj_prefix}{$obj_id}">{$lang.text_out_of_stock}</span> 
          {/if}
      {elseif (($product.out_of_stock_actions == "S") && ($product.tracking != "O"))}
          <div class="form-field" style="padding-left:0px;">
            <label for="product_notify_{$obj_prefix}{$obj_id}" style="margin-left:0px; width:auto; white-space: normal;">
              <input id="product_notify_{$obj_prefix}{$obj_id}" type="checkbox" class="checkbox" name="product_notify" {if $product_notification_enabled == "Y"}checked="checked"{/if} onclick="
                            {if $auth.user_id eq 0}
                                $('#product_notify_email').attr('style', this.checked ? 'padding: 0px;' : 'display: none;');
                                $('#product_notify_email_{$obj_prefix}{$obj_id}').attr('disabled', this.checked ? '' : 'disabled');
                                if (!this.checked) {$ldelim}
                                    jQuery.ajaxRequest('{"products.product_notifications?enable="|fn_url:'C':'rel':'&'}' + 'N&product_id={$product.product_id}&email=' + $('#product_notify_email_{$obj_prefix}{$obj_id}').get(0).value, {$ldelim}cache: false{$rdelim});
                                {$rdelim}
                            {else}
                                $('#product_notify_email').attr('style', this.checked ? 'padding: 0px;display: block;' : 'display: none;');
                                $('#product_notify_email_{$obj_prefix}{$obj_id}').attr('disabled', this.checked ? '' : 'disabled');
                                jQuery.ajaxRequest('{"products.product_notifications?enable="|fn_url:'C':'rel':'&'}' + (this.checked ? 'Y' : 'N') + '&product_id=' + '{$product.product_id}', {$ldelim}cache: false{$rdelim});
                            {/if} "/>
              {$lang.notify_when_back_in_stock} </label>
          </div>
      {if $auth.user_id eq 0}
      <div class="notify_me_cntnr_box_blk">
          <div class="form-field notify_me_input_box_blk" id="product_notify_email" style="padding: 0px;{if $product_notification_enabled != "Y"} display: none;{/if}">
            <input class="notify_me_input_box" placeholder="Enter e-mail address" type="email" name="email" id="product_notify_email_{$obj_prefix}{$obj_id}" size="20" value="" class="input-text{if $product_notification_email == ''} cm-hint{/if}" disabled="disabled" />
           <span class="button-submit cm-button-main notify_me_input_box_btn_span"> <input title="{$lang.go}" class="go-button notify_me_input_box_btn" alt="{$lang.go}" onclick="if (jQuery.is.email($('#product_notify_email_{$obj_prefix}{$obj_id}').get(0).value) && !jQuery.is.blank($('#product_notify_email_{$obj_prefix}{$obj_id}').get(0).value)) {$ldelim}jQuery.ajaxRequest('{"products.product_notifications?enable=Y"|fn_url:'C':'rel':'&'}' + '&product_id=' + '{$product.product_id}' + '&email=' + $('#product_notify_email_{$obj_prefix}{$obj_id}').get(0).value, {$ldelim}method: 'get'{$rdelim}, {$ldelim}cache: false{$rdelim});{$rdelim} return false;" type="submit" value="Notify" /></span>
           </div>
       </div>
       
      {/if}
      {elseif (($product_amount <= 0 || $product_amount < $product.min_qty) && ($product.avail_since > $smarty.const.TIME))}
      {include file="common_templates/coming_soon_notice.tpl" avail_date=$product.avail_since add_to_cart=$product.out_of_stock_actions}
      {/if}
      {/if}
      
      {if !($config.express_checkout) || ($product_amount <= 0 || $product_amount < $product.min_qty) || !((!$product.promotion_id>0 && $config.hide_express_promotional_product) || !$config.hide_express_promotional_product)}
      {if $show_list_buttons}
      <{if $separate_buttons}div class="buttons-container"{else}span{/if} class="nl_add_wish_list" id="cart_buttons_block_{$obj_prefix}{$obj_id}">
      {hook name="products:buy_now"}
      {if $product.feature_comparison == "Y"}
      {if $separate_buttons}
      </div>
      <div class="buttons-container">
     {/if}
      {include file="buttons/add_to_compare_list.tpl" product_id=$product.product_id}
      {/if}
      {/hook}
      </{if $separate_buttons}div{else}span{/if}>
      {/if}
      {/if}
      </div>
    
    {if $details_page}
     {if $mode == "onedaysale"}
     {else}

     {if $config.express_checkout && !($product_amount <= 0 || $product_amount < $product.min_qty) && ((!$product.promotion_id>0 && $config.hide_express_promotional_product) || !$config.hide_express_promotional_product)}
    
        <a id="button_express_{$obj_id}" class="cm-submit-link express_checkout_button" name="dispatch[checkout.express_checkout]">{$lang.express_checkout}</a>
    
    {if $show_list_buttons}
    <{if $separate_buttons}div class="buttons-container"{else}span{/if} class="nl_add_wish_list express_checkout_wishlist" id="cart_buttons_block_{$obj_prefix}{$obj_id}">
        {hook name="products:buy_now"}
        {if $product.feature_comparison == "Y"}
            {if $separate_buttons}
            </div>
            <div class="buttons-container">
            {/if}
            {include file="buttons/add_to_compare_list.tpl" product_id=$product.product_id}
        {/if}
        {/hook}
    </{if $separate_buttons}div{else}span{/if}>
    {/if}
    {/if}

     {if $product.avail_since > $smarty.const.TIME}
      {include file="common_templates/coming_soon_notice.tpl" avail_date=$product.avail_since add_to_cart=$product.out_of_stock_actions}
      {/if}     
    <div class="clearboth"></div>
    {/if}
    {/if}
    
    
    {/hook} 
    <!--add_to_cart_update_{$obj_prefix}{$obj_id}--> 
  </div>
{/if}

      

<!--add_to_cart_update_{$obj_prefix}{$obj_id}-->


{/capture}
{if $no_capture}
  {assign var="capture_name" value="add_to_cart_`$obj_id`"}
  {$smarty.capture.$capture_name}
{/if}

{capture name="product_features_`$obj_id`"}
  {if $show_features}
    <div class="cm-reload-{$obj_prefix}{$obj_id}" id="product_features_update_{$obj_prefix}{$obj_id}">
      <input type="hidden" name="appearance[show_features]" value="{$show_features}" />
      {include file="views/products/components/product_features_short_list.tpl" features=$product.product_id|fn_get_product_features_list|escape no_container=true}
    <!--product_features_update_{$obj_prefix}{$obj_id}--></div>
    {/if}
{/capture}
{if $no_capture}
  {assign var="capture_name" value="product_features_`$obj_id`"}
  {$smarty.capture.$capture_name}
{/if}

{capture name="prod_descr_`$obj_id`"}
  {if $show_descr}
    {if $product.short_description}
      {$product.short_description|unescape}
    {else}
      {$product.full_description|unescape|strip_tags|truncate:160}{if !$hide_links && $product.full_description|strlen > 180} <a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="lowercase">{$lang.more}</a>{/if}
    {/if}
  {/if}
{/capture}
{if $no_capture}
  {assign var="capture_name" value="prod_descr_`$obj_id`"}
  {$smarty.capture.$capture_name}
{/if}

{********************** Old Price *****************}
{capture name="old_price_`$obj_id`"}
  {if $show_price_values && $show_old_price}
    {if $product.discount || $product.list_discount}
    <span class="cm-reload-{$obj_prefix}{$obj_id}" id="old_price_update_{$obj_prefix}{$obj_id}">
      <input type="hidden" name="appearance[show_price_values]" value="{$show_price_values}" />
      <input type="hidden" name="appearance[show_old_price]" value="{$show_old_price}" />
      {if $product.is_wholesale_product == 1 && !empty($product.retail_price) && $product.retail_price > $product.price && $product.retail_price < $product.list_price}
        {if !$details_page}
          <label class="lst_prc_list_view list-price">{$lang.list_price}</label>
        {/if}
      {/if}
      {if $product.discount}
        <span class="list-price nowrap" id="line_old_price_{$obj_prefix}{$obj_id}">{if $details_page}<span class="lst_price_tit_nl {if $after_apply_promotion != 0}prc_third_app{/if}">{$lang.old_price}: </span>{/if}<strike>{include file="common_templates/price.tpl" value=$product.original_price|default:$product.base_price span_id="old_price_`$obj_prefix``$obj_id`" class="list-price nowrap"}</strike></span>
      {elseif $product.list_discount}
        <span class="list-price nowrap" id="line_list_price_{$obj_prefix}{$obj_id}">{if $details_page}<span class="lst_price_tit_nl {if $after_apply_promotion != 0}prc_third_app{/if}">{$lang.list_price}: </span>{/if}<strike>{include file="common_templates/price.tpl" value=$product.list_price span_id="list_price_`$obj_prefix``$obj_id`" class="list-price nowrap"}</strike></span>
      {/if}
    <!--old_price_update_{$obj_prefix}{$obj_id}--></span>
    {/if}
  {/if}
{/capture}

{capture name="retail_price_`$obj_id`"}
  {if $show_price_values && $show_old_price}
    {if $product.discount || $product.list_discount}
    <span class="cm-reload-{$obj_prefix}{$obj_id} no_mobile" id="retail_price_update_{$obj_prefix}{$obj_id}">
      <input type="hidden" name="appearance[show_price_values]" value="{$show_price_values}" />
      <input type="hidden" name="appearance[show_retail_price]" value="{$show_old_price}" />
      {if $product.is_wholesale_product == 1 && !empty($product.retail_price) && $product.retail_price > $product.price && $product.retail_price < $product.list_price}
        {if !$details_page}  
            <label class="lst_prc_list_view list-price">{$lang.retail_price_list_view}</label>
        {/if}
      {/if}
      <span class="list-price nowrap" id="line_list_price_{$obj_prefix}{$obj_id}">
            {if $details_page}
                <span class="lst_price_tit_nl {if $after_apply_promotion != 0}prc_third_app{/if}">
                    {$lang.retail_price}:
                </span>
            {/if}
            <strike>
                {include file="common_templates/price.tpl" value=$product.retail_price span_id="retail_price_`$obj_prefix``$obj_id`" class="list-price nowrap"}
            </strike>
        </span>
    <!--old_price_update_{$obj_prefix}{$obj_id}--></span>
    {/if}
  {/if}
{/capture}
{if $no_capture}
  {assign var="capture_name" value="old_price_`$obj_id`"}
  {$smarty.capture.$capture_name}
{/if}

{********************** Price *********************}
{capture name="price_`$obj_id`"}
  <span class="cm-reload-{$obj_prefix}{$obj_id} price-update" id="price_update_{$obj_prefix}{$obj_id}">
    <input type="hidden" name="appearance[show_price_values]" value="{$show_price_values}" />
    <input type="hidden" name="appearance[show_price]" value="{$show_price}" />
    {if $product.is_wholesale_product == 1 && !empty($product.retail_price) && $product.retail_price > $product.price && $product.retail_price < $product.list_price}
        {if !$details_page}
            <label class="lst_prc_list_view list-price">{$lang.final_price_list_view}</label>
        {/if}
    {/if}
    {if $show_price_values}
      {if $show_price}
      {hook name="products:prices_block"}
        {if $product.price|floatval || $product.zero_price_action == "P" || ($hide_add_to_cart_button == "Y" && $product.zero_price_action == "A")}
          <span {if $after_apply_promotion != 0}style="font:12px 'Trebuchet MS', Arial, Helvetica, sans-serif; color:#666;" {/if} class="price{if !$product.price|floatval} hidden{/if} {if $after_apply_promotion != 0}prd_pg_strike{/if} " id="line_discounted_price_{$obj_prefix}{$obj_id}">
            {if $details_page}
                {if $product.promotions}
                    <span class="lst_price_tit_nl {if $product.is_wholesale_product}whole_sale_title{/if}{if $after_apply_promotion != 0}prc_third_app{/if}">
                        {if $product.is_wholesale_product}
                            {$lang.wholesale_price}
                        {else}
                            {$lang.special_price}
                        {/if}
                    </span>
                {else}
                    <span class="lst_price_tit_nl {if $product.is_wholesale_product}whole_sale_title{/if}{if $after_apply_promotion != 0}prc_third_app{/if}">
                        {if $product.is_wholesale_product}
                            {$lang.wholesale_price}
                        {else}
                            {$lang.price}
                        {/if}
                {/if}: </span>
            {/if}
            {include file="common_templates/price.tpl" value=$product.price span_id="discounted_price_`$obj_prefix``$obj_id`" class="price"}</span>
        {elseif $product.zero_price_action == "A"}
          {assign var="base_currency" value=$currencies[$smarty.const.CART_PRIMARY_CURRENCY]}
          <span class="price"><span class="lst_price_tit_nl {if $after_apply_promotion != 0}prc_third_app{/if}">{$lang.enter_your_price}:</span> {if $base_currency.after != "Y"}{$base_currency.symbol}{/if}<input class="input-text-short" type="text" size="3" name="product_data[{$obj_id}][price]" value="" />{if $base_currency.after == "Y"}&nbsp;{$base_currency.symbol}{/if}</span>
        {elseif $product.zero_price_action == "R"}
          <span style="color: #EE811D; font: bold 13px trebuchet ms;">{$lang.contact_us_for_price}</span>
        {/if}
      {/hook}
      {/if}
    {elseif $settings.General.allow_anonymous_shopping == "P" && !$auth.user_id}
      <span class="price">{$lang.sign_in_to_view_price}</span>
    {/if}
  <!--price_update_{$obj_prefix}{$obj_id}--></span>
{/capture}
{if $no_capture}
  {assign var="capture_name" value="price_`$obj_id`"}
  {$smarty.capture.$capture_name}
{/if}

{******************* Clean Price ******************}
{capture name="clean_price_`$obj_id`"}
  {if $show_price_values && $show_clean_price && $settings.Appearance.show_prices_taxed_clean == "Y" && $product.taxed_price}
    <span class="cm-reload-{$obj_prefix}{$obj_id}" id="clean_price_update_{$obj_prefix}{$obj_id}">
      <input type="hidden" name="appearance[show_price_values]" value="{$show_price_values}" />
      <input type="hidden" name="appearance[show_clean_price]" value="{$show_clean_price}" />
      {if $product.clean_price != $product.taxed_price && $product.included_tax}
        <span class="list-price nowrap" id="line_product_price_{$obj_prefix}{$obj_id}">({include file="common_templates/price.tpl" value=$product.taxed_price span_id="product_price_`$obj_prefix``$obj_id`" class="list-price nowrap"} {$lang.inc_tax})</span>
      {elseif $product.clean_price != $product.taxed_price && !$product.included_tax}
        <span class="list-price nowrap">({$lang.including_tax})</span>
               
                
      {/if}
    <!--clean_price_update_{$obj_prefix}{$obj_id}--></span>
  {/if}
{/capture}
{if $no_capture}
  {assign var="capture_name" value="clean_price_`$obj_id`"}
  {$smarty.capture.$capture_name}
{/if}

{********************** You Save ******************}
{capture name="list_discount_`$obj_id`"}
  {if $show_price_values && $show_list_discount && $details_page}
    {if $product.discount || $product.list_discount}
      <span class="cm-reload-{$obj_prefix}{$obj_id}" id="line_discount_update_{$obj_prefix}{$obj_id}">
        <input type="hidden" name="appearance[show_price_values]" value="{$show_price_values}" />
        <input type="hidden" name="appearance[show_list_discount]" value="{$show_list_discount}" />
        {if $product.discount}
          <span class="list-price nowrap" id="line_discount_value_{$obj_prefix}{$obj_id}">{$lang.you_save}: {include file="common_templates/price.tpl" value=$product.discount span_id="discount_value_`$obj_prefix``$obj_id`" class="list-price nowrap"}&nbsp;(<span id="prc_discount_value_{$obj_prefix}{$obj_id}" class="list-price nowrap">{$product.discount_prc}</span>%)</span>
        {elseif $product.list_discount}
          <span class="list-price nowrap" id="line_discount_value_{$obj_prefix}{$obj_id}">{$lang.you_save}: {include file="common_templates/price.tpl" value=$product.list_discount span_id="discount_value_`$obj_prefix``$obj_id`" class="list-price nowrap"}&nbsp;(<span id="prc_discount_value_{$obj_prefix}{$obj_id}" class="list-price nowrap">{$product.list_discount_prc}</span>%)</span>
        {/if}
      <!--line_discount_update_{$obj_prefix}{$obj_id}--></span>
    {/if}
  {/if}
{/capture}
{if $no_capture}
  {assign var="capture_name" value="list_discount_`$obj_id`"}
  {$smarty.capture.$capture_name}
{/if}

{************************************ Discount label ****************************}
{capture name="discount_label_`$obj_prefix``$obj_id`"}

  <!-- Added By Sudhir dt 09 octo 2012 to show third price percentage-->
    {assign var="after_apply_promotion" value=0}
    {if $product.promotion_id !=0}
      {assign var="after_apply_promotion" value=$product|fn_get_3rd_price}
    {/if}<!-- Added By Sudhir end here-->

  {if (($show_discount_label && ($product.discount_prc || $product.list_discount_prc) && $show_price_values) ||($after_apply_promotion!=0))}
    <div class="discount-label cm-reload-{$obj_prefix}{$obj_id} {if $after_apply_promotion !=0} third_price_discount {/if}" id="discount_label_update_{$obj_prefix}{$obj_id}">

      <input type="hidden" name="appearance[show_discount_label]" value="{$show_discount_label}" />
      <input type="hidden" name="appearance[show_price_values]" value="{$show_price_values}" />
      <div id="line_prc_discount_value_{$obj_prefix}{$obj_id}">
            { $product.promotions}
        {if $product.promotions}
            {assign var="disc_label" value=$product|calculate_discount_perc}
                                {else}
            {if $product.discount}
                {assign var="disc_label" value=$product.discount_prc}
            {else}
                {assign var="disc_label" value=$product.list_discount_prc}
            {/if}
        {/if}
    <!-- Added By Sudhir dt 09 octo 2012 to show third price percentage-->
    {if $product.promotion_id !=0}
      {if $after_apply_promotion !=0}     
        {assign var="disc_label" value=$product|calculate_3rd_price_percentage:$after_apply_promotion}
      {/if}
    {/if}<!-- Added By Sudhir end here-->
                      
                <span id="prc_discount_value_label_{$obj_prefix}{$obj_id}" style="float:left; margin-top:5px; margin-left:3px; width:90%; text-align:center;">{$disc_label}%</span>
                <span style="float:left; font:bold 11px arial; margin-top:-4px; margin-left:10px;">Off</span>
      </div>
    <!--discount_label_update_{$obj_prefix}{$obj_id}--></div>
  {/if}  
{*added by anoop - value added service stamps*}
  {if $controller=='products' and $mode=='view'}
    {if $product.value_added_services.qty_disc_flag == '1'}
                        <div class="icon_EDB">    
                        </div>
    {/if}
    {if $product.value_added_services.min_qty_disc_flag == '1'}
                  <div class="icon_EDR">                             
                  </div>
    {/if}
  {/if}
{*anoop code ends here*}
{/capture}
{if $no_capture}
  {assign var="capture_name" value="discount_label_`$obj_prefix``$obj_id`"}
  {$smarty.capture.$capture_name}
{/if}

{capture name="product_amount_`$obj_id`"}
{if $show_product_amount && $product.is_edp != "Y" && $settings.General.inventory_tracking == "Y"}
  <span class="cm-reload-{$obj_prefix}{$obj_id}" id="product_amount_update_{$obj_prefix}{$obj_id}">   
    <input type="hidden" name="appearance[show_product_amount]" value="{$show_product_amount}" />
    {if !$product.hide_stock_info}
      {if $settings.Appearance.in_stock_field == "Y"}
        {if $product.tracking != "D"}
          {if ($product_amount > 0 && $product_amount >= $product.min_qty) && $settings.General.inventory_tracking == "Y" || $details_page}
            {if ($product_amount > 0 && $product_amount >= $product.min_qty) && $settings.General.inventory_tracking == "Y"}
              <div class="form-field product-list-field">
                <label>{$lang.in_stock}:</label>
                <span id="qty_in_stock_{$obj_prefix}{$obj_id}" class="qty-in-stock" style="float:left; display:inline;">
                  {$product_amount}&nbsp;{$lang.items}
                </span> 
              </div>
            {elseif $settings.General.inventory_tracking == "Y" && $settings.General.allow_negative_amount != "Y"}
              <p class="strong out-of-stock">{$lang.text_out_of_stock}</p>
            {/if}
          {/if}
        {/if}
      {else}
        {if ((($product_amount > 0 && $product_amount >= $product.min_qty) || $product.tracking == "D") && $settings.General.inventory_tracking == "Y" && $settings.General.allow_negative_amount != "Y") || ($settings.General.inventory_tracking == "Y" && $settings.General.allow_negative_amount == "Y")}
          
                    {if $smarty.session.onedaysale == "YES"}
                      <div class="qtybox" style="clear:both;">
                            {if $product_amount > $config.onedaysale_instock_limit}
                                <span>{$lang.in_stock}</span>
                            {elseif $product_amount <= $config.onedaysale_instock_limit && $product_amount > 0}
                                Quantity Left : {$product_amount}
                            {else}
                                <span style="color:#EE811D; font:18px/22px 'Trebuchet MS', Arial, Helvetica, sans-serif; text-transform:uppercase; font-weight:bold">{$lang.sold_out}</span>
                            {/if}
                        </div>
                    {else}                  
                    
                    <span class="strong in-stock" id="in_stock_info_{$obj_prefix}{$obj_id}" style="float:left; display:inline; width:45%; font:15px trebuchet ms; padding-right:4px;">{$lang.in_stock}
                    {if ((($product_amount > 0 && $product_amount >= $product.min_qty) || $product.tracking == "D") && $settings.General.inventory_tracking == "Y" && $settings.General.allow_negative_amount != "Y") || ($settings.General.inventory_tracking == "Y" && $settings.General.allow_negative_amount == "Y")}
                     <div class="clearboth"></div>

                    {/if}                    
                    </span>
                 
                {/if} 
                 
      {elseif $details_page && ($product_amount <= 0 || $product_amount < $product.min_qty) && $settings.General.inventory_tracking == "Y" && $settings.General.allow_negative_amount != "Y"}
          {if $smarty.session.onedaysale == "YES"}
                      <div class="qtybox" style="clear:both;">
                            <span style="color:#EE811D; font:18px/22px 'Trebuchet MS', Arial, Helvetica, sans-serif; text-transform:uppercase; font-weight:bold">{$lang.sold_out}</span>
                        </div>
                    {else}
                    <span class="strong out-of-stock" style="float:left;" id="out_of_stock_info_{$obj_prefix}{$obj_id}">{$lang.text_out_of_stock}</span>
                    {if ($product.out_of_stock_actions == "S") && ($product.tracking != "O")}
                    <span class="strong in-stock" id="in_stock_info_300470" style="float:left; clear:both; display:inline; width:auto; font:15px trebuchet ms; padding-right:10px; padding-top:0;">{$lang.notify_me_text}
          <div class="clearboth"></div>
                    <div class="form-field product-list-field" style="float:left; display:inline; padding-left:0px !important; font:12px/14px trebuchet ms; color:#636566;">
                    <span class="valign">{$lang.notify_me_text_desc}</span>
                    <label class="ahover_nl" style="margin:0;width:auto!important; font-weight:normal; float:none; clear:none; color:#048ccc;">{$lang.notify_me_text_question}
                    <div class="div_text" style="left:0px; top:-2px; z-index:444; font-weight:normal; width:220px;">{$lang.notify_me_text_long_desc}</div>
                    </label>
                    </div> 
                           </span>{/if}
                    {/if}
        {/if}
      {/if}
    {/if}
        
       {* [andyye] *}
   {if $controller!="product_quick_view"}
    {if $product_amount>0 && $smarty.session.onedaysale == "NO"}
        <div class="productShippingDetails">
           {if isset($addons.sdeep.free_shipping_html) && $addons.sdeep.free_shipping_html && ((isset($product.free_shipping) && $product.free_shipping == 'Y') ||  ($product.shipping_freight < "1"))}
                <span>
                {$addons.sdeep.free_shipping_html|unescape}
                
                </span>
            {else if  isset($product.shipping_freight) && $product.free_shipping == 'Y'}
                <span>
                    Shipping Charge : 
                    {include file="common_templates/price.tpl" value=$product.shipping_freight span_id="shipping_charge_`$obj_prefix``$obj_id`" class="list-price nowrap"}
                </span>
                {if $product.is_wholesale_product}
                    <br/>
                    <br/>
		 <span>{$lang.shipping_wholesale_product|replace:'[MIN]':$product.min_qty}</span>
		{/if}
              {/if}
        </div>
    {/if}    
    {/if}
        
    <div class="clearboth"></div>
    {* [/andyye] *}
  <!--product_amount_update_{$obj_prefix}{$obj_id}--></span>
{/if}
{/capture}
{if $no_capture}
  {assign var="capture_name" value="product_amount_`$obj_id`"}
  {$smarty.capture.$capture_name}
{/if}

{capture name="product_options_`$obj_id`"}
  {if $show_product_options}
  <div class="cm-reload-{$obj_prefix}{$obj_id}" id="product_options_update_{$obj_prefix}{$obj_id}">
    <input type="hidden" name="appearance[show_product_options]" value="{$show_product_options}" />
    {hook name="products:product_option_content"}
      {if $disable_ids}
        {assign var="_disable_ids" value="`$disable_ids``$obj_id`"}
      {else}
        {assign var="_disable_ids" value=""}
      {/if}
      {include file="views/products/components/product_options.tpl" id=$obj_id product_options=$product.product_options name="product_data" capture_options_vs_qty=$capture_options_vs_qty disable_ids=$_disable_ids}
    {/hook}
  <!--product_options_update_{$obj_prefix}{$obj_id}--></div>
  {/if}
{/capture}
{if $no_capture}
  {assign var="capture_name" value="product_options_`$obj_id`"}
  {$smarty.capture.$capture_name}
{/if}

{capture name="advanced_options_`$obj_id`"}
  {if $show_product_options}
    <div class="cm-reload-{$obj_prefix}{$obj_id}" id="advanced_options_update_{$obj_prefix}{$obj_id}" >
      {*if !$details_page*}
            
            
      {*/if*}
            
            <!--advanced_options_update_{$obj_prefix}{$obj_id}--></div>
  {/if}
{/capture}
{if $no_capture}
  {assign var="capture_name" value="advanced_options_`$obj_id`"}
  {$smarty.capture.$capture_name}
{/if}

{capture name="qty_`$obj_id`"}
  {if $show_qty}
    <div class="cm-reload-{$obj_prefix}{$obj_id}" id="qty_update_{$obj_prefix}{$obj_id}">
    <input type="hidden" name="appearance[show_qty]" value="{$show_qty}" />
    <input type="hidden" name="appearance[capture_options_vs_qty]" value="{$capture_options_vs_qty}" />
    {if !empty($product.selected_amount)}
      {assign var="default_amount" value=$product.selected_amount}
    {elseif !empty($product.min_qty)}
      {assign var="default_amount" value=$product.min_qty}
    {else}
      {assign var="default_amount" value="1"}
    {/if}
    
    {if ($product.qty_content || $show_qty) && $product.is_edp !== "Y" && $cart_button_exists == true && ($settings.General.allow_anonymous_shopping == "Y" || $auth.user_id)}
      <div class="productOptions" id="qty_{$obj_prefix}{$obj_id}">
        <label for="qty_count_{$obj_prefix}{$obj_id}">{$quantity_text|default:$lang.quantity}:</label>
        <div>
        {if $product.qty_content && $settings.General.inventory_tracking == "Y"}
        <select name="product_data[{$obj_id}][amount]" id="qty_count_{$obj_prefix}{$obj_id}">
        {assign var="a_name" value="product_amount_`$obj_prefix``$obj_id`"}
        {assign var="selected_amount" value=false}
        {foreach name="`$a_name`" from=$product.qty_content item="var"}
          <option value="{$var}" {if $product.selected_amount && ($product.selected_amount == $var || ($smarty.foreach.$a_name.last && !$selected_amount))}{assign var="selected_amount" value=true}selected="selected"{/if}>{$var}</option>
        {/foreach}
        </select>        
        {else}
        {if $settings.Appearance.quantity_changer == "Y"}
        <div class="center valign cm-value-changer">
          <a class="cm-increase"><img src="{$images_dir}/icons/up_arrow.gif" width="11" height="5" border="0" /></a>
          {/if}
        <input type="number" min="1" size="5" class="input-text-short cm-amount" id="qty_count_{$obj_prefix}{$obj_id}" name="product_data[{$obj_id}][amount]" value="{$default_amount}" {if $product.check_for_product_diff_price}onchange="fn_change_qty_prod(this.value,{$product.product_id},{$product.list_price});" {/if}/>            
          {if $settings.Appearance.quantity_changer == "Y"}
          <a class="cm-decrease"><img src="{$images_dir}/icons/down_arrow.gif" width="11" height="5" border="0" /></a>
        </div>
        {/if}
        {/if}
    
{*Added by shashikant to show size chart at product page*}       
{if $config.enable_size_chart && !empty($sizechart_size_values)}
<div class="sizingChart">
    <div id='sizing_chart' onclick ='popup_sizing_chart();'>{$lang.sizing_chart}</div>
    
    <div class="lightBox" id="sizing_chart_options" style="display:none;">
        <div class="box">
            <h1>
                Men's T-Shirts
                <a class="cancel">x</a>
            </h1>
            <div class="content">
                <img id="det_img_1617407645" src="{$config.ext_images_host}/{$image_size}" width="187" height="185" border="0" alt="image_size_cat" title="Size Options">
                
                <div class="size">
                    <h2>Size Options</h2>
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">                            
                            
                                <tr>
                                    <td>Size (IN)</td>
                                    {foreach from=$sizechart_size_parts item="size"}
                                        <td>{$size.size_part}</td>
                                    {foreachelse}
                                        &ndash;
                                    {/foreach}
                                </tr>
                                
                                {foreach from=$sizechart_sizes item="part"}
                                    <tr>
                                        <td>{$part.size}</td>
                                        {foreach from=$sizechart_size_values item="vals"}
                                            {if $part.size_id eq $vals.size_id}
                                                <td>
                                                    {$vals.value}
                                                </td>
                                            {/if}
                                        {/foreach}
                                    </tr>
                                {foreachelse}
                                    &ndash;
                                {/foreach}
                            </table>
                </div>
                
            </div>
        </div>
        
        <div class="overlay"></div>
    </div>
    
</div>

{/if}
{*Added by shashikant to show size chart at product page*}

        {if $product.is_wholesale_product}
            <br/>
            <span>{$lang.quantity_wholesale_product|replace:'[MIN]':$product.min_qty}</span>
        {/if}
        </div>
      </div>     
       
      {if $product.prices}
        {include file="views/products/components/products_qty_discounts.tpl"}
      {/if}
    {elseif !$bulk_add}
      <input type="hidden" name="product_data[{$obj_id}][amount]" value="{$default_amount}" />
    {/if}
    <!--qty_update_{$obj_prefix}{$obj_id}--></div>
  {/if}
{/capture}
{if $no_capture}
  {assign var="capture_name" value="qty_`$obj_id`"}
  {$smarty.capture.$capture_name}
{/if}

{capture name="min_qty_`$obj_id`"}
  {*{if $min_qty && $product.min_qty}
    <p class="description">{$lang.text_cart_min_qty|replace:"[product]":$product.product|replace:"[quantity]":$product.min_qty}.</p>
  {/if}*}
{/capture}
{if $no_capture}
  {assign var="capture_name" value="min_qty_`$obj_id`"}
  {$smarty.capture.$capture_name}
{/if}

{capture name="product_edp_`$obj_id`"}
  {if $show_edp && $product.is_edp == "Y"}
    <p class="description">{$lang.text_edp_product}.</p>
    <input type="hidden" name="product_data[{$obj_id}][is_edp]" value="Y" />
  {/if}
{/capture}
{if $no_capture}
  {assign var="capture_name" value="product_edp_`$obj_id`"}
  {$smarty.capture.$capture_name}
{/if}

{capture name="form_close_`$obj_id`"}
{if !$hide_form}
</form>
{/if}
{/capture}
{if $no_capture}
  {assign var="capture_name" value="form_close_`$obj_id`"}
  {$smarty.capture.$capture_name}
{/if}

{foreach from=$images key="object_id" item="image"}
  <div class="cm-reload-{$image.obj_id}" id="{$object_id}">
    {if $image.link}
      <a href="{$image.link}">
      <input type="hidden" value="{$image.link}" name="image[{$object_id}][link]" />
    {/if}
    <input type="hidden" value="{$image.obj_id},{$image.width},{$image.height},{$image.type}" name="image[{$object_id}][data]" />
    {include file="common_templates/image.tpl" image_width=$image.width image_height=$image.height show_thumbnail="Y" obj_id=$object_id images=$product.main_pair object_type="product"}
    {if $image.link}
      </a>
    {/if}
  <!--{$object_id}--></div>
{/foreach}

{hook name="products:product_data"}{/hook}
<!-- code of pincode availability -->
{capture name="availability"}
<div class="loading_image" style="display:block;min-height: 127px;">
    <img src="{$config.ext_images_host}/images/availability-progress-bar.gif"  style="margin-top :45px;"/>
    </div>
    <div class="prd_page_pin_code_mid_blk produ_detai_right_b_mng">
        <div  class="form-field pincode_input_bx pincode_prd_page_blk_new" id="pincode_avail">
            <div class="pin_icon_nl pin_cod" style="width:10px;">&nbsp;</div>
             <label>{$lang.enter_pincode_message}</label>
             <div class="input_pin_cod_nl">
            <input class="input_text" type="tel" name="pincode" id="pincode" placeholder="Enter Pincode" maxlength="6"> 
            <span  class="button-submit">
                <input class="box_functions_button" id="check_but" type="button" value="Check" onclick="check_pincode({$product.product_id}, 0);" style="border-top-left-radius: 0; border-bottom-left-radius: 0; cursor:pointer;">
            </span>
            </div>
        </div>

        <div id="change_pin" style="display:none; margin-bottom:7px;" class="pincode_prd_page_blk_new ">
            <div class="pin_icon_nl pin_cod" style="padding-top:4px; line-height:20px;">{$lang.pincode_delivery_title}</div>
            <div class="prd_opt_cod_disable" style="padding-top:7px;">
                <label><span><span class="date_info" id="edd">{$lang.pdd_product_page}
                    <span id="fdate" class="bold"></span> {$lang.pdd_mid} <span id="sdate" class="bold"></span> {$lang.pincode_to} </span>
                    <span class="not_servicable" id="not_servicable">{$lang.not_servicable}</span>
                </span>
                                <span><span id="pin_display" class="bold"></span>&nbsp;<a class="ahover_nl pincode_change">{$lang.change_pin}</a></span>
                </label>
                                <span class="dispatch_pin_code">{if $product.dispatch_days}{$lang.product_dispatch_time|replace:'[DISPATCH]':$product.dispatch_days}{/if}</span>
            </div>
        </div>
         <div id="payment_mode" class="prd_opt_cod_enable pincode_prd_page_blk_new ">
         	<div class="pin_icon_nl other">{$lang.pincode_payments_title}</div>
            <ul class="pincode_ul ">
                            <li class="pincode_li"><span class="pincode_normal"><span>{$lang.pincode_payments_method}{if $product.price >= $config.emi_min_amount} {$lang.emi}{else} {$lang.emi_not}{/if} <span class="cod_payment">{if $product.allow_cod eq 'yes'}{$lang.both_cod_prepaid}{else}{$lang.only_prepaid}{/if}</span></span></span></li>
                </ul>
        </div>
        <div id="return" class="prd_opt_cod_enable pincode_prd_page_blk_new ">
        	<div class="pin_icon_nl other">{$lang.pincode_return_title}</div>
            <ul class="pincode_ul">
                <li class="pincode_li"><span class="pincode_normal"><span>{if $product.return_period && $product.is_returnable == "Y"}{$product.return_period}{$lang.return_period_post_message_new}{else}{$lang.return_period_not_available}{/if}</span></span>
                                </li>
                </ul>
        </div> 
        <div id="guarantee" class="prd_opt_cod_enable pincode_prd_page_blk_new ">
	        <div class="pin_icon_nl other">{$lang.pincode_guarantee_title}</div>
            <ul class="pincode_ul">
                <li class="pincode_li"><span class="pincode_normal">{$lang.pincode_guarantee_message_new}</span></li>
                </ul>
        </div>
    </div>

    {literal}
        <script type="text/javascript">
            if(document.getElementById('sec_discounted_price_{/literal}{$product.product_id}{literal}') != null)
            {
               var first_time_price = document.getElementById('sec_discounted_price_{/literal}{$product.product_id}{literal}').innerHTML;
            }
            if(document.getElementById('sec_shipping_charge_{/literal}{$product.product_id}{literal}') != null)
            {
                var first_time_shipping = document.getElementById('sec_shipping_charge_{/literal}{$product.product_id}{literal}').innerHTML;
            }
            if(document.getElementById('you_save_amount_rs') != null)
            {
                var first_time_you_save = document.getElementById('you_save_amount_rs').innerHTML;
            }   
            if(document.getElementById('you_save_prcent_disc') != null)
            {
               var first_time_you_save_prcnt = document.getElementById('you_save_prcent_disc').innerHTML;
            }        
                    //alert(product_price_arr);
                    $(document).ready(function() {
                        $('.pincode_change').bind('click', change_pincode);
                        var pincode = ReadCookie('pincode');
                        var prod_id = {/literal}{$product.product_id};{literal}
                        //    alert(pincode+'==='+prod_id);
                        if (pincode != '')
                        {
                            check_pincode(prod_id, pincode);
                        }
                        else
                        {
                            $('.loading_image').css('display', 'none');
                            $('.prd_page_pin_code_mid_blk').css('display', 'block');
                        }

                    });

                    function change_pincode()
                    {
                        $('#pincode_avail').show();
                        $('#check_but').removeAttr('disabled');
                        $('#change_pin').hide();
                    }
                    function check_pincode(prod_id, pincode)
                    {
                        if (pincode == '') {
                            var pincode = $('#pincode').val();
                            if(pincode.length!=6)
                                {
                                    $('#pin_display').text(pincode);
                                    $('#edd').hide();
                                    $(".dispatch_pin_code").hide();
                                    $('#pincode_avail').hide();
                                    $('#change_pin').show();
                                    $('.pincode_change').html('{/literal}{$lang.try_another_location}{literal}');
                                    $('#not_servicable').show();
                                    $('.pincode_input_bx .input_text').addClass('error_pin');
                                    return false;
                                }
                                else
                                    {
                                         $('.pincode_input_bx .input_text').removeClass('error_pin');
                                    }
                        }
                        $('#check_but').attr('disabled','disabled');
                        $('#edd').hide();
                        $('#fdate').html('');
                        $('#sdate').html('');
                        $('.loading_image').css('display', 'block');
                        $('.prd_page_pin_code_mid_blk').css('display', 'none');

                        $.ajax({
                            type: "GET",
                            url: "nss.php",
                            data: {pincode_no: pincode, product_ids: prod_id},
                            dataType: 'text json',
                            success: function(result) {
                                $('.loading_image').css('display', 'none');
                                $('.prd_page_pin_code_mid_blk').css('display', 'block');
                                //alert(result);//alert(result.fdate);alert(result.sdate);
                                $('#pin_display').text(pincode);
                                if (result.pin_result == '0') {
                                    $('#pincode_avail').hide();
                                    $('#change_pin').show();
                                    $(".dispatch_pin_code").hide();
                                    $('.pincode_change').html('{/literal}{$lang.try_another_location}{literal}');
                                    $('#not_servicable').show();
                                }
                                else if (result.pin_result == '3')
                                {
                                    $('#pincode_avail').hide();
                                    $('#change_pin').show();
                                    $('.pincode_change').html('{/literal}{$lang.change_pin}{literal}');
                                    $('.cod_payment').html('{/literal}{$lang.both_cod_prepaid}{literal}');
                                    $('#not_servicable').hide();
                                }
                                else if (result.pin_result == '4' || result.pin_result == '1')
                                {
                                    $('#pincode_avail').hide();
                                    $('#change_pin').show();
                                    $('.pincode_change').html('{/literal}{$lang.change_pin}{literal}');
                                    $('.cod_payment').html('{/literal}{$lang.only_prepaid}{literal}');
                                    $('#not_servicable').hide();
                                }
                                else if (result.pin_result == '-1')
                                {
                                    //$('#check_but').removeAttr('disabled');
                                    $('#pincode_avail').hide();
                                    $('#change_pin').show();
                                    $('.pincode_change').html('{/literal}{$lang.try_another_location}{literal}');
                                    $('#not_servicable').show();
                                }
                                if (result.pin_result == '4' || result.pin_result == '3')
                                {
                                    $('#edd').show();
                                    $('#fdate').html(result.fdate);
                                    $('#sdate').html(result.sdate);
                                    $(".dispatch_pin_code").show();
                                }
                            }
                        });
                        return false;
                    }
                    
         function popup_sizing_chart(){
                $("#sizing_chart_options").show();
         }
         $('.cancel').click(function() {
                $('#sizing_chart_options').hide();
        });
                    
                    
                    function fn_change_qty_prod(qty,product_id,list_price)
                    {
                        if(qty < 1)
                            return;
                        if(qty == 1)
                        {
                            if(first_time_price){
                                document.getElementById('sec_discounted_price_'+product_id).innerHTML = first_time_price;
                            }
                            if(first_time_shipping != undefined){
                                document.getElementById('sec_shipping_charge_'+product_id).innerHTML = first_time_shipping;
                            }   
                            if(first_time_you_save != undefined){
                                document.getElementById('you_save_amount_rs').innerHTML = first_time_you_save;
                            }
                            if(first_time_you_save_prcnt != undefined){
                               document.getElementById('you_save_prcent_disc').innerHTML = first_time_you_save_prcnt;
                            }
                        }
                        else
                        {
                            var x = qty;
                            if(product_price_arr[x] != undefined)
                            {
                                if(document.getElementById('sec_discounted_price_'+product_id) != null){
                                   document.getElementById('sec_discounted_price_'+product_id).innerHTML = product_price_arr[x].price;
                                } 
                                if(document.getElementById('sec_shipping_charge_'+product_id) != null){
                                    document.getElementById('sec_shipping_charge_'+product_id).innerHTML = product_price_arr[x].shipping_charge;
                                }
                                if(document.getElementById('you_save_amount_rs') != null){
                                    document.getElementById('you_save_amount_rs').innerHTML = list_price-product_price_arr[x].price;
                                }
                                if(document.getElementById('you_save_prcent_disc') != null){
                                    document.getElementById('you_save_prcent_disc').innerHTML = (((list_price-product_price_arr[x].price)/list_price)*100).toFixed(0)+'%';
                                }
                                return;                                
                            }
                            else
                            {
                                while(x > 1 )
                                {
                                    if(product_price_arr[x] != undefined)
                                    {
                                        if(document.getElementById('sec_discounted_price_'+product_id) != null){
                                        document.getElementById('sec_discounted_price_'+product_id).innerHTML = product_price_arr[x].price;
                                        } 
                                        if(document.getElementById('sec_shipping_charge_'+product_id) != null){
                                            document.getElementById('sec_shipping_charge_'+product_id).innerHTML = product_price_arr[x].shipping_charge;
                                        }
                                        if(document.getElementById('you_save_amount_rs') != null){
                                            document.getElementById('you_save_amount_rs').innerHTML = list_price-product_price_arr[x].price;
                                        }
                                        if(document.getElementById('you_save_prcent_disc') != null){
                                            document.getElementById('you_save_prcent_disc').innerHTML = (((list_price-product_price_arr[x].price)/list_price)*100).toFixed(0)+'%';
                                        }
                                        return;  
                                    }
                                    x--;
                                }
                                if(first_time_price){
                                    document.getElementById('sec_discounted_price_'+product_id).innerHTML = first_time_price;
                                }
                                if(first_time_shipping != undefined){
                                    document.getElementById('sec_shipping_charge_'+product_id).innerHTML = first_time_shipping;
                                }   
                                if(first_time_you_save != undefined){
                                    document.getElementById('you_save_amount_rs').innerHTML = first_time_you_save;
                                }
                                if(first_time_you_save_prcnt != undefined){
                                   document.getElementById('you_save_prcent_disc').innerHTML = first_time_you_save_prcnt;
                                }
                                return;
                            } 
                        }
                        
                    }
                    
        </script>
    {/literal}
{/capture}

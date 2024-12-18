{* $Id: cleus_left_search_category_tree.tpl 12684 2011-06-14 15:19:15Z subkey $ *}
{literal}
<style>
.new_chan_menu_search{padding:5px!important; width: 92%;}
.show_child{display:block;}
.hide_child{display:none;}
</style>
{/literal}
{assign var="items" value=$smarty.request.q|fn_get_product_search_by_category}
{assign var="item_count" value="0"}
{foreach from=$items item="categories" key="key" name="pro_search_root"}
	<ul class="nav_mainmenu new_menu_link_nl "  style="margin: 0 0 0px -1px; border-bottom:0;{if $smarty.foreach.pro_search_root.last}margin-bottom:10px; border-bottom:1px solid #ccc;{/if}">
        <li>
        <div class="nav_mainmenu_label">
        	<a href="javascript:void(0)" class="pro_search_meta" id="{$key|replace:" ":"_"|replace:"&":""}">
                <div style="font:bold 11px/16px 'verdana', Arial, Helvetica, sans-serif; color:#000;" class="nl_cat_text_span">
                	<span id="{$key|replace:" ":"_"|replace:"&":""}_expand">{if $item_count <= "10"}-{else}+{/if}</span>
                    {$key}
                    <span id="{$key|replace:" ":"_"|replace:"&":""}_p_count"></span> 
                </div>
            </a>
            
        </div>
        </li>
        <li>
        {if $item_count < "10"}
        	{assign var="child_to_show" value="show_child"}
        {else}
        	{assign var="child_to_show" value="hide_child"}
        {/if}
        <ul style="padding-left:13px; width:158px;" id="{$key|replace:" ":"_"|replace:"&":""}_child" class="nav_submenu {$child_to_show}">
        {assign var="no_of_product" value="0"}
        {foreach from=$categories item="cat" name="pro_search"}
            <li>
            <div class="nav_mainmenu_label">
            <a href="index.php?dispatch=products.search&q={$smarty.request.q}&subcats=N&status=A&pname=Y&product_code=Y&match=all&pkeywords=Y&search_performed=Y&cid={$cat.category_id}" class="new_chan_menu_search"{if $smarty.foreach.pro_search.last}style="border-bottom:0;"{/if}><div class="nl_cat_text_span">{$cat.category} ({$cat.no_of_products})</div>
                </a>
            </div>
            </li>
            {assign var="item_count" value=$item_count+1}
            {assign var="no_of_product" value=$no_of_product+$cat.no_of_products}
        {/foreach}
        {assign var="tar_div" value=$key|replace:" ":"_"|replace:"&":""|cat:"_p_count"}
        {literal}
        	<script>
            	//$('#'+{/literal}{$tar_div}{literal}+'_p_count').html('('+{/literal}{$no_of_product}{literal}+')');
				document.getElementById('{/literal}{$tar_div}{literal}').innerHTML = '('+{/literal}{$no_of_product}{literal}+')';
            </script>
        {/literal}
        </ul>
        </li>
    </ul>
{/foreach}

{literal}
	<script type="text/javascript">
    	$(function(){
			$('.pro_search_meta').click(function(){
				var current_id = this.id;
				$('#'+current_id+'_child').slideToggle(300);
				if($('#'+current_id+'_expand').html() == '-'){
					$('#'+current_id+'_expand').html('+');
				}else{
					$('#'+current_id+'_expand').html('-');	
				}
			});	
			
		});
    </script>
{/literal}


{* $Id$ *}

{if $smarty.const.PRODUCT_TYPE == "MULTIVENDOR"}
{assign var="lang_vendor_supplier" value=$lang.vendor}
{else}
{assign var="lang_vendor_supplier" value=$lang.supplier}
{/if}

{if ($company_name || $company_id) && $settings.Suppliers.display_supplier == "Y"}
    <div style="float:left; display:inline; font:11px verdana; color:#636566; margin-top:5px; width:100%; height:20px;"> 
    <div class="float_left">{$lang.sold_by} &nbsp; </div>
        {* [andyye]: modified code *}
        {*if $company_name}{$company_name}{else}{$company_id|fn_get_company_name}{/if*}
        {if $company_id}{include file="addons/sdeep/common_templates/vendor_name.tpl" vendor_id=$company_id size="10" right=true}
        {else}
            {if $company_name}{$company_name}{/if}
        {/if}
        </div>
{/if}

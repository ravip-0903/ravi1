<div class="search-field">
{assign var="payment_methods" value="EN"|fn_get_simple_payment_methods}
<!--<pre>
{$payment_methods|print_r}
</pre>-->
	<label for="payment_method">{$lang.payment_method}:</label>
    {if !$smarty.request.ff}
	<select name="payment_method" id="payment_method">
		<option value="">--</option>
        {foreach from=$payment_methods key="payment_id" item="payment_name"}
		<option value="{$payment_id}" {if $search.payment_method == $payment_id}selected="selected"{/if}>{$payment_name}</option>
        {/foreach}
	</select>
    {else}
    <input type="radio" name="payment_method_ff" value='COD' {if $smarty.request.payment_method_ff=='COD'} checked="checked"{/if} /> COD | 
    <input type="radio" name="payment_method_ff" value='Prepaid' {if $smarty.request.payment_method_ff=='Prepaid'} checked="checked"{/if} /> Prepaid | 
    <input type="radio" name="payment_method_ff" value='Both' {if $smarty.request.payment_method_ff=='Both'} checked="checked"{/if} /> Both 
    <span style="padding-left:10px">&nbsp;</span>
    
    NSS 
    <select name="nss">
    	<option value="">--</option>
        {foreach from=$carriers item="carrier"}
        <option value="{$carrier.carrier_id}" {if $smarty.request.nss==$carrier.carrier_id}selected="selected"{/if}>{$carrier.carrier_name}</option>
        {/foreach}
    </select>
    <span style="padding-left:10px">&nbsp;</span>
    Search Notes <input type="text" name="searchnotes" value="{$smarty.request.searchnotes}" />
    <br />
    <div style="padding-top:10px">
        Region 
        <select name="region_id">
            <option value="">--</option>
            {foreach from=$region_list item="region"}
            <option value="{$region.region_id}" {if $smarty.request.region_id==$region.region_id}selected="selected"{/if}>{$region.region_name}</option>
            {/foreach}
        </select>
        <span style="padding-left:10px">&nbsp;</span>
        Fulfillment Type 
        <select name="fulfillment_id">
            <option value="">--</option>
            {foreach from=$fulfillment_list item="fulfillment"}
            <option value="{$fulfillment.fulfillment_id}" {if $smarty.request.fulfillment_id==$fulfillment.fulfillment_id}selected="selected"{/if}>{$fulfillment.description}</option>
            {/foreach}
        </select>
        <span style='padding-left:10px'>&nbsp;</span>
        Label Printed <input type="radio" name="label_printed" value="y" {if $smarty.request.label_printed=='y'} checked="checked"{/if} /> Yes 
        <input type="radio" name="label_printed" value="n" {if $smarty.request.label_printed=='n'} checked="checked"{/if} /> No 
        <input type="radio" name="label_printed" value="" {if $smarty.request.label_printed==''} checked="checked"{/if} /> All
    </div>
    <div style="padding-top:10px">
    	
        
        Search Address <input type="text" name="searchaddress" value="{$smarty.request.searchaddress}" />
        <span style='padding-left:10px'>&nbsp;</span>
        Order Status Age >= <input type="text" name="orderage" value="{$smarty.request.orderage}" style="width:50px" />
        <span style='padding-left:50px'>&nbsp;</span>
        Exception Cause 
        <select name="exception_cause[]" multiple="multiple" style="position:absolute;height:150px;margin-left:10px">
            <optgroup label="Reasons">
            {foreach from=$cause_list item="cause"}
            <option value="{$cause.id}" {if $cause.id|in_array:$smarty.request.exception_cause}selected="selected"{/if}>{$cause.cause}</option>
            {/foreach}
            </optgroup>
            <optgroup label="Actions">
            {foreach from=$action_list item="cause"}
            <option value="{$cause.id}" {if $cause.id|in_array:$smarty.request.exception_cause}selected="selected"{/if}>{$cause.cause}</option>
            {/foreach}
            </optgroup>
            <optgroup label="Tags">
            {foreach from=$tag_list item="cause"}
            <option value="{$cause.id}" {if $cause.id|in_array:$smarty.request.exception_cause}selected="selected"{/if}>{$cause.cause}</option>
            {/foreach}
            </optgroup>
            
        </select>
    </div>
    {/if}
</div>
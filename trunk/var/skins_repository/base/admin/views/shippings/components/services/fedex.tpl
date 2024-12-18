{* $Id: fedex.tpl 11631 2011-01-19 09:30:07Z 2tl $ *}

<fieldset>

<div class="form-field">
	<label for="user_key">{$lang.authentication_key}:</label>
	<input id="user_key" type="text" name="shipping_data[params][user_key]" size="30" value="{$shipping.params.user_key}" class="input-text" />
</div>

<div class="form-field">
	<label for="user_key_password">{$lang.authentication_password}:</label>
	<input id="user_key_password" type="text" name="shipping_data[params][user_key_password]" size="30" value="{$shipping.params.user_key_password}" class="input-text" />
</div>

<div class="form-field">
	<label for="account_number">{$lang.account_number}:</label>
	<input id="account_number" type="text" name="shipping_data[params][account_number]" size="30" value="{$shipping.params.account_number}" class="input-text" />
</div>

<div class="form-field">
	<label for="ship_fedex_meter_number">{$lang.ship_fedex_meter_number}:</label>
	<input id="ship_fedex_meter_number" type="text" name="shipping_data[params][meter_number]" size="30" value="{$shipping.params.meter_number}" class="input-text" />
</div>

<div class="form-field">
	<label for="test_mode">{$lang.test_mode}:</label>
	<input type="hidden" name="shipping_data[params][test_mode]" value="N" />
	<input id="test_mode" type="checkbox" name="shipping_data[params][test_mode]" value="Y" {if $shipping.params.test_mode == "Y"}checked="checked"{/if} class="checkbox" />
</div>

<div class="form-field">
	<label for="package_type">{$lang.package_type}:</label>
	<select id="package_type" name="shipping_data[params][package_type]">
		<option value="YOUR_PACKAGING" {if $shipping.params.package_type == "YOUR_PACKAGING"}selected="selected"{/if}>{$lang.ship_fedex_package_type_your_packaging}</option>
		<option value="FEDEX_BOX" {if $shipping.params.package_type == "FEDEX_BOX"}selected="selected"{/if}>{$lang.ship_fedex_package_type_fedex_box}</option>
		<option value="FEDEX_10KG_BOX" {if $shipping.params.package_type == "FEDEX_10KG_BOX"}selected="selected"{/if}>{$lang.ship_fedex_package_type_fedex_10kg_box}</option>
		<option value="FEDEX_25KG_BOX" {if $shipping.params.package_type == "FEDEX_25KG_BOX"}selected="selected"{/if}>{$lang.ship_fedex_package_type_fedex_25kg_box}</option>
		<option value="FEDEX_ENVELOPE" {if $shipping.params.package_type == "FEDEX_ENVELOPE"}selected="selected"{/if}>{$lang.ship_fedex_package_type_fedex_envelope}</option>
		<option value="FEDEX_PAK" {if $shipping.params.package_type == "FEDEX_PAK"}selected="selected"{/if}>{$lang.ship_fedex_package_type_fedex_pak}</option>
		<option value="FEDEX_TUBE" {if $shipping.params.package_type == "FEDEX_TUBE"}selected="selected"{/if}>{$lang.ship_fedex_package_type_fedex_tube}</option>
	</select>
</div>

<div class="form-field">
	<label for="ship_fedex_drop_off_type">{$lang.ship_fedex_drop_off_type}:</label>
	<select id="ship_fedex_drop_off_type" name="shipping_data[params][drop_off_type]">
		<option value="REGULAR_PICKUP" {if $shipping.params.drop_off_type == "REGULAR_PICKUP"}selected="selected"{/if}>{$lang.ship_fedex_drop_off_type_regular_pickup}</option>
		<option value="REQUEST_COURIER" {if $shipping.params.drop_off_type == "REQUEST_COURIER"}selected="selected"{/if}>{$lang.ship_fedex_drop_off_type_request_courier}</option>
		<option value="STATION" {if $shipping.params.drop_off_type == "STATION"}selected="selected"{/if}>{$lang.ship_fedex_drop_off_type_station}</option>
	</select>
</div>

<div class="form-field">
	<label for="max_weight">{$lang.max_box_weight}:</label>
	<input id="max_weight" type="text" name="shipping_data[params][max_weight_of_box]" size="30" value="{$shipping.params.max_weight_of_box|default:0}" class="input-text" />
</div>

<div class="form-field">
	<label for="ship_fedex_height">{$lang.ship_fedex_height}:</label>
	<input id="ship_fedex_height" type="text" name="shipping_data[params][height]" size="30" value="{$shipping.params.height}" class="input-text" />
</div>

<div class="form-field">
	<label for="ship_fedex_width">{$lang.ship_fedex_width}:</label>
	<input id="ship_fedex_width" type="text" name="shipping_data[params][width]" size="30" value="{$shipping.params.width}" class="input-text" />
</div>

<div class="form-field">
	<label for="ship_fedex_length">{$lang.ship_fedex_length}:</label>
	<input id="ship_fedex_length" type="text" name="shipping_data[params][length]" size="30" value="{$shipping.params.length}" class="input-text" />
</div>

</fieldset>

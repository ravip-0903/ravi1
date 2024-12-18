{* $Id: intershipper.tpl 11342 2010-12-02 13:47:56Z alexions $ *}

<fieldset>

<div class="form-field">
	<label for="password">{$lang.password}:</label>
	<input id="password" type="text" name="shipping_data[params][password]" size="30" value="{$shipping.params.password}" class="input-text" />
</div>

<div class="form-field">
	<label for="username">{$lang.username}:</label>
	<input id="username" type="text" name="shipping_data[params][username]" size="30" value="{$shipping.params.username}" class="input-text" />
</div>

<div class="form-field">
	<label for="ship_intershipper_delivery_type">{$lang.ship_intershipper_delivery_type}:</label>
	<select id="ship_intershipper_delivery_type" name="shipping_data[params][delivery_type]">
		<option value="COM" {if $shipping.params.delivery_type == "COM"}selected="selected"{/if}>{$lang.ship_intershipper_delivery_type_com}</option>
		<option value="RES" {if $shipping.params.delivery_type == "RES"}selected="selected"{/if}>{$lang.ship_intershipper_delivery_type_res}</option>
	</select>
</div>

<div class="form-field">
	<label for="ship_intershipper_ship_method">{$lang.ship_intershipper_ship_method}:</label>
	<select id="ship_intershipper_ship_method" name="shipping_data[params][ship_method]">
		<option value="DRP" {if $shipping.params.ship_method == "DRP"}selected="selected"{/if}>{$lang.ship_intershipper_ship_method_drp}</option>
		<option value="PCK" {if $shipping.params.ship_method == "PCK"}selected="selected"{/if}>{$lang.ship_intershipper_ship_method_pck}</option>
		<option value="SCD" {if $shipping.params.ship_method == "SCD"}selected="selected"{/if}>{$lang.ship_intershipper_ship_method_scd}</option>
	</select>
</div>

<div class="form-field">
	<label for="max_weight">{$lang.max_box_weight}:</label>
	<input id="max_weight" type="text" name="shipping_data[params][max_weight_of_box]" size="30" value="{$shipping.params.max_weight_of_box|default:0}" class="input-text" />
</div>

<div class="form-field">
	<label for="length">{$lang.length}:</label>
	<input id="length" type="text" name="shipping_data[params][length]" size="30" value="{$shipping.params.length}" class="input-text" />
</div>

<div class="form-field">
	<label for="width">{$lang.width}:</label>
	<input id="width" type="text" name="shipping_data[params][width]" size="30" value="{$shipping.params.width}" class="input-text" />
</div>

<div class="form-field">
	<label for="height">{$lang.height}:</label>
	<input id="height" type="text" name="shipping_data[params][height]" size="30" value="{$shipping.params.height}" class="input-text" />
</div>

<div class="form-field">
	<label for="ship_intershipper_dimensional_unit">{$lang.ship_intershipper_dimensional_unit}:</label>
	<select id="ship_intershipper_dimensional_unit" name="shipping_data[params][dimensional_unit]">
		<option value="IN" {if $shipping.params.dimensional_unit == "IN"}selected="selected"{/if}>{$lang.ship_intershipper_dimensional_unit_in}</option>
		<option value="CM" {if $shipping.params.dimensional_unit == "CM"}selected="selected"{/if}>{$lang.ship_intershipper_dimensional_unit_cm}</option>
	</select>
</div>

<div class="form-field">
	<label for="ship_intershipper_contents_type">{$lang.ship_intershipper_contents_type}:</label>
	<select id="ship_intershipper_contents_type" name="shipping_data[params][contents_type]">
		<option value="OTR" {if $shipping.params.contents_type == "OTR"}selected="selected"{/if}>{$lang.ship_intershipper_contents_type_otr}</option>
		<option value="IHM" {if $shipping.params.contents_type == "IHM"}selected="selected"{/if}>{$lang.ship_intershipper_contents_type_ihm}</option>
		<option value="AHM" {if $shipping.params.contents_type == "AHM"}selected="selected"{/if}>{$lang.ship_intershipper_contents_type_ahm}</option>
		<option value="LQD" {if $shipping.params.contents_type == "LQD"}selected="selected"{/if}>{$lang.ship_intershipper_contents_type_lqd}</option>
	</select>
</div>

<div class="form-field">
	<label for="package_type">{$lang.package_type}:</label>
	<select id="package_type" name="shipping_data[params][package_type]">
		<option value="BOX" {if $shipping.params.package_type == "BOX"}selected="selected"{/if}>{$lang.ship_intershipper_package_type_box}</option>
		<option value="LTR" {if $shipping.params.package_type == "LTR"}selected="selected"{/if}>{$lang.letter}</option>
		<option value="ENV" {if $shipping.params.package_type == "ENV"}selected="selected"{/if}>{$lang.envelope}</option>
		<option value="TUB" {if $shipping.params.package_type == "TUB"}selected="selected"{/if}>{$lang.ship_intershipper_package_type_tub}</option>
	</select>
</div>

<div class="form-field">
	<label for="ship_intershipper_cod_value">{$lang.ship_intershipper_cod_value}:</label>
	<input id="ship_intershipper_cod_value" type="text" name="shipping_data[params][cod_value]" size="30" value="{$shipping.params.cod_value}" class="input-text" />
</div>

<div class="form-field">
	<label for="ship_intershipper_insured_value">{$lang.ship_intershipper_insured_value}:</label>
	<input id="ship_intershipper_insured_value" type="text" name="shipping_data[params][insured_value]" size="30" value="{$shipping.params.insured_value}" class="input-text" />
</div>

</fieldset>

{literal}
<style>
.subheaders-group_2{border-radius: 5px;
-moz-border-radius: 5px;
background: #F8F8F8;
font:13px/16px "Trebuchet MS", Arial, Helvetica, sans-serif;
border: 1px solid #DDD;
padding: 10px;
width: 670px;
margin: -15px 0 0;
float: left;
filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#eeeeee');
background: -webkit-gradient(linear, left top, left bottom, from(white), to(#EEE));
background: -moz-linear-gradient(top, white, #EEE);}
.subheaders-group_2 .form-field label.cm-required{width:185px; font:12px/16px "Trebuchet MS", Arial, Helvetica, sans-serif;}
.subheaders-group_2 .input-text{width:468px; padding: 5px; border-radius: 5px; border: 1px solid #D2D7D9;} 
.subheaders-group_2 select{width:480px;padding: 5px; border-radius: 5px; border: 1px solid #D2D7D9;}
</style>
{/literal}
{$lang.ds_top_content}

<div class="subheaders-group_2">
<form class="ds_form_fill_nl" name="ds" method="post"   >
  <div class="form-field">
  <label for="Email" class="cm-required cm-email ">{$lang.ds_email}:</label>
  <input name="Email" type="text" size="55" value="" class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" id="Email" />
  </div>
  <div class="form-field">
      <label for="Merchant_name" class="cm-required" >{$lang.ds_merchant_name}:</label>
    <input name="Merchant_name" type="text" size="55" value="" class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" id="Merchant_name" />
  </div>
      <div class="form-field">
      <label for="Category_name" class="cm-required">{$lang.ds_category_name}:</label>
    <input name="Category_name" type="text" size="55" value="" class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" id="Category_name" />
 </div>
    <div class="form-field">
      <label for="Deal_select" class="cm-required">{$lang.ds_deal_select}:</label>
      <select name="Deal_select" id="Deal_select" class="round_five profile_detail_field cont_nl_slt_width">
        <option value="">Select</option>
        {foreach from=$promotion_type item="pro_type"}
			<option value="{$pro_type.promotion_type_id}">{$pro_type.type}</option>
		{/foreach}
      </select>
   </div>
    <div class="form-field">
      <label for="Product" class="cm-required " >{$lang.ds_products}:</label>
     <input name="Product" type="text" size="55" value="" class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100"  id="Product" />
  </div>
   <div class="form-field">
      <label for="Brand" class="cm-required">{$lang.ds_brands}:</label>
     <input name="Brand" type="text" size="55" value="" class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" id="Brand" />
 </div>
  <div class="form-field">
      <label for="Mrp" class="cm-required cm-integer">{$lang.ds_mrp}:</label>
      <input name="Mrp" type="text" size="55" value="" class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" id="Mrp" />
 </div>
  <div class="form-field">
     <label for="Rsp" class="cm-required cm-integer ">{$lang.ds_rsp}:</label>
      <input name="Rsp" type="text" size="55" value="" class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" id="Rsp" />
</div>
 <div class="form-field">
      <label for="Deal_price" class="cm-required cm-integer">{$lang.ds_deal_price}:</label>
      <input name="Deal_price" type="text" size="55" value="" class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" id="Deal_price" />
  </div>
 <div class="form-field">
   <label for="Minqty" class="cm-required cm-integer">{$lang.ds_minqty}:</label>
    <input name="Minqty" type="text" size="55" value="" class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" id="Minqty" />
  </div>
    
    <div class="form-field">
   <label for="merchant_id" class="cm-required cm-integer">{$lang.ds_merchant_id}:</label>
    <input name="merchant_id" type="text" size="55" value="" class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" id="merchant_id" />
  </div>
    <div class="form-field">
   <label for="mobile_no" class="cm-required cm-phone">{$lang.ds_mobile_no}:</label>
    <input name="mobile_no" type="text" size="55" value="" class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" id="mobile_no" />
  </div>
    
<div class="form-field">
	<label for="is_minqty" class="cm-required" >{$lang.ds_is_minqty}:</label>

	  <label style="width:50px; float:left; margin:0; clear:none">
	    <input name="is_minqty" type="radio" value="yes" checked="checked" class="input-text round_five " style="width:auto; background:none;border:0;" />
	    Yes</label>
	
	  <label style="width:50px; float:left; margin:0; clear:none">
	    <input type="radio" name="is_minqty" value="no" class="input-text round_five " style="width:auto; background:none;border:0;"  />
	    No</label>

</div>
    <div class="form-field">
	<label for="Possession_time" class="cm-required">{$lang.ds_possession_time}:</label>
     <select name="Possession_time" class="round_five profile_detail_field cont_nl_slt_width" id="Possession_time">
       <option value=""> Select-</option>
       <option value="3 Days">3 Days</option>
       <option value="7 Days">7 Days</option;>
       <option value="15 Days">15 Days</option>
       <option value="16days">More than 15 days</option>
	 
        </select>      
    </div>

      
   {include file="buttons/save.tpl" but_name="dispatch[ds.add]" but_text="Submit" but_role="button_main" but_class="box_functions_button nl_btn_blue"}   
  
  <div style="clear:both"></div>

</form>
</div>

  <div style="clear:both"></div>
      {$lang.ds_tnc}
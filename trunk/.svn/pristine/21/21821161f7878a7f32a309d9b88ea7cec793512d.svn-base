{if $config.express_checkout == 1}
<div id="shipping_address_express">
  <div class="box_headerTwo">
    <h1 class="box_headingTwo">{$lang.express_checkout_display_title}</h1>            
  </div>        
  <span class="float_left" style="width:100%;">
    {$lang.express_checkout_display_message}
  </span>
  <form class="express_checkout" name="express" method="post">
      <input type="hidden" name="token" value="{$smarty.session.form_token_value}"/>
    <div class="formOneColumn">
      <h1>{$lang.express_shipping_address}</h1>
      <div></div>
      <div class="row cm-required">
        <label for="s_firstname" class="cm-required">First Name</label>
        <input type="text" id="s_firstname" name="user_data[s_firstname]" size="32" value="{$express_fields.s_firstname}" />
      </div>

      <div class="row cm-required">
        <label for="s_lastname" class="cm-required">Last Name</label>
        <input type="text" id="s_lastname" name="user_data[s_lastname]" size="32" value="{$express_fields.s_lastname}" />
      </div>

      <div class="row cm-required">
        <label for="s_address" class="cm-required">Address</label>
        <input type="text" id="s_address" name="user_data[s_address]" size="32" value="{$express_fields.s_address}" />                
      </div>
      <div class="row cm-required">
        <label for="s_address_2" class="">Address, line 2:</label>                
        <input type="text" id="s_address_2" name="user_data[s_address_2]" size="32" value="{$express_fields.s_address_2}" />
      </div>
      <div class="row cm-required">
        <label for="s_country" class="cm-required">Country:</label>
        {assign var="country" value=$value|default:$settings.General.default_country}
        {if $express_fields.s_country ==''}
        {assign var="country_codes" value=$country}
        {else}
        {assign var="country_codes" value= $express_fields.s_country}
        {/if}
        <select id="s_country" name="user_data[s_country]" onchange="select_country(this.value, 's')" >
          <option value="">- {$lang.select_country} -</option>
          {foreach from=$countries item=country}
          <option {if $country_codes == $country.code}selected="selected" {/if} value="{$country.code}">{$country.country}</option>
          {/foreach}

        </select>                
      </div>

      
      <div class="row cm-required">
        <label for="s_state" class="cm-required">State</label>
        {if $country_codes == 'IN'}
        <select id="s_state" class="form_onecolumn_row_field_listbox" name="user_data[s_state]"   >
          <option value="">- {$lang.select_state} -</option>
          {* Initializing default states *}
          {assign var="country_code" value=$settings.General.default_country}
          {assign var="state_code" value=$value|default:$settings.General.default_state}
          {if $states}
          {foreach from=$states.$country_code item=state}
          <option {if $state.code == $express_fields.s_state}selected="selected"{/if} value="{$state.code}">{$state.state}</option>
          {/foreach}
          {/if}
        </select>
        <input type="text" id="s_state_d" name="user_data[s_state]" size="32" maxlength="64" value="{$express_fields.s_state}" disabled="disabled" style="display:none;" />
        {else}
        <select id="s_state" class="form_onecolumn_row_field_listbox" name="user_data[s_state]" style="display:none;" disabled="disabled" >
          <option value="">- {$lang.select_state} -</option>
          {* Initializing default states *}
          {assign var="country_code" value=$settings.General.default_country}
          {assign var="state_code" value=$value|default:$settings.General.default_state}
          {if $states}
          {foreach from=$states.$country_code item=state}
          <option {if $state.code == $express_fields.s_state}selected="selected"{/if} value="{$state.code}">{$state.state}</option>
          {/foreach}
          {/if}
        </select>
        <input type="text" id="s_state_d" name="user_data[s_state]" size="32" maxlength="64" value="{$express_fields.s_state}" />
        {/if}
      </div>
<div class="row cm-required">
        <label for="s_city" class="cm-required">City</label>
        <input type="text" id="s_city" name="user_data[s_city]" size="32" value="{$express_fields.s_city}" />
      </div>
      <div class="row cm-required">
        <label for="s_zipcode" class="cm-required cm-integer">Pincode</label>
        <input type="tel" id="s_zipcode" name="user_data[s_zipcode]" size="32" value="{$express_fields.s_zipcode}" maxlength="6">    
      </div>
      <div class="row cm-required">
        <label for="s_phone" class="cm-required cm-phone">Mobile Number</label>
        <input type="tel" id="s_phone" name="user_data[s_phone]" size="32" value="{$express_fields.s_phone}" maxlength="10" />
      </div>
    </div>

    {*2nd Column*}
    <div id="billing_address" class="formOneColumn">
      <h1>{$lang.express_billing_address} 
        {if $express_fields.billing_status == ''}
        {assign var="billing_stat" value='Y'}
        {else}
        {assign var="billing_stat" value=$express_fields.billing_status}
        {/if}
        <input  style="display:inline; width:15px; margin-left:3px;" type="checkbox" {if $billing_stat == 'Y'} checked="checked"{/if} name="user_data[billing_status]" id="billing_status">
        <label  style="display:inline;">{$lang.express_checkout_check_mesage}</label>
      </h1>


      <div class="row cm-required">
        <label for="b_firstname" class="cm-required">First Name</label>
        <input type="text" id="b_firstname" name="user_data[b_firstname]" size="32" value="{$express_fields.b_firstname}" disabled="disabled"/>
      </div>
      <div class="row cm-required">
        <label for="b_lastname" class="cm-required">Last Name</label>
        <input type="text" id="b_lastname" name="user_data[b_lastname]" size="32" value="{$express_fields.b_lastname}" disabled="disabled"/>
      </div>
      <div class="row cm-required">
        <label for="b_address" class="cm-required">Address</label>                
        <input type="text" id="b_address" name="user_data[b_address]" size="32" value="{$express_fields.b_address}" disabled="disabled"/>
      </div>
      <div class="row cm-required">
        <label for="b_address_2" class="">Address, line 2:</label>
        <input type="text" id="b_address_2" name="user_data[b_address_2]" size="32" value="{$express_fields.b_address_2}" disabled="disabled"/>
      </div>
      <div class="row cm-required">
        <label for="b_country" class="cm-required">Country:</label>
        {assign var="country" value=$value|default:$settings.General.default_country}
        {if $express_fields.b_country ==''}
        {assign var="country_codes" value=$country}
        {else}
        {assign var="country_codes" value= $express_fields.b_country}
        {/if}
        <select id="b_country" name="user_data[b_country]" onchange="select_country(this.value, 'b')" name="user_data[b_country]" >
          <option value="">- {$lang.select_country} -</option>
          {foreach from=$countries item=country}
          <option {if $country_codes == $country.code}selected="selected"{/if} value="{$country.code}">{$country.country}</option>
          {/foreach}
        </select>                
      </div>

      <div class="row cm-required">
        <label for="b_state" class="cm-required">State</label>
        {if $country_codes == 'IN'}
        <select id="b_state" class="form_onecolumn_row_field_listbox" name="user_data[b_state]"   >
          <option value="">- {$lang.select_state} -</option>
          {* Initializing default states *}
          {assign var="country_code" value=$settings.General.default_country}
          {assign var="state_code" value=$value|default:$settings.General.default_state}
          {if $states}
          {foreach from=$states.$country_code item=state}
          <option {if $state.code == $express_fields.b_state}selected="selected"{/if} value="{$state.code}">{$state.state}</option>
          {/foreach}
          {/if}
        </select>
        <input type="text" id="b_state_d" name="user_data[b_state]" size="32" maxlength="64" value="{$express_fields.b_state}" disabled="disabled" style="display:none;" />
        {else}
        <select id="b_state" class="form_onecolumn_row_field_listbox" name="user_data[b_state]" style="display:none;"  disabled="disabled">
          <option value="">- {$lang.select_state} -</option>
          {* Initializing default states *}
          {assign var="country_code" value=$settings.General.default_country}
          {assign var="state_code" value=$value|default:$settings.General.default_state}
          {if $states}
          {foreach from=$states.$country_code item=state}
          <option {if $state.code == $express_fields.b_state}selected="selected"{/if} value="{$state.code}">{$state.state}</option>
          {/foreach}
          {/if}
        </select>
        <input type="text" id="b_state_d" name="user_data[b_state]" size="32" maxlength="64" value="{$express_fields.b_state}"  />
        {/if}
      </div>
            <div class="row cm-required">
        <label for="b_city" class="cm-required">City</label>
        <input type="text" id="b_city" name="user_data[b_city]" size="32" value="{$express_fields.b_city}" />
      </div>
      <div class="row cm-required">
        <label for="b_zipcode" class="cm-required cm-integer">Pincode</label>
        <input type="tel" id="b_zipcode" name="user_data[b_zipcode]" size="32" value="{$express_fields.b_zipcode}" maxlength="6">
      </div>
      <div class="row cm-required">
        <label for="b_phone" class="cm-required cm-phone">Mobile Number</label>
        <input type="tel" id="b_phone" name="user_data[b_phone]" size="32" value="{$express_fields.b_phone}" maxlength="10" />
      </div>
    </div>

    <div class="box_headerTwo" style="margin-top:30px;">
      <h1 class="box_headingTwo">{$lang.express_payment_options_id}</h1>
    </div>

    <div class="float_left margin_top_ten">
      <label for="payment_option" class="cm-required">{$lang.exp_paymnet_type_id}</label>
      <select id="payment_option" class="form_onecolumn_row_field_listbox" name="user_data[payment_option]" onchange="select_payment(this.value, '')">
        <option value="">- {$lang.select_any_payment_option} -</option>
        {assign var="payment_types" value=""|get_payment_types}
        {foreach from=$payment_types item=p_types}
        {if $p_types.payment_type_id!=6}
        <option  {if $p_types.payment_type_id == $pymnt_options.payment_type_id}selected="selected"{/if} value="{$p_types.payment_type_id}">{$p_types.name}</option>
        {/if}
        {/foreach}
      </select>
    </div>


    <div class="float_left margin_top_ten">
        <label for="payment_option_id" class="cm-required">{$lang.express_check_box}</label>
        <select id="payment_option_id" class="form_onecolumn_row_field_listbox" name="user_data[payment_option_id]">
          <option  value="">Select one option</option>;
          {foreach from=$paymnt_data item=ids}

          <option  {if $ids.payment_option_id == $pymnt_options.payment_option_id}selected="selected"{/if} value="{$ids.payment_option_id}">{$ids.name}</option>

          {/foreach}
        </select>
      </div>
      <div class="clearboth height_twenty"></div>


      <div class="box_headerTwo" style="margin-top:30px;">
        <h1 class="box_headingTwo">{$lang.express_checked_block}</h1>
      </div>


      <div class="float_left margin_top_ten">            
        <input type="checkbox" {if $express_fields.apply_cb == 'Y'}checked="checked"{/if} name="user_data[apply_cb]">
        <label style="font:11px verdana;" for="apply_cb">{$lang.express_apply_express_cb}</label>
      </div>
      <div class="clearboth height_ten"></div>
      <div class="float_left margin_top_ten">            
        <input type="checkbox" {if $express_fields.apply_promotion == 'Y'}checked="checked"{/if}  name="user_data[apply_promotions]">
        <label style="font:11px verdana;" for="apply_promotions">{$lang.express_apply_promotions}</label>
      </div>

      <div class="clearboth height_ten"></div>


      <a style="color:#fff; margin-left:10px;" class="nl_btn_blue" href="{"profiles.myaccount"|fn_url}">Cancel</a>
      {include file="buttons/save.tpl" but_name="dispatch[profiles.express_settings]" but_text="Submit" but_role="button_main" but_class="box_functions_button nl_btn_blue"}   


    </form>
  </div>
  {/if}
  {literal}
  <script type="text/javascript">

    function select_country(country_code, type)
    {
      if (country_code != 'IN')
      {
        $('#' + type + '_state').attr('disabled', 'disabled');
        $('#' + type + '_state').css('display', 'none');
        $('#' + type + '_state_d').removeAttr('disabled');
        $('#' + type + '_state_d').css('display', 'block');
        $('#' + type + '_state_d').removeClass('hidden');
      } else {
        $('#' + type + '_state').removeAttr('disabled', 'disabled');
        $('#' + type + '_state').css('display', 'block');
        $('#' + type + '_state_d').attr('disabled','disabled');
        $('#' + type + '_state_d').css('display', 'none');
      }
    }
    function select_payment(opt, period)
    {

      $.ajax({
        type: "GET",
        url: 'index.php',
        dataType: 'text',
        data: {dispatch: 'profiles.checkout_express_settings', id: opt},
        success: function(response) {

          $('#payment_option_id').html(response);
        }

      });

    }

    $('#billing_status').click(function() {

      check();

    });

    $(document).ready(function() {
      $('.express_checkout').bind('submit', saveaddress);

      check();

    });


    function check()
    {
      if ($('#billing_status').attr("checked") == true)
      {
        $('#b_firstname').attr('disabled', 'disabled');
        $('#b_lastname').attr('disabled', 'disabled');
        $('#b_address').attr('disabled', 'disabled');
        $('#b_address_2').attr('disabled', 'disabled');
        $('#b_country').attr('disabled', 'disabled');
        $('#b_city').attr('disabled', 'disabled');
        $('#b_state').attr('disabled', 'disabled');
        $('#b_zipcode').attr('disabled', 'disabled');
        $('#b_phone').attr('disabled', 'disabled');
      }
      else
      {

        removedisabled();

      }
    }
    function removedisabled()
    {
      $('#b_firstname').removeAttr('disabled','disabled');
      $('#b_lastname').removeAttr('disabled','disabled');
      $('#b_address').removeAttr('disabled','disabled');
      $('#b_address_2').removeAttr('disabled','disabled');
      $('#b_country').removeAttr('disabled','disabled');
      $('#b_city').removeAttr('disabled','disabled');
      if($('#b_country').val() == 'IN')
      {
        $('#b_state').removeAttr('disabled','disabled');
      }
      $('#b_zipcode').removeAttr('disabled','disabled');
      $('#b_phone').removeAttr('disabled','disabled');
    }

    function saveaddress()
    { 

      if($(this).find('.cm-failed-field').length == 0 ){

        if($('#billing_status').attr("checked") == true)
        {

          $('#b_firstname').val($('#s_firstname').val());
          $('#b_lastname').val($('#s_lastname').val());
          $('#b_address').val($('#s_address').val());
          $('#b_address_2').val($('#s_address_2').val());
          $('#b_country').val($('#s_country').val());
          $('#b_city').val($('#s_city').val());
        //$('#b_state_d').val($('#s_state_d').val());
        $('#b_zipcode').val($('#s_zipcode').val());
        $('#b_phone').val($('#s_phone').val());
        $('#b_state').removeAttr('disabled','disabled');
        removedisabled();
      }
    }
  }

</script>
{/literal}

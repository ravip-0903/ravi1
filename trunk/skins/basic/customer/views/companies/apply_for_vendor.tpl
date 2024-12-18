<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>

<body>
{literal}
<style>
.merchant_info_sell_nl_pg{clear:both; text-align:right; font-size:14px; color:#EE811D;}
.merchant_info_sell_nl_pg label.name{clear:both; display:block; text-align:right;}
.merchant_info_sell_nl_pg span{clear:both; display:block; text-align:right; font-size:12px; color:#999; margin:-4px 0 0 0;}
</style>
{/literal}
<div id="content">
<div id="merchant_page">
	<div class="merchant_benefits no_mobile">
    <div class="merchant_banner"><img src="http://cdn.shopclues.com/images/banners/merchant_sell_banner.jpg" width="674" height="130" alt="Merchant Benefits" title="Merchant Benefits" /></div>
    <h2>Benefits of Selling at ShopClues</h2>
    <div class="benefit_box">
     <div class="quote_box">
         <img class="quote_left" src="http://cdn.shopclues.com/images/skin/left_quote.png" />
         <img class="merchant_image" src="http://cdn.shopclues.com/images/skin/user_pic.jpg" />
         <div class="message">
         We were pleasantly surprised by the immediate response which I got through ShopClues. Their customer base is growing at a fast pace.
    	 </div>
         <img class="quote_right" src="http://cdn.shopclues.com/images/skin/right_quote.png" />
         <div class="merchant_info_sell_nl_pg">
         <label>Mr. Manish Mangotra</label>
         <span>DGM Marketing, Guardian Lifecare</span>
         </div>
     </div>
    <p><span class="points">1</span>Expand online with no upfront or recurring cost</p>
    <p><span class="points">2</span>Your own branded online store & product listings</p>
    <p><span class="points">3</span>Millions of online shoppers can access your products</p>
    <p><span class="points">4</span>Secured and multiple online payment options</p>
    <p><span class="points">5</span>Effective, low cost, and scalable </p>
    <p><span class="points">6</span>World-class fulfillment -- we pick, pack and ship for you</p>
    <p><span class="points">7</span>Performance based pricing -- pay only when you sell</p>
    <p><span class="points">8</span>All the advantages of marketplace e.g. power of combined scale, cross-sell</p>
    <p><span class="points">9</span>Outstanding customer services by E-Commerce experts</p>
    <p><span class="points">10</span>Anytime, anywhere, any device access to manage your online store</p>
    
   
    </div>
    </div>
    
    <!-- registration -->
    <form action="{"companies.apply_for_vendor"|fn_url}" method="post" name="apply_for_vendor_form">
    <div class="registration_form">
        <h2>Registration Form</h2>
        <img class="free_registration" src="http://cdn.shopclues.com/images/skin/free_registration.png" />

        <div class="registration_box">
<div class="form-field">
        <span class="title first">
        <label for="company_admin_firstname" class="cm-required cm-trim">{$lang.your_name}:</label>
        </span>
       	<input type="text" name="company_data[admin_firstname]" id="company_admin_firstname" size="32" value="{$firstname}" class="input-text{if $settings.General.use_email_as_login != 'Y'} disabled" disabled="disabled" {else}{/if}/>
        </div>
	<div class="clearboth"></div>        
       <div class="form-field">
       <span class="title"><label for="company_description_company" class="cm-required cm-trim">{$lang.company}:</label></span>
        <input type="text" name="company_data[company]" id="company_description_company" size="32" value="{$company}" class="input-text" {if $smarty.request.error=='name'} style="border:1px solid #ff0000;"{/if} />
        </div>
        
        <div class="form-field"><span class="title"><label for="company_description_phone" class="cm-required cm-trim">{$lang.phone}:</label></span>
        <input type="tel" name="company_data[phone]" id="company_description_phone" size="32" value="{$phone}" class="input-text" {if $smarty.request.error=='phone'} style="border:1px solid #ff0000;"{/if} /></div>
        <span class="title">
        <label for="company_description_email" class="cm-email cm-trim cm-required">{$lang.email}:</label>
        </span>

	{if $smarty.request.aid}
	   <input type="hidden" name="aid" value="{$smarty.request.aid}">
	{/if}

        <input type="email" name="company_data[email]" id="company_description_email" size="32" value="{$email}" class="input-text" />
        {*Sahil*}
        <div class="form-field">
	{assign var="country_code" value=$company_data.country|default:$settings.General.default_country}
	{assign var="warehouse_state_code" value=$warehouse_data.warehouse_state|default:$settings.General.default_state}
	<label for="state" class="">{$lang.state}:</label>	 
        <select id="state" name="company_data[state]" {if !$states.$country_code}class="hidden"{/if}>
		<option value="">- {$lang.select_state} -</option>
		{* Initializing default states *}
		{if $states.$country_code}
			{foreach from=$states.$country_code item=warestate}
				<option {if $warehouse_state_code == $warestate.code}selected="selected"{/if} value="{$warestate.code}">{$warestate.state}</option>
			{/foreach}
		{/if}
	</select>
	 </div>
        <span class="title"><label for="company_address_city">{$lang.city}:</label></span>
        <input type="text" name="company_data[city]" id="company_address_city" size="32" value="{$company_data.city}" class="input-text" />
       
       <span class="title">Business Type:</span>
        <select class="business_type" name="company_data[business_type]" id="company_description_business_type" onchange="return chk_category_type(this);">
        	<option value="">--Select--</option>
        	{foreach from=$root_categories item=root_categories}
    			<option value="{$root_categories.category_id}" {if $root_categories.category_id==$category_id}selected='selected'{/if}>{$root_categories.category}</option>
		{/foreach}
    			<option value="0">Other</option>
        </select>
        <div id="oth_cat" style="display:none;">
        <span class="title">Other:</span>
        <input type="text" name="company_data[business_type_other]" id="company_description_business_type_other" size="32" class="input-text" /></div>
        
        
        <span class="title">Do you already sell online?</span>
        <select name="company_data[sell_online]" id="company_description_sell_online">
        	<option value="">--Select--</option>
        	<option value="yes" {if $sell_online=='yes'}selected='selected'{/if}>Yes</option>
           	<option value="no"  {if $sell_online=='no'}selected='selected'{/if}>No</option>
        </select>
        <span class="title">Do you have a ready online catalog?</span>
        <select name="company_data[online_cat]" id="company_description_online_cat">
        	<option value="">--Select--</option>
            <option value="yes" {if $online_cat=='yes'}selected='selected'{/if}>Yes</option>
           	<option value="no" {if $online_cat=='no'}selected='selected'{/if}>No</option>
        </select>
        <div class="clearboth"></div>
        {include file="buttons/button.tpl" but_class="register_btn" but_text=$lang.submit but_name="dispatch[companies.apply_for_vendor]" but_id="but_apply_for_vendor"}
       <!-- <a class="register_btn" href="#">
        	<span>Register</span>
        </a>-->
        </div>
    </div>
    </form>
    <!-- eof registration -->
    <div class="clearboth"></div>
    
    {$lang.merchant_link_for_sellers}
    
    
</div>    
</div>
</body>
</html>
{literal}
<script type="text/javascript">
function chk_category_type(obj){
	if (obj.value == 0){
		$('#oth_cat').show();
	} else {
		$('#oth_cat').hide();
	}
}
</script>
{/literal}

{* $Id: myaccount.tpl 12544 2011-05-27 10:34:19Z bimib $ *}

<form method="post" action="{""|fn_url}" name="del_addressbook" id="del_addressbook">
<div class="mobile float_right"><a href="#nw_addrss_bk_add" onclick="return false;" id="mobile_add_new_address"><span class="plus_lrg">+</span> Add New Address</a></div>
<div class="box_header" style="margin:5px 0 0; width:100%;">
<h1 class="box_heading">{$lang.address_book}</h1>
<a href="index.php?dispatch=profiles.upload_excel" class="pj2_address_book_action">{$lang.upload_excel}</a>
{if $smarty.session.multiaddress_viewed == 'Y'}
    <a href="index.php?dispatch=checkout.mashipping" class="pj2_address_book_action">{$lang.back_to_multiaddress}</a>
{/if}


</div>
{if $smarty.session.success_reports}
	<div class="pj2_add_book_error_message" style="padding-right:20px; position:relative;" id="pj2_add_book_error_message">{$smarty.session.success_reports}	<img border="0" width="13" height="13" title="Close" style="position: absolute; right: 5px; top: 5px;" alt="Close" src="skins/basic/customer/images/icons/icon_close.gif" class="address_book_error hand" id="address_book_error">

</div>
{/if}


<input type="hidden" name="mode" value="manage" />
<input type="hidden" name="selected_section" value="general" />
<input type="hidden" name="token" value="{$smarty.session.form_token_value}"/>
<div class="mam_add_up_blk">
    <div class="mam_add_up_head">
        <div class="mam_add_up_div mam_frst_nm">Personal Information</div>
        <div class="mam_add_up_div mam_add">Address Details</div>
        <div class="mam_add_up_div mam_city"></div>
    </div>
   
    {foreach from=$user_profiles item=user_profile name="uprpf"}
        {if $user_profile.s_zipcode eq '' && $user_profile.s_firstname eq '' && $user_profile.s_lastname eq '' && $user_profile.s_address eq '' && $user_profile.s_phone eq '' && $user_profile.s_city eq '' && $user_profile.s_profile_name eq ''}
             {assign var="display_flag" value="0"}
        {else}
            {assign var="display_flag" value="1"}
        {/if}
        <div class="mam_add_up_field">

            {if $smarty.foreach.uprpf.iteration ==2}
            <div class="box_addressbook_action">
                <input type="checkbox" name="prfChkAll" style="float:left; margin:5px 5px 5px 15px;" id="prfChkAll" onChange="toggle_all(this);" title="Select/Deselect All">
                <div id="prfSellAll" style="float:left; margin:2px 0 0 5px ;">Select All</div>
                <input type="hidden" name="mode_action" value="delete_prf" />
                <input type="submit" class="pj2_address_book_del_action" id="del_sel_prf" style="border:0; margin:0; color:#ff0000;" name="dispatch[profiles.upload_excel]" value="Delete Profile">
            </div>
        {/if}</div>
    <div class="mam_add_up_field">
        {if $smarty.foreach.uprpf.iteration !=1}
            <input style="float:left; margin:3px 10px 3px 15px;" type="checkbox" name="prfChk[]" id="prfChk" value="{$user_profile.profile_id}">
        {/if}
        <div class="mam_add_up_nxt_field" style="{if $display_flag eq 0}display:none;{else}display:block;{/if}">
            <div class="mam_add_field_display" id="first_info">
                <span class="mam_add_pro_nam">{$user_profile.profile_name}</span><br/>
                <span>{$user_profile.s_firstname}</span> <span>{$user_profile.s_lastname}</span><br/>
                <span>{$user_profile.s_phone}</span><br/>
            </div>
            <div class="mam_add_field_display" id="second_info">
                <span>{$user_profile.s_address}</span><br/>
                <span>{$user_profile.s_address_2}</span><br/>
                <span>{$user_profile.s_city}</span><br/>
                <span>{$user_profile.s_state}</span><br/>
                <span>{$user_profile.s_zipcode}</span><br/>
            </div>
        </div>
            <div class="box_addressbook" style="{if $display_flag eq 0}display:none;{else}display:block;{/if}">
                <div class="box_addressbook_functions">
                <a class="box_header_linkright">Edit</a>
                {if $user_profile.profile_type != "P" && !$hide_profile_delete}
                  {include file="buttons/button.tpl" but_meta="cm-confirm" but_rev="checkout_steps,cart_items,checkout_totals" but_role="delete" but_text="&nbsp;" but_href="profiles.delete_addressbook?profile_id=`$user_profile.profile_id`"}
                {/if}
                <!--<a href="index.php?dispatch=profiles.delete_addressbook&profile_id={$user_profile.profile_id}" class="box_header_linkright">Delete</a>-->
                </div>
            </div>
    </div>
        <div id="add_update_address_form" style="{if $display_flag eq 0}display:block;{else}display:none;{/if}">
            <div class="mam_add_up_field">
                <div class="mam_add_up_nxt_field">
                    <div class="mam_add_blk_ma">
                        <input type="hidden" name="profile_id[]" value="{$user_profile.profile_id}" />
                        <div class="mam_add_up_input_field mam_frst_nm_bx"><input type="text" name="profile_name[]" placeholder="Profile Name" value="{$user_profile.profile_name}" /></div>
                        <div class="mam_add_up_input_field mam_lst_nm_bx  mam_first"><input type="text" name="first_name[]" placeholder="First Name" value="{$user_profile.s_firstname}" /></div>
                        <div class="mam_add_up_input_field mam_lst_nm_bx  mam_last"><input type="text" name="last_name[]" placeholder="Last Name" value="{$user_profile.s_lastname}" /></div>
                        <div class="mam_add_up_input_field mam_mobile_no_bx"><input type="tel" name="phone[]" maxlength="10" placeholder="Mobile"  value="{$user_profile.s_phone}" /></div>
                    </div>
                    <div class="mam_add_blk_ma">
                        <div class="mam_add_up_input_field mam_add_bx"><textarea name="address[]" placeholder="Address" >{$user_profile.s_address}</textarea></div>
                        <div class="mam_add_up_input_field mam_city_bx"><input type="text" name="address_2[]" placeholder="Address 2" value="{$user_profile.s_address_2}"/></div>
                    </div>
                    <div class="mam_add_blk_ma">
                        <div class="mam_add_up_input_field mam_pincode_bx"><input type="text" name="city[]" placeholder="City" value="{$user_profile.s_city}"/></div>
                        <div class="mam_add_up_input_field mam_state_bx">
                            <select type="text" name="state[]">
                            <option value="">- {$lang.select_state} -</option>
                                {assign var="country_code" value=$settings.General.default_country}
                                {assign var="state_code" value=$value|default:$settings.General.default_state}
                                {if $states}
                                    {foreach from=$states.$country_code item=state}
                                        <option {if $state.code == $user_profile.s_state}selected="selected"{/if} value="{$state.code}">{$state.state}</option>
                                    {/foreach}
                                {/if}
                            </select>
                        </div>
                        <div class="mam_add_up_input_field mam_pincode_bx mam_pin"><input type="tel" name="zipcode[]" placeholder="Zipcode" maxlength="6" value="{$user_profile.s_zipcode}"/></div>
                    </div>
                </div>
                    <div class="mam_add_up_input_field mam_pincode_bx"><a class="close_edit">Close</a></div>
            </div>
        </div>
    {foreachelse}
        <div class="clearboth height_ten"></div>
        <p class="no-items">{$lang.no_data}</p>
    {/foreach}        
    <div class="mam_add_up_field" id="add_new_profile">
        <div class="mam_add_up_nxt_field">
            <div class="mam_add_up_input_field mam_frst_nm_bx no_mobile" style="float:left;"><a id="add_new_address"><span class="plus_lrg">+</span> Add New Address</a></div>
            <div class="form_submit_div" style="float:right;{if $display_flag eq 0}display:block;{else}display:none;{/if}">
                <input class="save_button" type="submit" name="dispatch[profiles.update_addressbook]" value="Save" />
            </div>
        </div>
    </div>
</div>
</form>
<div id="add_new_address_form"  style="display:none;">
            <div class="mam_add_up_field address_book_add" id="nw_addrss_bk_add">
                <div class="mam_add_up_nxt_field">
                    <div class="mam_add_blk_ma">
                    <input type="hidden" name="profile_id[]" value="0" />
                    <div class="mam_add_up_input_field mam_frst_nm_bx"><input type="text" class="cm-required" name="profile_name[]" placeholder="Profile Name" onblur="this.style.background = '#FFF';" /></div>
                    <div class="mam_add_up_input_field mam_lst_nm_bx"><input type="text" class="cm-required" name="first_name[]" placeholder="First Name" onblur="this.style.background = '#FFF';" /></div>
                    <div class="mam_add_up_input_field mam_lst_nm_bx"><input type="text" class="cm-required" name="last_name[]" placeholder="Last Name" onblur="this.style.background = '#FFF';" /></div>
                    <div class="mam_add_up_input_field mam_mobile_no_bx"><input type="tel" class="cm-required" name="phone[]" maxlength="10" placeholder="Mobile" onblur="this.style.background = '#FFF';" /></div>
                </div>
                <div class="mam_add_blk_ma">
                    <div class="mam_add_up_input_field mam_add_bx"><textarea name="address[]" placeholder="Address" class="cm-required"  onblur="this.style.background = '#FFF';"></textarea></div>
                    <div class="mam_add_up_input_field mam_city_bx"><input type="text" name="address_2[]" placeholder="Address 2" onblur="this.style.background = '#FFF';"/></div>
                </div>
                <div class="mam_add_blk_ma">
                    <div class="mam_add_up_input_field mam_pincode_bx"><input type="text" name="city[]" class="cm-required" placeholder="City" onblur="this.style.background = '#FFF';"/></div>
                    <div class="mam_add_up_input_field mam_state_bx">
                        <select type="text" name="state[]" class="cm-required" onblur="this.style.background = '#FFF';">
                        <option value="">- {$lang.select_state} -</option>
                            {assign var="country_code" value=$settings.General.default_country}
                            {assign var="state_code" value=$value|default:$settings.General.default_state}
                            {if $states}
                                {foreach from=$states.$country_code item=state}
                                    <option value="{$state.code}">{$state.state}</option>
                                {/foreach}
                            {/if}
                        </select>
                    </div>
                    <div class="mam_add_up_input_field mam_pincode_bx"><input type="tel" name="zipcode[]" maxlength="6" placeholder="Zipcode" onblur="this.style.background = '#FFF';" /></div>
                </div>
            </div> 
                        <div class="mam_add_up_input_field mam_pincode_bx"><a class="close_add_new_form" onclick="$(this).parents('.mam_add_up_field').remove();" >Close</a></div>
        </div>
</div>
{literal}
<script>
 $(document).ready(function(){
        $('#add_new_address,#mobile_add_new_address').bind('click', addform);
        $('.box_header_linkright').bind('click', editform);
        $('.close_edit').bind('click', closeform);
        $('.save_button').bind('click', checkform);
    });
    
    function checkform()
    { 
        	var elms=this.form.elements;
                for(var i=0;i<elms.length;i++)
		{  
			var elm=elms[i];
			var val = elm.value.replace(/(^\s+|\s+$)/g, '');
                        var mobile  = /^\d{10}$/;
                        var zip  = /^\d{6}$/;
                        var name = /^[A-Za-z\s]+$/;
                        if(elm.name.indexOf('address_2') > -1){
                            continue;
                        }
                        if(elm.name.indexOf('phone') > -1){
                            if(!mobile.test(val))
                                {
                                    elm.focus();
                                    elm.style.background='#FFCCCC';
                                    $('input:[value='+val+']').parents('#add_update_address_form').css('display','block');
                                    $('input:[value='+val+']').parents('#add_update_address_form').prev().children(".mam_add_up_nxt_field").css('display','none');
                                    $('input:[value='+val+']').parents('#add_update_address_form').prev().children(".box_addressbook").css('display','none');
                                    return false;
                                }
                        }
                        if(elm.name.indexOf('zipcode') > -1){
                            if(!zip.test(val))
                                {
                                    elm.focus();
                                    elm.style.background='#FFCCCC';
                                    $('input:[value='+val+']').parents('#add_update_address_form').css('display','block');
                                    $('input:[value='+val+']').parents('#add_update_address_form').prev().children(".mam_add_up_nxt_field").css('display','none');
                                    $('input:[value='+val+']').parents('#add_update_address_form').prev().children(".box_addressbook").css('display','none');
                                    return false;
                                }
                        }
                         if((elm.name.indexOf('first_name') > -1) || (elm.name.indexOf('last_name') > -1)){
                            if(!name.test(val))
                                {
                                    elm.focus();
                                    elm.style.background='#FFCCCC';
                                    $('input:[value='+val+']').parents('#add_update_address_form').css('display','block');
                                    $('input:[value='+val+']').parents('#add_update_address_form').prev().children(".mam_add_up_nxt_field").css('display','none');
                                    $('input:[value='+val+']').parents('#add_update_address_form').prev().children(".box_addressbook").css('display','none');
                                    return false;
                                }
                        }
                        if(val=='')
			{
				elm.focus();
				elm.style.background='#FFCCCC';
                                $('input:[value='+val+']').parents('#add_update_address_form').css('display','block');
                                $('input:[value='+val+']').parents('#add_update_address_form').prev().children(".mam_add_up_nxt_field").css('display','none');
                                $('input:[value='+val+']').parents('#add_update_address_form').prev().children(".box_addressbook").css('display','none');
				return false;
			}
			
		}
		return true;
    }
    function closeform()
    {
        $(this).parent().parent().parent().prev().children(".mam_add_up_nxt_field").css('display','block');
        $(this).parent().parent().parent().prev().children(".box_addressbook").css('display','block');
        $(this).parent().parent().parent().css('display','none');
        var pro_name = $(this).parent().parent().parent().prev().children(".mam_add_up_nxt_field").children("#first_info").children("span:nth-child(1)").text();
        var fname = $(this).parent().parent().parent().prev().children(".mam_add_up_nxt_field").children("#first_info").children("span:nth-child(3)").text();
        var lname = $(this).parent().parent().parent().prev().children(".mam_add_up_nxt_field").children("#first_info").children("span:nth-child(4)").text();
        var mobile = $(this).parent().parent().parent().prev().children(".mam_add_up_nxt_field").children("#first_info").children("span:nth-child(6)").text();
        var add = $(this).parent().parent().parent().prev().children(".mam_add_up_nxt_field").children("#second_info").children("span:nth-child(1)").text();
        var add2 = $(this).parent().parent().parent().prev().children(".mam_add_up_nxt_field").children("#second_info").children("span:nth-child(3)").text();
        var city = $(this).parent().parent().parent().prev().children(".mam_add_up_nxt_field").children("#second_info").children("span:nth-child(5)").text();
        var state = $(this).parent().parent().parent().prev().children(".mam_add_up_nxt_field").children("#second_info").children("span:nth-child(7)").text();
        var pin = $(this).parent().parent().parent().prev().children(".mam_add_up_nxt_field").children("#second_info").children("span:nth-child(9)").text();
        $(this).parent().parent().parent().children(".mam_add_up_field").children(".mam_add_up_nxt_field").children(".mam_add_blk_ma").children(".mam_frst_nm_bx").children("input").val(pro_name);
        $(this).parent().parent().parent().children(".mam_add_up_field").children(".mam_add_up_nxt_field").children(".mam_add_blk_ma").children(".mam_first").children("input").val(fname);
        $(this).parent().parent().parent().children(".mam_add_up_field").children(".mam_add_up_nxt_field").children(".mam_add_blk_ma").children(".mam_last").children("input").val(lname);
        $(this).parent().parent().parent().children(".mam_add_up_field").children(".mam_add_up_nxt_field").children(".mam_add_blk_ma").children(".mam_mobile_no_bx").children("input").val(mobile);
        $(this).parent().parent().parent().children(".mam_add_up_field").children(".mam_add_up_nxt_field").children(".mam_add_blk_ma").children(".mam_add_bx").children("textarea").val(add);
        $(this).parent().parent().parent().children(".mam_add_up_field").children(".mam_add_up_nxt_field").children(".mam_add_blk_ma").children(".mam_city_bx").children("input").val(add2);
        $(this).parent().parent().parent().children(".mam_add_up_field").children(".mam_add_up_nxt_field").children(".mam_add_blk_ma").children(".mam_pincode_bx").children("input").val(city);
        $(this).parent().parent().parent().children(".mam_add_up_field").children(".mam_add_up_nxt_field").children(".mam_add_blk_ma").children(".mam_pin").children("input").val(pin);
        $(this).parent().parent().parent().children(".mam_add_up_field").children(".mam_add_up_nxt_field").children(".mam_add_blk_ma").children(".mam_state_bx").children("select").children("option[value='"+state+"']").attr("selected", "selected");
    }
    
    function editform()
    {
        $(this).parent().parent().parent().children(".mam_add_up_nxt_field").css('display','none');
        $(this).parent().parent().parent().children(".box_addressbook").css('display','none');
        $(this).parent().parent().parent().next().css('display','block');
        $('.form_submit_div').css('display','block'); 
    }
    function addform()
    {
        $('.form_submit_div').css('display','block'); 
        var cloneData=$("#add_new_address_form").html(); 
        $('#add_new_profile').before(cloneData);
	$('html, body').animate({
		scrollTop: $(".address_book_add:first").offset().top-70
	    }, 200);


    }

	$('#address_book_error').click(function(){
		$('#pj2_add_book_error_message').hide();
		$('#address_book_error').hide();
		$.ajax({
		  type: "GET",
		  url: "index.php",
		  data: {dispatch:'profiles.remove_error'}
		});
	});
	$('#prfChkAll').click(function(){
		$('INPUT[name=prfChk[]][type=checkbox]').attr('checked',this.checked);
	});
	function toggle_all(obj){
		if(obj.checked){
			$('#prfSellAll').html("Deselect All");
		}else{
			$('#prfSellAll').html("Select All");
		}
	}

	$('#del_sel_prf').click(function(){

		var co = confirm("Are you sure you want to delete these profile?");
		if(co == true){

			var pfObj = $('INPUT[name=prfChk[]][type=checkbox]');
			var flag = 0;

			for (var i=0; i < pfObj.length; i++)
			{
			  if(pfObj[i].checked){
				  flag++;
			  }
			}
				if(flag == 0){
					alert("Please Checked the profile to delete");
					return false;
				}
		}else{
			return false;
		}
	});
</script>
{/literal}

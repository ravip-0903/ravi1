<div id="trackorder_status" style="float:left; width:170px;">

<div class="sidebox-wrapper ">
<h3 class="sidebox-title"><span>Help Topics</span></h3>
    <!--<span class="stars_icon"></span>-->
    <div class="clearboth"></div>
	<div class="sidebox-body">
            
       <ul>
		<li><a href="/shipping-and-delivery.html" id="sandd">Shipping &amp; Delivery</a></li>
		<li><a href="/cancel-items-or-orders.html" id="como">Cancel Or Modify Order</a></li>
		<li><a href="/return-or-replacement.html" id="ror">Return or Replacement</a></li>
		<li><a href="/payments.html" id="pay">Payments</a></li>
<li><a href="/ordering.html" id="ord">Ordering</a></li>
<li><a href="/product-query.html" id="pq">Product Query</a></li>
		<li><a href="/promotions-and-coupon.html" id="pac">Promotions &amp; Coupon</a></li>
		<li><a href="/clues-bucks.html" id="cb">Clues Bucks</a></li>
		<li><a href="/gift-certificate.html" id="gc">Gift Certificate</a></li>
		
		<li><a target="_blank" href="/buyer-protection.html">Buyer Protection</a></li>
<li><a target="_blank" href="/bandoftrust.html">Band of Trust</a></li>
<li><a target="_blank" href="http://www.shopclues.com/sell">Selling at ShopClues</a></li>
<li><a href="managing-your-account.html" id="mya">Managing Your Account</a></li>
	</ul>
</div>
	<div class="sidebox-bottom"><span>&nbsp;</span></div>
</div>


</div>


<div id ="order_tools" style="float:left; margin-left:20px;">
<div>
    <h1 class="main_heading" style="color: #EE811D; font-size: 22px;">{$lang.Order_Lookup}</h1>
<form method="POST">
<div style="margin-top:20px;">
    <div><label style="color: #636566; font: 11px/20px Verdana,Geneva,sans-serif;">Order Number<span style="color: red;">*</span></label> </div>
<div><input name="order_id" id="order_id" value="{$smarty.request.order_id}" type="text"  style="border-radius:5px; padding:5px; border:1px inset;" /></div>
</div>

<div style="margin-top:15px;">
    <div><label style="color: #636566; font: 11px/20px Verdana,Geneva,sans-serif;">Email<span style="color: red;">*</span></label> </div>
<div><input name="email_id" id="email_id"  value="{$smarty.request.email_id}" type="email"  style="border-radius:5px; padding:5px; border:1px inset;"/></div>
</div>
<input type="hidden" id="x" name="x" value="{$smarty.request.x}" />
<div>
<div></div>
<div id ="submitButton_order"><input name="" type="button" value="Submit"  onclick="validate_form()" style="background-color:#0587bb; float:right; padding:5px; color:#fff; border:none; border-radius:5px; font-size:13px trebuchet ms; margin-top:10px;" /></div>
</div>
</form>
</div>
<p> </p><p> </p><p> </p><p> </p><p> </p>
<p align='justify'> {$lang.lookup_msg}</p>

<!--<p align='justify'><a href='{$config.http_location}/index.php?dispatch=write_to_us.add'>{$lang.order_id_blank}</a></p> -->

</div>

<div id="self_help_tools" style="float:right; width:170px;">

<div class="sidebox-wrapper ">
<h3 class="sidebox-title"><span>Self Help Tools</span></h3>
    <!--<span class="stars_icon"></span>-->
    <div class="clearboth"></div>
	<div class="sidebox-body">
            
     <ul>
		<!--<li><a href="/track-orders.html">Track Order</a></li>-->
		<!--<li><a href="/cancel-items-or-orders.html">Confirm COD Order</a></li>-->
		<li><a target="_blank" href="https://secure.shopclues.com/index.php?dispatch=orders.search">Cancel Order</a></li>
		<!--<li><a href="/managing-your-account.html">Resend Shipping Details</a></li>
		<li><a href="/payments.html">Confirm Order Delivery</a></li>
		<li><a href="/promotions-and-coupon.html">Edit Order Address</a></li>-->
		<li><a target="_blank" href="https://secure.shopclues.com/index.php?dispatch=orders.search">Request Return Of Order</a></li>
		<li><a href="/clues-bucks.html" target="_blank">Clues Bucks Tracker</a></li>
        <li><a href="/giftcertificate" target="_blank">Gift Certificate Tracker</a></li>
		<li><a target="_blank" href="https://secure.shopclues.com/index.php?dispatch=profiles.manage_addressbook">Update Address Book</a></li>
		<li><a target="_blank" href="https://secure.shopclues.com/update-profile">Change Password</a></li>
	</ul>
</div>
	<div class="sidebox-bottom"><span>&nbsp;</span></div>
</div>

</div>

{literal}
<script type="text/javascript">
       function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'none')
          e.style.display = 'block';
       else
          e.style.display = 'none';
    }
	function validate_form()
	{
		var url="index.php?dispatch=order_lookup.details";
		var order_id=$.trim(document.getElementById('order_id').value);
		var email_id=$.trim(document.getElementById('email_id').value);
		var x=$.trim(document.getElementById('x').value);
		if(x=='y'){
		 url+='&order_id='+order_id+'&email_id='+email_id+'&x='+x}
		else{
		 url+='&order_id='+order_id+'&email_id='+email_id}
		
        window.open(url,'_self');
		
	}
           
</script>
{/literal}

<div style="float:left; width:170px;">
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

<div style="float:left; margin-left:20px; width:600px;">
<h1 style="float:left; width:100%; font-size:22px; color:#ee811d; border-bottom:1px solid #eee;">{$lang.Search_Service_Center}</h1>
   
      <div style="float:left; width:100%; font-size:15px; color:#333; margin-top:10px;">
	{$lang.Search_Service_Center_comment}
      
      </div>


<div style="float:left; margin-top:20px;">
     <label style="float:left; width:100%; font:13px trebuchet ms; color:#333;">Brand</label> 
     <select name="brand" id="brand" style="float:left; border-radius:5px; padding:5px; border:1px inset; width:150px;" class="cm-ajax" onchange="selectname(this.value);" >
           <option value="">select</option>
         {foreach from=$brands item="b"}
             <option value="{$b.brand_id}">{$b.variation}</option>
         {/foreach} 
     </select>
     
</div>
     <input id="brand1" name="brand1" type="hidden" value=" " />
     
     <div style="float:left; margin-top:20px; margin-left:50px;">
     <label style="float:left; width:100%; font:13px trebuchet ms; color:#333;">City</label>     
     <select name="city" id="city" style="float:left; border-radius:5px; padding:5px; border:1px inset; width:150px;" class="cm-ajax" onchange="selectaddress(this.value);">
     </select>
</div>
     <div style="clearboth"></div>
     
     <div style="float:left; width:100%; font:13px arial; line-height: 23px; color:#333; margin-top:20px;">
<div id="url"></div>    
<div id="add"></div>

</div>

</div>

<div style="float:right; width:170px;">
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
                <li><a target="_blank" href="{$config.current_location}/index.php?dispatch=write_to_us.service_center">Search Service Center</a></li>
	</ul>
</div>
	<div class="sidebox-bottom"><span>&nbsp;</span></div>
</div>

</div>
{literal} 
<script>
function selectname(brand_id)
{  $('#brand1').val(brand_id);
   var v = $('#brand1').val();
  //alert(v);
  //alert(brand_id);
  document.getElementById('url').innerHTML = '';
  document.getElementById('city').innerHTML = '';
  document.getElementById('add').innerHTML = '';

 var URL = 'index.php';
 $.ajax({
 type: 'GET',
 url: URL,

 data: { dispatch:'write_to_us.get_city',brand_id: brand_id}
 }).done(function(msg)
 {
      temp = msg.split('~');
      var city=temp[0];
      var url=temp[1];
  document.getElementById('city').innerHTML = city;
  document.getElementById('url').innerHTML = url;

 });
}

function selectaddress(city_name)
{
    var brand2 = $('#brand1').val();
        //alert(city_name+''+brand2);
document.getElementById('add').innerHTML = '';

 var URL = 'index.php';
 $.ajax({
 type: 'GET',
 url: URL,
 data: { dispatch:'write_to_us.city_add',city_name:city_name,brand2:brand2}
 }).done(function(msg)
 {//alert(brand2);
  document.getElementById('add').innerHTML = msg;
 });
}

</script>
{/literal}


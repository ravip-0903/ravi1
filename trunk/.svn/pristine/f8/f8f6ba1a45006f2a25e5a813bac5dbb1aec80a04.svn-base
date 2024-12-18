<div id="popup-causelist" style="position:fixed;top:100px;left:200px;background-color:#fff;padding:20px;border:5px solid black;display:none">
	<div>
    	<div style="float:right"><a href="javascript:void(0)" onclick="jQuery('#popup-causelist').hide()">X</a></div>
        <div class="clearboth"></div>
    </div>
	<div id="submitexccauseform">
        <form class="" name="submitexccause" action="{""|fn_url}" method="get">
        	<input type="hidden" id="result_ids" name="result_ids" value="" />
            <input type="hidden" value="" name="order_id_cause" id="order_id_cause" />
            <input type="hidden" value="" name="order_status" id="order_status" />
            <span style="font-size:15px;max">Select for order(<span id="order_id_cause_text"></span>) -</span><br />
            
            <div style="">
            	<div style="float:left;height:200px;overflow:auto">
                    <span style="font-size:14px;color:#09C">Select a Reason</span><br />
                    {foreach from=$cause_list item="cause"}
                    <input type="radio" name="cause_list" value="{$cause.id}" />{$cause.cause}<br />
                    {/foreach}
                </div>
                <div style="float:left;margin-left:10px;height:200px;overflow:auto">
                    <span style="font-size:14px;color:#09C">Select an Action</span><br />
                    {foreach from=$action_list item="action"}
                    <input type="radio" name="action_list" value="{$action.id}" />{$action.cause}<br />
                    {/foreach}
                </div>
                <div style="float:left;margin-left:10px;height:200px;overflow:auto" id="tagdiv">
                    <span style="font-size:14px;color:#09C">Select Tags</span><br />
                    {assign var="cnt" value="1"}
                    {foreach from=$tag_list item="tag"}
                    <input type="checkbox" name="tag_list[]" id="taglist{$cnt++}" value="{$tag.id}" />{$tag.cause}<br />
                    {/foreach}
                </div>
                <div style="clear:both"></div>
            </div>   
            
            <div id="otherreasondiv" style="display:none">
            <input type="text" name="otherreason" id="otherreason" value="" />
            </div>
            <input type="hidden" name="dispatch" value="orders.applyexccause" />
            <input type="button" class="" name="submit" value="Apply" onclick="submitexception()" />
        </form>
        <div>
        	Add a new Tag - <input type="text" name="newtag" id="newtag" value="" /> <input type="button" value="add" onclick="addatag()"  />
        </div>
    </div>
    
</div>
{literal}
<script type="text/javascript">
var submitexccauseformhtml = document.getElementById('submitexccauseform').innerHTML;
function addatag()
{
	var tag = document.getElementById('newtag').value;
	$.ajax({
	  type: "GET",
	  url: "UniTechCity.php",
	  data: { dispatch:'orders.addnewtag',tag: tag }
	}).done(function( msg ) {
	  document.getElementById('tagdiv').innerHTML=msg;
	  submitexccauseformhtml = document.getElementById('submitexccauseform').innerHTML;
	});
}
function deletetag(id)
{
	$.ajax({
	  type: "GET",
	  url: "UniTechCity.php",
	  data: { dispatch:'orders.deletetag',id: id }
	}).done(function( msg ) {
	  jQuery('#causediv'+id).hide();
	});
}
function viewdetailedcasues(orderid)
{
	document.getElementById('viewdetailedcasues'+orderid).style.display='block';
}
function hidedetailedcasues(orderid)
{
	document.getElementById('viewdetailedcasues'+orderid).style.display='none';
}
function exception_cause_func(val)
{
	if(val=='other')
	{
		jQuery('#otherreasondiv').show();
	}
	else
	{
		jQuery('#otherreasondiv').hide();
	}
}
function addexceptioncause(orderid,status)
{
	var i=1;
	var orderid_str = '';
	while(document.getElementById('order_id_checked'+i))
	{
		if(document.getElementById('order_id_checked'+i).checked)
		{
			if(orderid_str=='')
			{
				orderid_str = document.getElementById('order_id_checked'+i).value;
			}
			else
			{
				orderid_str = orderid_str + ',' + document.getElementById('order_id_checked'+i).value;
			}
		}
		i++;
	}
	var orderidtext = orderid;
	if(orderid_str!='')
	{
		orderid=orderid_str;
		orderidtext = 'Multiple Selected';
	}
	document.getElementById('submitexccauseform').innerHTML=submitexccauseformhtml;
	jQuery('#popup-causelist').show();
	jQuery('#order_id_cause_text').html(orderidtext);
	jQuery('#order_id_cause').val(orderid);
	jQuery('#order_status').val(status);
	jQuery('#result_ids').val('exccausehistory' + orderid);
	
}
function submitexception()
{
	var excause = '';
	for(i=0;i<document.submitexccause.cause_list.length;i++)
	{
		if(document.submitexccause.cause_list[i].checked)
		{
			excause=document.submitexccause.cause_list[i].value;
		}
	}
	var exaction = '';
	for(i=0;i<document.submitexccause.action_list.length;i++)
	{
		if(document.submitexccause.action_list[i].checked)
		{
			exaction=document.submitexccause.action_list[i].value;
		}
	}
	var extag = '';
	i=1;
	while(document.getElementById('taglist' + i))
	{
		if(document.getElementById('taglist' + i).checked)
		{
			if(extag=='')
			{
				extag = document.getElementById('taglist' + i).value;
			}
			else
			{
				extag = extag + ',' + document.getElementById('taglist' + i).value;
			}
		}
		i++;
	}
	if(excause == '' && exaction == '' && extag=='')
	{
		alert("select an exception");
		return false;
	}

	$.ajax({
	  type: "GET",
	  url: "UniTechCity.php",
	  data: { dispatch:'orders.applyexccause',exception_cause: excause,exception_action:exaction,exception_tag:extag, order_id_cause: document.getElementById('order_id_cause').value,order_status:document.getElementById('order_status').value,other_cause:document.getElementById('otherreason').value,orderpage:1 }
	}).done(function( msg ) {
		
		var dataarr = msg.split(",hpsepsc,");
		for(i=0;i<dataarr.length;i=parseInt(i)+2)
		{
			document.getElementById('exccausehistory' + dataarr[i]).innerHTML = dataarr[parseInt(i)+1];
		}
	  
	  jQuery('#popup-causelist').hide();
	});
	return false;
}
</script>
{/literal}
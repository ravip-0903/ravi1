{* $Id: carriers.tpl 12089 2011-03-22 14:34:09Z alexions $ *}

<!--{if $carrier == "USP"}
	{assign var="url" value="http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?strOrigTrackNum=`$tracking_number`"}
	{assign var="carrier_name" value=$lang.usps}
{elseif $carrier == "UPS"}
	{assign var="url" value="http://wwwapps.ups.com/WebTracking/processInputRequest?AgreeToTermsAndConditions=yes&amp;tracknum=`$tracking_number`"}
	{assign var="carrier_name" value=$lang.ups}
{elseif $carrier == "FDX"}
	{assign var="url" value="http://fedex.com/Tracking?action=track&amp;tracknumbers=`$tracking_number`"}
	{assign var="carrier_name" value=$lang.fedex}
{elseif $carrier == "AUP"}
	<form name="tracking_form{$shipment_id}" target="_blank" action="http://ice.auspost.com.au/display.asp?ShowFirstScreenOnly=FALSE&ShowFirstRecOnly=TRUE" method="post">
		<input type="hidden"  name="txtItemNumber" maxlength="13" value="{$tracking_number}" />
	</form>
	{assign var="url" value="javascript: document.tracking_form`$shipment_id`.submit();"}
	{assign var="carrier_name" value=$lang.australia_post}
{elseif $carrier == "DHL" || $shipping.carrier == "ARB"}
	<form name="tracking_form{$shipment_id}" target="_blank" method="post" action="http://track.dhl-usa.com/TrackByNbr.asp?nav=Tracknbr">
		<input type="hidden" name="txtTrackNbrs" value="{$tracking_number}" />
	</form>
	{assign var="url" value="javascript: document.tracking_form`$shipment_id`.submit();"}
	{assign var="carrier_name" value=$lang.dhl}
{elseif $carrier == "CHP"}
	{assign var="url" value="http://www.post.ch/swisspost-tracking?formattedParcelCodes=`$tracking_number`"}
	{assign var="carrier_name" value=$lang.chp}
{/if}-->

{if $carrier == "First_Flight"}
	{assign var="url" value="http://firstflight.net/n_contrac_new.asp?tracking1=`$tracking_number`"}
	{assign var="carrier_name" value=$lang.mycarrier_First_Flight}
{elseif $carrier == "Gati"}
	{assign var="url" value="http://google.com?tracking1=`$tracking_number`"}
	{assign var="carrier_name" value=$lang.mycarrier_Gati}
{elseif $carrier == "Maruti_Courier"}
	{assign var="url" value="http://erp.shreemarutionline.com/Tracking.aspx?tracking1=`$tracking_number`"}
	{assign var="carrier_name" value=$lang.mycarrier_Maruti_Courier}
{elseif $carrier == "blue_dart"}
	{assign var="url" value="http://www.bluedart.com/maintracking.html?tracking1=`$tracking_number`"}
	{assign var="carrier_name" value=$lang.mycarrier_blue_dart}
{/if}

{capture name="carrier_name"}
{$carrier_name}
{/capture}

{capture name="carrier_url"}
{$url}
{/capture}
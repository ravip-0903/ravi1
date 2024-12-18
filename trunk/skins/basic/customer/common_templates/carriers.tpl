{* $Id: carriers.tpl 12089 2011-03-22 14:34:09Z alexions $ *}

<!--{if $carrier == "USP"}
	{assign var="url" value="http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?strOrigTrackNum=`$tracking_number`"}
	{assign var="carrier_name" value=$lang.usps}
{elseif $carrier == "UPS"}
	{assign var="url" value="http://wwwapps.ups.com/WebTracking/processInputRequest?AgreeToTermsAndConditions=yes&amp;tracknum=`$tracking_number`"}
	{assign var="carrier_name" value=$lang.ups}
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
{assign var="carrier" value=$carrier|regex_replace:'/[\_\r\t\n\`\~\!\@\#\$\^\%\&\*\(\)]/':''|replace:' ':''|strtolower}

{if $carrier == "First_Flight" || $carrier == "first_flight" || $carrier == "FirstFlight" || $carrier == "firstflight" || $carrier == "first flight" || $carrier == "First Flight" || $carrier == "ffcl"}
	{assign var="url" value="http://firstflight.net/n_contrac_new.asp?tracking1=`$tracking_number`"}
	{assign var="carrier_name" value=$lang.mycarrier_First_Flight}
{elseif $carrier == "Gati"}
	{assign var="url" value="http://google.com?tracking1=`$tracking_number`"}
	{assign var="carrier_name" value=$lang.mycarrier_Gati}
{elseif $carrier == "Maruti_Courier"}
	{assign var="url" value="http://erp.shreemarutionline.com/Tracking.aspx?tracking1=`$tracking_number`"}
	{assign var="carrier_name" value=$lang.mycarrier_Maruti_Courier}
{elseif $carrier == "blue_dart" || $carrier == "blue dart" || $carrier == "Blue_Dart" || $carrier == "Blue Dart" || $carrier == "BlueDart" || $carrier == "bluedart"}
	{assign var="url" value="http://www.bluedart.com/maintracking.html?tracking1=`$tracking_number`"}
	{assign var="carrier_name" value=$lang.mycarrier_blue_dart}
{elseif $carrier == "DTDC"}
	{assign var="url" value="http://www.dtdc.in/dtdcTrack/Tracking/consignInfo.asp?tracking1=`$tracking_number`"}
	{assign var="carrier_name" value=$lang.mycarrier_dtdc}
{elseif $carrier == "India_Post_Registered" || $carrier == "India Post" || $carrier == "IPS" || $carrier == "India post" || $carrier == "India registered post" || $carrier == "India Post Registered" || $carrier == "indiapostregistered" || $carrier == "indiaregisteredpost" || $carrier == "indiapost" || $carrier == "ips"}
	{assign var="url" value="http://services.ptcmysore.gov.in/Speednettracking/Track.aspx?articlenumber=`$tracking_number`"}
	{assign var="carrier_name" value=$lang.mycarrier_india_post_registered}
{elseif $carrier == "PRofessional"}
	{assign var="url" value="http://www.tpcindia.com/track.aspx?id=`$tracking_number`"}
	{assign var="carrier_name" value=$lang.mycarrier_professional}
{elseif $carrier == "Quantum"}
	{assign var="url" value="http://www.quantexexpress.com/tracking.html?tracking1=`$tracking_number`"}
	{assign var="carrier_name" value=$lang.mycarrier_quantum}
{elseif $carrier == "Aramax"}
	{assign var="url" value="http://www.aramex.com/track_results_multiple.aspx?ShipmentNumber=`$tracking_number`"}
	{assign var="carrier_name" value=$lang.mycarrier_aramax}
{elseif $carrier == "FDX"}
	{assign var="url" value="http://fedex.com/Tracking?action=track&amp;tracknumbers=`$tracking_number`"}
	{assign var="carrier_name" value=$lang.fedex}
{/if}

{capture name="carrier_name"}
{$carrier_name}
{/capture}

{capture name="carrier_url"}
{$url}
{/capture}
<?php
/***************************************************************************
*                                                                          *
*    Copyright (c) 2009 Simbirsk Technologies Ltd. All rights reserved.    *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
*                                                                          *
****************************************************************************
* PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
* "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
****************************************************************************/


//
// $Id: intershipper.php 11456 2010-12-23 12:01:47Z andyye $
//

if ( !defined('AREA') ) { die('Access denied'); }

function fn_get_intershipper_rates($code, $weight_data, $location = array(), &$auth, $shipping_settings, $package_info, $origination, $service_id, $allow_multithreading = false)
{
	static $cached_rates = array();

	$cached_rate_id = fn_generate_cached_rate_id($weight_data, $origination);

	$carrier = db_get_field("SELECT carrier FROM ?:shipping_services WHERE intershipper_code = ?s AND status = 'A'", $code);
	if (empty($carrier)) {
		return false;
	}

	if (!empty($cached_rates[$cached_rate_id])) {
		if (!empty($cached_rates[$cached_rate_id][$code])) {
			return array('cost' => $cached_rates[$cached_rate_id][$code]);
		} else {
			return false;
		}
	}
	
	// Prepare data for intershipper request
	$username = !empty($shipping_settings['intershipper']['username']) ? $shipping_settings['intershipper']['username'] : '';
	$password = !empty($shipping_settings['intershipper']['password']) ? $shipping_settings['intershipper']['password'] : '';

	$contents_type = !empty($shipping_settings['intershipper']['contents_type']) ? $shipping_settings['intershipper']['contents_type'] : '';
	$package_type = !empty($shipping_settings['intershipper']['package_type']) ? $shipping_settings['intershipper']['package_type'] : '';
	$dimensional_unit = !empty($shipping_settings['intershipper']['dimensional_unit']) ? $shipping_settings['intershipper']['dimensional_unit'] : '';
	$height = !empty($shipping_settings['intershipper']['height']) ? $shipping_settings['intershipper']['height'] : '0';
	$width = !empty($shipping_settings['intershipper']['width']) ? $shipping_settings['intershipper']['width'] : '0';
	$length = !empty($shipping_settings['intershipper']['length']) ? $shipping_settings['intershipper']['length'] : '0';
	$ship_method = !empty($shipping_settings['intershipper']['ship_method']) ? $shipping_settings['intershipper']['ship_method'] : '';
	$delivery_type = !empty($shipping_settings['intershipper']['delivery_type']) ? $shipping_settings['intershipper']['delivery_type'] : '';
	$insured_value = !empty($shipping_settings['intershipper']['insured_value']) ? $shipping_settings['intershipper']['insured_value'] : '';
	$cod_value = !empty($shipping_settings['intershipper']['cod_value']) ? $shipping_settings['intershipper']['cod_value'] : '';

	$origination_postal = $origination['zipcode'];
	$origination_country = $origination['country'];

	$destination_postal = $location['zipcode'];
	$destination_country = $location['country'];

	// define weight unit and value
	$weight_unit = 'LB';
	$weight = $weight_data['full_pounds'];

	// Build the query string to be sent to the IS server.
	// http://intershipper.com/Shipping/Intershipper/Website/MainPage.jsp?Page=Integrate
	// for additional information

	$url = 'http://www.intershipper.com/Interface/Intershipper/XML/v2.0/HTTP.jsp';
	$data = array (
		'Version' => '2.0.0.0',
		'Username' => $username,
		'Password' => $password,
		'ShipmentID' => '1234',
		'QueryID' => '23456', 
		'TotalCarriers' => '6',
		'CarrierCode1' => 'FDX',
		'CarrierCode2' => 'UPS',
		'CarrierCode3' => 'USP',
		'CarrierCode4' => 'DHL',
		'CarrierCode5' => 'ARB',
		'CarrierCode6' => 'CAN',
		'TotalClasses' => '4',
		'ClassCode1' => 'GND',
		'ClassCode2' => '1DY',
		'ClassCode3' => '2DY',
		'ClassCode4' => '3DY',
		'DeliveryType' => $delivery_type,
		'ShipMethod' => $ship_method,
		'OriginationPostal' => $origination_postal,
		'OriginationCountry' => $origination_country,
		'DestinationPostal' => $destination_postal,
		'DestinationCountry' => $destination_country,
		'Currency' => 'USD',
		'TotalPackages' => '1',
		'BoxID1' => '1',
		'Weight1' => $weight,
		'WeightUnit1' => $weight_unit,
		'Length1' => $length,
		'Width1' => $width,
		'Height1' => $height,
		'DimensionalUnit1' => $dimensional_unit,
		'Packaging1' => $package_type,
		'Contents1' => $contents_type,
		'Cod1' => $cod_value,
		'Insurance1' => $insured_value,
	);

	if ($allow_multithreading) {
		$h_req = fn_cm_register_request('GET', $url, $data, '', '', 'text/xml');
		return array($h_req, 'fn_ups_process_result', array($code));
	} else {
		list($headers, $result) = fn_http_request('GET', $url, $data);
		return fn_intershipper_process_result($headers, $result, $code, $cached_rates, $cached_rate_id);
	}
}

function fn_intershipper_process_result($headers, $result, $code, &$cached_rates = null, $cached_rate_id = null)
{
	$rates = fn_intershipper_get_rates($result);

	if ($cached_rates !== null && empty($cached_rates[$cached_rate_id]) && !empty($rates)) {
		$cached_rates[$cached_rate_id] = $rates;
	}

	if (!empty($rates[$code])) {
		return array('cost' => $rates[$code]);
	} else {
		if (defined('SHIPPING_DEBUG')) {
			return array('error' => fn_intershipper_get_error($result));
		}
	}

	return false;
}

function fn_intershipper_get_error($result) 
{
	$doc = new XMLDocument();
	$xp = new XMLParser();
	$xp->setDocument($doc);
	$xp->parse($result);
	$doc = $xp->getDocument();

	if (is_object($doc->root)) {
		$error = $doc->root->getElementByName('error');
		if (!empty($error)) {
			return $error->getValue();
		}
	}

	return false;
}

function fn_intershipper_get_rates($result) 
{
	$doc = new XMLDocument();
	$xp = new XMLParser();
	$xp->setDocument($doc);
	$xp->parse($result);
	$doc = $xp->getDocument();
	$return = array();

	if (is_object($doc->root)) {
		$package = $doc->root->getElementByName('package');
		if (!empty($package)) {
			$quotes = $package->getElementsByName('quote');
			for ($i = 0; $i < count($quotes); $i++) {
				$return[$quotes[$i]->getValueByPath('/service/code')] = $quotes[$i]->getValueByPath('/rate/amount') / 100;
			}
		}
	}

	return $return;
}

?>

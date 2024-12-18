<?php

/**
 *
 * @Author: Sushant Shinde
 * @Creation-Date: 23/11/2012
 * @Copyright: Mindgate Solution Pvt. Ltd.
 * @Description: This class is used to connect QuickPay service.
 * @Program-Specs-Referred: Functional Specification and TAD documents
 * @Revision:
 * ----------------------------------------------------------------------------------------------------------------
 * @Version    | @Last Revision Date | @Name                  | @Function/Module affected    | @Modifications Done
 * ----------------------------------------------------------------------------------------------------------------	
 * 0.1			23/11/2012			  Sushant Shinde		   Initial Class and functionalities created
 *
 *
 *
 */
class HTTPPost
{
	

/**
 * This method is used to post data to server end.
 *
 * Step 1 : Open the URL connection and set the request property
 * Step 2 : Send the request
 * Step 3 : Read the response
 *
 * @param String : URL
 * @param String : Parameters
 * @return String
 */
function excutePost($qpURL,$urlParameters)
{
	$url='';
	$connection='';
	$resStr=null;
	$result="";
	try
	{
		$url = $qpURL;
		
		// Step 1 : Open the URL connection and set the request property
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$urlParameters);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: application/x-www-form-urlencoded'));

		// Step 2 : Send the request
		$result = curl_exec ($ch);
		
		// Step 3 : Read the response
		$reponseInfo = curl_getinfo($ch);

		curl_close ($ch);
		
		if (preg_match('/OK/',$result))
		{
			echo "ok";
			return $result;
		}
		else
		{
			return $result;
		}
	}

	catch(Exception $e)
	{
		echo 'Message: ' .$e->getMessage();
	}

	return $result;
}

}
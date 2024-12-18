<?php
if(!defined('AREA') ) { die('Access denied'); }
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if($mode == 'add') {
		
		//code by arpit gaur to integrate with the ajax calls to the api
		
			/*include_once(DIR_ROOT.'/contact_us_api_calls_files/scStatusDescription.php');
			include_once(DIR_ROOT.'/contact_us_api_calls_files/scOrderStatus.php');
			include_once(DIR_ROOT.'/contact_us_api_calls_files/scOrderDetails.php');
			include_once(DIR_ROOT.'/contact_us_api_calls_files/scOrderApi.php');
			include_once(DIR_ROOT.'/contact_us_api_calls_files/phpurl.php');
			*/
		//code by arpit gaur ends here 
	   
		$customer_name		 	= $_REQUEST['name'];
		$from 					= $_REQUEST['email'];
		$customer_phone 		= $_REQUEST['phone'];
		$customer_orderid		= $_REQUEST['orderid'];
		$msg_subject 			= $_REQUEST['sub_issue']."-".$_REQUEST['orderid'];
		$msg_body 				.= 'Name : '.$customer_name.'<br>';
		$msg_body 				.= 'Phone : '.$customer_phone.'<br>';
		$msg_body 				.= 'Order ID : '.$customer_orderid.'<br>';
		$msg_body 				.= $_REQUEST['message'];
		
		//code by arpit gaur to add custom hidden response to the message body
		$msg_body.="<br/><hr></hr>".$_REQUEST['custom_hidden_response'];
		
		//echo $msg_body;die;
		
		$to 					= Registry::get('settings.Company.company_support_department');
		//print_r($_REQUEST);die;
		if(db_query("INSERT INTO clues_email_queue (user_id, from_email, to_email, subject, message, status) values('','".$from."','".$to."','".addslashes($msg_subject)."','".addslashes($msg_body)."','UNSENT')")){
				
		}
        
        //code by arpit | integration into the zendesk api for automatically raising ticket
        define("ZDAPIKEY", "glUcsLADOmVT2OPWbkHPYykzggAHSQxO3iuszolr");
        define("ZDUSER", "kaushik.chakraborty@shopclues.com");
        define("ZDURL", "https://shopcluescom.zendesk.com/api/v2");
        
        function curlWrap($url, $json)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
            curl_setopt($ch, CURLOPT_URL, ZDURL.$url);
            curl_setopt($ch, CURLOPT_USERPWD, ZDUSER."/token:".ZDAPIKEY);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $output = curl_exec($ch);
            
            curl_close($ch);
            $decoded = json_decode($output);
            echo $decoded;
            return $decoded;
        }
        
        foreach($_POST as $key => $value){
        if(preg_match('/^z_/i',$key)){
        $arr[strip_tags($key)] = strip_tags($value);
        }
        }
        
        $create = json_encode(array('ticket' => array('subject' => $msg_subject, 'description' => $msg_body, 'requester' => array('name' => $customer_name, 'email' => $from))), JSON_FORCE_OBJECT);
        $return = curlWrap("/tickets.json", $create);
        //print_r($return);
        
        //fn_set_notification('N', '', fn_get_lang_var('thanks_to_write_to_us'));
		return array(CONTROLLER_STATUS_OK, "write_to_us.add");
        //code by arpit ends here
	}	
}
elseif($mode == "add")
{
    //echo DIR_ROOT;die;
	//code by arpit gaur
			//include_once(DIR_ROOT.'/contact_us_api_calls_files/scStatusDescription.php');
			//include_once(DIR_ROOT.'/contact_us_api_calls_files/scOrderStatus.php');
			//include_once(DIR_ROOT.'/contact_us_api_calls_files/scOrderDetails.php');
			//include_once(DIR_ROOT.'/contact_us_api_calls_files/scOrderApi.php');
			//include_once(DIR_ROOT.'/contact_us_api_calls_files/phpurl.php');
			$dir_root='/contact_us_api_calls_files/';
			$view->assign('api_root',$dir_root);
}

?>
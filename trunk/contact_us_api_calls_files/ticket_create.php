<?php
//echo '<pre>';print_r($_REQUEST);die;
$TicketData['email'] = "support@shopclues.com";
$TicketData['orderid'] = " ";
$TicketData['name'] = "Shopclues Customer";
$TicketData['phone'] = "999999";
$TicketData['subject'] = "Not Provided By user";
$TicketData['subissues'] = "ShopClues Order";
$TicketData['message'] ="Not provided by user";

//Added by shashi kant to upload attachments from CS page
define("ZDAPIKEY", "glUcsLADOmVT2OPWbkHPYykzggAHSQxO3iuszolr");
define("ZDUSER", "kaushik.chakraborty@shopclues.com");
define("ZDURL", "https://Shopcluescom.zendesk.com/api/v2");

   //echo "<pre>";print_r($_FILES['uploadFile']);die;
    foreach ($_FILES['uploadFile']['name'] as $key => $file_name) {
        $rand1 = rand(0, 1000);
        $rand1 = rand(0, $rand1 * 2 + $rand1);
        $file_name = str_replace(' ', '-', $file_name);
        $file_name = preg_replace('/[^a-zA-Z0-9.\s]/', '-', $file_name);

        $file_name = $rand1 . $file_name;
        $file_size = $_FILES['uploadFile']['size'][$key];
        $upload_name = $_FILES['uploadFile']['tmp_name'][$key];

        if (($_FILES['uploadFile']['size'][$key] < 250000 || $_FILES['uploadFile']['size'][$key] == '') && ($_FILES['uploadFile']['type'][$key] == 'image/jpeg' || $_FILES['uploadFile']['type'][$key] == 'image/png' || $_FILES['uploadFile']['type'][$key] == 'image/gif' || $_FILES['uploadFile']['type'][$key] == 'image/jpg' || $_FILES['uploadFile']['type'][$key] == '')) {
            move_uploaded_file($upload_name, "images/zendesk_uploads/" . $file_name);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            curl_setopt($ch, CURLOPT_URL, "https://Shopcluescom.zendesk.com/api/v2/uploads.json?filename=$file_name");
            curl_setopt($ch, CURLOPT_USERPWD, ZDUSER . "/token:" . ZDAPIKEY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/binary'));
            curl_setopt($ch, CURLOPT_POST, true);
            $local = trim(Registry::get('config.loc_img')."zendesk_uploads/$file_name");
            $remote =  Registry::get('config.remote_img');
            $parameter = Registry::get('config.rsync_parameter');
            $rsyn = exec("rsync $parameter $local $remote &");
            $img =  Registry::get('config.internal_images_host').'/'.trim(Registry::get('config.loc_img'))."zendesk_uploads/$file_name";
            $file = fopen($local, 'r'); 
            $size = filesize($local);
            $fildata = fread($file, $size);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fildata);
            curl_setopt($ch, CURLOPT_INFILE, $file);
            curl_setopt($ch, CURLOPT_INFILESIZE, $size);
            curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_VERBOSE, true);

            $output = curl_exec($ch);
            curl_close($ch);
            $decoded = json_decode($output);
            //echo "<pre>"; print_r($decoded); "</pre>"; die;
            $tokens = $decoded->upload->token;

            $token_string .=$tokens . ',';

        }
    }

    $tokens = rtrim($token_string, ',');
    $tokens = explode(',', $tokens);
//End Added by shashi kant to upload attachments from CS page
$subject = db_get_field("select name from clues_issues where parent_issue_id='".$_REQUEST['subject']."' ");
$subissues = db_get_field("select name from clues_issues where issue_id='".$_REQUEST['subissues']."' ");
if($_REQUEST['sub_subissues']){
$sub_subissues = db_get_field("select name from clues_issues where issue_id='".$_REQUEST['sub_subissues']."' ");
$subissues=$sub_subissues;
			}
$_REQUEST['subject'] = $subject;
$_REQUEST['subissues'] = $subissues;

foreach($_REQUEST as $key => $value){
	if(trim($value) != "")
		$TicketData[$key] = $value;
}
$oapiData = explode(',', $_REQUEST['oapi_data']);
foreach($oapiData as $key => $value){
		$keyValueP = explode(":",$value);
		if(trim($keyValueP[1]) != "")
		$TicketData[$keyValueP[0]] = $keyValueP[1];

}
define("ZDAPIKEY", "glUcsLADOmVT2OPWbkHPYykzggAHSQxO3iuszolr");
define("ZDUSER", "kaushik.chakraborty@shopclues.com");
define("ZDURL", "https://Shopcluescom.zendesk.com/api/v2");

global  $RuleResponse;
global $data;
include_once('NewIssueFieldMapping.php');
include_once('phpurl.php');
include_once('orderStautsForZendesk.php');
include_once('zenDeskResource.php');

$zdOdStatusValue = strtolower(str_ireplace(" ","_", $OD->getStatusDescriptionByStatusCode($OD->getStatus())));

$description = $TicketData['message'];

/* Note: do not put a trailing slash at the end of v2 */

//Added by Shashi kant to upload image in case of RMA returns in Zendesk

if (($_FILES['product_pic']['size'] < 250000) && ($_FILES['product_pic']['type'] == 'image/jpeg' || $_FILES['product_pic']['type'] == 'image/png' || $_FILES['product_pic']['type'] == 'image/gif' || $_FILES['product_pic']['type'] == 'image/jpg')) {
        $file_name = $_FILES['product_pic']['name'];
        $file_name = str_replace(' ', '-', $file_name);
        $file_name = preg_replace('/[^a-zA-Z0-9.\s]/', '-', $file_name);
        $file_size = $_FILES['product_pic']['size'];
        $upload_name = $_FILES['product_pic']['tmp_name'];
                
            //print_r($file_name); die;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            curl_setopt($ch, CURLOPT_URL, "https://Shopcluescom.zendesk.com/api/v2/uploads.json?filename=$file_name");
            curl_setopt($ch, CURLOPT_USERPWD, ZDUSER . "/token:" . ZDAPIKEY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/binary'));
            curl_setopt($ch, CURLOPT_POST, true);
            $local = trim(Registry::get('config.loc_img')."/refund_product_images/$file_name");
            $remote =  Registry::get('config.remote_img');
            $parameter = Registry::get('config.rsync_parameter');
            $rsyn = exec("rsync $parameter $local $remote &");
            $img =  Registry::get('config.internal_images_host').'/'.trim(Registry::get('config.loc_img'))."refund_product_images/$file_name";
            $file = fopen($local, 'r');
            $size = filesize($local);
            $fildata = fread($file, $size);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fildata);
            curl_setopt($ch, CURLOPT_INFILE, $file);
            curl_setopt($ch, CURLOPT_INFILESIZE, $size);
            curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_VERBOSE, true);

            $output = curl_exec($ch);
            curl_close($ch);
            $decoded = json_decode($output);
            //echo "<pre>"; print_r($decoded); "</pre>"; die;
            $tokens = $decoded->upload->token;
}
//End Added by Shashi kant to upload image in case of RMA returns in Zendesk

                if($_REQUEST['dispatch'] == 'rma.add_return' ){
	            $TicketData['orderid'] = $order_id;
				$TicketData['subissues'] = 'Return requested for ShopClues order';
				$ZDAssignGroup = '20434751';
				$ZDAssignee = '204558218';
				$TicketData['email'] = $cust_details['email'];
				$TicketData['name']  = $cust_details['name'];

				$description = "Comment: ". $_REQUEST['comment']."\n". "Order Number: ". $order_id ."\n". "Return Number: ". $return_info['return_id']."\n". "Product: ". $product."\n". "Reason: ". $reason_name. "\n". "Action: ".$action_name. "\n";
				if(!empty($_REQUEST['account_no'])){
                                    $description.= "Account No: ".$_REQUEST['account_no']."\n";
                                }
                                if(!empty($_REQUEST['ifsc_code']))
                                {
                                    $description.= "Ifsc Code: ".$_REQUEST['ifsc_code']."\n";
                                }
                                if(!empty($_REQUEST['bank_branch']))
                                {
                                    $description.= "Bank Branch: ".$_REQUEST['bank_branch']."\n";
                                }
                                if(!empty($_REQUEST['bank_type']))
                                {
                                    $description.= "Bank Type: ".$_REQUEST['bank_type']."\n";
                                }
                                if(!empty($_REQUEST['bank_name']))
                                {
                                    $description.= "Bank Name: ".$_REQUEST['bank_name']."\n";
                                }
							
						if ($reason == '15'){
						    $NewIssueFields = "2_6_post_delivery_issues_wrong_color";
						}elseif($reason == '16'){
							$NewIssueFields = "2_8_post_delivery_issues_wrong_size";
						}
						elseif($reason == '17'){
							$NewIssueFields = "2_5_post_delivery_issues_wrong_quantity";
						}
						elseif($reason == '18'){
							$NewIssueFields = "2_7_post_delivery_issues_wrong_style";
						}
						elseif($reason == '12'){
							$NewIssueFields = "2_4_post_delivery_issues_wrong_product";
						}
						elseif($reason == '6'){
							$NewIssueFields = "2_4_post_delivery_issues_wrong_product";
						}
						elseif($reason == '3'){
							$NewIssueFields = "2_15_post_delivery_issues_significantly_different_from_description";
						}
						elseif($reason == '14'){
							$NewIssueFields = "2_14_post_delivery_issues_quality_issues";
						}
						elseif($reason == '4'){
							$NewIssueFields = "2_10_post_delivery_issues_dead_on_arrival";
						}elseif($reason == '5'){
							$NewIssueFields = "2_9_post_delivery_issues_manufacturing_defect";
						}elseif($reason == '8'){
							$NewIssueFields = "2_11_post_delivery_issues_damaged";
						}elseif($reason == '11'){
							$NewIssueFields = "2_10_post_delivery_issues_dead_on_arrival";
						}elseif($reason == '7'){
							$NewIssueFields = "2_11_post_delivery_issues_damaged";
						}else{
							$NewIssueFields = "2_16_post_delivery_issues_others";
						}
				       $update = json_encode(array('ticket' => array('subject' => ''.$TicketData['subissues'].' '.$TicketData['orderid'],'comment' => array("body" => $description, 'uploads' => $tokens),'group_id'=>$ZDAssignGroup,'assignee_id'=>$ZDAssignee,"requester"=>array("email"=>$TicketData['email'],'name'=>$TicketData['name']),'fields'=>array('209554461'=>$TicketData['orderid'],'21035813'=>$OD->getStatusDescriptionByStatusCode($OD->getStatus()) ),'custom_fields'=>array('21823867'=>$OD->getStatusDescriptionByStatusCode($OD->getStatus()),'20954461'=>$TicketData['orderid'],'21823857'=>$Ageing,'21834248'=>$product, '21072880'=>$NewIssueFields, '20966929'=>$cpny_name, '21076580'=>$prod_meta_cat, '20967054'=>$courier, '20966885'=>$tracking_number, '20966895'=>$shipment_date,'21749923'=>'email','21821376'=>$OD->getTotal()))));
						
					   }elseif($_REQUEST['modd'] == 'admin_view'){
						$update = json_encode(array('ticket' => array('subject' => ''.$TicketData['subissues'].'-'.$TicketData['orderid'],'description' => $description,'group_id'=>$ZDAssignGroup,'assignee_id'=>$ZDAssignee,"requester"=>array("email"=>$TicketData['email'],'name'=>$TicketData['name']),'fields'=>array('209554461'=>$TicketData['orderid'],'21035813'=>$OD->getStatusDescriptionByStatusCode($OD->getStatus()) ),'custom_fields'=>array('21823867'=>$OD->getStatusDescriptionByStatusCode($OD->getStatus()),'20954461'=>$TicketData['orderid'],'21823857'=>$Ageing,'21834248'=>$product, '21072880'=>$NewIssueField, '20966929'=>$cpny_name, '21076580'=>$prod_meta_cat, '20967054'=>$courier, '20966885'=>$tracking_number, '20966895'=>$shipment_date,'21749923'=>'phone','21821376'=>$OD->getTotal()))));
					   }else{
						$update = json_encode(array('ticket' => array('subject' => ''.$TicketData['subissues'].'-'.$TicketData['orderid'],'comment' => array("body" => $description, 'uploads' => $tokens),'group_id'=>$ZDAssignGroup,'assignee_id'=>$ZDAssignee,"requester"=>array("email"=>$TicketData['email'],'name'=>$TicketData['name']),'fields'=>array('209554461'=>$TicketData['orderid'],'21035813'=>$OD->getStatusDescriptionByStatusCode($OD->getStatus()) ),'custom_fields'=>array('21823867'=>$OD->getStatusDescriptionByStatusCode($OD->getStatus()),'20954461'=>$TicketData['orderid'],'21823857'=>$Ageing,'21834248'=>$product, '21072880'=>$NewIssueField, '20966929'=>$cpny_name, '21076580'=>$prod_meta_cat, '20967054'=>$courier, '20966885'=>$tracking_number, '20966895'=>$shipment_date,'21749923'=>'email','21821376'=>$OD->getTotal()))));
				   } 
//echo "<pre>";print_r($update); die;
$data = curlWrap("/tickets.json", $update, "POST");

$reqt_no = $data['ticket']['id'];
$_GET['GIT_GENERATED_TICKET_ID']=$data['ticket']['id'];
$ticket_channel = $data['ticket']['custom_fields'][0]['value'];
$group_id = $data['ticket']['group_id'];
 if($group_id == "20434751"){
     $group = 'Delivered';
 }else if($group_id == "20438111"){
     $group = 'In-Bound';
 }else if($group_id == "20185011"){
     $group = 'Not Shipped';
 }else if($group_id == "20434761"){
     $group = 'Payments';
 }else if($group_id == "20247568"){
     $group = 'Sales';
 }else{
     $group = 'Shipped';
 }
 
$assignee_id = $data['ticket']['assignee_id'];

if($assignee_id == "197097368"){
     $assignee = 'BD';
 }else if($assignee_id == "204558218"){
     $assignee = 'Returns Level-1';
 }else if($assignee_id == "289907987"){
     $assignee = 'Returns Level-2';
 }else if($assignee_id == "197294691"){
     $assignee = 'Admin';
 }else if($assignee_id == "207272724"){
     $assignee = 'Inbound';
 }else if($assignee_id == "207597625"){
     $assignee = 'Not-Shipped-L1';
 }else if($assignee_id == "207345785"){
     $assignee = 'Not-Shipped-L2';
 }else if($assignee_id == "211857573"){
     $assignee = 'Payments';
 }else if($assignee_id == "212632051"){
     $assignee = 'Shipped Level-1';
 }
 else{
     $assignee = 'Shipped-Level2';
 }

function curlWrap($url, $json, $action)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
	curl_setopt($ch, CURLOPT_URL, ZDURL.$url);
	curl_setopt($ch, CURLOPT_USERPWD, ZDUSER."/token:".ZDAPIKEY);
	switch($action){
		case "POST":
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			break;
		case "GET":
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
			break;
		case "PUT":
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		default:
			break;
	}
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
	curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	$output = curl_exec($ch) or die(curl_error($ch));
//	echo $output;
	curl_close($ch);
	$decoded = json_decode($output,true);
	return $decoded;
}
?>

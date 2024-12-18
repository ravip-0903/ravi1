<?php
/***************************************************************************
*   Added by shashi kant to create merchants tickets at zendesk     *
 *                                                                       *
****************************************************************************/
define("ZDAPIKEY", "tVZo7UcQNvvnVRi4rxzk2rNAm7CucjHd86r6eL3F");
define("ZDUSER", "devesh@shopclues.com");
define("ZDURL", "https://Shopcluesmerchant.zendesk.com/api/v2");

$issue_id = $_REQUEST['subject'];
$email = $_REQUEST['email'];
$name = $_REQUEST['name'];
$phone_no = $_REQUEST['phone'];
$mid = $_REQUEST['mid'];

if (!empty($_REQUEST['mid'])) {
    $merchant_data = db_get_row("select city,fulfillment_id,company from cscart_companies where company_id ='" . $_REQUEST['mid'] . "'");
    if ($merchant_data[fulfillment_id] == 1) {
        $merchant_type = "Premium";
    } elseif ($merchant_data[fulfillment_id] == 2) {
        $merchant_type = "Basic";
    } elseif ($merchant_data[fulfillment_id] == 3) {
        $merchant_type = "Mdf";
    }
}
if(isset($_REQUEST['subsubissue']) && $_REQUEST['subsubissue']!='' )
    {
            $NewIssueField= db_get_zendesk_issue($_REQUEST['subsubissue']);
    }
    elseif(isset($_REQUEST['sub_subissue']) && $_REQUEST['sub_subissue']!='' )
    {
            $NewIssueField=db_get_zendesk_issue($_REQUEST['sub_subissue']);
    }
    elseif(isset($_REQUEST['subissue']) && $_REQUEST['subissue'])
    {
            $NewIssueField=db_get_zendesk_issue($_REQUEST['subissue']);
    }
    else
    {
            $NewIssueField=db_get_zendesk_issue($_REQUEST['subject']);
    }

if ($_REQUEST['subissue']){    
$subject = db_get_field("select name from clues_issues where issue_id='" . $_REQUEST['subissue'] . "' ");
$main_issue = $subject;
} elseif ($_REQUEST['subject']) {
    $sub_subissues = db_get_field("select name from clues_issues where issue_id='" . $_REQUEST['subject'] . "' ");
    $main_issue = $sub_subissues;
    }
if ($_REQUEST['sub_subissue']){
$subissues = db_get_field("select name from clues_issues where issue_id='" . $_REQUEST['sub_subissue'] . "' ");
    }
if ($_REQUEST['subsubissue']) {
    $subsubissues = db_get_field("select name from clues_issues where issue_id='" . $_REQUEST['subsubissue'] . "' ");
    $sub_sub_issues = $subsubissues;
    
    }

foreach ($_FILES['uploadFile']['name'] as $key => $file_name) {
    $rand1 = rand(0, 1000);
    $rand1 = rand(0, $rand1 * 2 + $rand1);
    $file_name = str_replace(' ', '-', $file_name);
    $file_name = preg_replace('/[^a-zA-Z0-9.\s]/', '-', $file_name);

    $file_name = $rand1 . $file_name;
    $file_size = $_FILES['uploadFile']['size'][$key];
    $upload_name = $_FILES['uploadFile']['tmp_name'][$key];

    if (($_FILES['uploadFile']['size'][$key] < 1000000) && ($_FILES['uploadFile']['type'][$key] == 'image/jpeg' || $_FILES['uploadFile']['type'][$key] == 'image/png' || $_FILES['uploadFile']['type'][$key] == 'image/gif' || $_FILES['uploadFile']['type'][$key] == 'image/jpg' || $_FILES['uploadFile']['type'][$key] == 'application/doc' || $_FILES['uploadFile']['type'][$key] == 'application/docx' || $_FILES['uploadFile']['type'][$key] == 'application/pdf' || $_FILES['uploadFile']['type'][$key] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || $_FILES['uploadFile']['type'][$key] == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' || $_FILES['uploadFile']['type'][$key] == 'application/msword')) {
        move_uploaded_file($upload_name, "images/merchant_image_uploads/" . $file_name);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_URL, "https://Shopcluesmerchant.zendesk.com/api/v2/uploads.json?filename=$file_name");
        curl_setopt($ch, CURLOPT_USERPWD, ZDUSER . "/token:" . ZDAPIKEY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/binary'));
        curl_setopt($ch, CURLOPT_POST, true);
        $local = trim(Registry::get('config.loc_img') . "merchant_image_uploads/$file_name");
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
        
        $tokens = $decoded->upload->token;
        $token_string .=$tokens . ',';
    }

}

$tokens = rtrim($token_string, ',');
$tokens = explode(',', $tokens);

$description = $_REQUEST['message'];
if(!empty($sub_sub_issues)){
    
$update = json_encode(array('ticket' => array('subject' => '' .trim($main_issue)."-".trim($subissues)."-".trim($sub_sub_issues), 'comment' => array('body' => $description, 'uploads' => $tokens),'requester' => array('email' => $email, 'name' => $name, 'phone' => $phone_no), 'custom_fields' => array('21458139' => $NewIssueField, '21507585' => $mid, '21630250' => $merchant_data['city'], '21458469' => $merchant_type, '21471034' => $merchant_data['company']))));

} elseif(!empty($subissues)) {
    
$update = json_encode(array('ticket' => array('subject' => '' .trim($main_issue)."-".trim($subissues), 'comment' => array('body' => $description, 'uploads' => $tokens),'requester' => array('email' => $email, 'name' => $name, 'phone' => $phone_no), 'custom_fields' => array('21458139' => $NewIssueField, '21507585' => $mid, '21630250' => $merchant_data['city'], '21458469' => $merchant_type, '21471034' => $merchant_data['company']))));
    
}else {
$update = json_encode(array('ticket' => array('subject' => '' .trim($main_issue), 'comment' => array('body' => $description, 'uploads' => $tokens),'requester' => array('email' => $email, 'name' => $name, 'phone' => $phone_no), 'custom_fields' => array('21458139' => $NewIssueField, '21507585' => $mid, '21630250' => $merchant_data['city'], '21458469' => $merchant_type, '21471034' => $merchant_data['company']))));    
}

$data = fn_create_merchant_ticket("/tickets.json", $update, "POST");

$ticket_no = $data['ticket']['id'];
$created_time = $data['ticket']['created_at'];
$comment = $data['ticket']['description'];
$assignee_id = $data['ticket']['assignee_id'];
$assignee_data = fn_create_merchant_ticket("/users/" . $assignee_id . ".json");
$assignee_name = $assignee_data['user']['name'];

function fn_create_merchant_ticket($url, $json, $action) {
    
   define("ZDAPIKEY", "tVZo7UcQNvvnVRi4rxzk2rNAm7CucjHd86r6eL3F");
   define("ZDUSER", "devesh@shopclues.com");
   define("ZDURL", "https://Shopcluesmerchant.zendesk.com/api/v2");

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_URL, ZDURL . $url);
    curl_setopt($ch, CURLOPT_USERPWD, ZDUSER . "/token:" . ZDAPIKEY);
    switch ($action) {
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
    curl_close($ch);
    $decoded = json_decode($output, true);
    return $decoded;
}

 function db_get_zendesk_issue($id)
 {
	 $sql="select clues_issues.`desc` from clues_issues where issue_id='$id'";
	 $result=  db_get_field($sql);
	 return $result;//returning the issue_type_field
 }
?>
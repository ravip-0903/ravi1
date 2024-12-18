<?php 
include_once('rule/rule.php');
include_once('rule/rulecontext.php');

if(isset($_REQUEST['orderid']) && trim($_REQUEST['orderid']) != ""){	
	$_REQUEST['orderid'] = trim($_REQUEST['orderid']);
	include('phpurl.php');
}
$showMessage = true;
$rule = array();
$ruleSize = sizeof($rule);
$rule[$ruleSize][0] = new Rule('Check4Order1');
$rule[$ruleSize][0]->addVariable('OrderStaus', 'Paid');
$rule[$ruleSize][0]->addVariable('CustomerOrderStaus', '');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addVariable('Ageing', 5);
$rule[$ruleSize][0]->addVariable('CustomerAgeing', '');
$rule[$ruleSize][0]->addOperator('GREATERTHANOREQUALTO');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize][0]->addVariable('IssueSubject', 'Order Status');
$rule[$ruleSize][0]->addVariable('CustomerIssueSubject', '');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addVariable('IssueSubSubject', 'Order is in processing state for more than three days');
$rule[$ruleSize][0]->addVariable('CustomerIssueSubSubject', 'Open');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize]['Result'] = "There is a delay in procuring the product from the merchant ".$OD->getCompanyId()." . We are working on it. If you want to request a cancellation please click here";
$rule[$ruleSize]['MSGBOX'] = true;
$rule[$ruleSize]['RULENAME'] = 'RULE_1';

$ruleSize = sizeof($rule);
$rule[$ruleSize][0] = new Rule('Check4Order2');
$rule[$ruleSize][0]->addVariable('OrderStaus', 'Paid');
$rule[$ruleSize][0]->addVariable('CustomerOrderStaus', '');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addVariable('Ageing', 5);
$rule[$ruleSize][0]->addVariable('CustomerAgeing', '');
$rule[$ruleSize][0]->addOperator('LESSTHAN');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize][0]->addVariable('IssueSubject', 'Order Status');
$rule[$ruleSize][0]->addVariable('CustomerIssueSubject', '');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addVariable('IssueSubSubject', 'Order is in processing state for more than three days');
$rule[$ruleSize][0]->addVariable('CustomerIssueSubSubject', 'Open');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize]['Result'] = "We are working to fulfill the order";
$rule[$ruleSize]['MSGBOX'] = false;
$rule[$ruleSize]['RULENAME'] = 'RULE_2';

$ruleSize = sizeof($rule);
$rule[$ruleSize][0] = new Rule('Check4Order3');
$rule[$ruleSize][0]->addVariable('OrderStaus', 'MilkRun Complete');
$rule[$ruleSize][0]->addVariable('CustomerOrderStaus', '');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addVariable('Ageing', 2);
$rule[$ruleSize][0]->addVariable('CustomerAgeing', '');
$rule[$ruleSize][0]->addOperator('LESSTHAN');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize][0]->addVariable('IssueSubject', 'Order Status');
$rule[$ruleSize][0]->addVariable('CustomerIssueSubject', '');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addVariable('IssueSubSubject', 'Order is in processing state for more than three days');
$rule[$ruleSize][0]->addVariable('CustomerIssueSubSubject', 'Open');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize]['Result'] = "Order is ready to be shipped within 24-hours";
$rule[$ruleSize]['MSGBOX'] = false;
$rule[$ruleSize]['RULENAME'] = 'RULE_3';


$ruleSize = sizeof($rule);
$rule[$ruleSize][0] = new Rule('Check4Order4');
$rule[$ruleSize][0]->addVariable('OrderStaus', 'MilkRun Complete');
$rule[$ruleSize][0]->addVariable('CustomerOrderStaus', '');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addVariable('Ageing', 2);
$rule[$ruleSize][0]->addVariable('CustomerAgeing', '');
$rule[$ruleSize][0]->addOperator('GREATERTHANOREQUALTO');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize][0]->addVariable('IssueSubject', 'Order Status');
$rule[$ruleSize][0]->addVariable('CustomerIssueSubject', '');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addVariable('IssueSubSubject', 'Order is in processing state for more than three days');
$rule[$ruleSize][0]->addVariable('CustomerIssueSubSubject', 'Open');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize]['Result'] = "Your order is shipped, please send us an email if you havent received the shipment details.";
$rule[$ruleSize]['MSGBOX'] = false;
$rule[$ruleSize]['RULENAME'] = 'RULE_4';


$ruleSize = sizeof($rule);
$rule[$ruleSize][0] = new Rule('Check4Order5');
$rule[$ruleSize][0]->addVariable('OrderStaus', 'Shipped');
$rule[$ruleSize][0]->addVariable('CustomerOrderStaus', '');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addVariable('Ageing', 5);
$rule[$ruleSize][0]->addVariable('CustomerAgeing', '');
$rule[$ruleSize][0]->addOperator('LESSTHAN');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize][0]->addVariable('IssueSubject', 'Order Is Shipped');
$rule[$ruleSize][0]->addVariable('CustomerIssueSubject', '');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addVariable('IssueSubSubject', 'Its been more than five days since it was shipped');
$rule[$ruleSize][0]->addVariable('CustomerIssueSubSubject', 'Open');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize]['Result'] = "It should have been shipped. Please click here is you have not received the shipment details. Please allow 48-hours.";
$rule[$ruleSize]['MSGBOX'] = false;
$rule[$ruleSize]['RULENAME'] = 'RULE_5';


$ruleSize = sizeof($rule);
$rule[$ruleSize][0] = new Rule('Check4Order6');
$rule[$ruleSize][0]->addVariable('OrderStaus', 'Shipped');
$rule[$ruleSize][0]->addVariable('CustomerOrderStaus', '');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addVariable('Ageing', 5);
$rule[$ruleSize][0]->addVariable('CustomerAgeing', '');
$rule[$ruleSize][0]->addOperator('GREATERTHANOREQUALTO');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize][0]->addVariable('IssueSubject', 'Order Is Shipped');
$rule[$ruleSize][0]->addVariable('CustomerIssueSubject', '');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addVariable('IssueSubSubject', 'Its been more than five days since it was shipped');
$rule[$ruleSize][0]->addVariable('CustomerIssueSubSubject', 'Open');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize]['Result'] = "Please send us this message and we will look into it.";
$rule[$ruleSize]['MSGBOX'] = true;
$rule[$ruleSize]['RULENAME'] = 'RULE_6';


$ruleSize = sizeof($rule);
$rule[$ruleSize][0] = new Rule('Check4Order7');
$rule[$ruleSize][0]->addVariable('OrderStaus', 'Shipped');
$rule[$ruleSize][0]->addVariable('CustomerOrderStaus', '');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addVariable('Ageing', 3);
$rule[$ruleSize][0]->addVariable('CustomerAgeing', '');
$rule[$ruleSize][0]->addOperator('GREATERTHANOREQUALTO');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize][0]->addVariable('IssueSubject', 'Order Is Shipped');
$rule[$ruleSize][0]->addVariable('CustomerIssueSubject', '');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addVariable('IssueSubSubject', 'I am not able to track the shipment');
$rule[$ruleSize][0]->addVariable('CustomerIssueSubSubject', 'Open');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize]['Result'] = "The shipment details are::
If you are not able to track after 48-hour, please send us a message";
$rule[$ruleSize]['MSGBOX'] = false;
$rule[$ruleSize]['RULENAME'] = 'RULE_7';


$ruleSize = sizeof($rule);
$rule[$ruleSize][0] = new Rule('Check4Order8');
$rule[$ruleSize][0]->addVariable('OrderStaus', 'Delivered');
$rule[$ruleSize][0]->addVariable('CustomerOrderStaus', '');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addVariable('Ageing', 11);
$rule[$ruleSize][0]->addVariable('CustomerAgeing', '');
$rule[$ruleSize][0]->addOperator('GREATERTHANOREQUALTO');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize][0]->addVariable('IssueSubject', 'Order Delivered');
$rule[$ruleSize][0]->addVariable('CustomerIssueSubject', '');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addVariable('IssueSubSubject', 'Product Quality Is bad');
$rule[$ruleSize][0]->addVariable('CustomerIssueSubSubject', 'Open');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize]['Result'] = "Please send us the message with the nature of problem you are facing";
$rule[$ruleSize]['MSGBOX'] = true;
$rule[$ruleSize]['RULENAME'] = 'RULE_8';


$ruleSize = sizeof($rule);
$rule[$ruleSize][0] = new Rule('Check4Order9');
$rule[$ruleSize][0]->addVariable('OrderStaus', 'Complete');
$rule[$ruleSize][0]->addVariable('CustomerOrderStaus', '');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addVariable('Ageing', 11);
$rule[$ruleSize][0]->addVariable('CustomerAgeing', '');
$rule[$ruleSize][0]->addOperator('LESSTHANOREQUALTO');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize][0]->addVariable('IssueSubject', 'Returns');
$rule[$ruleSize][0]->addVariable('CustomerIssueSubject', '');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addVariable('IssueSubSubject', 'I want to return the product');
$rule[$ruleSize][0]->addVariable('CustomerIssueSubSubject', 'Open');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize]['Result'] = "Please us the message with the details";
$rule[$ruleSize]['MSGBOX'] = true;
$rule[$ruleSize]['RULENAME'] = 'RULE_9';


$ruleSize = sizeof($rule);
$rule[$ruleSize][0] = new Rule('Check4Order10');
$rule[$ruleSize][0]->addVariable('OrderStaus', 'Checkout Incomplete');
$rule[$ruleSize][0]->addVariable('CustomerOrderStaus', '');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addVariable('Ageing', 2);
$rule[$ruleSize][0]->addVariable('CustomerAgeing', '');
$rule[$ruleSize][0]->addOperator('LESSTHANOREQUALTO');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize][0]->addVariable('IssueSubject', 'Payment Issues');
$rule[$ruleSize][0]->addVariable('CustomerIssueSubject', '');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addVariable('IssueSubSubject', 'Payment deducted but order is not confirmed');
$rule[$ruleSize][0]->addVariable('CustomerIssueSubSubject', 'Open');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize]['Result'] = "Some times It takes 48-hours to confirm the payment";
$rule[$ruleSize]['MSGBOX'] = false;
$rule[$ruleSize]['RULENAME'] = 'RULE_10';


$ruleSize = sizeof($rule);
$rule[$ruleSize][0] = new Rule('Check4Order11');
$rule[$ruleSize][0]->addVariable('OrderStaus', 'Checkout Incomplete');
$rule[$ruleSize][0]->addVariable('CustomerOrderStaus', '');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addVariable('Ageing', 2);
$rule[$ruleSize][0]->addVariable('CustomerAgeing', '');
$rule[$ruleSize][0]->addOperator('GREATERTHANOREQUALTO');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize][0]->addVariable('IssueSubject', 'Payment Issues');
$rule[$ruleSize][0]->addVariable('CustomerIssueSubject', '');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addVariable('IssueSubSubject', 'Payment deducted but order is not confirmed');
$rule[$ruleSize][0]->addVariable('CustomerIssueSubSubject', 'Open');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize]['Result'] = "We have not received the payment.";
$rule[$ruleSize]['MSGBOX'] = true;
$rule[$ruleSize]['RULENAME'] = 'RULE_11';


$ruleSize = sizeof($rule);
$rule[$ruleSize][0] = new Rule('Check4Order12');
$rule[$ruleSize][0]->addVariable('OrderStaus', 'Shipped');
$rule[$ruleSize][0]->addVariable('CustomerOrderStaus', '');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addVariable('PromotionId', 895);
$rule[$ruleSize][0]->addVariable('CustomerPromotionId', '');
$rule[$ruleSize][0]->addOperator('GREATERTHANOREQUALTO');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize][0]->addVariable('IssueSubject', 'Order Status');
$rule[$ruleSize][0]->addVariable('CustomerIssueSubject', '');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addVariable('IssueSubSubject', 'Order is in processing state for more than three days');
$rule[$ruleSize][0]->addVariable('CustomerIssueSubSubject', 'Open');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize]['Result'] = "Your order will be shipped through surface mode. It will take 5-7 days for deliver.";
$rule[$ruleSize]['MSGBOX'] = false;
$rule[$ruleSize]['RULENAME'] = 'RULE_12';


$ruleContext = new RuleContext('Check4OrderFact');
$ruleContext->addVariable('CustomerOrderStaus', $OD->getStatusDescriptionByStatusCode($OD->getStatus()));
$ruleContext->addVariable('CustomerAgeing', (int)$Ageing);
$ruleContext->addVariable('CustomerIssueSubject', $_REQUEST['subject']);
$ruleContext->addVariable('CustomerIssueSubSubject', $_REQUEST['sub_issue']);
$ruleContext->addVariable('CustomerPromotionId', $OD->getPromotionIds());

$ruleSuccess = false;


$ruleMessage = "";
$RuleResponse = "";
$RuleName = "";
for($i=0;$i<sizeof($rule);++$i){
       $result = $rule[$i][0]->evaluate($ruleContext);
       if($result->value){
               $RuleResponse =  $rule[$i]['Result'];
               $ruleSuccess = true;
               $RuleName =$rule[$i]['RULENAME'];
               $showMessage = $rule[$i]['MSGBOX'];
               break;
       }
}

//testing code by arpit gaur

$description = " contact :".$OD->getPhone()." Order Status as of ". date('dS F Y', $ODS->getTransitionDate())." is ".$OD->getStatusDescriptionByStatusCode($OD->getLastStatus())."RuleResponse ".$ruleResponse." Rule Executed |~|$RuleName|~|"; 
//ending by arpit gaur


echo "
<input type='hidden' id='hidden_details_of_order_response' value='".$description."' />

<script>
	var message_concat=$('#hidden_details_of_order_response').val();
	//alert(message_concat);
	//$('#message').val('');
	//var already_message=$('#message').val();
	//var final_message=already_message + '<br/>' + message_concat;
	//$('#message').val(final_message);
	$('#htm2display').hide();
	$('#custom_hidden_response').val(message_concat);
	//alert($('#custom_hidden_response').val());
</script>";
/*<!--
<br><br>
	<div class=\"mainbox-container\">
		<div class=\"mainbox-body\">
<div class=\"box_OrderHeader\">
Order Detail
</div>

<div class=\"box_Orderdetails\">
<div class=\"box_Orderdetails_title\">Order Number</div>
<div class=\"box_Orderdetails_content\">".$OD->getOrderId()."</div>  
</div>
<div class=\"box_Orderdetails\">
<div class=\"box_Orderdetails_title\">
Order Status as of <br><strong>".date('dS F Y', $ODS->getTransitionDate())."</strong> is
</div>
<div class=\"box_Orderdetails_content\">
".$OD->getStatusDescriptionByStatusCode($OD->getLastStatus())."
</div> 

 
</div>

<input type=hidden name=oapi_data value=\"name:".$OD->getFirstname()." ".$OD->getLastname().",email:".$OD->getEmail().",orderid:".$OD->getOrderId().",phone:".$OD->getPhone().",statusDate:".date('dS F Y', $ODS->getTransitionDate()).",orderstatus:".$OD->getStatusDescriptionByStatusCode($OD->getLastStatus()).",promotioncode:".$OD->getPromotionIds()."\">
<div class=\"clearboth\"></div>-->";/**/



//echo $result->toString();

//echo "<input type=\"hidden\" name=\"RuleResponse\" value=\"|_$RuleResponse\">";
 
if($showMessage)
{
//echo "<br>Was this Information useful to you ? Yes: <input type=radio name=usefull value=YES> No: <input type=radio name=usefull value=NO>";

?>
<!--
<div class="cont_nl_address_box">
<label for="message" class="cont_nl_address">Message: </label>

</div>
<div class="cont_nl_address_box" style="padding:0;">
<textarea name="message" id="message" rows="3" cols="40" class="round_five profile_detail_field" style="max-width:345px;  height: 133px; max-height: 90px; width:345px;"></textarea>
</div>
<div class="box_functions sup_act_btn">
    
	
 
		<input class="box_functions_button" type="submit" name="dispatch[write_to_us.add]" value="Submit" style="margin-left:120px; curser:hand; cursor:pointer;">

	
<span style="color:#999; float:left; margin-top:-43px; font-size:11px;">Your message will be sent to customer support.</span>
</div>-->
<?php }?>
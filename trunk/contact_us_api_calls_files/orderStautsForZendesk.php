<?php
include_once('rule/rule.php');
include_once('rule/rulecontext.php');
global $RuleResponse;
$RuleResponse = "";
	$RuleName = "";
if(isset($_REQUEST['orderid']) && trim($_REQUEST['orderid']) != ""){	
	$_REQUEST['orderid'] = trim($_REQUEST['orderid']);
	include('phpurl.php');
//echo "here";
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
$rule[$ruleSize][0]->addVariable('IssueSubSubject', 'Order is in processing state for more than 3 days');
$rule[$ruleSize][0]->addVariable('CustomerIssueSubSubject', 'Open');
$rule[$ruleSize][0]->addOperator('EQUALTO');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize][0]->addOperator('AND');
$rule[$ruleSize]['Result'] = "There is a delay in procuring the product from the merchant ".$OD->getCompanyName()." . We are working on it. If you want to request a cancellation please click here";
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
	$NoRuleExecuted =true;
	//echo "Ageing => $Ageing \nCustomerOrderStaus :". $OD->getStatusDescriptionByStatusCode($OD->getStatus())."\n CustomerIssueSubject". $_REQUEST['subject']."\n CustomerIssueSubSubject". $_REQUEST['sub_issue']." ";
	for($i=0;$i<sizeof($rule);++$i){
       $result = $rule[$i][0]->evaluate($ruleContext);
	//  echo $result->toString();
       if($result->value){
      //echo
	           $RuleResponse =  $rule[$i]['Result'];
               $ruleSuccess = true;
               $RuleName =$rule[$i]['RULENAME'];
               $showMessage = $rule[$i]['MSGBOX'];
			   $NoRuleExecuted = false;
               break;
       }
	}
	if($NoRuleExecuted){$RuleResponse = "_._";}
}else{
$RuleResponse = "_._";
	$RuleName = "NO RULE";
}//die();
//$RuleResponse,$RuleName,$Ageing,$OD->getStatusDescriptionByStatusCode($OD->getStatus()),$OD->getPromotionIds();
?>
<?php
define('AREA', 'A');
define('AREA_NAME', 'admin');
//require dirname(__FILE__).'/../core/fn.init.php';
require  dirname(__FILE__) . '/../prepare.php';
require  dirname(__FILE__) . '/../init.php';

function tools_fn_pgss_login()
{
	$sql = "select user_id from cscart_users where email='script@shopclues.com'";
	$row = db_get_row($sql);
	fn_login_user($row['user_id']);
}
function fn_check_priviledges($privi_name){
	if($_SESSION['auth']['user_id']==1)
	{
		return 1;
	}
	else
	{
		$query = "SELECT cu.user_id FROM `cscart_users` cu "
			  . " LEFT JOIN `cscart_usergroup_links` cul ON cu.user_id = cul.user_id"
			  . " LEFT JOIN cscart_usergroup_privileges cup ON cul.usergroup_id = cup.usergroup_id "
			  . " WHERE cu.status = 'A' AND cu.user_id = '" . $_SESSION['auth']['user_id'] 
			  . "' AND (cu.user_login = 'admin' OR cup.privilege = '".$privi_name."') and cul.status='A'";
		$manage_privi_links = db_get_field($query);
		return $manage_privi_links;
	}
}
tools_fn_pgss_login();
		
			  $carrier_name = $_REQUEST['carrier_name'];
			  $awbno = $_REQUEST['awbno'];
			  $carrier_status = $_REQUEST['carrier_status'];
			  $receiver_name = $_REQUEST['receiver_name'];
			  $receiver_contact = $_REQUEST['receiver_contact'];
			  $update_date1 = $_REQUEST['update_date'];
			  $extra_info = $_REQUEST['extra_info'];
						
					$update_date = date("Y-m-d H:i:s", $update_date1);	
						
			$orderid = db_get_row("select csi.order_id from cscart_shipment_items csi inner join cscart_shipments cs on cs.shipment_id=csi.shipment_id where cs.tracking_number='".$awbno."' group by csi.order_id");
			$carrierid = db_get_row("select carrier_id from clues_carrier_lookup where carrier_name LIKE '%".$carrier_name."%' OR carrier_value LIKE '%".$carrier_name."%'");
				
			$already=db_get_row("select id from clues_shipment_tracking_center where awbno='".$awbno."' and carrier_id='".$carrierid['carrier_id']."' and sc_status='Delivered'");
							 //Date format date('Y-m-d H:i:s')	
							 if(empty($already) && isset($awbno)){
								db_query("update clues_shipment_tracking_center set latest=0 where awbno='".$awbno."' and carrier_id='".$carrierid['carrier_id']."'");
							
								db_query("insert into clues_shipment_tracking_center set carrier_id='".$carrierid['carrier_id']."',order_id='".$orderid['order_id']."',awbno='".$awbno."',carrier_status='".$carrier_status."',sc_status='Delivered',status_update_date='".$update_date."',reason_code='0',memo='".$extra_info."',receiver_name='".$receiver_name."',receiver_contact='".$receiver_contact."'");
								
								$orderstatus = db_get_row("select status from cscart_orders where order_id='".$orderid['order_id']."'");
								
								if($orderstatus['status']=='A' && $carrier_status=='Delivered'){
									
									fn_change_order_status($orderid['order_id'],'H', 'A',$notify = array("C"=>true,"A"=>false,"S"=>false));
									
									
								}							 
							 }								
?>
<?php
define('AREA', 'A');
define('AREA_NAME', 'admin');
require  dirname(__FILE__) . '/../prepare.php';
require  dirname(__FILE__) . '/../init.php';

//echo date("i-s");
   $last_timestamp = db_get_field("select transition_date from clues_log_verify_profile_from_order order by id desc limit 1");
   $sql_condition="";
   //$sql_condition=" where o.order_id is not null ";
   if(!empty($last_timestamp)){
		//$sql_condition .="  and  h.transition_date >= $last_timestamp ";
		$sql_condition .="   and  transition_date >= $last_timestamp ";
   }
   // echo $sql = "select h.transition_date,o.order_id,o.user_id,concat(o.s_firstname,o.s_lastname,o.s_address, o.s_address_2, o.s_city,  o.s_state ,o.s_country ,o.s_zipcode ,o.s_phone) as 'order_address'  FROM clues_order_history  h left join cscart_orders o on h.order_id=o.order_id and h.to_status='C' and  o.status = 'C' ". $sql_condition ."  limit 1000"; 
   $sql = "select h.*,o.order_id,o.user_id,concat(o.s_firstname,o.s_lastname,o.s_address, o.s_address_2, o.s_city,  o.s_state ,o.s_country ,o.s_zipcode ,o.s_phone) as 'order_address'  FROM cscart_orders o   join ( select order_id ,transition_date,id from clues_order_history  where to_status='C' ". $sql_condition ." order by transition_date  limit 10000)  h on  o.order_id=h.order_id and h.order_id is not null and o.order_id is not null";
    //"select o.order_id,o.user_id,concat(o.s_firstname,o.s_lastname,o.s_address, o.s_address_2, o.s_city,  o.s_state ,o.s_country ,o.s_zipcode ,o.s_phone) as 'order_address'  FROM cscart_orders o  where o.order_id in ( select order_id from clues_order_history  where to_status='C'  $sql_condition ) limit 1000"; 
	$results =  db_get_array($sql);
	if(count($results) > 0){	
		foreach($results as $row){
			$profile_sql="SELECT profile_id, concat(s_firstname,s_lastname,s_address, s_address_2, s_city,  s_state ,s_country ,s_zipcode ,s_phone) as 'profile_address' ,verified FROM cscart_user_profiles WHERE user_id =". $row['user_id'] . " and verified != 1";
			$profiles = db_get_array($profile_sql);
			//print_r($profiles);
			if(count($profiles) > 0){
				foreach($profiles as $profile_row){
					if(are_both_addresses_same($row['order_address'],$profile_row['profile_address'])){
						db_query("update  cscart_user_profiles set verified=1 WHERE profile_id =". $profile_row['profile_id'] );						
						break;												
					}
				}
			}			
		}
		
		if(!empty($row['transition_date'])){
			db_query("insert into clues_log_verify_profile_from_order (transition_date) values('$row[transition_date]')");
		}	
	}	

//echo date("i-s");
// case-insensitive string comparison 
function are_both_addresses_same($address1,$address2){
	 $pattern='/[^a-zA-Z0-9]/i';
	 $address1= preg_replace($pattern,'',$address1);
	 $address2= preg_replace($pattern,'',$address2);
	 strcasecmp($address1, $address2); 
	if (strcasecmp($address1, $address2) == 0) {
		return true;
	}else{
		return false;
	}
}
	
exit;
?>

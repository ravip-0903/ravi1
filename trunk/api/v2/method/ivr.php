<?php

	/* 	This is an example class script proceeding secured API
		To use this class you should keep same as query string and function name
		Ex: If the query string value rquest=delete_user Access modifiers doesn't matter but function should be
		     function delete_user(){
				 Your code goes here
			 }
		Class will execute the function dynamically;
		
		usage :
		
		    $object->response(output_data, status_code);
			$object->_request	- to get santinized input 	
			
			output_data : JSON (I am using)
			status_code : Send status message for headers 	*/



	class ivr extends REST {
		public $data = "";

		public function __construct(){
			parent::__construct();				// Init parent contructor
		}
                      
               public function ivr(){

                   if($this->get_request_method() == "GET"){

                        $params = $this->_request;
                        if(empty($params['order_id'])){
                            $msg = array('status' => "Error", "msg" => "Order id can not be empty");
                            $this->response($this->json($msg), 400);  
                        }

                        if(!(is_numeric($params['order_id']))){
                            $msg = array('status' => "Error", "msg" => "Invalid Parameters");
                            $this->response($this->json($msg), 400);
                        }
			if(!empty($params['notify']) && $params['notify'] == 'true'){
                        	$res = $this->getOrderStatus($params['order_id'], true);
			} else {
                        	$res = $this->getOrderStatus($params['order_id']);
			}

                        $this->response($this->json($res), 200);
		   } else {
                       $this->response('',406);
		   }
               }

		function getOrderStatus($order_id, $notify = false){

			$ord_status = db_get_row("SELECT email, status,timestamp,payment_id FROM cscart_orders WHERE order_id = '".$order_id."'");
			$pdd_edd = fn_get_pdd_edd($order_id);//by ajay for Estimated Delivery Date
			
			if(empty($ord_status)){
				return array("status"=> "invalid", "error"=>"invalid_order");
			}

			if($ord_status['status'] == 'A'){ // If Order is in Shipped State
				$result = db_get_array("SELECT co.status, 
                                    from_unixtime(cs.timestamp) as shipment_date
                                    FROM cscart_orders co INNER JOIN cscart_shipment_items
                                    csi ON co.order_id=csi.order_id INNER JOIN cscart_shipments cs
                                    ON cs.shipment_id=csi.shipment_id WHERE 
                                    co.order_id='".$order_id."' AND co.status='A' LIMIT 0,1");
				$result[0]['edd'] = array("edd_start_date"=>$pdd_edd['edd1'], "edd_end_date"=>$pdd_edd['edd2']);
			}elseif($ord_status['status'] == 'O'){
				$result = array("status"=>'O', "edd"=>array("edd_start_date"=>$pdd_edd['edd1'], "edd_end_date"=>$pdd_edd['edd2']));	
			}elseif($ord_status['status'] == 'C'){ // If Order is in Complete State
				$result = db_get_array("SELECT to_status as status, from_unixtime(transition_date) as delivery_date FROM clues_order_history where to_status='C' and order_id='".$order_id."'");
			}elseif($ord_status['status'] == 'P'){ //If Order is in Paid State
				$result = array("status"=> "P", "order_date"=>date('Y-m-d H:i:s',$ord_status['timestamp']), "edd"=>array("edd_start_date"=>$pdd_edd['edd1'], "edd_end_date"=>$pdd_edd['edd2']) );
            }elseif($ord_status['status'] == '37'){ //Cancellation request by SC-OSLA
                if($ord_status['payment_id']=='6'){
					$payment_type="COD";
				}else{
					$payment_type="PAID";
				}
                $query=db_get_row("SELECT value FROM cscart_status_data where status='37' and type='O' and param ='refund_sla_time'");
				$sla_time = $query['value']*60*60;
				$canc_time = db_get_row("SELECT transition_date FROM clues_order_history where to_status='37' and order_id='".$order_id."'");
                $sla_date = $sla_time + $canc_time['transition_date'];

				$result = array("status"=>37,"payment_type"=>$payment_type, "days_to_refund"=>date('Y-m-d H:i:s',$sla_date));

             }elseif($ord_status['status'] == '24'){ //Cancelled by shopclues- OSLA and 
                                if($ord_status['payment_id']=='6'){
                                	$payment_type = "COD";
					$result = array("status"=>24, "payment_type"=>$payment_type);
                                } else {
					$refund=db_get_row("SELECT id, payment_id, refund_attempt, cod_refund_in_cb_amt, batch_id FROM clues_refunds WHERE status='P' and  order_id='".$order_id."'  order by id desc limit 0,1");
					if($refund['refund_attempt'] == 3 && $refund['cod_refund_in_cb_amt'] > 0){
						$refund_type="cluesbucks";
					}else{
						$refund_type="Bank";
					}					
					$payment_type ="PAID";
					$result = array("status"=>24, "refund_date"=>date('Y-m-d H:i:s', $refund['batch_id']), "payment_type"=>$payment_type, "refund_type"=>$refund_type);
                                }
			}elseif($ord_status['status'] == '92'){
				 //COD confirmed(auto)
				 
		       		$result = array("status"=> "92", "order_date"=>date('Y-m-d H:i:s',$ord_status['timestamp']), "edd"=>array("edd_start_date"=>$pdd_edd['edd1'], "edd_end_date"=>$pdd_edd['edd2']) ); 
		       		}elseif($ord_status['status'] == '93'){
				 // 93- PGW Failure auto confirm
				 
		       		$result = array("status"=> "93", "order_date"=>date('Y-m-d H:i:s',$ord_status['timestamp']), "edd"=>array("edd_start_date"=>$pdd_edd['edd1'], "edd_end_date"=>$pdd_edd['edd2']) ); 
                        }elseif($ord_status['status'] == '74'){ //Cancellation request by SC-Others
                                $query=db_get_row("SELECT value FROM cscart_status_data where status='74' and type='O' and param ='refund_sla_time'");
                                $sla_time=$query['value']*60*60;
                                $sla_date = $sla_time+$ord_status['timestamp'];
                                if($ord_status['payment_id']=='6')
                                {
                                    $payment_type = "COD";
                                } else {
                                    $payment_type ="PAID";
                                }
                                $result = array("status"=>74,"payment_type"=>$payment_type, "days_to_refund"=>date('Y-m-d H:i:s',$sla_date));  
                                
                        }elseif($ord_status['status'] == '56'){ //Cancelled by customer-OSLA
                                if($ord_status['payment_id']=='6'){
                                	$payment_type = "COD";
                                	$result = array("status"=>$ord_status['status'],"payment_type"=>$payment_type );
                                } else {
					$refund=db_get_row("SELECT id, payment_id, refund_attempt, cod_refund_in_cb_amt, batch_id FROM clues_refunds WHERE status='P' and order_id='".$order_id."'  order by id desc limit 0,1");
					if($refund['refund_attempt'] == 3 && $refund['cod_refund_in_cb_amt'] > 0){
						$refund_type="cluesbucks";
					}else{
						$refund_type="Bank";
					}					
					$payment_type ="PAID";
					$result = array("status"=>56, "refund_date"=>date('Y-m-d H:i:s', $refund['batch_id']), "payment_type"=>$payment_type, "refund_type"=>$refund_type);
                                }
                        }elseif($ord_status['status'] == 'L' || $ord_status['status'] == 'E'){ // L- Manifested, E- Milkrun initiated
                              if($ord_status['payment_id']=='6'){
                                $query=db_get_row("SELECT id, order_id,to_status,transition_date  FROM `clues_order_history` WHERE to_status IN ('Q', '92', '93') and order_id='".$order_id."'order by id desc limit 0,1"); // 93- PGW Failure auto confirm , 92- OTP auto confirm
                                $payment_type='COD';
                                $result = array("status"=> $ord_status['status'], "order_confirm_date"=>date('Y-m-d H:i:s',$query['transition_date']), "edd"=>array("edd_start_date"=>$pdd_edd['edd1'], "edd_end_date"=>$pdd_edd['edd2']) ); 
                              }  else {
                                $payment_type='PAID';
                                $result = array("status"=> $ord_status['status'], "order_confirm_date"=>date('Y-m-d H:i:s',$ord_status['timestamp']), "edd"=>array("edd_start_date"=>$pdd_edd['edd1'], "edd_end_date"=>$pdd_edd['edd2']) );
                              }
                              
                        }elseif($ord_status['status'] == '28'){ // MilkRun Complete - On Hold
                                $result = array("status"=> "28", "order_confirm_date"=>date('Y-m-d H:i:s',$ord_status['timestamp']));       
                        }elseif($ord_status['status'] == 'Q'){ //COD-Confirmed
                                $result = array("status"=> "Q", "order_confirm_date"=>date('Y-m-d H:i:s',$ord_status['timestamp']), "edd"=>array("edd_start_date"=>$pdd_edd['edd1'], "edd_end_date"=>$pdd_edd['edd2']) ); 
                        }elseif($ord_status['status'] == 'H'){ // If Order is Delivered
				$canc_time = db_get_row("SELECT transition_date FROM clues_order_history where to_status='H' and order_id='".$order_id."'");
                                $result = array("status" => 'H', "delivery_date"=>date('Y-m-d H:i:s',$canc_time['transition_date']));

                        }elseif($ord_status['status'] == 'N' || $ord_status['status'] == 'F' || $ord_status['status'] == 'Z'){ // N- Checkout Incomplete, F- Failed, G - MilkRun Complete, Z - "Canceled - Order Transfer"
                                $result = array("status"=> $ord_status['status']); 
                        }elseif($ord_status['status'] == 'G'){ 
							    $result = array("status"=> $ord_status['status'], "edd"=>array("edd_start_date"=>$pdd_edd['edd1'], "edd_end_date"=>$pdd_edd['edd2']) );     
                        }elseif($ord_status['status'] == '34'){ //Cancellation request by SC-NSS delivery
                                $query=db_get_row("SELECT value FROM cscart_status_data where status='34' and type='O' and param ='refund_sla_time'");
				$sla_time = $query['value']*60*60;
				$canc_time = db_get_row("SELECT transition_date FROM clues_order_history where to_status='34' and order_id='".$order_id."'");
                                $sla_date = $sla_time + $canc_time['transition_date'];

                                if($ord_status['payment_id']=='6'){
					$payment_type="COD";
				}else{
					$payment_type="PAID";
				}
				$result = array("status"=>34, "payment_type" => $payment_type, "days_to_refund"=>date('Y-m-d H:i:s',$sla_date));
                        }elseif($ord_status['status'] == '41'){ //Cancellation request by customer-Others
                                $query=db_get_row("SELECT value FROM cscart_status_data where status='41' and type='O' and param ='refund_sla_time'");
				$sla_time = $query['value']*60*60;

				$canc_time = db_get_row("SELECT transition_date FROM clues_order_history where to_status='41' and order_id='".$order_id."'");
                                $sla_date = $sla_time + $canc_time['transition_date'];

                                if($ord_status['payment_id']=='6')
                                {
                                	$payment_type = "COD";
                                }
                                else
                                {
	                                $payment_type = "PAID"; 
                                }
				$result = array("status"=>41,"payment_type"=>$payment_type, "days_to_refund"=>date('Y-m-d H:i:s',$sla_date));
                                
                                }elseif($ord_status['status'] == '79' || $ord_status['status'] == 'J' ){ // 79 - RTO delivered to merchant, J - Return To Origin
		                        if($ord_status['payment_id']=='6')
		                        {
		                        	$payment_type = "COD";
		                        } else {
			                        $payment_type = "PAID"; 
		                        }
					$result = array("status"=>$ord_status['status'],"payment_type"=>$payment_type);
                        }elseif($ord_status['status'] == '25'){ // 25 - Cancelled by shopclues- Policy violation
                                if($ord_status['payment_id']=='6'){
                                	$payment_type = "COD";
                                } else {
					$refund=db_get_row("SELECT payment_id, refund_attempt, cod_refund_in_cb_amt, batch_id FROM clues_refunds WHERE status='P' and  order_id='".$order_id."'  order by id desc limit 0,1");
					if($refund['refund_attempt'] == 3 && $refund['cod_refund_in_cb_amt'] > 0){
						$refund_type="cluesbucks";
					}else{
                                 		$refund_type="Bank";
					}
					$payment_type ="PAID";
					$result = array("status"=>25, "refund_date"=>date('Y-m-d H:i:s', $refund[0]['batch_id']), "payment_type"=>$payment_type, "refund_type"=>$refund_type);
                                }
			}elseif($ord_status['status'] == '13'){ // 13 -Cancelled by customer -Shipment Delay
				if($ord_status['payment_id']=='6'){
		                       	$payment_type = "COD";
				} else {
					$refund=db_get_row("SELECT payment_id, refund_attempt, cod_refund_in_cb_amt, batch_id FROM clues_refunds WHERE status='P' and  order_id='".$order_id."'  order by id desc limit 0,1");
					if($refund['refund_attempt'] == 3 && $refund['cod_refund_in_cb_amt'] > 0){
						$refund_type="cluesbucks";
					} else {
		                       		$refund_type="Bank";
					}					
					$payment_type ="PAID";
					$result = array("status"=>13, "refund_date"=>date('Y-m-d H:i:s', $refund['batch_id']), "payment_type"=>$payment_type, "refund_type"=>$refund_type);
				}
			}elseif($ord_status['status'] == '73'){ // 73 - Cancelled by customer SC -Incomplete Order Information
		                        if($ord_status['payment_id']=='6'){
		                        	$payment_type = "COD";
		                        } else {
						$refund=db_get_row("SELECT payment_id, refund_attempt, cod_refund_in_cb_amt, batch_id FROM clues_refunds WHERE status='P' and  order_id='".$order_id."'  order by id desc limit 0,1");
						if($refund['refund_attempt'] == 3 && $refund['cod_refund_in_cb_amt'] > 0){
							$refund_type="cluesbucks";
						}else{
				
		                         		$refund_type="Bank";
						}					
						$payment_type ="PAID";
						$result = array("status"=>73, "refund_date"=>date('Y-m-d H:i:s', $refund['batch_id']), "payment_type"=>$payment_type, "refund_type"=>$refund_type);
		                        }
                                }elseif($ord_status['status'] == '91'){ //	91 - Cancelled by customer SC -OSLA(Auto)
		                        if($ord_status['payment_id']=='6'){
		                        	$payment_type = "COD";
		                        } else {
						$refund=db_get_row("SELECT payment_id, refund_attempt, cod_refund_in_cb_amt, batch_id FROM clues_refunds WHERE status='P' and  order_id='".$order_id."'  order by id desc limit 0,1");
						if($refund['refund_attempt'] == 3 && $refund['cod_refund_in_cb_amt'] > 0){
							$refund_type="cluesbucks";
						}else{
				
		                         		$refund_type="Bank";
						}					
						$payment_type ="PAID";
						$result = array("status"=>91, "refund_date"=>date('Y-m-d H:i:s', $refund[0]['batch_id']), "payment_type"=>$payment_type, "refund_type"=>$refund_type);
		                        }
                                }elseif($ord_status['status'] == 'Y'){ // Y - Cancelled by Shopclues
		                        if($ord_status['payment_id']=='6'){
		                        	$payment_type = "COD";
		                        } else {
						$refund=db_get_row("SELECT payment_id, refund_attempt, cod_refund_in_cb_amt, batch_id FROM clues_refunds WHERE status='P' and  order_id='".$order_id."'  order by id desc limit 0,1");
						if($refund['refund_attempt'] == 3 && $refund['cod_refund_in_cb_amt'] > 0){
							$refund_type="cluesbucks";
						} else {
		                         		$refund_type="Bank";
						}					
						$payment_type ="PAID";
						$result = array("status"=>"Y", "refund_date"=>date('Y-m-d H:i:s', $refund[0]['batch_id']), "payment_type"=>$payment_type, "refund_type"=>$refund_type);
		                        }
                                }elseif($ord_status['status'] == '54'){ //Cancelled by customer SC -Empty Box
		                        if($ord_status['payment_id']=='6'){
		                        	$payment_type = "COD";
		                        } else {
						$refund=db_get_row("SELECT payment_id, refund_attempt, cod_refund_in_cb_amt, batch_id FROM clues_refunds WHERE status='P' and  order_id='".$order_id."'  order by id desc limit 0,1");
						if($refund['refund_attempt'] == 3 && $refund['cod_refund_in_cb_amt'] > 0){
							$refund_type="cluesbucks";
						}else{
				
		                         		$refund_type="Bank";
						}					
						$payment_type ="PAID";
						$result = array("status"=>54, "refund_date"=>date('Y-m-d H:i:s', $refund[0]['batch_id']), "payment_type"=>$payment_type, "refund_type"=>$refund_type);
		                        }
                                }elseif($ord_status['status'] == '83'){ //Cancellation request by customer-Shipment delay
		                        $query=db_get_row("SELECT value FROM cscart_status_data where status='83' and type='O' and param ='refund_sla_time'");
					$sla_time = $query['value']*60*60;
					$sla_date = $sla_time+$ord_status['timestamp'];
		                        if($ord_status['payment_id']!=='6')
		                        $payment_type = "PAID";
					$result = array("status"=>83,"payment_type"=>$payment_type, "days_to_refund"=>date('Y-m-d H:i:s',$sla_date));
                                }elseif($ord_status['status'] == '90'){ //Cancellation request by customer-Shipment delay
		                        $query = db_get_row("SELECT value FROM cscart_status_data where status='90' and type='O' and param ='refund_sla_time'");
					$sla_time = $query['value']*60*60;
					$sla_date = $sla_time+$ord_status['timestamp'];
		                        if($ord_status['payment_id']!=='6')
		                        $payment_type = "PAID"; 
					$result = array("status"=>90,"payment_type"=>$payment_type, "days_to_refund"=>date('Y-m-d H:i:s',$sla_date));
		                        
                                }elseif($ord_status['status'] == '45'){ //Cancellation request by customer-Shipment delay
		                        $query=db_get_row("SELECT value FROM cscart_status_data where status='45' and type='O' and param ='refund_sla_time'");

					$sla_time = $query['value']*60*60;
					$sla_date = $sla_time+$ord_status['timestamp'];
		                        if($ord_status['payment_id']=='6'){
			                        $payment_type = "COD"; 
					}else{
			                        $payment_type = "PAID"; 
					}
					$result = array("status"=>45,"payment_type"=>$payment_type, "days_to_refund"=>date('Y-m-d H:i:s',$sla_date));

                                }elseif($ord_status['status'] == '19' || $ord_status['status'] == 'I'){ // 19 - "Cancelled by customer Shopclues-NSS delivery", I - Canceled by Customer - Other
                           					            
		                        if($ord_status['payment_id']=='6'){
		                        	$payment_type = "COD";
						$result = array("status"=>$ord_status['status'], "payment_type"=>$payment_type);
		                        } else {
									$refund=db_get_row("SELECT payment_id, refund_attempt, cod_refund_in_cb_amt, batch_id FROM clues_refunds WHERE status='P' and  order_id='".$order_id."'  order by id desc limit 0,1");
									if($refund['refund_attempt'] == 3 && $refund['cod_refund_in_cb_amt'] > 0){
										$refund_type="cluesbucks";
									} else {
										$refund_type="Bank";
									}		
						$payment_type ="PAID";
						$result = array("status"=>$ord_status['status'], "refund_date"=>date('Y-m-d H:i:s', $refund['batch_id']), "payment_type"=>$payment_type, "refund_type"=>$refund_type);
		                        }
		                        
                                }elseif($ord_status['status'] == '44'){ //Cancellation request by SC -Other
		                        $query=db_get_row("SELECT value FROM cscart_status_data where status='44' and type='O' and param ='refund_sla_time'");
					$sla_time = $query['value']*60*60;
					$sla_date = $sla_time+$ord_status['timestamp'];
		                        if($ord_status['payment_id']=='6')
		                        {
		                        	$payment_type = "COD";
						$result = array("status"=>44,"payment_type"=>$payment_type);
		                        } else {
		                        	$payment_type = "PAID";    
						$result = array("status"=>44,"payment_type"=>$payment_type, "days_to_refund"=>date('Y-m-d H:i:s',$sla_date));
		                        }
				}elseif($ord_status['status'] == '39'){ //Cancellation Request  by SC - Excess Sold/Backorder
		                        $query=db_get_row("SELECT value FROM cscart_status_data where status='39' and type='O' and param ='refund_sla_time'");
					$sla_time = $query['value']*60*60;
					$sla_date = $sla_time+$ord_status['timestamp'];
		                        if($ord_status['payment_id']=='6')
		                        {
			                        $payment_type = "COD";
		                        } else {
			                        $payment_type = "PAID";    
		                        }
					$result = array("status"=>39,"payment_type"=>$payment_type, "days_to_refund"=>date('Y-m-d H:i:s',$sla_date));
                                }elseif($ord_status['status'] == '10' || $ord_status['status'] == '55' ){ //Cancellation request by customer-Other and Cancellation Request by Customer - OSLA

		                        $query=db_get_row("SELECT value FROM cscart_status_data where status='".$ord_status['status']."' and type='O' and param ='refund_sla_time'");
					$sla_time = $query['value']*60*60;
					$sla_date = $sla_time+$ord_status['timestamp'];
		                        if($ord_status['payment_id']=='6')
		                        {
		                       		$payment_type = "COD";
		                        } else {
		                        	$payment_type = "PAID";    
		                        }
					$result = array("status"=>$ord_status['status'],"payment_type"=>$payment_type, "days_to_refund"=>date('Y-m-d H:i:s',$sla_date));
		                        //added by ajay for  RTO - RTM Eligible-78
		                        }elseif($ord_status['status'] == '78'){
									
										if($ord_status['payment_id']=='6')
										{
											$payment_type = "COD";
										}else{
											$payment_type = "PAID";    
										}
		                        $result = array("status"=>$ord_status['status'],"payment_type"=>$payment_type );
		                        //end by ajay
                                }elseif($ord_status['status'] == '52'){ //Cancelled by Shopclues-Shipment delay
		                        if($ord_status['payment_id']=='6'){
		                        	$payment_type = "COD";
		                        } else {
						$refund=db_get_row("SELECT payment_id, refund_attempt, cod_refund_in_cb_amt, batch_id FROM clues_refunds WHERE status='P' and  order_id='".$order_id."'  order by id desc limit 0,1");
						if($refund['refund_attempt'] == 3 && $refund['cod_refund_in_cb_amt'] > 0){
							$refund_type="cluesbucks";
						}else{
		                         		$refund_type="Bank";
						}					
						$payment_type ="PAID";
						$result = array("status"=>52, "refund_date"=>date('Y-m-d H:i:s', $refund['batch_id']), "payment_type"=>$payment_type, "refund_type"=>$refund_type);
		                        }
                                }elseif($ord_status['status'] == '12'){ //Cancelled by merchant
		                        if($ord_status['payment_id']=='6'){
		                        	$payment_type = "COD";
		                        } else {
						$refund=db_get_row("SELECT payment_id, refund_attempt, cod_refund_in_cb_amt, batch_id FROM clues_refunds WHERE status='P' and  order_id='".$order_id."'  order by id desc limit 0,1");
						if($refund['refund_attempt'] == 3 && $refund['cod_refund_in_cb_amt'] > 0){
							$refund_type="cluesbucks";
						}else{
				
		                         		$refund_type="Bank";
						}					
						$payment_type ="PAID";
						$result = array("status"=>12, "refund_date"=>date('Y-m-d H:i:s', $refund['batch_id']), "payment_type"=>$payment_type, "refund_type"=>$refund_type);
		                        }
                                }elseif($ord_status['status'] == '48'){ //Cancellation Request  shopclue-Wrong address delivery
		                        $query=db_get_row("SELECT value FROM cscart_status_data where status='48' and type='O' and param ='refund_sla_time'");
					$sla_time = $query['value']*60*60;
					$sla_date = $sla_time+$ord_status['timestamp'];
		                        if($ord_status['payment_id']=='6')
		                        {
		                        $payment_type = "COD";
		                        }
		                        else {
		                        $payment_type = "PAID";    
		                        }
					$result = array("status"=>48,"payment_type"=>$payment_type, "days_to_refund"=>date('Y-m-d H:i:s',$sla_date)); 
                                }elseif($ord_status['status'] == '51'){ //Cancelled by merchant
		                        if($ord_status['payment_id']=='6'){
		                        	$payment_type = "COD";
		                        } else {
						$refund=db_get_row("SELECT payment_id, refund_attempt, cod_refund_in_cb_amt, batch_id FROM clues_refunds WHERE status='P' and  order_id='".$order_id."'  order by id desc limit 0,1");
						if($refund['refund_attempt'] == 3 && $refund['cod_refund_in_cb_amt'] > 0){
							$refund_type="cluesbucks";
						}else{
				
		                         		$refund_type="Bank";
						}					
						$payment_type ="PAID";
					$result = array("status"=>51, "refund_date"=>date('Y-m-d H:i:s', $refund['batch_id']), "payment_type"=>$payment_type, "refund_type"=>$refund_type);
		                        }
                                }elseif($ord_status['status'] == '53'){ //Cancelled by shopclues-Wrong address delivery
		                        if($ord_status['payment_id']=='6'){
		                        	$payment_type = "COD";
		                        } else {
						$refund=db_get_row("SELECT payment_id, refund_attempt, cod_refund_in_cb_amt, batch_id FROM clues_refunds WHERE status='P' and  order_id='".$order_id."'  order by id desc limit 0,1");
						if($refund['refund_attempt'] == 3 && $refund['cod_refund_in_cb_amt'] > 0){
							$refund_type="cluesbucks";
						}else{
				
		                         		$refund_type="Bank";
						}					
						$payment_type ="PAID";
						$result = array("status"=>53, "refund_date"=>date('Y-m-d H:i:s', $refund['batch_id']), "payment_type"=>$payment_type, "refund_type"=>$refund_type);
		                        }
                                }elseif($ord_status['status'] == '20' || $ord_status['status'] == '9'){ //Canceled by ShopClues - NSS Pick-Up and RTO Shipped to Merchant
		                        if($ord_status['payment_id']=='6'){
		                        	$payment_type = "COD";
		                        	$result = array("status"=>$ord_status['status'],"payment_type"=>$payment_type);
		                        } else {
						$refund=db_get_row("SELECT payment_id, refund_attempt, cod_refund_in_cb_amt, batch_id FROM clues_refunds WHERE status='P' and  order_id='".$order_id."'  order by id desc limit 0,1");
						if($refund['refund_attempt'] == 3 && $refund['cod_refund_in_cb_amt'] > 0){
							$refund_type="cluesbucks";
						}else{
		                         		$refund_type="Bank";
						}					
						$payment_type ="PAID";
						$result = array("status"=>$ord_status['status'], "refund_date"=>date('Y-m-d H:i:s', $refund['batch_id']), "payment_type"=>$payment_type, "refund_type"=>$refund_type);
		                        }
                                }elseif($ord_status['status'] == '22' || $ord_status['status'] == '84'){ //Canceled by ShopClues - Fraud Alert and Canceled by ShopClues - Shipment Lost 
		                        if($ord_status['payment_id']=='6'){
		                        	$payment_type = "COD";
		                        	 $result = array("status"=>$ord_status['status'],"payment_type"=>$payment_type);
		                        } else {
						$refund=db_get_row("SELECT payment_id, refund_attempt, cod_refund_in_cb_amt, batch_id FROM clues_refunds WHERE status='P' and  order_id='".$order_id."'  order by id desc limit 0,1");
						if($refund['refund_attempt'] == 3 && $refund['cod_refund_in_cb_amt'] > 0){
							$refund_type="cluesbucks";
						} else {
		                         		$refund_type="Bank";
						}					
						$payment_type ="PAID";
						$result = array("status"=>$ord_status['status'], "refund_date"=>date('Y-m-d H:i:s', $refund['batch_id']), "payment_type"=>$payment_type, "refund_type"=>$refund_type);
		                        }
                                }elseif($ord_status['status'] == 'S' || $ord_status['status'] == '80' || $ord_status['status'] == '81' || $ord_status['status'] == '30'){ // S - RMA Action Approved, 80 - RMA Delivered to Merchant, 81 - RMA - RTM Eligible, RMA - RTM Not Eligible
					$query = db_get_array("SELECT DISTINCT cscart_rma_returns.return_id, cscart_rma_property_descriptions.property AS action FROM cscart_rma_returns LEFT JOIN cscart_rma_return_products ON cscart_rma_return_products.return_id = cscart_rma_returns.return_id LEFT JOIN cscart_rma_property_descriptions ON (cscart_rma_property_descriptions.property_id = cscart_rma_returns.action AND cscart_rma_property_descriptions.lang_code = 'EN') LEFT JOIN cscart_users ON cscart_rma_returns.user_id = cscart_users.user_id LEFT JOIN cscart_orders ON cscart_rma_returns.order_id = cscart_orders.order_id WHERE cscart_orders.order_id='".$order_id."' ORDER BY cscart_rma_returns.timestamp desc LIMIT 0, 1");

		                         $result = array("status"=>$ord_status['status'], "rma_action"=> $query[0]['action']);
                                }elseif($ord_status['status'] == '6'){ //Rma- No-Pickup
				        $refund=db_get_row("SELECT payment_id, refund_attempt, cod_refund_in_cb_amt, batch_id FROM clues_refunds WHERE status='P' and  order_id='".$order_id."'  order by id desc limit 0,1");
					if($refund['refund_attempt'] == 3 && $refund['cod_refund_in_cb_amt'] > 0){
						$refund_type="cluesbucks";
                                        } else {
                                 		$refund_type="Bank";
					}
	                                $query = db_get_row("SELECT value FROM cscart_status_data where status='6' and type='O' and param ='refund_sla_time'");
					$sla_time = $query['value']*60*60;
					$sla_date = $sla_time+$ord_status['timestamp'];

					$query = db_get_array("SELECT DISTINCT cscart_rma_returns.return_id, cscart_rma_property_descriptions.property AS action FROM cscart_rma_returns LEFT JOIN cscart_rma_return_products ON cscart_rma_return_products.return_id = cscart_rma_returns.return_id LEFT JOIN cscart_rma_property_descriptions ON (cscart_rma_property_descriptions.property_id = cscart_rma_returns.action AND cscart_rma_property_descriptions.lang_code = 'EN') LEFT JOIN cscart_users ON cscart_rma_returns.user_id = cscart_users.user_id LEFT JOIN cscart_orders ON cscart_rma_returns.order_id = cscart_orders.order_id WHERE cscart_orders.order_id='".$order_id."' ORDER BY cscart_rma_returns.timestamp desc LIMIT 0, 1");

			                $result = array("status"=>6, "refund_date"=>date('Y-m-d H:i:s', $refund['batch_id']), "refund_type"=>$refund_type, "days_to_refund"=>date('Y-m-d H:i:s',$sla_date),"rma_action"=> $query[0]['action']);

                                }elseif($ord_status['status'] == '65' || $ord_status['status'] == '64' ){ //RMA Shipped to Merchant and RMA Manifested
					$query = db_get_array("SELECT DISTINCT cscart_rma_returns.return_id, cscart_rma_property_descriptions.property AS action FROM cscart_rma_returns LEFT JOIN cscart_rma_return_products ON cscart_rma_return_products.return_id = cscart_rma_returns.return_id LEFT JOIN cscart_rma_property_descriptions ON (cscart_rma_property_descriptions.property_id = cscart_rma_returns.action AND cscart_rma_property_descriptions.lang_code = 'EN') LEFT JOIN cscart_users ON cscart_rma_returns.user_id = cscart_users.user_id LEFT JOIN cscart_orders ON cscart_rma_returns.order_id = cscart_orders.order_id WHERE cscart_orders.order_id='".$order_id."' ORDER BY cscart_rma_returns.timestamp desc LIMIT 0, 1");

                                 $result = array("status"=>$ord_status['status'], "rma_action"=> $query[0]['action']);
                                }elseif($ord_status['status'] == 'M'){ //Refunded
					$refund=db_get_row("SELECT batch_id FROM clues_refunds WHERE status='P' and  order_id='".$order_id."'  order by id desc limit 0,1");
					$result = array("status"=>"M", "refund_date"=>date('Y-m-d H:i:s', $refund['batch_id']));
                                
					}elseif($ord_status['status'] == '50' || $ord_status['status'] == '47' || $ord_status['status'] == '46'){ //Cancellation Request by ShopClues - Shipment Lost and Cancellation Request by ShopClues - Shipment Delay,Cancellation Request by ShopClues - Shipment Untraceable

				$shipment = db_get_array("SELECT from_unixtime(cs.timestamp) as shipment_date
					FROM cscart_orders co 
					INNER JOIN cscart_shipment_items csi ON co.order_id=csi.order_id
					INNER JOIN cscart_shipments cs ON cs.shipment_id=csi.shipment_id
					WHERE co.order_id='".$order_id."' AND co.status='".$ord_status['status']."' LIMIT 0,1");

		            $query = db_get_row("SELECT value FROM cscart_status_data where status='".$ord_status['status']."' and type='O' and param ='refund_sla_time'");
					$sla_time = $query['value']*60*60;

					$canc_time = db_get_row("SELECT transition_date FROM clues_order_history where to_status='".$ord_status['status']."' and order_id='".$order_id."'");
                                        $sla_date = $sla_time + $canc_time['transition_date'];

		                        if($ord_status['payment_id']=='6')
		                        {
		                        	$payment_type = "COD";
		                        } else {
		                        	$payment_type = "PAID";
		                        }
					$result = array("status"=>$ord_status['status'],"payment_type"=>$payment_type, "days_to_refund"=>date('Y-m-d H:i:s',$sla_date), "shipment_date"=>$shipment[0]['shipment_date']);

				} else {
	                                $result = array("status"=> $ord_status['status']);
				}
			//by ajay add source column in IVR log
				if($_REQUEST['source'] == "csform"){
					$source_from = "csform";
				}else{
					$source_from = "ivr";
				}
		   // end by ajay
		   		
			if($ord_status['status'] == 'A' || $ord_status['status'] == 'C'){ // If Order is in Shipped State
				db_query("INSERT INTO clues_ivr_log SET request_ip='".$_SERVER['REMOTE_ADDR']."', order_id='".$order_id."', status='".$result[0]['status']."', notify='".$notify."', data='".base64_encode(serialize($result))."', source='".$source_from."'");
			} else {
				db_query("INSERT INTO clues_ivr_log SET request_ip='".$_SERVER['REMOTE_ADDR']."', order_id='".$order_id."', status='".$result['status']."', notify='".$notify."', data='".base64_encode(serialize($result))."', source='".$source_from."'");
			}
				if($notify){
					if($ord_status['status'] == 'A'){ // If Order is in Shipped State
					        require_once  DIR_ADDONS . 'my_changes/func.php';
						$order_info = fn_get_order_info($order_id, true);

						$edp_data = fn_generate_ekeys_for_edp(array('status_from'=>'L', 'status_to'=>'A'), $order_info);
						fn_order_notification($order_info, $edp_data, array());

					}
				}

			return $result;
		}

		/*
		 *	Encode array into JSON
		*/
		
		function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
	}

?>



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


        require_once DIR_ADDONS . "my_changes/func.php";
	class orders extends REST {
	
		public $data = "";

		public function __construct(){
			parent::__construct();				// Init parent contructor
		}
		
		/**
		* @email_id as email id of the user
                * @order_id as the first name of the user
                * order details if the order belongs to the specifed emailid
                */
               public function trackorder()
               {
                   if($this->get_request_method() != "GET"){

				$this->response('',406);
		   }
                   $params = $this->_request;
                   if (empty($params['email_id']) || empty($params['order_id']) || !(is_numeric($params['order_id']))) {

                            $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_params'));
                            $this->response($this->json($error), 200);
                   }
                   else
                   {
                        $res=db_get_row("select order_id from cscart_orders where order_id='".$params['order_id']."' and email='".$params['email_id']."'");
                        
                        if(!empty($res))
                        {
                                $order_info = fn_get_order_info($params['order_id']);

                                $shipments = db_get_array('SELECT ?:shipments.shipment_id, ?:shipments.comments, ?:shipments.tracking_number, ?:shipping_descriptions.shipping AS shipping, ?:shipments.carrier,?:shipments.timestamp FROM ?:shipments LEFT JOIN ?:shipment_items ON (?:shipments.shipment_id = ?:shipment_items.shipment_id) LEFT JOIN ?:shipping_descriptions ON (?:shipments.shipping_id = ?:shipping_descriptions.shipping_id) WHERE ?:shipment_items.order_id = ?i AND ?:shipping_descriptions.lang_code = ?s GROUP BY ?:shipments.shipment_id', $order_info['order_id'], DESCR_SL);

                                if (!empty($shipments)) {
                                        foreach ($shipments as $id => $shipment) {
                                                $shipments[$id]['items'] = db_get_array('SELECT item_id, amount FROM ?:shipment_items WHERE shipment_id = ?i', $shipment['shipment_id']);
                                        }
                                }
                                $trackin = db_get_array("SELECT carrier_status, sc_status, from_location, to_location, status_update_date, memo, awbno, receiver_name, receiver_contact FROM clues_shipment_tracking_center WHERE order_id=".$order_info['order_id']." ORDER BY latest desc, date_of_creation desc");
                                foreach($trackin as $tr=>$track){
                                    $tracking[$track['awbno']][]=$track;
                                }
                                 
                                //print_r(STATUSES_ORDER);die;
                                $status_type = fn_get_statuses(STATUSES_ORDER, true,true);
                                //print_r($status_type);die;
                                if(STATUSES_ORDER == 'R' || STATUSES_ORDER == 'G')
                                    $status_name = fn_get_status_customer_facing_name($order_info['status'],STATUSES_ORDER); 
                                else
                                    $status_name = fn_get_status_customer_facing_name($order_info['status']);
                                
                                $shipments['order_status'] = $status_name['customer_facing_name'];                                
                                $shipments['status_msg'] = fn_get_lang_var('status_change_date_message');
                                $shipments['status_changed_date'] = fn_get_status_change_date($order_info['order_id'],$order_info['status']);
                                $shipments['what_this_status_means_title'] = fn_get_lang_var('what_this_status_means');                                
                                $lang_var = 'order_message_'.$order_info['status'];
                                if ($order_info['status']=='N' || $order_info['status']=='F'){  
                                    $days_in_status = fn_get_status_days($order_info['order_id'],$order_info['status'],$order_info['timestamp']);

                                    if ($days_in_status < 48){
                                        $lang_var_less_48 = $lang_var.'_less48';
                                        if(fn_get_lang_var($lang_var_less_48) !='')
                                            $lang = fn_get_lang_var($lang_var_less_48);    
                                    }
                                    else{
                                        $lang_var_above_48 = $lang_var.'_above48';
                                        if(fn_get_lang_var($lang_var_less_48) !='')
                                            $lang = fn_get_lang_var($lang_var_above_48);    
                                    }
                                }
                                else{
                                    if(fn_get_lang_var($lang_var) !=''){
                                        $lang = fn_get_lang_var($lang_var);
                                    }
                                }
                                $shipments['what_this_status_means_info'] = $lang;
                                
                                if (!empty($shipments[0])) {

                                    $shipments['shipping_information_title']=fn_get_lang_var('shipping_information');
                                    $carrier = fn_get_tracking_url($shipments[0]['carrier']);
                                    $shipments['shipping_information_details']['carrier'] =$carrier['carrier_name'];
                                    if($carrier['is_url_trackable'] == 1){
                                       $ca = fn_get_lang_var('is_trackable');
                                       $ca = str_replace('[CARRIER_NAME]',$carrier['carrier_name'],$ca);
                                       $tracking_url .= $carrier['tracking_url'];
                                       $tracking_url .= $shipments[0]['tracking_number'];
                                       $ca = str_replace('[TRACKING_URL]',$tracking_url,$ca);
                                       $shipments['shipping_information_details']['tracking'] =$ca;
                                    }
                                    elseif($carrier['is_url_trackable'] == 0){
                                        $ca = fn_get_lang_var('is_not_trackable');
                                        $ca = str_replace('[CARRIER_NAME]',$carrier['carrier_name'],$ca);
                                        $ca = str_replace('[TRACKING_NUMBER]',$shipments[0]['tracking_number'],$ca);
                                        $tracking_url = $carrier['tracking_url'];   
                                        $ca = str_replace('[TRACKING_URL]',$tracking_url,$ca);
                                        $shipments['shipping_information_details']['tracking'] =$ca;
                                    }
                                    else{
                                        $ca = fn_get_lang_var('not_tracking');
                                        $ca = str_replace("[CARRIER_NAME]",$carrier['carrier_name'],$ca);
                                        $ca = str_replace('[LABEL]',replace("[TRACKING_NUMBER]",fn_get_lang_var('tracking_num')),$shipments[0]['tracking_number']);
                                        $shipments['shipping_information_details']['tracking'] =$ca;
                                    }
                                }
                                
                                $payment = fn_get_new_payment_info($params['order_id']);
                                if($order_info['payment_method']['payment_id'] == 0){                                    
                                    $order_info['payment_method']['method'] = fn_get_lang_var('clues_bucks_payment');
                                    $order_info['payment_method']['paid_using'] =  fn_get_lang_var('clues_bucks_payment');
                                }
                                else{
                                    unset($order_info['payment_method']);
                                    $order_info['payment_method']['method'] = $payment['type_name'];
                                    $order_info['payment_method']['paid_using'] =  $payment['name'];                                    
                                }
                                $order_data = db_get_row("select status, order_id from cscart_orders where order_id='".$order_info['order_id']."' and user_id= '".$order_info['user_id']."' ");
                                $allow_cancell = db_get_field("select value from cscart_status_data where status='".$order_data['status']."' and param= 'allow_cancelation' ");
                                if($allow_cancell == 'Y'){
                                    $order_info['allow_cancellation'] = 1;
                                    $sql = "SELECT reason_id, reason FROM  clues_order_cancellation_reason WHERE 1";
                                    $reasons = db_get_array($sql);
                                    $order_info['reasons'] = $reasons; 
                                }
                                else{
                                    $order_info['allow_cancellation'] = 0;
                                }
                                $orders['tracking'] =  $tracking;
                                $shipments['carrier_name'] = $carrier['carrier_name'];
                                $orders['shipments'] = $shipments;
                                $orders['order_info'] = $order_info;
                                $this->response($this->json($orders), 200);
                        }
                        else
                        {
                            $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_order_not_available'));
                            $this->response($this->json($error), 200);
                        } 
                    }
               }
               
                /* 
		 *	Simple Api for Order History
		 * 	___________________________________________________________________________________
		 *			Cancelling a order 
		 * 	____________________________________________________________________________________	
		 *	
                 *      Method          : cancelOrder();     
                 * 
		 *	Request 	: Strictly DELETE
		 * 	Parameters   	
		 *	@user_id     	: user id of the user 
		 *      @order_id       : order id for which cancellation is requested
                 *      @reasons        : reasons for cancellation (allowed values {
                 *                        4 > Wrong product ordered                
                 *                        2 > Found lower price else where  
                 *                        5 > Delivery address issue 
                 *                        6 > Other (these values are dynamic in db. it can change over time)
                 *                         
                 *                         })
                 * 
                 * 
                 *      @comment        : (optional) user comment
                 *      @cur_status     : current status of the product 
                 * 
		 * 	query format 	: api/orders
		 *
		 *
                 *       ___________________________________________________________________________________
		 *			Quering Order History
		 * 	____________________________________________________________________________________	
                 * 
                 *      Method          : getOrder();     
                 * 
		 *	Request 	: Strictly GET
		 * 	Parameters   	
		 *	@user_id     	: user id of the user whose address info is required.
                 *      @sort_by        : {date,order_id,total,status}
                 *      @sort_order     : asc or desc
                 *  
                 *      query           : api/orders&user_id=1393638&sort_by=order_id&sort_order=asc
                 *  
                 */
               
               
               
               
               
               
               public function orders(){
                   
                  
                   if($this->get_request_method() == "GET"){
                       
                        $params = $this->_request;
                        if(empty($params['user_id'])){
                            $msg = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_empty_user_id'));
                            $this->response($this->json($msg), 200);  

                        }
                        if(!(is_numeric($params['user_id']))){
                            $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_user_id'));
                            $this->response($this->json($error), 200);
                        } 

                        $response = $this->getOrders();
                        if(!(empty($response['summary'])))
                            $this->response($this->json($response), 200);
                        else{
                            $error = array('status' => "Success", "msg" => fn_get_lang_var('api_param_order_not_available'));
                            $this->response($this->json($error), 200);
                        } 
                    
		   }
                   elseif($this->get_request_method() == "PUT"){
                       $this->cancelOrder();     
                       
                   }
                   
                   else
                       $this->response('',406);
                                                          
               }
               
               
               public function getOrders($user_id = ''){
                   
                   if(empty($user_id)){
                       $params = $this->_request;
                                 
                   }        
                   else{
                       $params['user_id'] = $user_id;                     
                       //$params['page'] = 1;
                       //$params['limit'] = 3;
                       $params['sort_by'] ='date';
                       $params['sort_order']='desc';
                   }               
                    
                    list($orders, $search) = fn_get_orders($params, $params['limit']);
                    foreach($orders as $key=>$order_detail)
                    {
                            $order_info[]=fn_get_order_info($order_detail['order_id']);
                            $main_order_id = db_get_field("select main_order_id from clues_order_clone_rel where clone_order_id='".$order_detail['order_id']."'");
                            if (!empty($main_order_id))
                              {
                                $return_order = db_get_field("select return_id from cscart_rma_returns where order_id='".$main_order_id."'");
                                      if (!empty($return_order)){
                                      $orders[$key]['allow_cancelation']= 'N';
                                  }
                              }
                              $orders[$key]['pdd_edd'] = fn_get_pdd_edd($order_detail['order_id']);

                             if(STATUSES_ORDER == 'R' || STATUSES_ORDER == 'G')
                                $status_name = fn_get_status_customer_facing_name($orders[$key]['status'],STATUSES_ORDER); 
                            else
                               $status_name = fn_get_status_customer_facing_name($orders[$key]['status']);
                            //print_r($status_name);
                            $orders[$key]['status_name'] = $status_name['customer_facing_name'];                              
                    }

                    $orders_item=array();
                    if(!empty($order_info))
                    {   
                        foreach($order_info as $order_detail){
                            $orders_item[]=$order_detail['items'];

                            if($order_detail['allow_return']=='Y')
                            {
                                    $return_order[$order_detail['order_id']]='Y';
                            }
                            if(isset($order_detail['order_return_status']) && $order_detail['order_return_status']=='N')
                            {
                                    $return_order[$order_detail['order_id']]='N';
                            }
                            elseif(isset($order_detail['order_return_status']) && $order_detail['order_return_status']=='E')
                            {
                                    $return_order[$order_detail['order_id']]='E';
                            }

                        }
                    }
                    
                     
                    $response = array();
                    $response['summary'] = $orders;
                    $response['details'] = $orders_item;
                    return $response;    
                                                
               }
               
               private function cancelOrder(){
                   
                        $order_id=$this->_request['order_id'];
						$user_id=$this->_request['user_id'];
						$reason=$this->_request['reasons'];
						$comment=$this->_request['comment'];
                       
                        if(!isset($order_id) || !isset($user_id) || !isset($reason)){
                            $msg = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_params'));
                            $this->response($this->json($msg), 200);
                                                      
                        }
                        
                        if(!(is_numeric($order_id)) || !(is_numeric($user_id)) || !(is_numeric($reason))){
                            
                            $msg = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_params'));
                            $this->response($this->json($msg), 200);
                                                       
                        }
                                              
                        $order_data = db_get_row("select status, order_id from cscart_orders where order_id='".$order_id."' and user_id= '".$user_id."' ");
                        $allow_cancell = db_get_field("select value from cscart_status_data where status='".$order_data['status']."' and param= 'allow_cancelation' ");
                        $sql = "select cancell_status from clues_order_cancellation_reason where reason_id=".$reason;
                        $reason_status = db_get_field($sql);
                        if(empty($reason_status)){
                            $msg = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_reason'));
                            $this->response($this->json($msg), 200);                            
                            
                        }
                        if(!empty($order_data)){
                            if($allow_cancell == 'Y'){
                                db_query("insert into clues_customer_cancellation set order_id='".$order_id."',user_id='".$user_id."',reason_id='".$reason."',comment='".addslashes($comment)."'");
                                $res = fn_change_order_status($order_id,$reason_status,$order_data['status'],$notify = array("C"=>false,"A"=>false,"S"=>false));
                                if($res){
                                    $msg = array('status' => "Successful", "msg" => fn_get_lang_var('api_cancel_request_recieved_for_order') .$order_id);
                                    $this->response($this->json($msg), 200);                        
                                } 
                                else{

                                    $msg = array('status' => "Failed", "msg" => fn_get_lang_var('api_operation_not_successful'));
                                    $this->response($this->json($msg), 200);
                                }

                            }
                            else{
                                $msg = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_cancellation_not_allowed'));
                                $this->response($this->json($msg), 200);
                             }                                 
                        }
                        else{
                                $msg = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_user_or_order'));
                                $this->response($this->json($msg), 200);
                             }  
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

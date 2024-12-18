<?php

    require dirname(__FILE__) ."/users.php";
    require dirname(__FILE__) ."/profiles.php";
    require dirname(__FILE__) ."/cart.php";
    require  DIR_ADDONS . 'my_changes/func.php';


    class checkout extends REST {

        public $data = "";

        public function __construct(){
                parent::__construct();				// Init parent contructor
        }


        public function checkout(){
            //echo $this->get_request_method();die;
            if($this->get_request_method() != "POST"){
                $this->response('',406);
            }
            if($_SERVER['CONTENT_TYPE'] != 'application/json'){
                
                $msg = array('status' => "Error", "msg" => fn_get_lang_var('api_param_invalid_content_type'));
                $this->response($this->json($msg), 400);
            }
            $default['edit_step'] = "step_one";
            $valid_steps = array("step_one",
                                 "step_two",
                                 "step_three",
                                 "step_four",
                                 "place_order",
                                 "order_summary"
                            );

            $edit_step = trim($this->_request['edit_step']);
            if(!empty($edit_step)){
                if(!(in_array($edit_step,$valid_steps))){
                    $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_step'));
                    $this->response($this->json($error), 200);
                }
            }
            else{
                $edit_step = $default['edit_step'];
            }

            if(trim($edit_step) == "step_one"){

                $this->Step1();
            }

            elseif(trim($edit_step) == "step_two" || trim($edit_step) == "step_three" || trim($edit_step) == "step_four" || trim($edit_step) == "place_order"){

                $user_id = intval($this->_request['user_id']);
                if(empty($user_id) || !(is_numeric($user_id)) ){
                     $error = array('status' => "Failed", "msg" =>fn_get_lang_var('api_param_invalid_user_id'));
                     $this->response($this->json($error), 200);
                }

                $user = $this->check_user();

                if($user['user_id'] != $user_id){
                     $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_user_id'));
                     $this->response($this->json($error), 200);
                }
                if(trim($edit_step) == "step_two"){
                    //if its a valid user continue to step two
                    $this->Step2();
                }

                if(trim($edit_step) == "step_three"){
                    //if its a valid user continue to step three
                    $this->Step3();
                }
                if(trim($edit_step) == "step_four"){
                    //if its a valid user continue to step four
                    $this->Step4();
                }

                if(trim($edit_step) == "place_order"){
                    //if its a valid user continue to place order
                    $this->place_order();
                }

            }

            elseif(trim($edit_step) == "order_summary"){
                    //if its a valid user continue to place order
                    $this->order_summary();
            }
        }


        public function reg_login(){
            //function to login registered user at step one

            $user = new users();
			$user->_request = $this->_request;
            $status = $user->user_login();
            if($status['error'] == 1){
                $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_username_password'));
                $this->response($this->json($error), 200);
            }
            else{
                return $status['user_data'];
            }
        }

        public function unreg_login(){

            //function to login anynomous at step one
            $user = new users();

			$user->_request = $this->_request;
            $status = $user->anyno_login();
            if($status['reg'] == 1){
            	$user_data = db_get_row("SELECT user_id, firstname, lastname,email,password FROM cscart_users WHERE status='A' AND email='".$this->_request['user']."'");
                $user_data['hash_key'] = $user_data['password'];
                unset($user_data['password']);
                $data['user_info'] = $user_data;
                $data['user_info']['user_type'] = 'R';
                return $data;
            }
            else{
                $user_data = db_get_row("SELECT password FROM cscart_users WHERE status='A' AND email='".$this->_request['user']."'");
                $resp['user_info'] = $status['user_info'];
                $resp['user_info']['hash_key'] = $user_data['password'];
                $resp['user_info']['user_type'] = 'U';
                $resp['shipping_info'] = $status['shipping_info'];
                return $resp;
            }
        }

        public function Step1(){

            require  DIR_ADDONS . 'sdeep/func.php';
            if(!empty($this->_request['user']) && !empty($this->_request['password'])){
                $user_data = $this->reg_login();
                //check if user has cart
                $cart = $this->check_cart($user_data['user_id']);
                //add new items to cart cart if user has brought new items for cart during checkout.
                $this->combine_cart($user_data['user_id'],$cart['cart']);
                $address = new profiles();
                $dummy = $address->getAddressBook($user_data['user_id']);
                $data['user_info'] = $user_data;
                $data['shipping_info'] = $dummy[0];
                
                //log step 1 data
                
                $log['checkout_step'] = 'step_one';
                $log['email_id'] = $this->_request['user'];
                $log['user_id'] = $user_data['user_id'];
                $this->RecordLogs($log);
                $this->response($this->json($data), 200);
            }
            elseif(!empty($this->_request['user'])){
                $user_data = $this->unreg_login();
                $cart = $this->check_cart($user_data['user_info']['user_id']);
                $this->combine_cart($user_data['user_info']['user_id'],$cart['cart']);
                $log['checkout_step'] = 'step_one';
                $log['email_id'] = $this->_request['user'];
                $log['user_id'] = $user_data['user_id'];
                $this->RecordLogs($log);
                $this->response($this->json($user_data), 200);
            }
            
        }

        public function Step2(){

            $this->check_cart($this->_request['user_id']);
            $update = new profiles();

            if(!isset($this->_request['profile_id']) || empty($this->_request['profile_id']) ){
             	$update->_request['primary'] = 0;
            	$up = $update->createAddressBook('create');
            	$user_info['user_id'] = $this->_request['user_id'];
            	$user_info['profile_id'] = $up;
                $msg = array('status' => "Success", "msg" => $user_info);
                $this->response($this->json($msg), 200);
            }
            $update->_request['primary'] = 1;
            $up = $update->createAddressBook('update');
            if($up){
                $user_info['user_id'] = $this->_request['user_id'];
            	$user_info['profile_id'] = $this->_request['profile_id'];
                
                //log step 2 data
                $log['checkout_step'] = 'step_two';
                $log['profile_id'] = $user_info['profile_id'];
                $log['user_id'] = $user_info['user_id'];
                $this->RecordLogs($log);
                $msg = array('status' => "Success", "msg" => $user_info);
                $this->response($this->json($msg), 200);
            }
            else{
                 //log step 2 data
                $log['checkout_step'] = 'step_two';
                $log['profile_id'] = $user_info['profile_id'];
                $log['user_id'] = $user_info['user_id'];
                $log['error'] = "Profile id and user id doesn't match";
                $this->RecordLogs($log);
                
                $msg = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_profile_user_not_match'));
                $this->response($this->json($msg), 200);
            }
        }

        public function check_profile($address){

            unset($address['verified']);
            unset($address['s_title']);
            unset($address['s_address_2']);
            foreach($address as $k=>$v){
                if(empty($v)){
                    return false;
                }
            }
            return true;
        }

        public function get_profile(){
            $address = new profiles();
            $dummy = $address->getAddressBook($this->_request['user_id']);
            //print_r($dummy);
            foreach($dummy as $k => $v){
                    if($v['profile_id'] == $this->_request['profile_id'])
                    return $v;
            }
            $msg = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_profile_user_not_match'));
            $this->response($this->json($msg), 200);

        }

        public function apply_coupons(&$cart, $coupon_code1) {
        $error = 0;
        if (empty($coupon_code1)) {
            $response['msg'] = fn_get_lang_var('no_such_coupon_exist');
            $error = 1;
        }
        if ($error == 0) {
		array_change_key_case ($cart['coupons'],CASE_UPPER );
            if (empty($cart['order_id'])) {
                foreach($coupon_code1 as $key=>$cv) {
                    $cart['pending_coupon'] = strtoupper($cv);
                    $cart['recalculate'] = true;
                    $cart['api']=1;
                    fn_calculate_cart_content($cart, $_SESSION['auth'], 'A', true, 'F', true);
                        
                    if($cart['coupon_error'] != ''){
                        $response['msg'][$cv] = $cart['coupon_error'];
                        $error = 1;
                    }
                    $cv = strtoupper($cv);
                    if ($error != 1) {
                        if( !(array_key_exists($cv,$cart['coupons']))){
                            $response['msg'][$cv] = fn_get_lang_var('api_coupon') . ' '. $cv . ' ' . fn_get_lang_var('api_coupon_not_applicable');
                            $error = 1;                     
                        }
                    }
                    if($error == 0){
                        $response['msg'][$cv] = fn_get_lang_var('api_coupon') . ' ' . $cv . ' ' . fn_get_lang_var('api_applied');

                    }
                    unset($cart['coupon_error']);
                }
            }
        }
        $resp['error'] = $error;
        $resp['msg'] = $response['msg'];
        return $resp;
    }


        public function apply_gc(&$cc){

            $gift_cert_code = $this->_request['gift_certi'];
            $resp['error'] = 0;
            if (!empty($gift_cert_code)) {
                if (true == fn_check_gift_certificate_code($gift_cert_code, true)) {
                    if (!isset($cc['use_gift_certificates'][$gift_cert_code])) {
                            $cc['use_gift_certificates'][$gift_cert_code] = 'Y';
                            $cc['pending_certificates'][] = $gift_cert_code;
                            $resp['msg'] = fn_get_lang_var('text_gift_cert_applied');
                    } else {
                            $resp['msg'] = fn_get_lang_var('certificate_already_used');
                    }
                } else {
                    $status = db_get_field("SELECT status FROM ?:gift_certificates WHERE gift_cert_code = ?s", $gift_cert_code);
                    if (!empty($status) && !strstr('A', $status)) {
                            $resp['error'] = 1;
                            $resp['msg'] = fn_get_lang_var('certificate_code_not_available');
                    } else {
                            $resp['error'] = 1;
                            $resp['msg'] = fn_get_lang_var('certificate_code_not_valid');
                    }
                }
            }
            return $resp;
        }


        public function apply_cb(&$cart){

            $cart['api'] = 1;
            $resp['error'] = 0;
            if ($this->_request['points_to_use'] > $cart['user_data']['points']) {
                $resp['error'] = 1;
                $resp['msg'] =fn_get_lang_var('text_points_exceed_points_on_account');
            }
            if (empty($cart['points_info']['total_price'])) {
                $cart['points_info']['total_price'] = 0;
            }
            if ($this->_request['points_to_use'] > $cart['points_info']['total_price']) {
                $resp['error'] = 1;
                $resp['msg'] = fn_get_lang_var('text_points_exceed_points_that_can_be_applied');
            }

            if ( $resp['error'] == 0 &&  !empty($this->_request['points_to_use']) && abs($this->_request['points_to_use'] == $this->_request['points_to_use'])) {
                $cart['points_info']['in_use']['points'] = $this->_request['points_to_use'];
                $resp['msg'] = "Clues bucks has been applied";
            }
            return $resp;
        }

        public function get_paymentMethod(){
            $payment_types = get_payment_types();
            $key_name = 'method';
            $i = 0;
            foreach($payment_types as $k=>$v){
				if($v['payment_type_id'] == Registry::get('config.api_emi_payment_type_id') && Registry::get('config.api_no_emi')){
					continue;
				}
                $payment_methods[$key_name.++$i]['name'] = $v['name'];
                //$payment_methods[$v['payment_type_id']]['position'] = $v['position'];
                $options = get_payment_options($v['payment_type_id']);
                foreach($options as $key => $val){
                    $payment_methods[$key_name.$i]['options'][$key]['name'] = $val['name'];
                    $payment_methods[$key_name.$i]['payment_type_id'] =$v['payment_type_id'];
                    $payment_methods[$key_name.$i]['options'][$key]['payment_option_id'] = $val['payment_option_id'];
                }                
            }
			$payment_methods['count'] = $i;

            return $payment_methods;
        }
        
        public function set_paymentMethod(&$cart){           
                        
            //default 61 for cash on delivery
            if(isset($this->_request['payment_option_id']) && $this->_request['payment_option_id'] != Registry::get('config.apiCod_payment_option_id') ){
                $payment_option_id = $this->_request['payment_option_id'];             
            }
            else{
                $payment_option_id = Registry::get('config.apiCod_payment_option_id');
            }
            $sql = "select cpop.payment_option_pgw_id, cpop.payment_option_id, cpop.payment_gateway_id, cpop.priority, cpop.status, cpt.name as payment_type, cpo.name as payment_option from clues_payment_option_pgw cpop join clues_payment_options cpo on (cpop.payment_option_id=cpo.payment_option_id)
                    join clues_payment_types cpt on (cpo.payment_type_id=cpt.payment_type_id)
                    join cscart_payments cp on (cpop.payment_gateway_id=cp.payment_id)
                    where cpop.payment_option_id='".$payment_option_id."' and cpop.status = 'A' and cp.status = 'A' order by priority asc";

            $pgw_avail = db_get_row($sql);
            $payment_id = !empty($pgw_avail['payment_gateway_id']) ? (int) $pgw_avail['payment_gateway_id'] : '-1';
            if($payment_id == -1){
                $msg = array('status' => "Failed", "msg" => "Invalid Payment Method");
                $this->response($this->json($msg), 200);                
            }            
            
            /* Redirect all the online payment to payu*/
            if(Registry::get('config.api_single_gateway')){
                if($payment_id != Registry::get('config.apiCod_payment_id') ){
					$payment_id = Registry::get('config.api_payment_gateway');                                        //  hardcoded the payment details as all the payments are mapped to Payu for now
                    $pgw_avail['payment_option_id'] = $this->_request['payment_option_id'];
                    $pgw_avail['payment_gateway_id'] = Registry::get('config.api_payment_gateway');         
                }
            }
            if($payment_id == Registry::get('config.apiCod_payment_id')){
                    $cart['cod_fee'] = Registry::get('config.cod_fee_amt');
            }else{
                    $cart['cod_fee'] = '';
            }
            $cart['emi_id'] = '';
            $cart['emi_fee'] = '';
            $cart['payment_id'] = $payment_id;
            $cart['payment_option_id'] = $payment_option_id;
            $cart['payment_details'] = $pgw_avail;
            $cart['payment_updated'] = false;
            if($payment_id == Registry::get('config.apiCod_payment_id')){
                    $cart['gifting']['gift_it'] = 'N';
            }
            if($payment_id ==  Registry::get('config.apiCod_payment_id')){
                    $cart['shipping_error']='yes';
            }else{
                    $cart['shipping_error']='no';
            }
            
            $log['payment_type'] = $cart['payment_details']['payment_type'];
            $log['payment_option'] = $cart['payment_details']['payment_option'];
            $log['gateway_id'] = $cart['payment_details']['payment_gateway_id'];
            $this->RecordLogs($log);

        }
        
        public function check_cod_conditions(&$cart,$exit){
            $max_cod_amount = (Registry::get('config.max_cod_amount')) ? Registry::get('config.max_cod_amount') : '0';
            if($max_cod_amount > 0){
               if($cart['total'] > $max_cod_amount ){
                   if($exit == TRUE){
                        $error = array('status' => "Failed", "msg" =>'Maximum COD amount exceeded');
                        $this->response($this->json($error), 200);
                   }
                   $cart['error']['cod_amount'] = 'Maximum COD amount exceeded';
               }
            }

            if($this->check_cod($cart['products']) == 'NO'){
                 if($exit == TRUE){
                        $error = array('status' => "Failed", "msg" =>'COD Not Allowed for some of the products');
                        $this->response($this->json($error), 200);
                   }
                $cart['error']['cod'] = 'COD Not Allowed for some of the products';
            }            
        }

        public function Step3(){

            $log = array();
            $log['checkout_step'] = 'step_three';
            $log['profile_id'] = $this->_request['profile_id'];
            $log['user_id'] = $this->_request['user_id'];
            $auth = $_SESSION['auth'];
            $auth['user_id'] = $this->_request['user_id'];

            //check if user has cart
            if(!isset($this->_request['profile_id']) || empty($this->_request['profile_id']) ){
            	$msg = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_profile_id'));
                $this->response($this->json($msg), 200);

            }

            $this->check_cart($this->_request['user_id']);
            $address = $this->get_profile($this->_request['profile_id']);
            if(!$this->check_profile($address)){
                $msg = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_empty_profile'));
                $this->response($this->json($msg), 200);
            }
            $cart = new user_cart();
            $cart->_request['profile_id'] = $address['profile_id'];
            $response = $cart->get_cartItems($this->_request['user_id'],'step_three');
            $response['cart']['user_data'] = fn_get_user_info($this->_request['user_id'],'true',$this->_request['profile_id']);;
            fn_fill_address($response['cart']['user_data'] , fn_get_profile_fields('O'));
            $user_cart = $response['cart'];
                        
            $payment_methods = $this->get_paymentMethod();
            
            if(isset($this->_request['payment_option_id']) || $this->_request['payment_option_id'] !=''){
                $this->set_paymentMethod($user_cart);               
            }
            
           
            //apply gc
            if(!empty($this->_request['gift_certi'] ) || isset($this->_request['gift_certi'])){
                $gc_status = $this->apply_gc($user_cart);
                if($gc_status['error'] == 1){
                    $response['error']['gc'] = $gc_status['msg'];
                    $log['gc_error'] = $this->_request['gift_certi'];
                }
                elseif(isset($gc_status['msg'])){
                    $response['msg']['gc'] = $gc_status['msg'];
                    $log['gc_success'] = $this->_request['gift_certi'];
                }
            }

            //apply clues bucks
            $cb_status = $this->apply_cb($user_cart);
            if($cb_status['error'] == 1){
                 $response['error']['cb'] = $cb_status['msg'];
                 $log['cluesbucks_error'] = $this->_request['points_to_use'];
            }
            elseif(isset($cb_status['msg'])){
                 $response['msg']['cb'] = $cb_status['msg'];
                 $log['cluesbucks_success'] = $this->_request['points_to_use'];
            }
            /* Applying Coupon Code */
            
            if(isset($this->_request['coupon_code']))            {
                $coupon_status = $this->apply_coupons($user_cart, $this->_request['coupon_code']);
                if($coupon_status['error']==1){
                    $response['error']['coupon'] = $coupon_status['msg'];
                    $log['coupon_error'] = $this->_request['coupon_code'];
                }
                elseif(isset($coupon_status['msg'])){
                    $response['msg']['coupon'] = $coupon_status['msg'];
                    $log['coupon_success'] = $this->_request['coupon_code'];
                }
            }
             /* Coupon Code Ends Here */
            
            if($user_cart['payment_option_id'] ==  Registry::get('config.apiCod_payment_option_id')){
                $this->check_cod_conditions($user_cart);                
            }

            fn_calculate_cart_content($user_cart, $auth, 'A', true, 'F', true,true);
            $response['cart'] = $user_cart;
            $response['payment_methods'] = $payment_methods;      
            
            $log['profile_id'] = $this->_request['profile_id'];
            $log['user_id'] = $this->_request['user_id'];
            $this->RecordLogs($log);

            //print_r($response);
            $this->response($this->json($response), 200);
        }


        public function Step4($place_order=0){

            $log = array();
            if($place_order == 1){
                $log['checkout_step'] = 'place_order';
            }
            else{
                $log['checkout_step'] = 'step_four';
            }
            
            
            if(!isset($this->_request['profile_id']) || empty($this->_request['profile_id']) ){
                $msg = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_profile_id'));
                $this->response($this->json($msg), 200);
            }
            
            
            $auth = $_SESSION['auth'];
            $auth['user_id'] = $this->_request['user_id'];
            $this->check_cart($this->_request['user_id']);
            $address = $this->get_profile();
            //print_r($address);
            if(!$this->check_profile($address)){
                $msg = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_empty_profile'));
                $this->response($this->json($msg), 200);
            }

            $cart = new user_cart();
            $final_cart = $cart->get_cartItems($this->_request['user_id'],'step_four');
            $user_cart = $final_cart['cart'];
            $user_cart['user_data'] = fn_get_user_info($this->_request['user_id'],'true',$this->_request['profile_id']);
            fn_fill_address($user_cart['user_data'] , fn_get_profile_fields('O'));            
            
            //choose payment method
            if(isset($this->_request['payment_option_id'])){
                $this->set_paymentMethod($user_cart);               
            }
            else{
                 $msg = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_payment_option'));
                 $this->response($this->json($msg), 200);                 
            }
            
            if (empty($user_cart['user_data']['email'])) {
                $error = array('status' => "Failed", "msg" =>fn_get_lang_var('api_param_empty_email'));
                $this->response($this->json($error), 200);
            }


            if (fn_cart_is_empty($user_cart) == true) {
                $error = array('status' => "Failed", "msg" =>fn_get_lang_var('api_empty_cart'));
                $this->response($this->json($error), 200);
            }

            if($user_cart['payment_option_id'] == Registry::get('config.apiCod_payment_option_id')){
                $this->check_cod_conditions($user_cart,true);                
            }

            //apply gift certificate
            if(!empty($this->_request['gift_certi'] ) || isset($this->_request['gift_certi'])){
                $gc_status = $this->apply_gc($user_cart);
                if($gc_status['error'] == 1){
                    $error = array('status' => "Failed", "msg" =>$gc_status['msg']);
                    $this->response($this->json($error), 200);
                }
            }

            $cb_status = $this->apply_cb($user_cart);
            if($cb_status['error'] == 1){
                 $error = array('status' => "Failed", "msg" =>$cb_status['msg']);
                 $this->response($this->json($error), 200);
            }

            /* Applying Coupon Code */

            if(isset($this->_request['coupon_code']))            {
                 $coupon_status = $this->apply_coupons($user_cart, $this->_request['coupon_code']);
                 if($coupon_status['error']==1)                 {
                    $error = array('status' => "Failed", "msg" =>$coupon_status['msg']);
                     $this->response($this->json($error), 200);
                 }
             }
            $log['profile_id'] = $this->_request['profile_id'];
            $log['user_id'] = $this->_request['user_id'];
            $this->RecordLogs($log);
             /* Coupon Code Ends Here */

            if($place_order == 1){                
                fn_calculate_cart_content($user_cart, $auth, 'A', true, 'F', true,true);
                return $user_cart;
            }
            else{
                fn_calculate_cart_content($user_cart, $auth, 'A', true, 'F', true,true);
                $final_cart['cart'] = $user_cart;
                $this->response($this->json($final_cart), 200);
            }
        }

        public function place_order(){
          
            $auth = $_SESSION['auth'];
            $auth['user_id'] = $this->_request['user_id'];

            $cart = $this->step4(1);

            $log['checkout_step'] = 'place_order';
            $log['profile_id'] = $this->_request['profile_id'];
            $log['user_id'] = $this->_request['user_id'];
            
            fn_set_hook('checkout_summary', $cart);

            fn_set_session_data('last_order_time', TIME);
            fn_save_cart_content($cart, $this->_request['user_id']);
            $cart['user_data']['verified'] = 0;
            list($order_id, $process_payment) = fn_place_order($cart, $auth);            
            //log the orders that are being placed through mobiles
            $this->log_orders($order_id,$cart,$this->_request['profile_id']);

            /* Redirect to payment gateway if payment oftion is not cash on delivery */
            
            if($cart['payment_option_id'] != Registry::get('config.apiCod_payment_option_id') && $process_payment == true){
				$log['online_order_id'] = $order_id;
				$this->RecordLogs($log);
                fn_start_payment($order_id);
                fn_order_placement_routines($order_id, array(), true, '',$cod_api=0);
            }
            else{
                fn_order_placement_routines($order_id, array(), true, '',$cod_api=1);
            }
            if($order_id){                
                $log['order_status'] = 'success';
                $log['order_id'] = $order_id;                
            }
            else{
                $log['order_status'] = 'failed';
            }
            

            if(!empty($order_id)){
                $order_info['status'] = 'Success';
                $order_info['order_id'] = $order_id;
                $code = 200;
            }
            else{
                $order_info['status'] = 'Failed';
                $order_info['order_id'] = 0;
                $code = 400;
            }
            //log the order as mobile order
            $this->RecordLogs($log);
            
            unset($order_info['payment_method']);
            unset($order_info['fields']);
            unset($order_info['google_analitycs_info']);
            $this->response($this->json($order_info), $code);
            unset($_SESSION);
            fn_flush();
        }

        public function order_summary(){

            if(empty($this->_request['order_id'])){
                $order_info['status'] = 'Success';
                $order_info['order_id'] = 0;
                $code = 200;
            }
            else{
                $order_info = fn_get_order_info($this->_request['order_id']);
                $code = 200;
            }
            $this->response($this->json($order_info), $code);
        }

        public function check_cod($products){

            $cod_allowed = 'YES';
            if(count($products) == "0"){
                $cod_allowed = 'NO';
                return  $cod_allowed;
            }
            foreach($products as $product){
                if($product['is_cod'] != 'Y'){
                        $cod_allowed = 'NO';
                }
            }
            return $cod_allowed;
        }

		public function check_product_inCart($product_id,$cart){
			
			$s_cart = $cart['products'];
			foreach($s_cart as $k => $v){
				if($v['product_id'] == $product_id)
					return $k;				
			}
			return 0;
		}

        public function combine_cart($user_id,$stored_cart){

            $cart = new user_cart();
			$matched_cart_id = array();
			foreach($this->_request['cart'] as $k=>$v){
				if( ($cart_id = $this->check_product_inCart($v['product_id'],$stored_cart)) != 0 ){					
					$matched_cart_id[$k] = $cart_id;
				}
				$cart->_request['product_id'] = $v['product_id'];
                $cart->_request['user_id'] = $user_id;
                $cart->_request['amount'] = $v['amount'];
                $cart->_request['options'] = $v['options'];
                $resp = $cart->add_cartItems($user_id, $step = 'step_four');
                if(!empty($resp['msg'])){
                   $error = array('status' => "Failed", "msg" => $resp['msg']);
                   $this->response($this->json($error), 200);
                }
            }
			fn_extract_cart_content($stored_cart, $user_id, $type = 'C', $user_type = 'R');
			foreach ($matched_cart_id as $k=>$cart_id) {
				
				if(	!isset($this->_request['cart'][$k]['amount']) || $this->_request['cart'][$k]['amount'] == null){
					$this->_request['cart'][$k]['amount'] = 1;
				}
				$stored_cart['products'][$cart_id]['amount'] = $this->_request['cart'][$k]['amount'];
			}
			fn_save_cart_content($stored_cart, $user_id);

        }

        public function log_orders($parent_order_id,$cart,$profile_id){
			$data['profile_id'] =  $profile_id;
			$data['cart'] = $cart;
			//gather child ids.			
			$child_orders = db_get_fields('select order_id from cscart_orders where parent_order_id='.$parent_order_id);
			$query = 'INSERT INTO cscart_order_data ( order_id, type, data) VALUES ('.$parent_order_id.', \'Z\' ,\''.mysql_real_escape_string(serialize($data)).'\')';
			$i = 0;
			foreach($child_orders as $k=>$v){
				$query.= ', ('.$v.', \'Z\',\''.mysql_real_escape_string(serialize($data)).'\')';
				$i++;
			}
			//echo $query;die;
			db_query($query);
			//$query = 'INSERT INTO cscart_orders ( order_id, type, data ) VALUES ( Value1, Value2 ), ( Value1, Value2 )';
			/*$_data = array (
                        'order_id' => $parent_order_id,

                        'type' => 'Z', //mobile

                        'data' => serialize($data)
                    );

            db_query("INSERT INTO ?:order_data ?e", $_data);*/

        }
        

        public function check_user(){
        // checks if the user is valid.
             return db_get_row("SELECT user_id FROM cscart_users WHERE status='A' AND user_id=".$this->_request['user_id']);

        }

        public function check_cart($user_id){
            $cart = new user_cart();
            $stored_cart = $cart->get_cartItems($user_id);
            if(count($stored_cart['cart']['products']) == 0 && count($this->_request['cart']) == 0 ){
                $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_empty_cart'));
                $this->response($this->json($error), 200);
            }
            return $stored_cart;
        }


        function json($data){
            if(is_array($data)){
                return json_encode($data);
            }
        }
    }

?>

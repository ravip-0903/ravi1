<?php
if(!defined('AREA') ) { die('Access denied'); }
if($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
	if($mode == 'email') 
        { 
            if($_REQUEST['sum_submit'])
            {
                 $now = time();
                $merchant_mid = $_REQUEST['store_mid'];
                $store_names = strip_tags($_REQUEST['store_name']);
                if(empty($merchant_mid) && empty($store_names))
                {
                   
                     fn_set_notification('E',fn_get_lang_var('Select_one_checkbox'));
                     return array(CONTROLLER_STATUS_REDIRECT, "seller_summit.email?i=".$now); 
                     
                }
                
                else if(isset($merchant_mid) && !is_numeric($merchant_mid))
                {
                    
                    fn_set_notification('E',fn_get_lang_var('numeric_value_only'));
                     return array(CONTROLLER_STATUS_REDIRECT, "seller_summit.email?i=".$now); 
                }
                else if(isset($merchant_mid) && is_numeric($merchant_mid))
                {
                    
                    $check =db_get_array("select count(*) as count,email,company_id,legal_name from cscart_companies where company_id =".$merchant_mid);
                }
               
                else if(isset($store_names))
                {
                    $check =db_get_array("select count(*) as count,email,company_id,legal_name from cscart_companies where legal_name ='".$store_names."' || company = '".$store_names."'");
                }
                
                if($check[0]['count'] > 0)
                {
                    $companyid = $check[0]['company_id'];
                    $store_name = $check[0]['legal_name'];
                    $check1 = db_get_array("select count(*) as count from clues_seller_summit where merchant_id =".$companyid);
                   //print_r($check1);die;
                    
                    if($check1[0]['count'] > 0)
                    {   
                        fn_set_notification('N',fn_get_lang_var('Thanks_for_register'));
                     return array(CONTROLLER_STATUS_REDIRECT, "seller_summit.email?i=".$now); 
                    
                    }
                    else
                    {
                         $insert = db_query("insert into clues_seller_summit (merchant_id,store_name) values($companyid,'".$store_name."')");
                        $to = $check[0]['email'];
                    //$to = 'rahul.gupta@shopclues.com';
                    $from =  Registry::get('config.summit_from');
                 $fn_mail = fn_instant_mail($to, $from, 'seller_summit/merchant_summit_subject.tpl','seller_summit/merchant_summit_body.tpl',$file=false,$heading='ShopClues.com');
                    fn_set_notification('N',fn_get_lang_var('Thanks_for_register'));
                     return array(CONTROLLER_STATUS_REDIRECT, "seller_summit.email?i=".$now); 
                    }
                    
                }
                else
                {    fn_set_notification('E',fn_get_lang_var('please_fill_correct_info'));
                     return array(CONTROLLER_STATUS_REDIRECT, "seller_summit.email?i=".$now); 
                }
            }
        }
    }   
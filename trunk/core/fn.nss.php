<?php

function get_servicability_type($product_id, $pincode,$log=false)
{
    $is_valid_pin       = false;
    $is_basic_merchant  = false;
    $is_mdf_merchant    = false;

    if(!is_numeric($pincode) OR strlen($pincode) !=6)
    {
      return INVALID_PIN;
    }
    if( Registry::get('config.enable_nss') == 0)
    {
        return BOTH_COD_PREPAID; //if feature is off, return everything is serviceable
    }

    $query = "SELECT fulfillment_id,cp.company_id,cp.is_cod,cp.is_shippable, cc.check_mdf_serviceability as check_mdf FROM cscart_products as cp INNER JOIN cscart_companies as cc ON cp.company_id = cc.company_id WHERE cp.product_id=$product_id";
    $company_data   = db_get_row($query);

    if(!empty($company_data)){
        $fulfillment_id = $company_data['fulfillment_id'];
        $company_id     = $company_data['company_id'];
        $is_cod         = $company_data['is_cod'];
        $is_shippable   = $company_data['is_shippable'];
        $check_mdf      = $company_data['check_mdf'];

        if($is_shippable != 'Y')
        {
          return NOT_SHIPPABLE;
        }
        if($fulfillment_id ==2)
        {
            $is_basic_merchant = true;
        }else if($fulfillment_id ==3)
        {
            $is_mdf_merchant = true;
            $is_cod          = 'N';
        }

        if($is_mdf_merchant && $check_mdf ==1)
        {
          $is_valid_pin = is_mdf_serviceable($company_id,$pincode); 
        }else
        {
          $is_valid_pin = is_valid_pin($pincode, $is_cod);        
        }

        if($is_cod == 'Y' && $is_valid_pin && ( !$is_basic_merchant OR is_payment_serviceable($company_id,$pincode,'cod')))
        {
            return BOTH_COD_PREPAID;

        }else if( $is_valid_pin && (!$is_basic_merchant OR is_payment_serviceable($company_id,$pincode,'prepaid')))
        {
            return ONLY_PREPAID;
        }

    }
    if($log && Registry::get('config.enable_nss_logs') == 1)
    {
        write_nss_logs($pincode,$product_id);
    }
    return NOT_SERVICABLE;
}

function is_valid_pin($pincode, $is_cod)
{
    $where_for_cod  = '';
    if($is_cod == 'Y')
    {
        $where_for_cod = "AND is_cod='Y'";
    }

    $query = "SELECT COUNT(*) as count FROM clues_carriers_service_area WHERE pincode=$pincode $where_for_cod";
    $p_data = db_get_field($query);
    if($p_data > 0)
    {
        return  true;
    }
    return false;
}

function is_payment_serviceable($company_id,$pincode,$payment_type)
{
    $cod_condition    = '';
    if($payment_type == 'cod')
        $cod_condition = "AND cs.is_cod='Y'";

     $query = "SELECT COUNT(*) as count FROM clues_carriers_service_area as cs INNER JOIN clues_advance_awb_preference as ca ON cs.carrier_id = ca.carrier_id WHERE cs.pincode = $pincode and ca.payment_type='$payment_type' and ca.company_id=$company_id $cod_condition ";
    $carrier_data = db_get_field($query);
    if($carrier_data > 0)
    {
        return true;
    }
    return false;

}

function is_mdf_serviceable($company_id,$pincode)
{
   $query = "SELECT count(*) as count FROM clues_mdf_serviceable_pin WHERE pincode=$pincode and company_id=$company_id";
   $data  = db_get_field($query);
   if($data > 0)
   {
    return true; 
   }
   return false;
}

function get_serviceable_carriers($order_id)
{   
    $carrier_ids = array();
    $payment_type ="prepaid";
    $cod_condition    = '';

    $query="SELECT fulfillment_id,co.company_id,co.s_zipcode, co.payment_id FROM cscart_orders as co INNER JOIN cscart_companies as cc ON co.company_id=cc.company_id WHERE order_id=$order_id";
    $data = db_get_row($query);
    if(!empty($data))
    {
        $company_id     = $data['company_id'];
        $fulfillment_id = $data['fulfillment_id'];
        $pincode        = $data['s_zipcode'];
        $payment_id     = $data['payment_id'];
        if($payment_id ==6)
        {
            $payment_type='cod';
            $cod_condition = "AND cs.is_cod='Y'";
        }
        if($fulfillment_id ==2)
        {
           $query = "SELECT cs.carrier_id FROM clues_carriers_service_area as cs INNER JOIN clues_advance_awb_preference as ca ON cs.carrier_id = ca.carrier_id WHERE cs.pincode = $pincode and ca.payment_type='$payment_type' and ca.company_id=$company_id $cod_condition "; 
           $carr_data = db_get_array($query);
        }else if($fulfillment_id ==1)
        {
          $query = "SELECT cs.carrier_id FROM clues_carriers_service_area as cs WHERE pincode=$pincode $cod_condition";
          $carr_data = db_get_array($query);
        }
    }
    foreach($carr_data as $key => $value)
    {
        array_push($carrier_ids,$value['carrier_id']);
    }
    
    return $carrier_ids;
}

function write_nss_logs($pincode,$product_id)
{
     $in_query="INSERT INTO clues_nss_logs(pincode,product_id,datetime) VALUES($pincode,$product_id,UNIX_TIMESTAMP())";
     $result = db_query($in_query);

}

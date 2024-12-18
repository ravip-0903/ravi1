<?php

if ( !defined('AREA') ) { die('Access denied'); }

/*********************Bazooka functions start here********************************/
/* returns false if status is not found in a bucket */

function get_bucket($order_status,$fulfillment_id)
{
      if(empty($order_status))
    {
        error_log("Parameter Missing in get_bucket ORDER STATUS==>$order_status at TIME ".date("D, j M g:i:s A",strtotime('now'))."\n",3,"/tmp/bazooka.log");
        return;
    }
 $no_buckets= Registry::get('config.no_of_buckets');
 $bazooka_arr = Registry::get('config.bazooka_arr');
 for($i=1; $i<=$no_buckets; $i++)
 {
   if(in_array($order_status,$bazooka_arr[$i][$fulfillment_id]))
    {
      return $i;
    }
 }
return false;
}

function get_last_status_bucket($order_id,$fulfillment_id)
{
if(!isset($order_id) || !isset($fulfillment_id))
{
    error_log("Parameter Missing in get_last_status_bucket Order ID==>$order_id and Fulfillment ID==>$fulfillment_id  at TIME ".date("D, j M g:i:s A",strtotime('now'))."\n",3,"/tmp/bazooka.log");
    return;
}
 $query = "SELECT status FROM cscart_orders WHERE order_id=$order_id";
 $current_status  = db_get_field($query);
 $bucket_id  = get_bucket($current_status,$fulfillment_id);
 if(!$bucket_id)
 {
   $query = "SELECT from_status FROM clues_order_history WHERE order_id=$order_id order by transition_date desc";
   $result = db_get_array($query);
   $i = 0;
    while(isset($result[$i]['from_status'])  && !$bucket_id = get_bucket($result[$i]['from_status'],$fulfillment_id))
    {
      $i++;
    }
 }
 if(!empty($bucket_id))
    {
      return $bucket_id;
    }
    return 1;

}

function get_completed_dates($current_bucket_id, $order_id, $fulfillment_id)
{
if(!isset($current_bucket_id) || !isset($order_id) || !isset($fulfillment_id))
{
    error_log("Parameter Missings in get_completed dates Currnet Bucket==>$current_bucket_id and $order_id==>$order_id and Fulfillmendt_id==.$fulfillment_id at TIME ".date("D, j M g:i:s A",strtotime('now'))."\n",3,"/tmp/bazooka.log");
    return;
}
  $cur_time=array();
	$bazooka_arr = Registry::get('config.bazooka_arr');
 	$sql="SELECT from_status, to_status,transition_date  FROM clues_order_history WHERE order_id=$order_id order by transition_date desc";
 	$result_arr = db_get_array($sql);
 	for($i=1;$i<=$current_bucket_id;$i++)
 	{
 		$cur_time[$i]=0;
 		foreach($result_arr as $pre_status)
 		{ 	 	
 	    		if(in_array($pre_status['from_status'],$bazooka_arr[$i][$fulfillment_id]) && $cur_time[$i]==0)
          {
            $cur_time[$i]=$pre_status['transition_date'];
            continue; 
          }
    }
    if($i>1 && $cur_time[$i]==0)
    {
      $cur_time[$i]=$cur_time[$i-1];
    }
    if($cur_time[$i]==0 && $i==1)
    {
      $sql="SELECT timestamp FROM cscart_orders where order_id=$order_id";
      $creation_date=db_get_field($sql);
      $cur_time[$i]=$creation_date;
    }
    if($i!=1 && $cur_time[$i]<$cur_time[$i-1])
    {
      $cur_time[$i]=$cur_time[$i-1];
    }
 	}
 	return $cur_time;
 	 	
}


function get_estimated_dates($current_bucket_id,$order_id,$grace_data,$create_date)
{ 
  if(!isset($create_date)) 
  {
      error_log("Parameter Missings in get_estimated dates Currnet Bucket==>$current_bucket_id and $order_id==>$order_id and Create Date==>.$create_date at TIME ".date("D, j M g:i:s A",strtotime('now'))."\n",3,"/tmp/bazooka.log");
      return;
  }
  $no_buckets   = Registry::get('config.no_of_buckets');
  $est_dates    = fn_calculate_edd_data($grace_data);
  $est_time     = array();
  for($i=$current_bucket_id+1;$i<=$no_buckets;$i++)
  {
    if($i==1|| $i==2)
    {
      $est_time[$i]=$create_date;
    }
    else if($i==3)
    {
      $est_time[$i]=$est_dates['picking_date'];
    }
    else if($i==4)
    {
      $est_time[$i]=$est_dates['shipping_date'];
    }
    else if($i==5)
    {
      $est_time[$i]=$est_dates['edd'];
    } 
  }
	return($est_time);
}

function get_bucket_colors($current_bucket_id,$end_type=0)
{
    if(!isset($current_bucket_id))
    {
        error_log("Parameter missing in get_bucket_colors Current Bucket==>$current_bucket_id at TIME ".date("D, j M g:i:s A",strtotime('now'))."\n",3,"/tmp/bazooka.log");
        return;
    }
  $no_buckets   = Registry::get('config.no_of_buckets');
  $colors_arr   = array();
  $colors_arr   = array_fill(1,$no_buckets,0);
  if($end_type == 0)
  {
    $colors_arr[$current_bucket_id] = 'blue';
  }else{
    $colors_arr[$current_bucket_id] = 'red'; 
  }
  if($current_bucket_id==$no_buckets)
  {
    $colors_arr[$current_bucket_id] = 'green';
  }
  for($i=1; $i<=$no_buckets;$i++)
  {
    if($i < $current_bucket_id)
    {
      $colors_arr[$i] = 'green';
    }
    if($i > $current_bucket_id)
    {
      $colors_arr[$i] = 'gray';
    }
  }
  return $colors_arr;
}

function get_hover_text($current_status, $current_bucket_id,$type='O')
{
    if(!isset($current_status) || !isset($current_bucket_id))
    {
        error_log("Parameter Missing in get_hover_text not found Current Status ==>$current_status  and Bucket ID ==>$current_bucket_id at TIME ".date("D, j M g:i:s A",strtotime('now'))."\n",3,"/tmp/bazooka.log");
    }
  $sql="SELECT customer_facing_name FROM cscart_status_descriptions WHERE status='".$current_status."' and type='".$type."'";
  $status_desc=db_get_field($sql);
  return($status_desc);
}

function get_holiday_descriptions($to_time,$from_time='0')
{
    if(!isset($to_time))
    {
        error_log("Parameter Missing in function get_holiday_descriptions To Time==>$to_time at TIME ".date("D, j M g:i:s A",strtotime('now'))."\n",3,"/tmp/bazooka.log");
    }
   $holiday_des='';
   if($from_time==0)
   {
   	$from_time=strtotime('now');
   }
   $sql="SELECT description,day from clues_courier_holiday_list WHERE day<=$to_time AND day>=$from_time";
   $result=db_get_array($sql);
   $arr_len=count($result);
   for($i=0;$i<$arr_len;$i++)
   {
       if($holiday_des=='')
       {    
           $holiday_des=date("j M ",($result[$i]['day']))." (".$result[$i]['description'].")";
       }
       else
       {
           if($i==$arr_len-1){
                $holiday_des.=" and ".date("j M ",($result[$i]['day']))." (".$result[$i]['description'].")";
           }
           else
           {
               $holiday_des.=",".date("j M ",($result[$i]['day']))." (".$result[$i]['description'].")";
           }
       } 
   }
   
   return $holiday_des;
}

function is_rma_refund_cancel_status($current_status)
{
    if(!isset($current_status))
    {
         error_log("Parameter Missing in function is_rma_refund_cancel_status Current Status==>$current_status at TIME ".date("D, j M g:i:s A",strtotime('now'))."\n",3,"/tmp/bazooka.log");
         return;
    }
  $rma_group=Registry::get('config.rma_group');
  $rto_group=Registry::get('config.rto_group');
  $cancelled_with_ev=Registry::get('config.cancelled_no_ev_group');
  $cancelled_without_ev=Registry::get('config.cancelled_with_ev_group');
  $refund=Registry::get('config.refunded_group');
  if(in_array($current_status,$rma_group) || in_array($current_status,$rto_group) )
  {
      return (Registry::get('config.bazooka_status_desc.return'));
  }
  else if(in_array($current_status,$refund))
  {
      return (Registry::get('config.bazooka_status_desc.refund'));
  }
  else if(in_array($current_status,$cancelled_with_ev) || in_array($current_status,$cancelled_without_ev))
  {
      return (Registry::get('config.bazooka_status_desc.cancel'));
  }
  else
    return 1;
}

function get_shipping_info_for_an_order($order_id)
{    
    if(empty($order_id))
    {
        error_log("Parameter Missing in get_shipping_info_for_an_order Order Id==>$order_id at TIME ".date("D, j M g:i:s A",strtotime('now'))."\n",3,"/tmp/bazooka.log");
        return false;
    }
    $str='';
  $sql="SELECT s.tracking_number,ci.carrier_name as carrier FROM `cscart_shipments` s 
                   inner join cscart_shipment_items si on si.shipment_id = s.shipment_id
                   inner join clues_carrier_lookup ci on s.carrier=ci.carrier_value
                  where si.order_id =$order_id";
  $shipping_details=db_get_array($sql);

  if(!empty($shipping_details))
  {
      $str="Order has been shipped through ".$shipping_details[0]['carrier']." with tracking number ".$shipping_details[0]['tracking_number'].".";
  }    
  return $str;
}

function fn_get_bazooka($order_id_arr, $fetch_images=0)
{
    if(Registry::get('config.bazooka_on')==0)
    {
        error_log("Bazooka is turnded off at TIME ".date("D, j M g:i:s A",strtotime('now'))."\n",3,"/tmp/bazooka.log");
        return;
    }
    if(empty($order_id_arr))
    {
          error_log("Parameter Missing in function bazooka Order Id not found at TIME ".date("D, j M g:i:s A",strtotime('now'))."\n",3,"/tmp/bazooka.log");
          return;
    }
  $start_time= round(microtime(true) * 1000);
  $no_buckets= Registry::get('config.no_of_buckets');
  $bazooka=array();
  
  $pdd_buffer_time  =  Registry::get('config.pdd_buffer_time');
  $default_image    =  Registry::get('config.internal_images_host')."/images/skin/no_image.gif";
  $image_url_for_multiple = Registry::get('config.internal_images_host')."/images/skin/img_defaultProduct.gif";
  
  foreach($order_id_arr as $order_id)
  {
        $extra_field='';
        $extra_join='';
        if($fetch_images)
        {
            $extra_field=" ,CONCAT('images/thumbnails/',floor(if(i.image_id!=0, i.image_id,il.image_id)/1000),'/160/160/',REPLACE(REPLACE(REPLACE(image_path, ' ', ''), ' ', ''),'_','')) as detailed_image_url ";
            $extra_join=" LEFT JOIN cscart_images_links il ON il.object_id = csod.product_id and il.object_type = 'product' and il.type='M'
                         LEFT JOIN cscart_images i ON il.detailed_id = i.image_id ";
        }    
        $select_data_query="SELECT cso.timestamp,csod.product_name,csod.product_id, csod.amount, cso.status,cc.fulfillment_id $extra_field
                                                  FROM  `cscart_orders` AS cso
                                                   INNER JOIN cscart_companies cc on cc.company_id = cso.company_id 
                                                  INNER JOIN cscart_order_details AS csod ON cso.order_id = csod.order_id
                                                  $extra_join
                                                  WHERE cso.order_id=$order_id group by csod.item_id";
        $data_array=db_get_array($select_data_query);
        if(empty($data_array))
        {
          error_log("DATA Array empty for ORDER ID $order_id at TIME ".date("D, j M g:i:s A",strtotime('now'))."\n",3,"/tmp/bazooka.log");
          continue;
        }
        $bazooka[$order_id]=array();
        $bazooka[$order_id]['fetch_images']=$fetch_images;
        $cur_status=$data_array[0]['status'];
        $bazooka[$order_id]['product_name']=$data_array[0]['product_name'];
        $bazooka[$order_id]['quantity']=$data_array[0]['amount'];
        $bazooka[$order_id]['image_url']=(empty($data_array[0]['detailed_image_url'])) ? $default_image : Registry::get('config.internal_images_host').'/'.$data_array[0]['detailed_image_url'];          //image url 
        $bazooka[$order_id]['m_product']=0;
        $bazooka[$order_id]['product_id']=$data_array[0]['product_id'];
        if(count($data_array)>1)
        {
                          $bazooka[$order_id]['m_product']=1;
                          $bazooka[$order_id]['image_url_for_multiple']=$image_url_for_multiple;
                          $bazooka[$order_id]['product_name']=fn_get_lang_var('multiple_product_title');
                          $bazooka[$order_id]['count_products']=count($data_array);
                          foreach($data_array as $key=>$products)
                          {
                              $bazooka[$order_id]['product'][$key]['product_name']=$products['product_name'];
                              $bazooka[$order_id]['product'][$key]['quantity']=$products['amount'];
                              $bazooka[$order_id]['product'][$key]['image_url']=(empty($products['detailed_image_url'])) ? $default_image :  Registry::get('config.internal_images_host').'/'.$products['detailed_image_url'];
                              $bazooka[$order_id]['product'][$key]['product_id']=$products['product_id'];
                          }

        }
        $f_id=$data_array[0]['fulfillment_id'];
        $create_date=$data_array[0]['timestamp'];
        if(empty($f_id))
        {
             error_log("Fulfillment ID was not found for order $order_id at TIME ".date("D, j M g:i:s A",strtotime('now'))."\n",3,"/tmp/bazooka.log");
             //behaviour;
        }
        $cur_bucket=get_last_status_bucket($order_id,$f_id);
        $grace_data=fn_get_grace_data($order_id);
        if(empty($grace_data))
        {
                 error_log("Grace Data was not found for order $order_id at TIME ".date("D, j M g:i:s A",strtotime('now'))."\n",3,"/tmp/bazooka.log");
        }
        $bazooka[$order_id]['color_bucket']= get_bucket_colors($cur_bucket);
        $bazooka[$order_id]['dates']=(get_completed_dates($cur_bucket,$order_id,$f_id) + get_estimated_dates($cur_bucket,$order_id,$grace_data,$create_date));
        $temp_dates=$bazooka[$order_id]['dates'];
        $now=  strtotime('now');
        $bazooka[$order_id]['hover_text']=get_hover_text($cur_status,$cur_bucket);
        $pdd_max=$bazooka[$order_id]['dates'][$no_buckets]+$pdd_buffer_time+fn_get_holidays($pdd_buffer_time,$bazooka[$order_id]['dates'][$no_buckets]);
        $bazooka[$order_id]['holiday_desc']=get_holiday_descriptions($pdd_max);
        $assign_status=true;
        
        if( empty($grace_data['promised_delivery_date']))
        {
          $assign_status = false;
        }

        for($i=$cur_bucket;$i<=$no_buckets;$i++)
        {
          if($i!=1 && strtotime(date("Y-m-d",$temp_dates[$i])) < strtotime((date("Y-m-d",$temp_dates[$i-1]))))
           {
               $bazooka[$order_id]['dates'][$i]='';
               $assign_status=false;
           }
        }
        for($i=$cur_bucket+1;$i<=$no_buckets;$i++)
        {
            if(strtotime(date("Y-m-d",$temp_dates[$i]))<strtotime(date("Y-m-d",$now)))
            {
                $bazooka[$order_id]['dates'][$i]='';
                $assign_status=false;
                for($j=$i;$j<=$no_buckets;$j++)
                {
                    $bazooka[$order_id]['dates'][$j]='';
                }
            }
        }
        if($assign_status)
        {
          if($grace_data['promised_delivery_date']>=end($bazooka[$order_id]['dates']))//check not equal condition,compare on date
          {
            $bazooka[$order_id]['status']= Registry::get('config.bazooka_status_desc.on_schedule');
          }
          else
          {
            $bazooka[$order_id]['status']=Registry::get('config.bazooka_status_desc.delayed');
          }
        }
        else
        {
          $bazooka[$order_id]['status']   = '';
        }
       foreach($bazooka[$order_id]['dates'] as $key=>$items)
       {
                if($bazooka[$order_id]['dates'][$key]=='')
                {
                    $bazooka[$order_id]['dates'][$key]='';
                }
                else
                {
                    $bazooka[$order_id]['dates'][$key]=date("D, j M ",($items));
                }
       }
       $complete_msg_for_bazooka=Registry::get('config.complete_msg_for_bazooka');
       $complete_msg_for_bazooka=str_replace("<Shipping Date>",$bazooka[$order_id]['dates'][4], $complete_msg_for_bazooka);
       $complete_msg_for_bazooka=str_replace("<EDD1>",$bazooka[$order_id]['dates'][5], $complete_msg_for_bazooka);
       $complete_msg_for_bazooka=str_replace("<EDD2>",date("D, j M",($pdd_max)), $complete_msg_for_bazooka);
       $bazooka[$order_id]['complete_description']=$complete_msg_for_bazooka;
       if($bazooka[$order_id]['holiday_desc']!=''){
           $bazooka[$order_id]['complete_description'].=" Estimated Delivery Date includes holidays on ".$bazooka[$order_id]['holiday_desc'];
       } 
       if($cur_bucket==$no_buckets)
       {
                      $bazooka[$order_id]['status']=Registry::get('config.bazooka_status_desc.complete');
       }
       if(is_rma_refund_cancel_status($cur_status)!=1)
       {
           //override
           $bazooka[$order_id]['color_bucket'][$cur_bucket]='red';
           $bazooka[$order_id]['holiday_desc']='';
           $bazooka[$order_id]['status']=is_rma_refund_cancel_status($cur_status);
           for($i=$cur_bucket+1;$i<=$no_buckets;$i++)
           {
               $bazooka[$order_id]['dates'][$i]='';
           }
       }
       if(isset($bazooka[$order_id]['color_bucket'][4])&&$bazooka[$order_id]['color_bucket'][4]=='blue')
       {
           $bazooka[$order_id]['ship_info']=get_shipping_info_for_an_order($order_id);
       }
       foreach($temp_dates as $key=>$items)
       {
           $temp_dates[$key]=date("D, j M g:i:s A",($items));
       }
       error_log("\nORDER ID==>".$order_id." FULFILLMENT ID==>$f_id CURRENT_TIME==>".date("D, j M g:i:s A",strtotime('now'))." DATES ==>".json_encode($temp_dates),3,"/tmp/bazooka.log");

  }
 error_log("\nBAZOOKA START\n".json_encode($bazooka)."\nexec_time==>".(round(microtime(true) * 1000)-$start_time)."ms\nBAZOOKA END\n",3,"/tmp/bazooka.log");
  Registry::get('view')->assign(bazooka_arr, $bazooka);
}



<?php
/***************************************************************************
*                                                                          *
*    Copyright (c) 2004 Simbirsk Technologies Ltd. All rights reserved.    *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
*                                                                          *
****************************************************************************
* PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
* "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
****************************************************************************/


//
// $Id: func.php 11577 2011-01-12 12:54:10Z klerik $
//

if ( !defined('AREA') ) { die('Access denied'); }







//
// Solr add/update functions
//

function fn_get_suggestion($term=''){
	
    if($term=='') $term = $_REQUEST['q'];

	if(!empty($term)){
		$client = new SolrClient(Registry::get('config.solr_url'));
		$query = new SolrQuery();

		$query->setQuery($term);
		$query->setParam('spellcheck','true');
		$query->setParam('spellcheck.count','5');
		$query->setParam('spellcheck.collate','true');
		//echo $query; die;
		
		try {
			$solrResult = $client->query($query)->getResponse();
			$prd = $solrResult->spellcheck->suggestions;
			if(is_object($prd)) {

				return $prd->collation;
			}
		} catch (Exception $e) {
			//error_log("fn_get_suggestion=".$e->getMessage(), 1, "vinay.gupta@shopclues.com");
			return false;
		}
 	}

}

function fn_get_solr_products_brand($params){


		$client = new SolrClient(Registry::get('config.solr_url'));
		$query = new SolrQuery();

		$query->setQuery('*.*');
		//$query->addField('brand brand_url');
		$query->setParam('group.format','simple');
		$query->setParam('group.main','true');
		$query->setParam('group','true');
		$query->setParam('group.field','brand');
		$query->setRows('-1');
		//echo $query; die;
		
		try {

if (defined('PROFILER')) {
  Profiler::set_query("Solr fn_get_solr_products_brand - Line 72: ".$query, "");
}            
			$solrResult = $client->query($query)->getResponse();
			$brandArr = $solrResult->response->docs;
			if(!empty($brandArr)) {
				foreach($brandArr as $key => $opt) {
			   		$firstChar = strtoupper(substr($opt['brand'], 0, 1));
			   		
			   		if($opt['brand']!='' && $opt['brand_url']!='')
			   			$rootArr[$firstChar][] = array('range_name'=>$opt['brand'],'url'=>$opt['brand_url'], 'index'=>$firstChar); 
				}
			}
			ksort($rootArr);
			return $rootArr;
		} catch (Exception $e) {
			error_log("fn_get_solr_products_brand=".$e->getMessage(), 1, "vinay.gupta@shopclues.com");
			return false;
		}
}

function fn_get_products_solr($params) {
     
    
    /*** added for solr query caching ***/
    $key = json_encode($params);
    $key = md5($key);
    $memcache = $GLOBALS['memcache'];
    if (check_memcache_clear_condition()) {
        // Unset the memcache store if the request variable 'clean' is set 
        // and a user is logged in.
        $memcache->delete($key);
    }

    if(Registry::get('config.memcache') && $GLOBALS['memcache_status'] && ($cached_value = $memcache->get($key)) !== false) {
        return $cached_value;
    } else {

        if(!empty($params['item_ids'])) {

            $client = new SolrClient(Registry::get('config.solr_url'));
            $query = new SolrQuery();
            $sort_by = $params['sort_by'];
            $sort_order = $params['sort_order'];
            $item_ids = $params['item_ids'];
            $arr_count = explode(',',$item_ids);
            $term = str_replace(",", ' OR product_id:', $item_ids);
            $query->setQuery('product_id:'.$term);
            $query->setParam('wt', 'xml');

            if(count($arr_count) > 0) $query->setRows(count($arr_count));

            if(!empty($sort_by)) { 
                if(strtolower($sort_order)=="asc") {
                    $query->addSortField("product_amount_available");
                    $query->addSortField($sort_by, SolrQuery::ORDER_ASC);
                } else {
                    $query->addSortField("product_amount_available");
                    $query->addSortField($sort_by);
                }     
            }

            try {
                     if (defined('PROFILER')) {
                        Profiler::set_query("Solr Query:"."/solr/select?".$query, "");
                    }
                    $solrResult = $client->query($query)->getResponse();
                    $prd = $solrResult->response->docs;
                    $products = array();
                    if(!empty($prd)) {
                            foreach($prd as $pr=>$prod) {
                                    $products[] = (array) $prod;
                            }                            
                    }
                    if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
                                $memcache->set($key, $products, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));
                    return $products;
                  
            } catch (Exception $e) {
                    error_log("fn_get_products_solr=".$e->getMessage(), 1, "vinay.gupta@shopclues.com");
                    return false;
            }
        }
    }
}

function fn_assign_pricekey($value, $p) {

	$fq = Registry::get('config.fn_assign_pricekey');
	
    if($p == 'key')	return $fq[$value];
    if($p == 'val')	return array_search('price:'.$value, $fq);
}

function fn_showprice($key) {

	$fq = Registry::get('config.fn_showprice');
    
	return $fq[$key];
}


function fn_assign_discountkey($value, $p) {

    $fq = Registry::get('config.fn_assign_discountkey');
    
    if($p == 'key') return $fq[$value];
    if($p == 'val') return array_search('discount_percentage:'.$value, $fq);
}

function fn_showdiscount($key) {

    $fq = Registry::get('config.fn_showdiscount');
    
    return $fq[$key];
}

function fn_solr_connect() {
	
	$client = new SolrClient(Registry::get('config.solr_url'));
    $query = new SolrQuery();

    return array($client,$query);
}

function cal_products_per_page() {   
    $dropdown_count = Registry::get('config.dropdown_count');
    $products_per_page = Registry::get('settings.Appearance.products_per_page');
    $dropdown_per_page = $products_per_page + ($dropdown_count * 4);
    $arr = array();
    //if($products_per_page < $dropdown_per_page) {
        $count = 1;
        for($i = $products_per_page; $i <= $dropdown_per_page; $i++) {
            if ($count%$dropdown_count == 1)
            {  
                  $arr[] = $i;
            }
        $count++;
        }
    //}
    //print_r($arr);
    return $arr;
}

function fn_solr_pagination($solrResult, $query) {

	$total_items = $solrResult->response->numFound;

	$offset = "0";
        if(!empty($_REQUEST['pp'])) {
            $products_per_page = $_REQUEST['pp'];
        } else {
            $products_per_page = Registry::get('settings.Appearance.products_per_page');
        }
	$limit = $items_per_page = $products_per_page;

        //By Ajay items_per_page manage by config for mobile and web
	if(Registry::get('config.products_limit_per_page') && Registry::get('config.products_limit_per_page') != 0 ) {
		$limit = $items_per_page = Registry::get('config.products_limit_per_page');
	}
       // End by Ajay

	if(ANDROID_API == 'TRUE') {
		$limit = $items_per_page = Registry::get('config.api_listing_per_page');//'4'
	}
	$l = fn_paginate($_REQUEST['page'], $total_items, $items_per_page);
	$larr = explode(',', trim($l));
	$offset = (int) trim(str_replace('LIMIT', '', $larr[0]));
	$limit = intval($offset + $items_per_page);

 	$query->setStart($offset);
	$query->setRows($items_per_page);

	return array($query, $total_items, $offset, $items_per_page);
}

function fn_search_boosting($term){
    
    if(!empty($term)) {
        
        $term = trim($term);
        $boostArr = array();

        $key = md5($term);
        $memcache = $GLOBALS['memcache'];
        if (check_memcache_clear_condition()) {
            // Unset the memcache store if the request variable 'clean' is set 
            // and a user is logged in.
            $memcache->delete($key);
        }

      if(Registry::get('config.memcache') && $GLOBALS['memcache_status'] && ($boostArr = $memcache->get($key)) !== false) {
           //echo 'memcached';
           $boostArr = unserialize($boostArr);
        } else {
            $sql = "SELECT bq,fq FROM `clues_search_boosting` WHERE keyword = '".$term."' ";
            $searchArr = db_get_array($sql);
            //print_r($searchArr); die;
            
            foreach($searchArr as $valArr) {
                foreach($valArr as $fkey=>$val) {
                    if(!empty($val)) {
                        $str = strtolower($valArr[$fkey]);                        
                        if(strstr($str, ";")) {
                            $valid = str_replace(';',' ',$str);
                            if(array_key_exists($fkey, $boostArr)) {
                                $boostArr[$fkey] .= ' '.$valid; 
                            } else {
                                $boostArr[$fkey] = $valid;                        
                            }
                        } else {
                            if(array_key_exists($fkey, $boostArr)) {
                                $boostArr[$fkey] .= ' '.$str; 
                            } else {
                                $boostArr[$fkey] = $str;                        
                            }
                        }
                    }
                }                
            }
            //print_r($boostArr); die;

            if(Registry::get('config.memcache') && $GLOBALS['memcache_status']) {
                $status = $memcache->set($key, serialize($boostArr), MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));
                if(!$status){
                    $memcache->delete($key);
                }
            } else {
              //echo "not set"; 
            }
        }
        return $boostArr;
    }
    
}

function encrypt_decrypt($action, $string) {
    $output = false;

    $encrypt_method = "AES-128-CBC"; //"AES-256-CBC"; // openssl enc -aes-128-cbc -d -in file.encrypted -base64 -pass pass:123
    $secret_key = 'sckey';
    $secret_iv = 'ivsckey';

    // hash
    $key = hash('sha256', $secret_key);
    
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'decrypt' ) {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}

function fn_track_search_user($keyword,$arr) {

    //print_r($arr);//die;
    if(!empty($keyword) && Registry::get('config.fn_track_search_user')) {
        //unset($_SESSION['search']);
        $_SESSION['last_searched'] = $keyword;
        
        $_SESSION['search'][$keyword]['search_source'] = Registry::get('config.search_source');
        $_SESSION['search'][$keyword]['search_time'] = time();
        $_SESSION['search'][$keyword]['sess_id'] =Session::get_id(); 

//echo '<pre>'; print_r($_COOKIE); //die;

        $user_id = $_SESSION['auth']['user_id'];
        if(empty($user_id)) {
            $user_id = $_COOKIE['sidk'];
        }
        $_SESSION['search'][$keyword]['user_id'] = $user_id;
        foreach($arr as $key=>$val) {

            if($key=="product_clicked") {
                $viewedArr = $_SESSION['search'][$keyword]['product_rendered'];
               // echo "<pre>";print_r($viewedArr);
                $click_array=array();
                foreach($viewedArr as $page=>$pageArr) {
                    if(in_array($val,$pageArr)) {
                        $prd_num = array_search($val, $pageArr);
                        $_SESSION['search'][$keyword][$key]['product_id']=$val;
                        $_SESSION['search'][$keyword][$key]['page'] = $page;
                        $_SESSION['search'][$keyword][$key]['prd_num'] =$prd_num;
                        
                    }
                }
            } else {
                 $_SESSION['search'][$keyword][$key] = $val;
            }
        }         
    }
} 


function fn_call_track($keyword) {
        
        //fn_save_search_user($keyword,$_SESSION['search']);  

        $arr = $_SESSION['search'][$keyword];
        //echo '<pre>'; print_r($arr);
        
        $arr1 = serialize($arr);
        $val = encrypt_decrypt('encrypt', $arr1);
        
        // create cookie with createcookie function and retrive cookie in jquery.
        setcookie("tsk", $val , $expire, "/",$domain);

}

function fn_save_search_user($keyword,$arr){

   //echo "<pre>";print_r($arr);die;
    foreach ($arr[$keyword]['product_viewed'] as $key => $val) {
        foreach ($val as $key => $value) {
            $sql = "insert into clues_track_search_user (keyword,session_id,user_id,product_id,search_source,time_of_search) values ('".$keyword."','".$arr[$keyword]['sess_id']."','".$arr[$keyword]['user_id']."','".$value."','".$arr[$keyword]['search_source']."','".$arr[$keyword]['search_time']."')";
            db_query($sql);           
        
        }
    }

        $sql_query="UPDATE clues_track_search_user SET page = '".$arr[$keyword]['product_clicked_details']['page']."', product_num = '".$arr[$keyword]['product_clicked_details']['prd_num']."' WHERE product_id=".$arr[$keyword]['product_clicked_details']['product_id'];
        db_query($sql_query);
    
     return true;
}

function fn_solr_search_field($query, $term) {
 $search_terms_solr=Registry::get('config.search_terms_solr');
	if(!empty($term)) {   
	        $mm = Registry::get('config.min_match');     	
	        $query->setQuery(addslashes($term));
            
            if(Registry::get('config.brand_search')) {
                $brand_id = fn_solr_search_brand($term);
                if(!empty($brand_id)) {
                    $brand_string = 'brand_id:'.$brand_id;
                    $query->addFilterQuery($brand_string);
                } else {
                    if(Registry::get('config.cat_search')) {
                         $cat_id = fn_solr_search_cat($term);
                         if(!empty($cat_id)) {                    
                            $cat_string = 'category_ids:*/'. $cat_id .' OR category_ids:'. $cat_id .' OR id_path:'. $cat_id .' OR id_path:'. $cat_id;
                            $query->addFilterQuery($cat_string);
                        }
                    }
                    $query->setParam('defType','dismax');
                    //$query->setParam('qf','product^5.0 meta_keywords^2.0 search_words^3.0 page_title^4.0');
                    
                    $query->setParam('qf',$search_terms_solr);
                    $query->setParam('mm', $mm); 
                }
            } else {
                $query->setParam('defType','dismax');
                //$query->setParam('qf','product^5.0 meta_keywords^2.0 search_words^3.0 page_title^4.0');
                $query->setParam('qf',$search_terms_solr);
                $query->setParam('mm', $mm); 
            }

            $query->setParam('fl', '* [elevated]'); 

            $query->setParam('bq','product_amount_available:1^10.0'); // show qty 0 products at last

            $bq = 'product_amount_available:1^10';
            if(Registry::get('config.category_boost')) $boostArr = fn_search_boosting($term);
            
            if(!empty($boostArr)) {                            
                $set = false;
                foreach($boostArr as $key=>$val) {
                    if(!$set && $key=='bq') { $val = $bq.' '.$val; $set= true; }
                    $query->setParam($key,$val);
                }
            } else {
                $query->setParam('bq',$bq);
            }
            
	} else {
		$query->setQuery('*.*');
	}

	return $query;
}

function fn_solr_search_brand($term) {
    
    $brand_id = '';
    if(!empty($term)) {   

        list($bclient, $bquery) = fn_solr_connect();
        
        //$term = preg_replace('/\s+/', '+', addslashes(trim($term))); // removes spaces with +
        
        $bquery->setQuery('brand_keyword:"'.$term.'"');
        $bquery->setParam('fl','brand_id');
        $bquery->setRows(1);

        //echo $bquery."<br>";
        $bResult = $bclient->query($bquery)->getResponse();
        $bcount = $bResult->response->numFound;
        if($bcount > 0) {
            $brandResp = $bResult->response->docs;
            //echo '<pre>'; print_r($brandResp[0]->brand_id); echo '</pre>'; die;
            $brand_id = $brandResp[0]->brand_id;
        }

    }

    return $brand_id;
}

function fn_solr_search_cat($term) {
    
    $cat_id= '';
    if(!empty($term)) {   

        list($cclient, $cquery) = fn_solr_connect();
        
        //$term = preg_replace('/\s+/', '+', addslashes(trim($term))); // removes spaces with +
        
        $cquery->setQuery('metacategory_keyword:"'.$term.'"');
        $cquery->setParam('fl','metacategory_id');
        $cquery->setRows(1);

        //echo $bquery."<br>";
        $cResult = $cclient->query($cquery)->getResponse();
        $ccount = $cResult->response->numFound;
        if($ccount > 0) {
            $catResp = $cResult->response->docs;
             //echo '<pre>'; print_r($catResp[0]->metacategory_id); echo '</pre>'; die;
            $cat_id = $catResp[0]->metacategory_id;
            
        }

    }

    return $cat_id;
}

function fn_check_elevated($product,$search_keyword,$position,$page) {
   /*if($product['[elevated]']==1 && $page == ''){
    fn_adword_view($product['product_id'],'I',$search_keyword,$position); 
   }*/
   if(!empty($search_keyword) && empty($product) && empty($page) && empty($position))
   {
		return addslashes($search_keyword);
   }
    return $product['[elevated]'];
}

function fn_remove_zero_cat($rootArr) {
        $sumArray = array();
        //print_r($rootArr['category']);
        foreach ($rootArr['category'] as $k=>$subArray) {
          foreach ($subArray as $id=>$value) {
            $sumArray[$k]+=$value['count'];
          }
          if($sumArray[$k] == 0) unset($rootArr['category'][$k]);
        }
        return $rootArr['category'];
}

function fn_promo_filter($params) {
    $promoArr = explode(':',$params['promofilter']);
    $filterArr = array('fq','df','br','product_amount_available');
    foreach($filterArr as $key=>$val) {
        //echo '<br>'.$key.' = '.$val;
        if(!in_array($val, $promoArr)) {
            unset($params[$val]);
        }
    }
    return $params;
}

function fn_retain_url($params) {
    $promoArr = explode(':',$params['promofilter']);
    $filterArr = array('br','fq','df','product_amount_available');

    $result = array_diff($filterArr, $promoArr);
    foreach($result as $val) {
        $arr[] = $val."%5B%5D";
    }

    $url = ':"'.implode('":"', $arr).'"';
    return $url;
}

function fn_remove_promo_filter($params) {
    //echo 'calling';
    
    //$promoArr = explode(':',$params['promofilter']);
    //$arr = array('br','fq','df','product_amount_available','fsrc');
    $arr = array('br','fq','fsrc');
    //echo '<pre>';print_r($arr);die;
    foreach($params as $key=>$val) {
        if(in_array($key, $arr)) {
            //echo '<br>'.$key." exists";
            unset($params[$key]);
        } else {
            if(is_array($val)) {
                $params[$key] = $val[0];
            } else {
                $params[$key] = $val;
            }
        }
    }   
    //print_r($params); die;

    $output = implode('&', array_map(function ($v, $k) { return $k . '=' . $v; }, $params, array_keys($params)));
    return 'index.php?'.$output;
}

function fn_solr_extra_params($query, $xarr) {
	
	$solrarr = Registry::get('config.solr_function');
	
	foreach($xarr as $key => $val) {
		$param = $field = $value = '';
	       if(strstr($val, ".")) {
			list($param,$field) = explode('.',$val);
                        
                }
                    
		if(key_exists($param, $solrarr)) {
                            if($param =='sort')
                            {
                                list($sort_field,$sort_order)=explode(':',$field);
                        
                                    if($sort_order =='asc'){
                                    $query ->addSortField($sort_field, SolrQuery::ORDER_ASC);
                                    }
                                    else{
                                        $query ->addSortField($sort_field);
                                    }
                            }
                            else{
                                
                                $solrfunc = $solrarr[$param];
                            }
		} else {
			$solrfunc = 'setParam';		
			if(preg_match('/[\W]/', $field)) {
				preg_match('/[\W]/', $field, $matches);
                                 $query->setParam('defType','edismax');
				list($field, $value) = explode($matches[0], $field);
				
			}
		}
		try {
			// calling dynamic function of solr with object and its parameters
                    if(!empty($value)) {			
				
				if (defined('PROFILER')) {
					$extra = '<br>$query->'.$solrfunc.'('.$field.','.$value.');';
					Profiler::set_query("Solr Extra_FieldValue - 880: ".$extra. "");
				}	
				call_user_func(array($query, $solrfunc), $field, $value);
			} else {
				
				if (defined('PROFILER')) {
					$extra = '<br>$query->'.$solrfunc.'('.$field.');';
					Profiler::set_query("Solr Extra_FieldOnly - 892: ".$extra. "");
				}
                                 
				call_user_func(array($query, $solrfunc), $field);
			}
		} catch(Exception $e) {
			echo $e->getMessage();
		}
		
	}
	
	if (defined('PROFILER')) {
	  Profiler::set_query("Solr ExtraQuery - 891: ".$query. "");
	}	
	return $query;
}
//lijo1
function fn_assign_filtertoitem($params) {

    //echo "<pre>";print_r($params);die;
$master_switch=Registry::get('config.zettata_master_switch');
    //if in url cid is empty then set it to 0 for search
        if(isset($params['cid'])){
            if(trim($params['cid'])==''){
                $params['cid']=0;
            }
        }
    $term = '';
    if(!empty($params['q']))
		$term = strtolower(addslashes($params['q']));
        $params['q']=$term;
        
        
    if(empty($params['company_id']) && $params['retain'] != 1 && $master_switch){
       
        if($params['z'] ==''){
            
            $z=fn_search_usage();
            $params['z']=$z;
          }else{
            
                $z=$params['z'];

              }
    }else{
        
        $z=0;
        $params['z']=0;
    }
        
    
//lijo3 
    $switch_val =0;
    if(ANDROID_API == 'TRUE') {
        $switch_val = 4;
    } else {
        $switch_val=fn_switch_condition($params);
    }

    if(Registry::get('config.solr_scin_search') && is_numeric($params['q'])) $switch_val = 4;

   //echo "Switch hey:- ".($switch_val)."<br>";
    if($switch_val == 1){
        //echo " Map zettata_to_solr<br>";
        // echo "fac1";print_r($params['fac']);echo "<br>";
        // echo "Z :- ".$params['z']."<br>";
        // echo "MS :- ".($master_switch)."<br>";
        // echo "Switch :- ".($switch_val)."<br>";
        
        if (defined('PROFILER')) {
          Profiler::set_query("Zettata fn_assign_filtertoitem - Switch-val-1 <br> Map Zettata to Solr<br> MS :-".$master_switch."<br>Z :-".$params['z'], "");
        } 
        $notification=array(1=>"br",2=>"fq",3=>"df",4=>"product_amount_available");

        $params=fn_map_zettata_to_solr($params);
        $not=array();
        foreach ($notification as $key => $value) {
           // echo $value;

            if (array_key_exists($value,$params)) {
                 $not[]="exist";
            }else{
                $not[]="not exist";
            }
        }
         if(in_array("exist",$not))
                     fn_set_notification('E',fn_get_lang_var('notice'), fn_get_lang_var('zettata_mapping_error'));
                
       
    }else{

        if($switch_val==2){

         //echo " Map solr_to_zettata<br>";
        // echo "fac2";print_r($params['fac']);echo "<br>";
         // echo "Z :- ".$params['z']."<br>";
         // echo "MS :- ".($master_switch)."<br>";
         // echo "Switch :- ".($switch_val)."<br>";
        if (defined('PROFILER')) {
          Profiler::set_query("Zettata fn_assign_filtertoitem - Switch-val-2 <br> Map Solr to Zettata<br> MS :- ".$master_switch."<br>Z :- ".$params['z'], "");
        } 




        $params=fn_map_solr_to_zettata($params);
        if(!empty($params['fac'])){
            fn_set_notification('E',fn_get_lang_var('notice'), fn_get_lang_var('zettata_mapping_error'));        
       
         }
        }
    }



	if($master_switch && ($switch_val ==2  || $switch_val == 3)  ){

        //echo "<br>zettata";
        if (defined('PROFILER')) {
          Profiler::set_query("Zettata fn_assign_filtertoitem - Zettata Only <br> Switch_val :- ".$switch_val."<br>MS :- ".$master_switch."<br>Z :- ".$params['z'], "");
        }
        $rootArr=show_zettata_result($params);
        $rootArr['search_usage']=$z; 
        
        //echo "<pre>";print_r($rootArr); die;
        return $rootArr;
   
    }else{
        //echo "<br>Solr";
        if (defined('PROFILER')) {
          Profiler::set_query("Zettata fn_assign_filtertoitem - Solr Only <br> Switch_val :- ".$switch_val."<br>MS :- ".$master_switch."<br>Z :- ".$params['z'], "");
        }
        list($client, $query) = fn_solr_connect();
        
        $term = preg_replace('/\p{C}+/u', "", $term);
        $query = fn_solr_search_field($query, $term);
 
     //echo $query;

        $query->setStart(0);
        $query->setRows(32);

        $query->setParam('spellcheck.build', 'true');
        $query->setParam('spellcheck.OnlyMorePopular', 'true');

        $query->setFacet(true);
        //$query->addFacetField('category_id');
        //$query->addFacetField('category');
        $query->addFacetField('show_metacategory');
        $query->addFacetField('product_amount_available');
        $query->setStats(true);
        $query->addStatsField('sort_price');
            if(!empty($params['sp'])){
                $price=  explode(",", $params['sp']);
                $pr="sort_price:[".$price[0]." TO ".$price[1]."]";
                $query->addFilterQuery($pr);
            }
            
	if(isset($params['company_id']) && ($params['company_id'] != '0')){
		$query->addFilterQuery("company_id:".$params['company_id']);
	}
    
    if(!empty($params['metacategory_status'])) {
        $status = $params['metacategory_status'];
    } else {
         $status = 'A';
    }
    $query->addFilterQuery("metacategory_status:".$status);

if (!empty($params['retain'])) {
    
            if(!empty($params['promofilter'])) {
                $filterArr = explode(':',$params['promofilter']);
                //print_r($filterArr);
                $params = fn_promo_filter($params);
            }

            //if(isset($params['fq']) && isset($params['first'])){
            if(isset($params['fq']) && in_array('fq',$filterArr)){
                
            $fq='';
            foreach($params['fq'] as $k=>$v){
                    if($k > 0){
                            $fq .= ' OR ';
                    }
                    $fq .= fn_assign_pricekey($v,'key');
            } 
            $query->addFilterQuery($fq);
        }

        //if(isset($params['df']) && isset($params['first'])){
        if(isset($params['df']) && in_array('df',$filterArr)){            
            $df='';
            foreach($params['df'] as $k=>$v){
                    if($k > 0){
                            $df .= ' OR ';
                    }
                    $df .= fn_assign_discountkey($v,'key');
            } 
            $query->addFilterQuery($df);
        }

        //if(isset($params['br']) && isset($params['first'])){
        if(isset($params['br']) && in_array('br',$filterArr)){
                $fq2='';
                foreach($params['br'] as $kb=>$vb){
                        if($kb > 0){
                                $fq2 .= ' OR ';
                        }
                        $fq2 .= 'brand_id:'.$vb;
                }
                $query->addFilterQuery($fq2);
        }

        //if(!empty($params['product_amount_available']) && isset($params['first'])){
        if(!empty($params['product_amount_available']) && in_array('product_amount_available',$filterArr)){
                $dp2='';
                foreach($params['product_amount_available'] as $kb=>$vb){
                        if($dp2 > 0){
                                $dp2 .= ' OR ';
                        }
                        $dp2 .= 'product_amount_available:'.$vb;
                }
                $query->addFilterQuery($dp2);
        }
}

	$rootArr = array();

	//if(!empty($term) || !empty($params['company_id'])) {
		try{
		   
if (defined('PROFILER')) {
  Profiler::set_query("Solr fn_assign_filtertoitem - Line 250: ".$query, "");
}           
		   //$solrResult2 = $client->query($query)->getResponse();
           $solrResult2 = fn_get_solr_response($client, $query);
		   	//echo "<pre>";print_r($solrResult2);
		   	$obj = $solrResult2->spellcheck->suggestions;			 
			//print_r($obj);
            if(is_object($obj)) {
                foreach ($obj as $key => $objects) {
                   $suggest = $obj->$key->suggestion[0];
                }
				//$rootArr['suggestion'] = $obj->$term->suggestion[0];
                $rootArr['suggestion'] = $suggest;
			}

		}catch(Exception $e){
            error_log("fn_assign_filtertoitem - Line No 260: ".$e->getMessage(), 1, "vinay.gupta@shopclues.com");
            return false;            
		}
 $cat_run = false;
 if(!empty($_REQUEST['search_performed'])) {
	   $facetfields = $solrResult2->facet_counts->facet_fields->show_metacategory;
          
       foreach($facetfields as $key => $val) { 
       		if(strpos($key, '_')) {
                list($cat_id, $cat_name, $meta_cat_name) = explode('_',$key);
                $rootArr['category'][$meta_cat_name][$cat_id] = array('cat_id'=>$cat_id,'cat_name'=>$cat_name,'count'=>$val);
          	} 
       }
       $cat_run = true;
        // remove zero categories
        $rootArr['category'] = fn_remove_zero_cat($rootArr);
 }

        /*for showing  category filters  in search*/
        $category_facets = array();
        //if(!empty($params['cid'])) {            
            $category_facets = fn_get_category_facets($params['cid']);        
            //echo "<pre>";
            //print_r($category_facets);
            if(!empty($category_facets))
                foreach($category_facets as $key => $val) {
                    if(!empty($params[$val]) && is_array($params[$val])) {
                        $fq2='';
                        foreach($params[$val] as $kb=>$vb){
                            if($kb > 0){
                                $fq2 .= ' OR ';
                            }
                            if($val=="show_merchant") { 
                                $fq2 .= 'company_id:'.$vb; 
                                
                            } else if($val=="show_market") { 
                                $fq2 .= 'market_id:'.$vb; 
                                
                            } else {
                                $option = explode('_', $val);
                                if($option[0]=='o') {
                                    $fq2 .= 'combination_inv:*'.$vb.'*';
                                    //$fq2 .= $val.':*'.$vb.'*';
                                } else {
                                    $fq2 .= $val.':'.$vb;
                                }
                            }
                        }
                        try {
                            //echo '<br>option1='.$fq2;
                             $query->addFilterQuery($fq2);
                        } catch(Exception $e) {
                            error_log("fn_assign_filtertoitem - Line No 695: ".$e->getMessage(), 1, "vinay.gupta@shopclues.com");
                            return false; 
                        }                        
                    }

                    $query->addFacetField($val);    
                }
        //}		
	//}
      //echo $query;  //for adding last selected filter in solr query by checking fsrc(last selected filter) 
      if(!empty($params['fsrc'])) {
            $form_src = $params['fsrc'];
            $fid = explode(':',$form_src);
            $fac_name = $fid['0'];
            $fid = $fid['1'];

             $query1 = new SolrQuery();         	
         	 $query1 = fn_solr_search_field($query1, $term);

            //$query1->setStart(0);
            //$query1->setRows(0);

            $query1->setFacet(true);
            $query1->addFilterQuery("metacategory_status:".$status);
            //$query1->addFacetField('category_id');
            //$query1->addFacetField('category'); 
            if(!empty($params['sp'])){
                $price=  explode(",", $params['sp']);
                $pr="sort_price:[".$price[0]." TO ".$price[1]."]";
                $query1->addFilterQuery($pr);
            }
            if(!empty($category_facets))
                foreach($category_facets as $key => $val) {

                if(!empty($params[$val])) {
                    $fq2='';
                    foreach($params[$val] as $kb=>$vb){
                        
                       if($fac_name == $val){ //&& $fid == $vb
                            continue;
                        }else{                   
                            if($kb > 0){
                                $fq2 .= ' OR ';
                            }                             
                            if($val=="show_merchant") { 
                                $fq2 .= 'company_id:'.$vb; 
                            } else if($val=="show_market") { 
                                $fq2 .= 'market_id:'.$vb; 
                            } else {
                                $option = explode('_', $val);
                                if($option[0]=='o') {
                                    $fq2 .= 'combination_inv:*'.$vb.'*';
                                    //$fq2 .= $val.':*'.$vb.'*';
                                } else {
                                    $option = explode('_', $val);
                                    if($option[0]=='o') {
                                        $fq2 .= 'combination_inv:*'.$vb.'*';
                                        //$fq2 .= $val.':*'.$vb.'*';
                                    } else {
                                        $fq2 .= $val.':'.$vb;
                                    }
                                }
                                //echo '<br>option2='.$fq2;                            
                               $query1->addFilterQuery($fq2);
                           }
                       }
                    }


                    $query1->addFacetField($val);    
                }
                
            if(isset($params['company_id']) && ($params['company_id'] != '0')){
                    $query1->addFilterQuery("company_id:".$params['company_id']);
            }

            if(isset($params['fq'])){
                $fq='';
                
                foreach($params['fq'] as $k=>$v){
                        if($fq != ''){
                                $fq .= ' OR ';
                        }
                        if(count($params['fq']) == 1){
                            if($fac_name == 'price' && $fid == $v){
                                continue;
                            }else{
                                $fq .= fn_assign_pricekey($v,'key');
                            }
                        }else{
                           //$fq .= fn_assign_pricekey($v,'key'); 
                        }
                        
                }
                if($fq != ''){
                    $query1->addFilterQuery($fq);
                }
            }

            if(isset($params['br'])){
                    $fq2='';
                    
                    foreach($params['br'] as $kb=>$vb){
                        if($fq2 != ''){
                                $fq2 .= ' OR ';
                        }    
                        
                        if(count($params['br']) == 1){
                            if($fac_name == 'brand' && $fid == $vb){
                                continue;
                            }else{
                                $fq2 .= 'brand_id:'.$vb;
                            }                        
                        }else{
                            //$fq2 .= 'variant_id:'.$vb;
                            
                        }
                            
                            
                    }
                    if($fq2 != ''){
                        $query1->addFilterQuery($fq2);
                    }
            }


           if(!empty($params['df'])){
                $df='';
                
                foreach($params['df'] as $k=>$v){
                        if($df != ''){
                                $df .= ' OR ';
                        }
                        if(count($params['df']) == 1){
                            if($fac_name == 'discount_percentage' && $fid == $v){
                                continue;
                            }else{
                                $df .= fn_assign_discountkey($v,'key');
                            }
                        }else{
                           //$df .= fn_assign_pricekey($v,'key'); 
                        }
                }
                if($df != ''){
                    $query1->addFilterQuery($df);
                }
            }          

            if(!empty($params['product_amount_available'])){
                    $dp='';                    
                    foreach($params['product_amount_available'] as $kb=>$vb){
                        if($dp != ''){
                                $dp .= ' OR ';
                        }
                        if(count($params['product_amount_available']) == 1){
                            if($fac_name == 'product_amount_available' && $fid == $vb){
                                continue;
                            }else{
                                $dp .= 'product_amount_available:'.$vb;
                            }                        
                        }
                    }
                    if($dp != ''){
                        $query1->addFilterQuery($dp);
                    }
            }            
		/*if(isset($params['is_cod'])){
		    $cod='';
		    $cod = 'is_cod:'.$params['is_cod'];
		    if($cod != ''){
				$query1->addFilterQuery($cod);
		    }
		}*/

            if(isset($params['cid']) && ($params['cid'] != '0') && !empty($params['cid'])) {
				if(strpos($params['cid'], ',')) {            	
					$qstr = $params['cid'];           
					//$catterm = str_replace(",", ' OR category_ids:', $qstr); $query->addFilterQuery('category_ids:'.$catterm);
					$parent_arr = explode(',', $qstr);
					$catterm = '';
					foreach($parent_arr as $key => $val) {
						$catterm .= 'metacategory_id:'. $val .' OR category_ids:*/'. $val .' OR category_ids:'. $val .' OR ';
					}
					$catterm = trim($catterm, ' OR ');
					$query1->addFilterQuery($catterm);
                } else {
                    //$query1->addFilterQuery("metacategory_id:".$params['cid'] ." OR  "."category_id:".$params['cid']);
							if(!empty($params['name'])){
                                $query1->addFilterQuery("metacategory_id:".$params['cid']." OR "."category_id:".$params['cid']." OR  "."category_ids:*/".$params['cid']." OR  "."category_ids:".$params['cid']);
                            }
                            else{
                                $query1->addFilterQuery("category_id:".$params['cid']);
                            }
                    //$query1->addFilterQuery("metacategory_id:".$params['cid'] ." OR  "."category_id:".$params['cid']." OR  "."category_ids:*/".$params['cid']." OR  "."category_ids:".$params['cid']);
                }
            }

            if(isset($params['company_id']) && ($params['company_id'] != '0')){
                    $query1->addFilterQuery("company_id:".$params['company_id']);
            }           

            $query1->addFacetField('show_brand');
            $query1->addFacetField('product_amount_available');

                        
            try{

if (defined('PROFILER')) {
  Profiler::set_query("Solr fn_assign_filtertoitem - Line 357: ".$query1, "");
}                
                //$solrResult3 = $client->query($query1)->getResponse();
                $solrResult3 = fn_get_solr_response($client, $query1);

            }catch(Exception $e){
                error_log("fn_assign_filtertoitem - Line No 361: ".$e->getMessage(), 1, "vinay.gupta@shopclues.com");
                return false; 
            }
            //echo '<pre>';print_r($solrResult3);echo '</pre>';
        }
        
        if(isset($params['fq'])){
            $fq='';
            foreach($params['fq'] as $k=>$v){
                    if($k > 0){
                            $fq .= ' OR ';
                    }
                    $fq .= fn_assign_pricekey($v,'key');
            } 
            $query->addFilterQuery($fq);
        }

        if(isset($params['df'])){
            $df='';
            foreach($params['df'] as $k=>$v){
                    if($k > 0){
                            $df .= ' OR ';
                    }
                    $df .= fn_assign_discountkey($v,'key');
            } 
            $query->addFilterQuery($df);
        }

        if(isset($params['br'])){
                $fq2='';
                foreach($params['br'] as $kb=>$vb){
                        if($kb > 0){
                                $fq2 .= ' OR ';
                        }
                        $fq2 .= 'brand_id:'.$vb;
                }
                $query->addFilterQuery($fq2);
        }

        if(!empty($params['product_amount_available'])){
                $dp2='';
                foreach($params['product_amount_available'] as $kb=>$vb){
                        if($dp2 > 0){
                                $dp2 .= ' OR ';
                        }
                        $dp2 .= 'product_amount_available:'.$vb;
                }
                $query->addFilterQuery($dp2);
        }        

	/*if(isset($params['is_cod'])){
	    $cod='';
	    $cod = 'is_cod:'.$params['is_cod'];
	    if($cod != ''){
		$query->addFilterQuery($cod);
	    }
	}*/


		if(isset($params['cid']) && ($params['cid'] != '0') && !empty($params['cid'])) {
			if(strpos($params['cid'], ',')) {            	
				$qstr = $params['cid'];           
				//$catterm = str_replace(",", ' OR category_ids:', $qstr); $query->addFilterQuery('category_ids:'.$catterm);
				$parent_arr = explode(',', $qstr);
				$catterm = '';
				foreach($parent_arr as $key => $val) {
					$catterm .= 'metacategory_id:'. $val .' OR category_ids:*/'. $val .' OR category_ids:'. $val .' OR ';
				}
				$catterm = trim($catterm, ' OR ');
				$query->addFilterQuery($catterm); 								
			} else {
					if(!empty($params['name'])){
                                $query->addFilterQuery("metacategory_id:".$params['cid']." OR "."category_id:".$params['cid']." OR  "."category_ids:*/".$params['cid']." OR  "."category_ids:".$params['cid']);
                    }
                    else{
                                $qstr = fn_check_meta_category($params['cid']); 
                                
                        if($qstr !=0){

                                $all_cat = explode(',', $qstr);
                                //echo "<pre>";print_r($all_cat);
                                $meta_query = '';
                                foreach($all_cat as $key => $val) {
                                    $meta_query .= 'category_ids:*/'. $val .' OR category_ids:'. $val .' OR ';
                                    }
                                 $meta_query = trim($meta_query, ' OR ');
                                
                                $query->addFilterQuery($meta_query); 
                            }
                        else{     
                                 $query->addFilterQuery("category_id:".$params['cid']);
                           }
                               // $query->addFilterQuery("category_id:".$params['cid']);
                        }
				//$query1->addFilterQuery("metacategory_id:".$params['cid'] ." OR  "."category_id:".$params['cid']);
				//$query->addFilterQuery("metacategory_id:".$params['cid'] ." OR  "."category_id:".$params['cid']." OR  "."category_ids:*/".$params['cid']." OR  "."category_ids:".$params['cid']);
			}			
		}        

        if(isset($params['company_id']) && ($params['company_id'] != '0')){
                $query->addFilterQuery("company_id:".$params['company_id']);
        }     

    $query->addFacetField('show_brand');
        /*edited by abhishek for search query factory outlet*/
         if(isset($params['brand'])) {
            $query->addFacetField('show_outlet_brand');
            $query->addFilterQuery("is_factory_outlet_product:1");
            $query->addFilterQuery("is_cob_status:1");
            $query->addFilterQuery("is_outlet_status:2");
            if(isset($params['brand_id']) && ($params['brand_id'] !=''))
                {
                    $query->addFilterQuery("outlet_brand_id:".$params['brand_id']);
            }
            if(!empty($params["show_outlet_brand"])){
                $cnt=0;
                $fq="";
                foreach ($params["show_outlet_brand"] as $valuek=>$value) {
                    if($cnt==0){
                        $fq="outlet_brand_id:".$value;
                    }else{
                        $fq.=" OR "."outlet_brand_id:".$value;
                    }
                    $cnt++;
                }
               $query->addFilterQuery($fq);
            }
           }     
      /*code ended*/
    try {

	try {

if (defined('PROFILER')) {
  Profiler::set_query("Solr fn_assign_filtertoitem - Line 406: ".$query. "");
}

			if(!empty($params['x']) && Registry::get('config.solr_extra_params')) {
				$query = fn_solr_extra_params($query, $params['x']);
			}

			//$solrResult = $client->query($query)->getResponse();
            $solrResult = fn_get_solr_response($client, $query);

			list($query, $total_items, $offset, $items_per_page) = fn_solr_pagination($solrResult, $query);
			$rootArr['products_count'] = $total_items;
			$rootArr['items_per_page'] = $items_per_page;
            
            // track user for search keyword
            fn_track_search_user($params['q'],array('total_items'=>$total_items));                        

if (defined('PROFILER')) {
  Profiler::set_query("Solr fn_assign_filtertoitem - productcount: ".$total_items. "");
}

       if(!empty($params['sort_by'])) {

            $query->addSortField('product_amount_available');

            $sort_type = $params['sort_by'];
             
            if($sort_type=="product") $sort_type = "label";

            if(strtolower($params['sort_order'])=="asc") {
                $query->addSortField($sort_type, SolrQuery::ORDER_ASC);
            } else {
                $query->addSortField($sort_type);
            }                                
        } else {
            $query->addSortField('product_amount_available');
            if($term=='') {
		$query->addSortField('boost_index', SolrQuery::ORDER_ASC);                
		$query->addSortField('popularity');
            } else {
                $query->addSortField('score');
            }
        }
    if(ANDROID_API == 'TRUE') {
        $query->addSortField('is_cod');
    }        


if (defined('PROFILER')) {
  Profiler::set_query("Solr fn_assign_filtertoitem - Line 425: ".$query, "");
}

		    //$solrResult4 = $client->query($query)->getResponse();
            $solrResult4 = fn_get_solr_response($client, $query);

			$products = $solrResult4->response->docs;
			$rootArr['products'] = $products;

        }catch(Exception $e){
            error_log("fn_assign_filtertoitem - Line No 431: ".$e->getMessage(), 1, "vinay.gupta@shopclues.com");
            return false; 
        }
        
        if(isset($params['fsrc']) && $params['fsrc'] != '' && $fac_name == "price"){
            $facetqueries = $solrResult3->facet_counts->facet_queries;
        }else{
            $facetqueries = $solrResult->facet_counts->facet_queries;
        }

    if(!empty($facetqueries))
        foreach($facetqueries as $key => $val) {
            $arr = explode(':', $key);
            if($arr[0]=="price") $rootArr['price'][] = array('key'=>$arr[1],'val'=>$val); 
        }

        if(!empty($params['fsrc']) && $fac_name == "discount_percentage") {
            $discountqueries = $solrResult3->facet_counts->facet_queries;
        }else{
            $discountqueries = $solrResult->facet_counts->facet_queries;
        }

    if(!empty($discountqueries))
        foreach($discountqueries as $key => $val) {
            $arr = explode(':', $key);
            if($arr[0]=="discount_percentage") $rootArr['discount_percentage'][] = array('key'=>$arr[1],'val'=>$val); 
        }        

        if(!empty($params['fsrc']) && $fac_name == "brand") {
            $brandqueries = $solrResult3->facet_counts->facet_fields->show_brand;
        }else{
            $brandqueries = $solrResult->facet_counts->facet_fields->show_brand;;
        }

        if(!empty($params['fsrc']) && $fac_name == "product_amount_available") {
            $dpqueries = $solrResult3->facet_counts->facet_fields->product_amount_available;
        }else{
            $dpqueries = $solrResult->facet_counts->facet_fields->product_amount_available;
        }        

    if(!empty($dpqueries))
        foreach($dpqueries as $key => $val) {
            //$arr = explode(':', $key);
            $rootArr['product_amount_available'][] = array('key'=>$key,'val'=>$val); 
        } 
                        
        /*if(empty($term) && empty($params['company_id'])) {
           $facetfields = $solrResult->facet_counts->facet_fields->show_metacategory;
           foreach($facetfields as $key => $val) { 
                if(strpos($key, '_')) {
                    list($cat_id, $cat_name) = explode('_',$key);
                    $rootArr['category'][$cat_id] = array('cat_id'=>$cat_id,'cat_name'=>$cat_name,'count'=>$val);
                } 
            }      
        }  */
    

if (defined('PROFILER')) {
  Profiler::set_query("Solr fn_assign_filtertoitem - Line 458: ".$query, "");
}

    //$solrResult2 = $client->query($query)->getResponse();
    $solrResult2 = fn_get_solr_response($client, $query);

	if(!empty($term)) {
		try{

			$products = $solrResult2->response->docs;
			$rootArr['products'] = $products;

			list($query, $total_items, $offset, $items_per_page) = fn_solr_pagination($solrResult2, $query);
			$rootArr['products_count'] = $total_items;
			$rootArr['items_per_page'] = $items_per_page;

		}catch(Exception $e){
            error_log("fn_assign_filtertoitem - Line No 468: ".$e->getMessage(), 1, "vinay.gupta@shopclues.com");
            return false; 
		}

        if(!empty($params['fsrc']) && $fac_name == "brand") {
            $brandqueries = $solrResult3->facet_counts->facet_fields->show_brand;
        }else{
            $brandqueries = $solrResult->facet_counts->facet_fields->show_brand;;
        }

        if((!empty($params['fsrc']) && $fac_name == "category")) {
            $facetfields = $solrResult3->facet_counts->facet_fields->show_metacategory;
        } else {
            $facetfields = $solrResult->facet_counts->facet_fields->show_metacategory;
        }
	   //$facetfields = $solrResult2->facet_counts->facet_fields->show_metacategory;

    /*if(!empty($facetfields))
       foreach($facetfields as $key => $val) {
       		if(strpos($key, '_')) {
            	list($cat_id, $cat_name) = explode('_',$key);
            	$rootArr['category'][$cat_id] = array('cat_id'=>$cat_id,'cat_name'=>$cat_name,'count'=>$val);
          	} 
        }*/

	}

    if(empty($term) && $cat_run == false) {
       $facetfields = $solrResult2->facet_counts->facet_fields->show_metacategory;
       //print_r($facetfields);
       foreach($facetfields as $key => $val) { 
            if(strpos($key, '_')) {
                list($cat_id, $cat_name, $meta_cat_name) = explode('_',$key);
                $rootArr['category'][$meta_cat_name][$cat_id] = array('cat_id'=>$cat_id,'cat_name'=>$cat_name,'count'=>$val);
            } 
        }
        // remove zero categories
        $rootArr['category'] = fn_remove_zero_cat($rootArr);

    }      
if(!empty($brandqueries))
    foreach($brandqueries as $key => $val) {
        list($brand_id, $brand_name) = explode('_',$key);
        $rootArr['brand'][$brand_id] = array('brand_id'=>$brand_id,'brand_name'=>$brand_name,'count'=>$val); 
    }

    $facet_in_query = get_solr_header($solrResult);

    foreach($facet_in_query as $key => $val) {   
    if(!empty($params['fsrc']) && $fac_name == $val){
     //echo '<br><br>dq1='.$query1;                                
        $$val = $solrResult3->facet_counts->facet_fields->$val;
     } else {
     //echo '<br><br>dq='.$query; 
         $$val = $solrResult->facet_counts->facet_fields->$val;
     }
        if(is_object($$val)) {
            $arr_filter = (array) $$val;
            $rootArr[$val] = fn_create_solr_filters($arr_filter, $val);
        } else {
            $rootArr[$val] = $$val;
        }
    }

     $rootArr['price_slider']['min']=floor($solrResult->stats->stats_fields->sort_price->min);
     $rootArr['price_slider']['max']=ceil($solrResult->stats->stats_fields->sort_price->max);
    
    $rootArr['search_usage']=$z; 
    if(Registry::get('config.solr_scin_search') && is_numeric($params['q'])) $rootArr['search_usage']=0;

    //echo "Query<br>".$query."<br>Query1<br>".$query1;die;
    //echo "<pre>";print_r($rootArr);die;
    return $rootArr;
  }
}

function get_solr_header($solrResult) {
       $responseHeader = $solrResult->responseHeader->params['facet.field'];
        if(!empty($responseHeader)) {
            $exclude_facet = array('show_metacategory', 'show_brand', 'price', 'id_path', 'discount_percentage');
            $arr = array_diff($responseHeader, $exclude_facet);  
            array_push($arr,'product_amount_available');
            return $arr;
        }
        return false;
}

function fn_create_solr_filters($facetfields, $filtername) {
    //echo $filtername."<br>";
    foreach($facetfields as $key => $val) {
            if(strpos($key, '_')) {
                list($id, $name) = explode('_',$key);   
            } else {
                $id = $name = $key;
            }

        $hide_filter = Registry::get('config.solr_hide_filter');
        if(array_key_exists($filtername,$hide_filter)) {  
            if(!strstr($hide_filter[$filtername],$key)) {
                $arr[$id] = array('id'=>$id,'name'=>$name,'count'=>$val);
            }
        } else {
            $arr[$id] = array('id'=>$id,'name'=>$name,'count'=>$val);
        }
    }
    return $arr;
}

function fn_show_solr_filterkey($filterkey) {

    $filtername = ucwords(strtolower(str_replace('_', ' ',str_replace('-',' ',$filterkey))));
    $filter_length = Registry::get('config.filter_length');
    if(empty($filter_length)) $filter_length = '15';
       
    if(strlen($filtername) > $filter_length){
        $nameArr = explode(' ',$filtername);
        array_pop($nameArr);
        $filtername = implode(' ',$nameArr);
    }
    //return "By ".$filtername;
    return $filtername;
}

function fn_get_solr_response($client, $query) {

    $results = array();
//echo '<br>'.$query;

    $key = md5($query);
    $memcache = $GLOBALS['memcache'];
    if (check_memcache_clear_condition()) {
        // Unset the memcache store if the request variable 'clean' is set 
        // and a user is logged in.
        $memcache->delete($key);
    }
    if(Registry::get('config.memcache') && $GLOBALS['memcache_status'] && ($productsArr = $memcache->get($key)) !== false) {
        $results = unserialize($productsArr);
        //echo '<br>memcached = '; print_r($results);
    } else {
        $results = $client->query($query)->getResponse();
        if(Registry::get('config.memcache') && $GLOBALS['memcache_status']) {
            $status = $memcache->set($key, serialize($results), MEMCACHE_COMPRESSED, Registry::get('config.solr_memcache_expire_time'));
            if(!$status){
                $memcache->delete($key);
            }
        } else {
          //echo "not set"; 
        }
    }

    return $results;
}
function fn_get_solr_categories($params,$items_per_page=0,$cids='') {
   if(!empty($params)) {
            list($client, $query) = fn_solr_connect();

			list($client, $query) = fn_solr_connect();

            $sort_by = $params['sort_by'];
            $sort_order = $params['sort_order'];

            $category_id = (!empty($params['category_id'])) ? $params['category_id'] : $params['category']; 
            //echo $category_id.'<br>';
            
            if(in_array($category_id,Registry::get('config.solr_root_category_id'))) {
                if($category_id == Registry::get('config.nrh_root_category_id') && !empty($params['market_id'])) {
                 $market_category_array = fn_get_market_category($params['market_id']);

                 $parentQuery = ''; 
                 foreach($market_category_array as $markey => $marval) {
                    foreach($marval as $mar_key => $mar_val) {
                        $parentQuery .= 'category_ids:*/'. $mar_val .' OR category_ids:'. $mar_val .' OR ';
                    }
                 } 
                  $query->setQuery($parentQuery . 'category_ids:*/'.$category_id.' OR category_ids:'.$category_id);
                } else {
                    $query->setQuery('*:*');
                    if($category_id == Registry::get('config.wholesale_root_category_id')) {
                        $query->addFilterQuery('is_wholesale_product:1');
                    }
                }
            } else {
            $parentQuery = '';
            if($category_id!='') {
                 $parent_ids = fn_check_parent_id($category_id);
                // print_r($parent_ids);
                 if($parent_ids != '') {  
                    //$parent = str_replace(",", ' OR category_ids:', $parent_ids);
                    //$parentQuery = 'category_ids:'.$parent.' OR ';                        
                     $parent_arr = explode(',', $parent_ids);
                     foreach($parent_arr as $key => $val) {
                        $parentQuery .= 'category_ids:*/'. $val .' OR category_ids:'. $val .' OR ';
                     } 
                     //echo $parentQuery;                     
                 }
             }

            if(count($cids) == 1 && $category_id!='') {
                 $query->setQuery($parentQuery . 'category_ids:*/'.$category_id.' OR category_ids:'.$category_id);
                 //$query->setQuery($parentQuery . 'category_ids:*/'.$category_id.' OR category_ids:'.$category_id.' && (product_amount_available:1^50.0 OR product_amount_available:0^0.0 )');
                 
            } else {
                $qstr = implode(",",$cids);            
                $term = str_replace(",", ' OR category_ids:', $qstr);
                $query->setQuery('category_ids:'.$term); 
            } 
         }              
           // echo $query;die;
            /*$solrResult5 = $client->query($query)->getResponse();
            echo '<pre>'; print_r($solrResult5); echo '</pre>';  die('func.php');
            $products = $solrResult5->response->docs;
            */
            $category_facets = fn_get_category_facets($category_id);            
            $query->setFacet('true'); 
            if(ANDROID_API == 'TRUE' || Registry::get('config.disable_metacategory')) {

                $query->addFacetField('show_metacategory');

            }else{

               $query->addFacetField('show_meta_id'); 
            } 
            $query->addFacetField('show_brand');
            $query->addFacetField('product_amount_available');
            //$query->addFacetField('price');
            //$query->addFacetField('id_path');
            $query->setFacetLimit(500); 
            
            //$query->setFacetMinCount('1'); // To remove zero counts for a facet from output
            /* added by pratik for nrh*/
            if(isset($params['market_id'])){
                $query->addFilterQuery("market_id:".$params['market_id']);
            }
            if(empty($param["show_market"]) && get_root_category($category_id) == Registry::get('config.nrh_root_category_id') && empty($params['market_id'])){
                $parent_ids = fn_check_parent_id($category_id); 
                $markets = fn_get_category_markets($parent_ids);

                $marketts = explode(",", $markets);
               $cnt=0;
                $fq="";
                foreach ($marketts as $value) {
                    if($cnt==0){
                        $fq="market_id:".$value;
                    }else{
                        $fq.=" OR "."market_id:".$value;
                    }
                    $cnt++;
                }
               $query->addFilterQuery($fq);
            }
            /*added by abhishek*/
            if(isset($params['brand'])) {
            $query->addFacetField('show_outlet_brand');
            $query->addFilterQuery("is_factory_outlet_product:1");
            $query->addFilterQuery("is_cob_status:1");
            $query->addFilterQuery("is_outlet_status:2");
            if(isset($params['brand_id']) && ($params['brand_id'] !=''))
                {
                    $query->addFilterQuery("outlet_brand_id:".$params['brand_id']);
            }
            if(!empty($params["show_outlet_brand"])){
                $cnt=0;
                $fq="";
                foreach ($params["show_outlet_brand"] as $valuek=>$value) {
                    if($cnt==0){
                        $fq="outlet_brand_id:".$value;
                    }else{
                        $fq.=" OR "."outlet_brand_id:".$value;
                    }
                    $cnt++;
                }
               $query->addFilterQuery($fq);
            }
           }         
        $rootArr = array();

    //if(!empty($term) || !empty($params['company_id'])) {
        try{
           
            if (defined('PROFILER')) {
              Profiler::set_query("Solr fn_get_solr_categories - Line 1654: ".$query, "");
            }           
               //$solrResult2 = $client->query($query)->getResponse();
               $solrResult_cat = fn_get_solr_response($client,$query);
               // echo "<pre>";print_r($solrResult_cat);die;
        }catch(Exception $e){
            error_log("fn_get_solr_categories - Line No 1653: ".$e->getMessage(), 1, "vinay.gupta@shopclues.com");
            return false;            
        }

        if(ANDROID_API == 'TRUE' || Registry::get('config.disable_metacategory')) {
        
        } else {
               $facetfields = $solrResult_cat->facet_counts->facet_fields->show_meta_id;
                  
               foreach($facetfields as $key => $val) { 
                    if(strpos($key, '_')) {
                        list($meta_cat_id,$meta_cat_name) = explode('_',$key);
                        $rootArr['category'][$meta_cat_id] = array('cat_id'=>$meta_cat_id,'cat_name'=>$meta_cat_name,'count'=>$val);
                    } 
               }
        }      
                // remove zero categories
                $rootArr['category'] = fn_remove_zero_cat($rootArr);

                
        $fac_name = '';
        if(isset($params['fsrc']) && $params['fsrc'] != '') {
            $form_src = $params['fsrc'];

            //echo '<pre>';print_r($form_src);die;
            $fid = explode(':',$form_src);
            $fac_name = $fid['0'];
            $fid = $fid['1'];
            
            list($client, $query1) = fn_solr_connect();
            
            $query1 = fn_solr_search_field($query1, $term);
            if($category_id == Registry::get('config.nrh_root_category_id') && !empty($params['market_id'])) {
                 $market_category_array = fn_get_market_category($params['market_id']);

                 $parentQuery = ''; 
                 foreach($market_category_array as $markey => $marval) {
                    foreach($marval as $mar_key => $mar_val) {
                        $parentQuery .= 'category_ids:*/'. $mar_val .' OR category_ids:'. $mar_val .' OR ';
                    }
                 } 
                  $query1->setQuery($parentQuery . 'category_ids:*/'.$category_id.' OR category_ids:'.$category_id);
              }
            $query1->setFacet('true'); 
            $query1->addFacetField('show_metacategory'); 
            $query1->addFacetField('show_brand');
            $query1->addFacetField('product_amount_available');
            //$query1->addFacetField('price');
            //$query1->addFacetField('id_path');
            $query1->setFacetLimit(500);

            //$query1->setStart(0);
            //$query1->setRows(100);

            if(isset($params['company_id']) && ($params['company_id'] != '0')){
                    $query1->addFilterQuery("company_id:".$params['company_id']);
            }          
//if(isset($params['brand'])) {
//            $query1->addFacetField('show_outlet_brand');
//            $query1->addFilterQuery("is_factory_outlet_product:1");
//            if(!empty($params["show_outlet_brand"])){
//                $cnt=0;
//                $fq="";
//                foreach ($params["show_outlet_brand"] as $valuek=>$value) {
//                    if($cnt==0){
//                        $fq="outlet_brand_id:".$value;
//                    }else{
//                        $fq.=" OR ".$value;
//                    }
//                    $cnt++;
//                }
//               $query1->addFilterQuery($fq);
//            }
//}            
           if(isset($params['market_id'])) {
                $query1->addFilterQuery("market_id:".$params['market_id']);
            }
            if(empty($param["show_market"]) && get_root_category($category_id) == Registry::get('config.nrh_root_category_id') && empty($params['market_id'])){
                $parent_ids = fn_check_parent_id($category_id); 
                $markets = fn_get_category_markets($parent_ids);

                $marketts = explode(",", $markets);
               $cnt=0;
                $fq="";
                foreach ($marketts as $value) {
                    if($cnt==0){
                        $fq="market_id:".$value;
                    }else{
                        $fq.=" OR "."market_id:".$value;
                    }
                    $cnt++;
                }
               $query1->addFilterQuery($fq);
            }
            
         if(isset($params['brand'])) {
           $query1->addFilterQuery("is_factory_outlet_product:1");
           $query1->addFilterQuery("is_cob_status:1");
           $query1->addFilterQuery("is_outlet_status:2");
            if(isset($params['brand_id']) && ($params['brand_id'] !='')){
                    $query1->addFilterQuery("outlet_brand_id:".$params['brand_id']);
            }
            if(!empty($params["show_outlet_brand"])){
                $fq=""; 
                foreach ($params["show_outlet_brand"] as $key=>$value) {
                        if($fq != ''){
                          $fq.= ' OR ';
                            }     
                        if($fac_name == 'show_outlet_brand'){
                                continue;
                            }else{
                                $fq.="outlet_brand_id:".$value;
                
                            }
                    
                }
              if($fq!='')
               $query1->addFilterQuery($fq);
            }
            $query1->addFacetField('show_outlet_brand');
}
            if(isset($params['fq'])){
                $fq='';
                
                foreach($params['fq'] as $k=>$v){
                        if($fq != ''){
                                $fq .= ' OR ';
                        }
                        if(count($params['fq']) == 1){
                            if($fac_name == 'price' && $fid == $v){
                                continue;
                            }else{
                                $fq .= fn_assign_pricekey($v,'key');
                            }
                        }else{
                           //$fq .= fn_assign_pricekey($v,'key'); 
                        }
                        
                }
                if($fq != ''){
                    $query1->addFilterQuery($fq);
                }
            }

          if(isset($params['df'])){
                $df='';
                
                foreach($params['df'] as $k=>$v){
                        if($df != ''){
                                $df .= ' OR ';
                        }
                        if(count($params['df']) == 1){
                            if($fac_name == 'discount_percentage' && $fid == $v){
                                continue;
                            }else{
                                $df .= fn_assign_discountkey($v,'key');
                            }
                        }else{
                           //$df .= fn_assign_pricekey($v,'key'); 
                        }
                        
                }
                if($df != ''){
                    $query1->addFilterQuery($df);
                }
            }            

            if(isset($params['br'])){
                    $fq2='';
                    
                    foreach($params['br'] as $kb=>$vb){
                        if($fq2 != ''){
                                $fq2 .= ' OR ';
                        }    
                        
                        if(count($params['br']) == 1){
                            if($fac_name == 'brand' && $fid == $vb){
                                continue;
                            }else{
                                $fq2 .= 'brand_id:'.$vb;
                            }                        
                        }else{
                            //$fq2 .= 'variant_id:'.$vb;
                            
                        }
                    }

                    if($fq2 != ''){
                        $query1->addFilterQuery($fq2);
                    }
            }
	
	/*if(isset($params['is_cod'])){
	    $cod='';
	    $cod = 'is_cod:'.$params['is_cod'];
	    if($cod != ''){
		$query1->addFilterQuery($cod);
	    }
	}*/
        
            if(isset($params['product_amount_available'])){
                    $dp='';
                    
                    foreach($params['product_amount_available'] as $kb=>$vb){
                        if($dp != ''){
                                $dp .= ' OR ';
                        }    
                        
                        if(count($params['product_amount_available']) == 1){
                            if($fac_name == 'product_amount_available' && $fid == $vb){
                                continue;
                            }else{
                                $dp .= 'product_amount_available:'.$vb;
                            }                        
                        }
                    }

                    if($dp != ''){
                        $query1->addFilterQuery($dp);
                    }
            }

        if(is_array($category_facets))
            foreach($category_facets as $key => $val) {
                
                if(!empty($params[$val])) {
                    $fq2='';
                    foreach($params[$val] as $kb=>$vb){
                        
                       if($fac_name == $val){ //&& $fid == $vb
                            continue;
                        }else{                   
                            if($kb > 0){
                                $fq2 .= ' OR ';
                            }                             
                            if($val=="show_merchant") { 
                                $fq2 .= 'company_id:'.$vb; 
                            } else if($val=="show_market") { 
                                $fq2 .= 'market_id:'.$vb;                                
                            } elseif($val=="show_promotion") { //vidisha start
                                $fq2 .= 'promotion_id:'.$vb;
                                
                            } elseif($val=="newarrivals") {
                                
                                 $days = Registry::get('config.solr_newarrivals');
                                 if(!empty($days)&& ($days != '') && ($days > 0))
                                 {
                                 $nowtime = time();
                                $preWeek = time() - ($days * 24 * 60 * 60);
                                $fq2 .= 'newarrivals:['.$preWeek.' TO '.$nowtime.']'; } 
                            
                            }//vidisha end
                            else {
                                $option = explode('_', $val);
                                if($option[0]=='o') {
                                    $fq2 .= 'combination_inv:*'.$vb.'*';
                                    //$fq2 .= $val.':*'.$vb.'*';
                                } else {
                                    $fq2 .= $val.':'.$vb;
                                }
                            }
                        }
                    }

                    if($fq2 != ''){
                        //echo '<br>option3='.$fq2;
                        $query1->addFilterQuery($fq2);
                    }
                }
                $query1->addFacetField($val); 
            }

            if(!empty($params['category_id']) && $params['category_id'] != Registry::get('config.nrh_root_category_id')){
                
                    //$query1->addFilterQuery("metacategory_id:".$params['category_id'] ." OR  "."category_id:".$params['category_id']);
                    //$query1->addFilterQuery("category_id:".$params['category_id']);

                    $query1->addFilterQuery($parentQuery . 'category_ids:*/'.$category_id.' OR category_ids:'.$category_id);
                    //$query1->addFilterQuery($parentQuery . 'category_ids:*/'.$category_id.' OR category_ids:'.$category_id.' && (product_amount_available:1^50.0 OR product_amount_available:0^0.0 )');
            }

            try{
                //$solrResult3 = $client->query($query1)->getResponse();
                $solrResult3 = fn_get_solr_response($client, $query1);

            }catch(Exception $e){
                error_log("fn_get_solr_categories - Line No 668: ".$e->getMessage(), 1, "vinay.gupta@shopclues.com");
                return false;                 
            }
            //echo '<pre>';print_r($solrResult3);echo '</pre>';
        }

            // for brand filter
            if(isset($params['br'])) {
                $fq2='';
                foreach($params['br'] as $kb=>$vb){
                    if($kb > 0){
                        $fq2 .= ' OR ';
                    }
                    $fq2 .= 'brand_id:'.$vb;
                }
                $query->addFilterQuery($fq2);
            }

            // for price filter
            if(isset($params['fq'])) {
                $fq='';
                foreach($params['fq'] as $k=>$v){
                    if($k > 0){
                        $fq .= ' OR ';
                    }
                    $fq .= fn_assign_pricekey($v,'key');
                }
                $query->addFilterQuery($fq);
            }            

            // for Discount filter
            if(isset($params['df'])) {
                $df='';
                foreach($params['df'] as $k=>$v){
                    if($k > 0){
                        $df .= ' OR ';
                    }
                    $df .= fn_assign_discountkey($v,'key');
                }
                $query->addFilterQuery($df);
            }

	/*if(isset($params['is_cod'])){
	   $cod='';
	   $cod = 'is_cod:'.$params['is_cod'];
	   if($cod != ''){
	       $query->addFilterQuery($cod);
	   }
	}*/ 

            // for Exclude Out of Stock filter
            if(!empty($params['product_amount_available'])) {
                $dp='';
                foreach($params['product_amount_available'] as $k=>$v){
                    if($k > 0){
                        $dp .= ' OR ';
                    }
                    $dp .= 'product_amount_available:'.$v;
                }
                $query->addFilterQuery($dp);
            }            

if(is_array($category_facets))
            foreach($category_facets as $key => $val) {
                
                if(!empty($params[$val])) {
                    $fq2='';
                    foreach($params[$val] as $kb=>$vb){
                        if($kb > 0){
                            $fq2 .= ' OR ';
                        }
                            if($val=="show_merchant") { 
                                $fq2 .= 'company_id:'.$vb; 
                            } else if($val=="show_market") { 
                                $fq2 .= 'market_id:'.$vb;                                 
                            }
                             elseif($val=="show_promotion") { //vidisha start
                                $fq2 .= 'promotion_id:'.$vb; 
                            
                                
                            } elseif($val=="newarrivals") {
                                
                              $days = Registry::get('config.solr_newarrivals');
                                 if(!empty($days)&& ($days != '') && ($days > 0))
                                 {
                                 $nowtime = time();
                                $preWeek = time() - ($days * 24 * 60 * 60);
                                $fq2 .= 'newarrivals:['.$preWeek.' TO '.$nowtime.']'; } 
                            
                            }//vidisha end
                            else {
                                $option = explode('_', $val);
                                if($option[0]=='o') {
                                    $fq2 .= 'combination_inv:*'.$vb.'*';
                                    //$fq2 .= $val.':*'.$vb.'*';
                                } else {
                                    $fq2 .= $val.':'.$vb;
                                }
                            }
                    }
                    //echo '<br>option4='.$fq2;
                    $query->addFilterQuery($fq2);
                }
				
                $query->addFacetField($val);    
			}

		    /*if($items_per_page != 0){
            	$page = (!empty($params['page'])) ? $params['page'] : 1;
                $offset = ($page-1)*32;
                $query->setStart($offset);
                $query->setRows($items_per_page);                              
            } else {
                if($items_per_page == '' || $items_per_page == 0){
                    $query->setRows('0');
                } else {
                    //$query->setRows($params['limit']);                              
                    $query->setRows('-1');
                }
            }*/                                     



            try {
        
                    //$solrResult = $client->query($query)->getResponse();
                    $solrResult = fn_get_solr_response($client, $query);

                    // START - set pagination for solr
                    list($query, $total_items, $offset, $items_per_page) = fn_solr_pagination($solrResult, $query);

if (defined('PROFILER')) {
  Profiler::set_query("Solr fn_get_solr_categories - products_count:".$total_items, "");
}


                    $rootArr['products_count'] = $total_items;
                    $rootArr['items_per_page'] = $items_per_page;
                   
                   if(!empty($params['page'])) {

                        $l = fn_paginate($params['page'], $total_items, $items_per_page);
                        $larr = explode(',', trim($l));
                        $offset = (int) trim(str_replace('LIMIT', '', $larr[0]));
                        $limit = intval($offset + $items_per_page);
                    }

                    $query->setStart($offset);
                    $query->setRows($items_per_page);  
                     // END - set pagination for solr

		           if(!empty($params['sort_by'])) {

                        $query->addSortField('product_amount_available');

	                    $sort_type = $params['sort_by'];
                         
                        if($sort_type=="product") $sort_type = "label";

	                    if(strtolower($params['sort_order'])=="asc") {
	                        $query->addSortField($sort_type, SolrQuery::ORDER_ASC);
	                    } else {
	                        $query->addSortField($sort_type);
	                    }                                
		            } else {
                        $query->addSortField('product_amount_available');
                        if($term=='') {
			    $query->addSortField('boost_index', SolrQuery::ORDER_ASC);
                            $query->addSortField('popularity');
                        } else {
                            $query->addSortField('score');
                        }
                    }

if (defined('PROFILER')) {
  Profiler::set_query("Solr fn_get_solr_categories - Line 786: ".$query, "");
}


				    //$solrResult4 = $client->query($query)->getResponse();
                    $solrResult4 = fn_get_solr_response($client, $query);

					//echo '<pre>'; print_r($solrResult4); echo '</pre>';  die('func.php');
					$products = $solrResult4->response->docs;
                    //$ptotal = $solrResult4->response->numFound;

					$rootArr['products'] = $products;

                    $facetfields = $solrResult->facet_counts->facet_fields->show_metacategory;
                    //$rootArr['category'] = fn_create_solr_filters($facetfields, 'category');
                   foreach($facetfields as $key => $val) { 
                        if(strpos($key, '_')) {
                            list($cat_id, $cat_name, $meta_cat_name) = explode('_',$key);
                            $rootArr['category'][$meta_cat_name][$cat_id] = array('cat_id'=>$cat_id,'cat_name'=>$cat_name,'count'=>$val);
                        } 
                    }
                    //Remove zero categories
                    $rootArr['category'] = fn_remove_zero_cat($rootArr);


			        if(!empty($params['fsrc']) && $fac_name == "brand") {

if (defined('PROFILER')) {
  Profiler::set_query("Solr fn_get_solr_categories - brandquery: ".$query1, "");
}                    
			            $brandqueries = $solrResult3->facet_counts->facet_fields->show_brand;
			        }else{
 			            $brandqueries = $solrResult->facet_counts->facet_fields->show_brand;
			        }

                    $rootArr['brand'] = fn_create_solr_filters($brandqueries, 'brand');


                    if(isset($params['fsrc']) && $params['fsrc'] != '' && $fac_name == "price"){

if (defined('PROFILER')) {
  Profiler::set_query("Solr fn_get_solr_categories - pricequery: ".$query1, "");
}                    
                        $facetqueries = $solrResult3->facet_counts->facet_queries;
                    }else{
                        $facetqueries = $solrResult->facet_counts->facet_queries;
                    }    
 
                    foreach($facetqueries as $key => $val) {
                        $arr = explode(':', $key);
                        if($arr[0]=="price") $rootArr['price'][] = array('key'=>$arr[1],'val'=>$val); 
                    }


                    if(isset($params['fsrc']) && $params['fsrc'] != '' && $fac_name == "discount_percentage"){

if (defined('PROFILER')) {
  Profiler::set_query("Solr fn_get_solr_categories - discountquery: ".$query1, "");
}                    
                        $discountqueries = $solrResult3->facet_counts->facet_queries;
                    }else{
                        $discountqueries = $solrResult->facet_counts->facet_queries;
                    }    
 
                    foreach($discountqueries as $key => $val) {
                        $arr = explode(':', $key);
                        if($arr[0]=="discount_percentage") $rootArr['discount_percentage'][] = array('key'=>$arr[1],'val'=>$val); 
                    }


                    if(isset($params['fsrc']) && $params['fsrc'] != '' && $fac_name == "product_amount_available"){

                        $dpqueries = $solrResult3->facet_counts->facet_fields->product_amount_available;
                    }else{
                        $dpqueries = $solrResult->facet_counts->facet_fields->product_amount_available;
                    }    
                    foreach($dpqueries as $key => $val) {
                        //$arr = explode(':', $key);
                        $rootArr['product_amount_available'][] = array('key'=>$key,'val'=>$val); 
                    }

 				    //$id_path = $solrResult->facet_counts->facet_fields->id_path;
				    //$rootArr['id_path'] = $id_path;
                
                $facet_in_query = get_solr_header($solrResult);

                foreach($facet_in_query as $key => $val) {   
                if(!empty($params['fsrc']) && $fac_name == $val){
                 //echo '<br><br>dq1='.$query1;                                
                    $$val = $solrResult3->facet_counts->facet_fields->$val;
                 } else {
                 //echo '<br><br>dq='.$query; 
                     $$val = $solrResult->facet_counts->facet_fields->$val;
                 }
                    if(is_object($$val)) {
                        $arr_filter = (array) $$val;
                        $rootArr[$val] = fn_create_solr_filters($arr_filter, $val);
                    } else {
                        $rootArr[$val] = $$val;
                    }
                }

/*if (defined('PROFILER')) {
  Profiler::set_query("Solr fn_get_solr_categories - rootArr:".print_r($rootArr), "");
} */         if($_REQUEST['brand']==1||$_REQUEST['brand']==2){
             unset($rootArr['brand']);
                  /*if($_REQUEST['brand']==1)
                  {
                      unset($rootArr["show_outlet_brand"]);
                  }*/
           }
           
           $rootArr['new_price']['min']=floor($solrResult->stats->stats_fields->sort_price->min);
           $rootArr['new_price']['max']=ceil($solrResult->stats->stats_fields->sort_price->max);
    
           //echo "<br>".$query."<br><br>".$query1;die;
          // echo "<pre>";print_r($rootArr);die;
         //echo '<pre>'; print_r($rootArr['show_promotion']);die;

    //Edited by vidisha goel 
if(in_array('show_promotion',Registry::get('config.solr_filters'))) {
    
                $show_pro_type_id = Registry::get('config.show_pro_type_id');
                    $promo_type = array();
                    foreach($rootArr['show_promotion'] as $val) {
            
                     $exArr = explode("#", $val['name']);
                      $exArr1 = explode("-", $exArr[1]);
                      $nowtime = time();
                     if((!in_array($exArr1[0],$show_pro_type_id)) && ($exArr1[1] == 'A') && ($nowtime <= $exArr1[2]) && ($val['count']) > Registry::get('config.promotion_count_grey'))
                     {
                   $promo_type[$val['id']] = array('id'=>$val['id'],'name'=>$exArr[0],'count'=>$val['count']);
                     }
               
                  } 
           unset($rootArr['show_promotion']);
           $rootArr['show_promotion'] = $promo_type;
 }   
 $rootArr['newarrivalscount'] = 0;
 $days = Registry::get('config.solr_newarrivals');
 if(!empty($days)&& ($days != '') && ($days > 0))
 {
  $nowtime1 = time();
  $preWeek1 = time() - ($days * 24 * 60 * 60);
  $newarrivalscount = 0;
       foreach($rootArr['newarrivals'] as $val) 
           { 
           if($val['count'] >0) {
                if($val['id'] <= $nowtime1 && $val['id'] >= $preWeek1){
                    $newarrivalscount++;
                }                         
           } else {
               break;
           }
           }          
 $rootArr['newarrivalscount'] = $newarrivalscount;
 $parent_id = db_get_field("SELECT parent_id FROM cscart_categories WHERE category_id = ". $params['category_id']);
 $rootArr['parent_id_check'] = $parent_id; 
  
 }
 // end    

//$rootArr['show_market'] = array('15'=>array('id'=>15, 'name'=>'Nehru Place', 'count'=>'1400'));
        //echo "<br>".$query."<br><br>".$query1;die;
          //echo "<pre>";print_r($rootArr);die;
                return $rootArr;
             
            } catch (Exception $e) {
                    error_log("fn_get_solr_categories - Line No 835 =".$e->getMessage(), 1, "vinay.gupta@shopclues.com");
                    return false;
            }
        }
    
}
// change by vidisha goel start 
function fn_get_memproducts_id($arr)
{
            $prd = $arr['products'];
            $productid = array();
             foreach($prd as $pr)
                 {
                   $productid[]= $pr['product_id'];
                 }
                $productids= implode(',',$productid);                 
                        if($productids != '')
                 {
                 $proquery=  db_get_array("SELECT products.product_id, products.list_price, products.amount, prices.retail_price,products.third_price, MIN( prices.price ) AS price
FROM cscart_products AS products
LEFT JOIN cscart_product_prices AS prices ON prices.product_id = products.product_id
WHERE products.product_id IN ($productids) GROUP BY 1");                   
                    }
                    $newarr = array();
                     foreach($proquery as $key => $value) 
                        {
                         $newarr[$value['product_id']] = $value;
                        }
                        //print_r($newarr); die;
                        $prdnew = array();
                     foreach($prd as $pr)
{  
$prid = $pr['product_id'];
$val_prod = $newarr[$prid]['product_id'].'-'.$newarr[$prid]['list_price'].'-'.$newarr[$prid]['price'].'-'.$newarr[$prid]['third_price'].'-'.$newarr[$prid]['retail_price'].'-'.$newarr[$prid]['amount'];
$memcache = $GLOBALS['memcache'];
//echo "   memcached " . $val_prod;
                             $key = md5($val_prod); 
                if(Registry::get('config.memcache') && $GLOBALS['memcache_status'] && ($mem_value = $memcache->get($key)) !== false)                             
                            {
                                 //echo "   memcached " . $val_prod;
                            $pr = unserialize($mem_value);
                            $prdnew[] = $pr;
                           
                            
                            }
                            else
                            {
                                        $pr['list_price']= $newarr[$prid]['list_price'];
                                                $pr['price']= $newarr[$prid]['price'];
                                                $pr['third_price']= $newarr[$prid]['third_price'];
                                                $pr['retail_price']= $newarr[$prid]['retail_price'];
                                                $pr['amount']= $newarr[$prid]['amount'];
                                                 //$prd[] = $pr;
                                                //echo"<pre>hello memchached created "; print_r($pr); echo '</pre>'; die;
                                                 $prodarr = serialize($pr);
                                                 $prdnew[] = $pr;

                  if(Registry::get('config.memcache') && $GLOBALS['memcache_status']) {
               $status = $memcache->set($key, $prodarr, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time')); // or die ("Failed to save data at the server");
                if(!$status){
                    $memcache->delete($key);
                }
            } else {
              //echo "not set"; 
            }
                                         }
//echo"<pre>"; print_r($prdnew); echo '</pre>'; die;
                                         }
                        $arr['products'] = $prdnew;
                       // echo"<pre>hello done"; print_r($arr); echo '</pre>'; die;
                        return $arr;
}
// change by vidisha goel end...
//code start by vidisha for retrieving data from mongo
function fn_get_mongo_product_data($arr)
{
    $prdnew = array();
    $prd = $arr['products'];
    foreach($prd as $key=>$pr)
    { 
    $pr = (array)$pr;
    
    $query['product_id'] = $pr['product_id'];
    $fields = array();
    $products = mongodb_find(Registry::get('config.mongo_product_connect'), $query, $fields, $offset, $limit); 
    if (empty($products[0]))
    {
        $prdnew[] = $pr;
    }
    else{
        $pr = array_merge($pr,$products[0]);
             $prdnew[] = $pr;
     
    }
    
    }
    $arr['products'] = $prdnew;
    return $arr;
}


//code ends by vidisha for retrieving data from mongo
function fn_check_parent_id($category_id) {

    if(!Registry::get('config.root_category_solr')) {
        //return db_get_field("SELECT GROUP_CONCAT(category_id) FROM ?:categories WHERE parent_id = ?i", $category_id);
        $parent_id = db_get_field("SELECT parent_id FROM cscart_categories WHERE category_id = ". $category_id);

        //If parent_id of current category = 0 then use '<current category>/%' else use '%/<current category>/%'
        if($parent_id == 0) {
            $cat_ids = db_get_field("SELECT GROUP_CONCAT(category_id) FROM cscart_categories WHERE (status = 'A' OR status='H') AND id_path like '%".$category_id."/%' ");
        } else {
            $cat_ids = db_get_field("SELECT GROUP_CONCAT(category_id) FROM cscart_categories WHERE (status = 'A' OR status='H') AND id_path like '%/".$category_id."/%' ");
        }
        //echo $category_id.'='.$parent_id.'='.$cat_ids.'<br>';
        return $cat_ids;

    } else {
        return true;
    }
}

//vim skins/basic/customer/blocks/list_templates/products_grid.tpl  - for special_offer_badge for product and search page.
function fn_check_category_meta($category_id) {
    //return db_get_field("SELECT is_meta FROM ?:categories WHERE category_id = ?i", $category_id);    
    //return db_get_field("SELECT show_product_listing FROM ?:categories WHERE category_id = ?i", $category_id);
    $sql = "SELECT show_product_listing, is_meta FROM ?:categories WHERE category_id = $category_id ";
    $catArr = db_get_row($sql);
    $ret = 'N';
    if($catArr['is_meta']=='N') {
         if($catArr['show_product_listing']=='Y'){
            $ret = 'Y';
         }else{
            $ret = 'N';
         }
    }
    return $ret;
}

function fn_get_products_cat_solr($params,$items_per_page,$cids) {
     
    //Returns the product's details at category page using solr.
    /*** added for solr query caching ***/

    $key = json_encode($params);
    $key = md5($key);
    $memcache = $GLOBALS['memcache'];
    if (check_memcache_clear_condition()) {
        // Unset the memcache store if the request variable 'clean' is set 
        // and a user is logged in.
        $memcache->delete($key);
    }
    if(Registry::get('config.memcache') && $GLOBALS['memcache_status'] && ($cached_value = $memcache->get($key)) !== false)
        return $cached_value;               
    
    else{

        if(!empty($params)) {

            $client = new SolrClient(Registry::get('config.solr_url'));
            $query = new SolrQuery();
            $sort_by = $params['sort_by'];
            $sort_order = $params['sort_order'];
            
            if(count($cids) == 1 && isset($params['category_id']))
                $query->setQuery('category_id:'.$params['category_id']);
            elseif(count($cids) == 1 && isset($params['category']))
                $query->setQuery('category_id:'.$params['category']);
            else{
                $qstr = implode(",",$cids);            
                $term = str_replace(",", ' OR category_id:', $qstr);
                $query->setQuery('category_id:'.$term);                
            }
            $query->setParam('wt', 'xml');
            if($items_per_page != 0){
                $offset = ($params['page']-1)*32;
                $query->setStart($offset);
                $query->setRows($items_per_page);                              
            }
            else
                $query->setRows($params['limit']);                              

            /*if(!empty($sort_by)) { 
                if(strtolower($sort_order)=="asc") {
                    $query->addSortField("product_amount_available");
                    $query->addSortField($sort_by, SolrQuery::ORDER_ASC);
                } else {
                    $query->addSortField("product_amount_available");
                    $query->addSortField($sort_by);
                }     
            }*/

            try {
                     if (defined('PROFILER')) {
                        Profiler::set_query("Solr Query:"."/solr/select?".$query, "");
                    }
                    $solrResult = $client->query($query)->getResponse();
                    $prd = $solrResult->response->docs;
                    $products = array();
                    if(!empty($prd)) {
                            foreach($prd as $pr=>$prod) {
                                    $products[] = (array) $prod;
                            }                            
                    }
                    if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
                                $memcache->set($key, $products, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));
                    return $products;
                  
            } catch (Exception $e) {
                    error_log("fn_get_products_cat_solr=".$e->getMessage(), 1, "vinay.gupta@shopclues.com");
                    return false;
            }
        }
    }
}


function fn_get_category_facets($category_id) {

   
    if(!empty($category_id)) {
        
        $solr_filters = Registry::get('config.solr_filters');
        $flag = (!empty($solr_filters)) ? '1' : '0';
            
        $productsArr = array();

        $key = 'category_filters'.$flag;
        $memcache = $GLOBALS['memcache'];
        if (check_memcache_clear_condition()) {
            // Unset the memcache store if the request variable 'clean' is set 
            // and a user is logged in.
            $memcache->delete($key);
        }
      if(Registry::get('config.memcache') && $GLOBALS['memcache_status'] && ($productsArr = $memcache->get($key)) !== false) {
           //echo 'memcached';
           $productsArr = unserialize($productsArr);

        } else {

            $sql = "SELECT pf.feature_id, REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(pfd.filter), ' ','_'), '\'',''), '.',''), '&', 'and'), '/', '_or_') as filters, pf.categories_path FROM `cscart_product_filters` pf LEFT JOIN `cscart_product_filter_descriptions` pfd ON pf.filter_id = pfd.filter_id WHERE pf.status = 'A' AND pf.added_in_search = 'Y' AND pfd.filter != '' AND pf.feature_id != '0' AND pf.feature_id != '53' order by pf.position asc";
            $arr1 = db_get_array($sql);

            if(Registry::get('config.show_global_filters')) {
                $sql2 = "select cpo.option_id as feature_id, REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(CONCAT('o_',LOWER(trim(cpod.option_name))), ' ','_'), '\'',''), '.',''), '&', 'and'), '/', '_or_') as filters,cpo.categories_path as categories_path from cscart_product_options cpo INNER JOIN cscart_product_options_descriptions cpod ON cpo.option_id = cpod.option_id where cpo.status = 'A' AND cpo.is_filter = 'Y' AND cpo.added_in_search = 'Y' AND product_id = 0 and categories_path != '' order by position ASC";
                $arr2 = db_get_array($sql2);            
            } else {
                $arr2 = array();
            }

            if(!empty($arr2)) {
                $filterArr = array_merge($arr1,$arr2);
            } else {
                $filterArr = $arr1;
            }            
           

            foreach($filterArr as $val) {
                $str_filters = str_replace('(', '', $val['filters']);
                $str_filters = str_replace(')', '', $str_filters);
                
                if(strstr($val['categories_path'], ",")) {
                    
                    $catArr = explode(",", $val['categories_path']);

                    foreach($catArr as $catids) {
                        if($catids!='' && $str_filters!='') {
                            $productsArr[$catids][] = $str_filters;
                        }
                    }
                } else {
                        $productsArr[$val['categories_path']][] = $str_filters;
                }                
            }
            
             //for is_trm and merchant filters in category            
            if(!empty($solr_filters)) {           
                $sql2 = "SELECT group_concat(category_id) as root_id FROM cscart_categories WHERE parent_id = 0";
                $arr = db_get_row($sql2);
                $catArr1 = explode(",",$arr['root_id']);
                $catArr = array_flip($catArr1);
if(!empty($productsArr) && !empty($catArr)) {
     $productsArr = $productsArr + $catArr;
}
               

                foreach($productsArr as $key=>$val) {               
                    foreach($solr_filters as $filtername) {
                        if(!is_array($productsArr[$key]))  $productsArr[$key] = array();
                        $productsArr[$key][] = $filtername;
                    }
                }
            }
            
            //echo "<pre>";print_r($productsArr); die;
            if(Registry::get('config.memcache') && $GLOBALS['memcache_status']) {
                $status = $memcache->set($key, serialize($productsArr), MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));
                if(!$status){
                    $memcache->delete($key);
                }
            } else {
              //echo "not set"; 
            }

        }

        $category_facets = array();
        $category_facets = $productsArr[$category_id];
        
        if(empty($category_facets) && !empty($solr_filters)) {
            foreach($solr_filters as $filtername) {               
               $category_facets[] = $filtername;
           }
        }
        return $category_facets;
    }
    
    if(empty($category_id)) {
        $solr_filters = Registry::get('config.solr_filters');
        return $solr_filters;
    }
    
}

function fn_remove_filter($params) {
    //echo 'calling';
    //print_r($params);die;
    $arr = array('br','fq','df','product_amount_available','fsrc','sp','bp','sprice','eprice');
   //print_r($arr);die;
    foreach($params as $key=>$val) {
        if(in_array($key, $arr)) {
            unset($params[$key]);
        } else {
            if(is_array($val)) {
                unset($params[$key]);
            } else {
                $params[$key] = $val;
            }
        }
    }   
    //print_r($params);die;

    $output = implode('&', array_map(function ($v, $k) { return $k . '=' . $v; }, $params, array_keys($params)));
    return 'index.php?'.$output;
}
function fn_adword_view($product_id,$type,$keyword,$position){
     $session_id= Session::get_id();
     //echo "insert into clues_adword_view set product_id='".$product_id."',type='".$type."',session_id='".$session_id."',keyword='".addslashes($keyword)."',position='".$position."'";
     db_query("insert into clues_adword_view set product_id='".$product_id."',type='".$type."',session_id='".$session_id."',keyword='".addslashes($keyword)."',position='".$position."'");
}

function fn_other_sellers_same_product($product){
    $fq = '';
    $bq = '';
    //echo '<pre>'; print_r($product); echo '</pre>'; die;
   //$brand_id = $product['product_features']['2553']['subfeatures']['53']['variant_id'];
  
   $category_id = '';
   foreach($product['category_ids'] as $key=>$val){
       //echo $key.' = '.$val.'<br>';
       $category_id .= 'category_ids:*/'.$key.' OR category_ids:'.$key.' ';
   }

   $product_id = $product['product_id'];   
   $company_id = $product['company_id'];
   $product_name = $product['product'];

    if(!empty($product_name)) {   

        list($bclient, $bquery) = fn_solr_connect(); 
        //$term = preg_replace('/\s+/', '+', addslashes(trim($term))); // removes spaces with +
        
        //q="Nokia asha 501"&fq=category_id:1431&defType=dismax&qf=product&mm=2&bq=brand_id:67148^10.0&group=true&group.field=company_id
        $mm = Registry::get('config.min_match');      
        $bquery->setQuery('"'.addslashes(strtolower($product_name)).'"');
        //$bquery->setParam('fl','id product company_id');
        //$bquery->setParam('omitHeader','true');
            
        $bquery->setParam('defType','dismax');
        $bquery->setParam('qf','product');
        $bquery->setParam('mm', $mm); 

        $other_sellers_limit = Registry::get('config.other_sellers_limit'); 
        if(!empty($other_sellers_limit)) $bquery->setRows($other_sellers_limit);

        $bquery->setFacet(true);
        
        // check for status 
        $bquery->addFilterQuery("metacategory_status:A");
        $bquery->addFilterQuery("product_amount_available:1");
   if(!empty($category_id)) {
        $category_id = rtrim($category_id,' ');
        $bquery->addFilterQuery($category_id);
    }
   
   /*if($brand_id != '') {
        $fq = trim($category_id,' ').' brand_id:'.$brand_id;
   } else {
        $fq = trim($category_id,' ');
   }*/

  
   //$fq .= ' -product_id:'.$product_id.' -company_id:'.$company_id;
   $bquery->addFilterQuery('-product_id:'.$product_id);
   $bquery->addFilterQuery('-company_id:'.$company_id);
   
  if(Registry::get('config.other_sellers_percentage')) {
    $percentage = Registry::get('config.other_sellers_percentage');
    $product_price = $product['price'];
    $percentage_amt = round(($product_price * Registry::get('config.other_sellers_percentage'))/100);
    $min_price = $product_price - $percentage_amt;
    $max_price = $product_price + $percentage_amt;

    //echo '<br>pp='.$product_price.' = '.$percentage.' = '.$percentage_amt.' = '.$min_price.' = '.$max_price;
    
    $priceFilter = 'price:['.$min_price.' TO '.$max_price.']';
    $bquery->addFilterQuery($priceFilter);
  }

        if($bq != '') $bquery->setParam('bq',$bq);
        //if($fq != '') $bquery->setParam('fq',$fq);
        
  $bquery->setParam('group.format','simple');
  $bquery->setParam('group.main','true');
  $bquery->setParam('group','true');
  $bquery->setParam('group.field','company_id');

  $bquery->addSortField('is_trm');
  //$bquery->addSortField('avg_rate');
  $bquery->addSortField('sort_price', SolrQuery::ORDER_ASC);

        if (defined('PROFILER')) {
          Profiler::set_query("Solr fn_other_sellers_same_product : ".$bquery, "");
        }        

        $bResult = $bclient->query($bquery)->getResponse();
        
        $bcount = $bResult->response->numFound;
        //echo 'count='.$bcount;
        if($bcount > 0) {          
            $sameproduct = $bResult->response->docs;
            return $sameproduct;
        } else {
            //echo '<br>No Records Found.<br><br>';
            return false;
        }
    }
    //echo '</pre>';
}

function fn_get_subcategory_name($category_id)
{ 
	if (!empty($category_id)) {
            return db_get_field("SELECT cd.category FROM cscart_categories cc LEFT JOIN cscart_category_descriptions cd ON cc.category_id = cd.category_id WHERE cc.parent_id != 0 AND cc.category_id = '". $category_id."' ");
	}
	return false;
}
function fn_track_user($cookie_track_order, $order_info,$product_data,$action) {
   //echo $cookie_track_order;//echo "<pre>";print_r($order_info);
        $cookie_track_order = json_decode($cookie_track_order,true);
        ///echo "<pre>";print_r($cookie_track_order);
        if(!empty($cookie_track_order)){
            if(!empty($order_info)){
            foreach ($order_info['items'] as $items => $order) {
                  $productId = $order['product_id'];
                 if(in_array($productId,$cookie_track_order)){
                    $key = array_search($productId, $cookie_track_order);//die;
                   if(!empty($key)){
                            if(Registry::get('config.track_user_mongo'))
                            {
                            $mongoconfig = Registry::get('config.mongo_log_connect');
                            $mongoconfig['collection']= 'clues_track_user';
                            $exArr = explode("-", $key);
                            $condition = array('$and' => array(array('session_id'=> $exArr[0] ),
    array('product_id'=>$exArr[1])));
                             if ($order_info['is_parent_order'] == 'Y') {
                               $query = array('order_id'=>$order['child'], 'action'=>$action,
                               'user_id'=>$_SESSION['auth']['user_id']);
                                    mongodb_update($mongoconfig, $query, $condition);
                                     }
                                     else{
                                         $query = array('order_id'=>$order['order_id'], 'action'=>$action,
                               'user_id'=>$_SESSION['auth']['user_id']);
                                    mongodb_update($mongoconfig, $query, $condition);
                                           }
                            }  
                            else {
                                  if ($order_info['is_parent_order'] == 'Y') {
                                   $sql = "update clues_track_user set order_id= '".$order['child']."' ,action='".$action."',user_id='".$_SESSION['auth']['user_id']."' where id='".$key."' and product_id='".$productId."'";
                         }else{
                                    $sql="update clues_track_user set order_id= '".$order['order_id']."' ,action='".$action."' ,user_id='".$_SESSION['auth']['user_id']."' where id='".$key."' and product_id='".$productId."'";
                         }
                         db_query($sql);
                            }

                   }
                }    
           }
        //setcookie($_COOKIE['track_order']);
        //echo "<pre>";print_r($_COOKIE['track_order']);
        unset($_SESSION['track_user']);
        //print_r($_SESSION['track_user']);
      }
      if(!empty($product_data)){
            foreach ($product_data as $key => $data){
            if(!empty($key) && is_int($key)){
                $productId=$key;
            }
          }
       $productId = (string)($productId);
            if(in_array($productId,$cookie_track_order)){
                        $key = array_search($productId, $cookie_track_order);
                   if(!empty($key)){
                       if(Registry::get('config.track_user_mongo'))
                            {
                           $mongoconfig = Registry::get('config.mongo_log_connect');
                            $mongoconfig['collection']= 'clues_track_user';
                            $exArr = explode("-", $key);
                            $condition = array('$and' => array(array('session_id'=> $exArr[0] ),
    array('product_id'=>$exArr[1])));
                            $query = array('action'=>$action,
                               'user_id'=>$_SESSION['auth']['user_id']);
                                    mongodb_update($mongoconfig, $query, $condition);
                       }
                        else {
                                                  $sql = "update clues_track_user set action='".$action."' ,user_id='".$_SESSION['auth']['user_id']."' where id='".$key."' and product_id='".$productId."'";
                                                db_query($sql);
                        }

                   }
            }
      }
    }
}

function fn_get_category_for_piwik($product_id) {
    $sql = "SELECT cpd.category as main,ccd1.category as leaf FROM cscart_products_categories cpc
            join cscart_categories cc on cc.category_id=cpc.category_id
            join cscart_category_descriptions cpd on cpd.category_id=SUBSTRING_INDEX(SUBSTRING_INDEX(id_path, '/',2), '/',1)
            join cscart_category_descriptions ccd1 on ccd1.category_id=cpc.category_id
            where cpc.product_id='" . $product_id . "' and cpc.link_type='M'";
    $product_cat = db_get_row($sql);
    $output = addslashes(js_array(array_values($product_cat)));
    return $output;
}

function js_str($product_category) {
    return '"' . addcslashes($product_category, "\0..\37\"\\") . '"';
}

function js_array($product_category) {
    $temp = array_map('js_str', $product_category);
    return '[' . implode(',', $temp) . ']';
}

function fn_get_source_for_cookie($referrer)
{ 
     $value=strpos($referrer,".html");
     $dispatch=explode("dispatch=",$referrer);
   
    if(empty($dispatch[1])&& empty($value)){
	  $source="HP:index";
    }
    elseif(strstr($dispatch[1],"products.search")){
          $parsed = parse_url( $referrer, PHP_URL_QUERY );
                    parse_str($parsed, $query );
              //$source_value=substr(strstr(addslashes($dispatch[0]),"q="),0,-1);
	     //$source_value=ltrim($source_value,"q=");
             if(!empty($query['q'])){
               $source.="Search:".addslashes($query['q']);
            }
    }
    elseif(!empty($value)){
          	$pos = strrpos($referrer, "/");
                $category= explode(".html",ltrim(preg_replace('/^([^,]*).*$/', '$1', substr($referrer, $pos)),'/'));
                $sql="select object_id from cscart_seo_names where name='".$category[0]."' and type='c'";
                $category_id=db_get_field($sql);
            if(!empty($category_id)){
                      $source.="Category:".$category_id;	
             }
   } 
   elseif(empty($value)){
        if(stristr($dispatch[1],"categories.view")){
           $parsed = parse_url($referrer, PHP_URL_QUERY );
                     parse_str($parsed, $queryArr);
                     $source_arr=$queryArr['category_id'];
          if(!empty($source_arr)){
                   $source.="Category:".$source_arr; 
          }
        }   
    }   
    
    return $source; 
}
//Function for Blocks using MONGODB 
function fn_mongo_connect($module) {
   
        if($module == 'block') {

            $host = Registry::get('config.mongo_block_connect.host');
            $connect = Registry::get('config.mongo_block_connect.db');
            $dbcollection = Registry::get('config.mongo_block_connect.collection');
            $user = Registry::get('config.mongo_block_connect.username');
            $pass = Registry::get('config.mongo_block_connect.password');
        }
        
      
        try {
            $userArr = array("username" => $user, "password" => $pass);
                $mongo = new MongoClient("mongodb://$host/$connect",$userArr);
                $db = $mongo->selectDB($connect);
                $collection = new MongoCollection($db, $dbcollection);

                return $collection;
        } 
        catch (Exception $e) {
            
            error_log("fn_mongo_connect=".$e->getMessage(), 1, "vinay.gupta@shopclues.com");
            return false;
        
        }
}

function fn_get_block_mongo($param) {
    
    /*** added for memcache block query caching ***/
    $key = json_encode($param);
    $key = md5($key);
    if (defined('PROFILER') && Registry::get('config.mongo_memcache')) {
        Profiler::set_query("mongo memcache_block_key: ".$key."");
    }
        
    $memcache = $GLOBALS['memcache'];

    if (check_memcache_clear_condition()) {
        // Unset the memcache store if the request variable 'clean' is set 
        // and a user is logged in.
        $memcache->delete($key);
    }
    if(Registry::get('config.memcache') && $GLOBALS['memcache_status'] && Registry::get('config.mongo_memcache') && ($cached_value = $memcache->get($key)) !== false) {
        return $cached_value;
    } else {
        $collection = fn_mongo_connect('block');
        if(!empty($collection)) {
            $field1 = array("object_id"=> array('$in' =>array('0',$param['object_id'])));
            $field2=array("block_type"=> array('$in' =>array($param['block_type'][0],$param['block_type'][1])));
            $field3=array("status" => $param['status']);
            $field4=array("assigned" => $param['assigned']);

            $data = array('block_id'=>1,'text_id'=>1,'block_type'=>1,'location'=>1,'disabled_locations'=>1,'status'=>1,'properties'=>1,'company_id'=>1,'description'=>1,'item_ids'=>1,'assigned'=>1);
            $param=array('$and'=>array($field1, $field2, $field3, $field4));
            $blocks1= mongo_find($collection, $param, $data);
            $blocks= array();

            foreach ($blocks1 as $doc) {
                $blocks[ $doc['block_id']]=$doc;    
            }
            
            if(Registry::get('config.memcache') && $GLOBALS['memcache_status']) {
                        $memcache->set($key, $blocks, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));            
            }
            
            return $blocks; 
        } else {
            return false;
        }
    }        
} 

function mongo_find( $collection, $param, $data) {
   
            if( !is_null($param) ){

                    if(!is_null( $data)){
                        $ret = $collection->find($param,$data)->timeout(-1);
                    }  else {
                        $ret = $collection->find($param)->timeout(-1);
                    }
                    if (defined('PROFILER')) {
                        Profiler::set_query("mongo mongo_find: ".var_export($ret->explain(),true)."");
                    }
                    return $ret;                                        
            } 
            else {           
                    return false;
                 }
}
function fn_track_product_view($cookie,$productId,$action,$session_id,$session_user_id) {  
        $productId = (string)($productId);
         $pos = strrpos($cookie,$productId);
        if($pos){
            $product_source = preg_replace('/^([^,]*).*$/', '$1', substr($cookie, $pos));
            list($product_id, $page, $page_value) = explode(":", $product_source);
            if (!empty($product_id) && !empty($page) && !empty($page_value)) {
                    $session_id = Session::get_id();
                    $user_agent=$_SERVER['HTTP_USER_AGENT'];
                    $host=$_SERVER['HTTP_HOST'];
                    $host_arr=explode(".",$host);
                   if(!empty($host_arr)){
                        if($host_arr[0] =='www'){
                            $host_value='W';
                        }
                        elseif($host_arr[0] =='m'){
                            $host_value='M';
                        }
                        else{
                            $host_value='W';
                        }
                   }
                   else{
                      $host= Registry::get('config.track_user_cookie');
                        $host_arr=explode(".",$host);
                        if($host_arr[0] =='www'){
                            $host_value='W';
                        }
                        elseif($host_arr[0] =='m'){
                            $host_value='M';
                        }
                        else{
                            $host_value='W';
                        }
                   }
                    

                    $search_array=array('S','Z');
                    if(!in_array($search_source,$search_array)){

                        if(Registry::get('config.zettata_debugg')){

                            $message=" Search Source=".$search_source." -- Cookie=".$product_source."\n";
//                            log_to_file('cookie_requests',$message);
                        }
                        $search_source='';
                    }
                   if(!in_array($productId,$_SESSION['track_user']))
                {
                        if(Registry::get('config.track_user_mongo'))
                        {
                            $new_data  =array(
                        "product_id" => $productId,
                        "page" => $page ,
                        "page_value" => addslashes($page_value),
                        "server" => gethostname(),
                        "useragent" => addslashes($user_agent) ,
                        "session_id" => $session_id,
                        "search_source" => $search_source ,
                        "action" => $action,
                        "user_id" => $session_user_id,
                        "site_source" => $host_value,
                        "timestamp" => date("Y-m-d H:i:s")
                        );                       
                            try
                            {       
                            $mongoconfig = Registry::get('config.mongo_log_connect');
                            $mongoconfig['collection']= 'clues_track_user';
                                $datas=mongodb_insert($mongoconfig, $new_data);
                                if(!empty($datas)){
                                    $key = $session_id.'-'.$productId;
                                 $track_user= array($key => $productId);
                            }
                                if(!empty($_SESSION['track_user']))
                                    $_SESSION['track_user']= $_SESSION['track_user'] + $track_user;
                                else
                                    $_SESSION['track_user']=$track_user;
                            }
                            catch(MongoConnectionException $e)
                            {
                                error_log("mongo_promotion_logging addons/cluesearch/func.php =".$e->getMessage(), 1, "vinay.gupta@shopclues.com");

   }
                        }
                        else
                        {
                       $sql = "insert into clues_track_user(product_id,page,page_value,server,useragent,session_id,search_source,action,user_id,site_source) values ('".$productId."','".$page."','".addslashes($page_value)."','".gethostname()."','".addslashes($user_agent)."','".$session_id."','".$search_source."','".$action."','".$session_user_id."','".$host_value."')"; 
                        $last_insert = db_query($sql);
                        if(!empty($last_insert))
                             $track_user= array($last_insert => $productId);

                        if(!empty($_SESSION['track_user']))
                             $_SESSION['track_user']= $_SESSION['track_user'] + $track_user;
                        else
                             $_SESSION['track_user']=$track_user;
                  }
            }
        }
        }
         $track_user= $_SESSION['track_user'];
         if(!empty($track_user)){
                    $value=json_encode($track_user);
                    $domain=Registry::get('config.cookie_domain');
                    $expire=time()+Registry::get('config.track_user_cookie');
                    setcookie("scto", $value , $expire, "/",$domain);
             
       }
}

function fn_check_meta_category($category_id){

       $parent_id = db_get_field("SELECT parent_id FROM cscart_categories WHERE category_id = ". $category_id);

         if($parent_id == 0) {

            $cat_ids = db_get_field("SELECT GROUP_CONCAT(category_id) FROM cscart_categories WHERE (status = 'A') AND id_path like '%".$category_id."/%' ");
       
              } else {
            $cat_ids = 0;

             }
         return $cat_ids;
}

function get_zettata_data($params) {

//echo "<pre>";print_r($params);die;

    $auth_key=Registry::get('config.zettata_authkey');
    $zettata_url=Registry::get('config.zettata_url');
    if(!empty($params['pp'])) {
        
        $zettata_rows=$params['pp'];
    
    }else{
        $zettata_rows=Registry::get('settings.Appearance.products_per_page');
    
    }
     
        $term=urlencode($params['q']);

        $cat='';
        if(!empty($params['cid'])){

            $cat="&filter=category_id%3A".$params['cid'];

        }

        if(!empty($params['sp'])){
                $price=  explode(",", $params['sp']);
                $pr="sort_price:[".$price[0]." TO ".$price[1]."]";
                $price_slider="&filter=".urlencode($pr);
            }

        if($params['auto_suggest'] == '1'){
            $auto_suggest="&isAutosuggest=true";
        }

        $filter='';
        if(!empty($params['fac'])){
            //echo "<pre>";print_r($params['fac']);
            $fil=array();
            foreach ($params['fac'] as $doc) {
                
                $fil=explode("@",$doc);
                
                //$filter=implode('&filter=', $fil);
                $filter=$filter."&filter=".$fil[1];

            }

        }

       $sort='';
        if(!empty($params['sort_by'])){

            $sort=$params['sort_by']." ". $params['sort_order'];
            $sort_en="&sort=".urlencode($sort);
        }

        $all_filter=$cat.$filter.$price_slider;

        if(!empty($params['page'])){

            $page="&page=".$params['page'];
        }

        if($params['spellcorrect']!=''){

            $spellcorrect="&spellCorrect=false";
        }

                try{

                    $url=$zettata_url."/select?q=".$term."&authKey=".$auth_key."&rows=".$zettata_rows.$all_filter.$sort_en.$page.$spellcorrect.$auto_suggest;
                    if (defined('PROFILER')) {
                          Profiler::set_query("Zettata ---- Line 2184 ".$url, "");
                        }

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 500);
                    $output = curl_exec($ch);
                    //echo "<pre>";print_r($output);die;
                   
                    $outputJsonArr  = json_decode(utf8_encode($output),TRUE);
                    //echo "<pre>";print_r($outputJsonArr);die;
                    curl_close($ch);
                    return $outputJsonArr;
                    }
                    catch(Exception $e){
                        echo $e->getMessage();
                        }
}

function fn_get_zettata_response($url, $request_headers, $cookie,$pincode="") {

    $results = array();
    if(Registry::get("config.pincode_zettata_track") && $pincode!="") {
        if(ANDROID_API == 'TRUE') {

            $key_url = $url."&uLocation=latlong:".$pincode;
        }else{

            $key_url = $url."&uLocation=pincode:IN:$pincode";
        }
        $key = md5($key_url);
        $url=$key_url;
    } else {
        $key = md5($url);
    }
    if (defined('PROFILER')) {
                          Profiler::set_query("Zettata new url---- Line 3024 ".$url, "");
    }
    $memcache = $GLOBALS['memcache'];
    if (check_memcache_clear_condition()) {
        // Unset the memcache store if the request variable 'clean' is set 
        // and a user is logged in.
        $memcache->delete($key);
    }
    if(Registry::get('config.memcache') && Registry::get('config.new_search_memcache') && $GLOBALS['memcache_status'] && ($zresponse = $memcache->get($key)) !== false) {
        //echo "found";
        $results = unserialize($zresponse);
        //echo '<br>memcached = '; print_r($results);
    } else {        
        //echo "not found";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 500);
        $output = curl_exec($ch);
        //echo "<pre>";print_r($output);die;       
        $results  = json_decode(utf8_encode($output),TRUE);
        //echo "<pre>";print_r($results);
        curl_close($ch);                    
        $numFound = $results['response']['numFound'];
        if($numFound > 0 && Registry::get('config.memcache') && Registry::get('config.new_search_memcache') && $GLOBALS['memcache_status']) {
            $status = $memcache->set($key, serialize($results), MEMCACHE_COMPRESSED, Registry::get('config.zettata_memcache_expire_time'));
            if(!$status){
                $memcache->delete($key);
            }
         //echo "<br>SET KEY";
        } else {
          //echo "not set"; 
        }
    }

    return $results;
}

//lijo
function show_zettata_result($params){

    
    $zet_arr=get_zettata_data($params);
    //unset($zet_arr['facet_counts']['facet_fields']['is_trm'][0]);
    //unset($zet_arr['facet_counts']['facet_fields']['isCod'][0]);
    if($zet_arr['facet_counts']['facet_fields']['is_trm'][0]['name']==0){

        unset($zet_arr['facet_counts']['facet_fields']['is_trm'][0]);
    }else{
        unset($zet_arr['facet_counts']['facet_fields']['is_trm'][1]);
   
    }
    if($zet_arr['facet_counts']['facet_fields']['isCod'][0]['name']==0){

        unset($zet_arr['facet_counts']['facet_fields']['isCod'][0]);
    }else{
        unset($zet_arr['facet_counts']['facet_fields']['isCod'][1]);
   
    }
    //echo "<pre>";print_r($zet_arr); die;

        $main=array();
        $main['suggestion']=$zet_arr['spellCorrectedQuery'];
        
    foreach ($zet_arr['facet_counts']['facet_fields']['category_s'] as $key=>$val) {
        
        //$main['category']=''; $category=array_pop(explode('>', $val['name']));
        $cat_array=explode('>', $val['name']);
        $category=array_pop($cat_array);

      
        if(count($cat_array)!=0){

            $main['category'][$cat_array[0]][$val['id']]['cat_id']=$val['id'];
            $main['category'][$cat_array[0]][$val['id']]['cat_name']=$category;
            $main['category'][$cat_array[0]][$val['id']]['count']=$val['numDocs'];
            $main['category'][$cat_array[0]][$val['id']]['filter']=$val['filter'];
        }else{
            
            $main['category'][$category][$val['id']]['cat_id']=$val['id'];
            $main['category'][$category][$val['id']]['cat_name']=$category;
            $main['category'][$category][$val['id']]['count']=$val['numDocs'];
            $main['category'][$category][$val['id']]['filter']=$val['filter'];  
        }
        //echo "<pre>";print_r($main);die;   
   
    }
    $main['products_count']=$zet_arr['response']['numFound'];

    
    if(!empty($params['pp'])) {
        
             $main['items_per_page'] = $params['pp'];
        } else {
             $main['items_per_page'] = Registry::get('settings.Appearance.products_per_page');
        }
    
    $i=0;
    foreach ($zet_arr['response']['docs'] as $doc) {
        
        $new=$doc['newarrivals'];
        foreach ($doc as $key => $val) {
            $main['products'][$i][$key]=$val;
            $main['products'][$i]['timestamp']=$new;
        }
         
      

        $i++;
     }

foreach ($zet_arr['facet_counts']['facet_fields'] as $key=>$doc) {

    if($key!='category_s' && $key!='Category'){

        $k=0;
        foreach ($doc as $key1 => $val) {

            if($val['name'] !=''){
                $main['facet'][$key][$k]=$val;
                $k++; 
            }
        }
    }
            
}

$i=0;
foreach ($zet_arr['facet_counts']['facet_ranges']['sort_price'] as $key=>$val) {

        $main['price'][$i]['key']=$val['start']." TO ".$val['end'];
        $main['price'][$i]['val']=$val['numDocs'];  
        $main['price'][$i]['filter']=$val['filter'];       
        $i++;
    }
$i=0;
foreach ($zet_arr['facet_counts']['facet_ranges']['discount_percentage'] as $key=>$val) {

        $main['discount_percentage'][$i]['key']=$val['start']." TO ".$val['end'];
        $main['discount_percentage'][$i]['val']=$val['numDocs'];  
        $main['discount_percentage'][$i]['filter']=$val['filter'];       
        $i++;
    }

    $main['price_slider']['min']=intval($zet_arr['stats']['stats_fields']['sort_price']['min']);  
    $main['price_slider']['max']=intval($zet_arr['stats']['stats_fields']['sort_price']['max']);  

//echo "<pre>";print_r($main); die;
return $main;
}

//lijo1
function fn_search_usage(){
            $z='';
            $time=time();
            $val=$time%9;
            $zettata_threshold=Registry::get('config.zettata_threshold');
            $master_switch=Registry::get('config.zettata_master_switch');

        if($master_switch){

            if($val < $zettata_threshold){
                 
                    $z=1;

                }else{

                    $z=0;
                }
        }else{
                $z=0;
        }
          

          if($_SESSION['search_source']==''){                
            $_SESSION['search_source'] = ($z==1) ? 'Z' : 'S';
          }  
    
    return $z;
}

function fn_zettata_showprice($key) {

    $fq = Registry::get('config.fn_zettata_showprice');
    
    return $fq[$key];
}

function fn_zettata_showdiscount($key) {

    $fq = Registry::get('config.fn_zettata_showdiscount');
    
    return $fq[$key];
}

function fn_price_check($filter){

            $item=explode("%3A",$filter);
            $item_find="price:".$item[1];

            $fq = Registry::get('config.fn_zettata_pricekey');
            return $fq[urldecode($item_find)];

}

function fn_discount_check($filter){

            $df = Registry::get('config.fn_zettata_discountkey');
            return $df[urldecode($filter)];

}

//lijo2
function fn_map_zettata_to_solr($params){


    if(!empty($params['fac'])){
            //echo "<pre>";print_r($params['fac']);
            $fil=array();
            foreach ($params['fac'] as $doc) {
                
                $fil=explode("@",$doc);
                
                $item=array();
                if($fil[0]=='brand'){

                    $item=explode("%3A",$fil[1]);
                    $params['br'][]=$item[1];

                }else if($fil[0]=='price'){

                    $item=explode("%3A",$fil[1]);
                    $item_find="price:".$item[1];

                    $fq = Registry::get('config.fn_zettata_pricekey');
                    $params['fq'][]=$fq[urldecode($item_find)];

                }else if($fil[0]=='discount'){

                    
                    $df = Registry::get('config.fn_zettata_discountkey');
                    $params['df'][]=$df[urldecode($fil[1])];

                }else if($fil[0]=='inStock'){

                    $params['product_amount_available'][]=1;

                }
            }

        }
    unset($params['fac']);

if(!empty($params['fsrc'])){

        $fil=explode(":",$params['fsrc']);

        if($fil[0]=='brand'){

                    $item=explode("%3A",$fil[1]);
                    $params['fsrc']='brand:'.$item[1];

                }else if($fil[0]=='price'){

                    $item=explode("%3A",$fil[1]);
                    $item_find="price:".$item[1];

                    $fq = Registry::get('config.fn_zettata_pricekey');
                    $params['fsrc']="price:".$fq[urldecode($item_find)];

                }else if($fil[0]=='discount'){
                    
                    $df = Registry::get('config.fn_zettata_discountkey');
                    $params['fsrc']="discount_percentage:".$df[urldecode($fil[1])];

                }else if($fil[0]=='inStock'){
                    
                    $params['fsrc']="product_amount_available:1";

                }

    }

//echo "<pre>";print_r($params);die;

    return $params;

}

function fn_map_solr_to_zettata($params){

    if(!empty($params['br'])){

        foreach ($params['br'] as $key => $val) {
          $params['fac'][]="brand@brand_id%3A".$val;
        
        }
        unset($params['br']);
    }


    if(!empty($params['fq'])){

        foreach ($params['fq'] as $key => $val) {

            $fq = Registry::get('config.fn_zettata_pricekey');
            $key = array_search($val, $fq); 
            $key_val=explode(":",$key);
            $params['fac'][]="price@sort_price%3A".urlencode($key_val[1]);
        
        }
        unset($params['fq']);
    }

    if(!empty($params['df'])){

        foreach ($params['df'] as $key => $val) {

            $fq = Registry::get('config.fn_zettata_discountkey');
            $key = array_search($val, $fq); 
            $params['fac'][]="discount@".urlencode($key);
        
        }
        unset($params['df']);
        
    }

    if(!empty($params['product_amount_available'])){

        $params['fac'][]="inStock@inStock:true";
        unset($params['product_amount_available']);
    }

    if(!empty($params['fsrc'])){

        $fil=explode(":",$params['fsrc']);

        if($fil[0]=='brand'){

                    
                    $params['fsrc']='brand:brand@brand_id%3A'.$fil[1];

                }else if($fil[0]=='price'){
                    
                    $fq = Registry::get('config.fn_zettata_pricekey');
                    $key = array_search($fil[1], $fq);
                    $key_val=explode(":",$key);
                    $params['fsrc']="price:price@sort_price%3A".urlencode($key_val[1]);

                }else if($fil[0]=='discount_percentage'){
                    
                    $df = Registry::get('config.fn_zettata_discountkey');
                    $key = array_search($val, $df); 
                    $params['fsrc']="discount:discount@".urlencode($key);

                }else if($fil[0]=='product_amount_available'){
                    
                    $params['fsrc']="inStock:inStock@inStock:true";

                }

    }
    

    //echo "<pre>";print_r($params);die;



    return $params;
}

//lijo3
function fn_switch_condition($params){
//echo "<pre>";print_r($params);
    $master_switch=Registry::get('config.zettata_master_switch');
    
  if(!empty($params['fac']) && (($master_switch ==1  && $params['z']==0) || (empty($master_switch) && $params['z']==1) || (empty($master_switch) && $params['z']==0)) ){
            
            $switch_condition=1;

    }else if(empty($params['fac']) && ($master_switch == 1 && $params['z']==1) && empty($params['company_id']) && $params['retain'] != 1){
            $switch_condition=2;

            }else if(!empty($params['fac']) && $master_switch ==1 && $params['z']==1 && empty($params['company_id']) && $params['retain'] != 1){

                $switch_condition=3;
            }else{

                $switch_condition=4;
            }

        return $switch_condition;
}

?>

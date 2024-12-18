<?php

define('AREA', 'C');
define('AREA_NAME', 'customer');

require 'config.local.php';



$term = strtolower($_REQUEST['term']);
$term1=urlencode($term);
if(!empty($term)){

if($config['zettata_master_switch']) {
	
	$auth_key=$config['zettata_authkey'];
	$zettata_url=$config['zettata_url'];

        

                try{

                    $url=$zettata_url."/suggest?q=".$term1."&authKey=".$auth_key."&rows=10";
                    //echo $url;
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 500);
                    $output = curl_exec($ch);
                    curl_close($ch);
                    $outputJsonArr  = json_decode($output,TRUE);
                    //echo "<pre>";print_r($outputJsonArr);die;
                    
                    $i=0;
                    if(!empty($outputJsonArr)) {
                        $p = array();
                        foreach($outputJsonArr['suggestions'] as $key=>$val) {
                                 
                                $p[$i]['value'] = strip_tags($val['suggestion']);
                                $p[$i]['label'] = $val['suggestion'];              
                            
                         if($i<6){
                            foreach($val['categories'] as $key=>$value){
                            	$i++;
                            	$fil=explode("%3A",$value['filter']);
                            	$p[$i]['value'] = strip_tags($val['suggestion']);
                                $p[$i]['label'] = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;<strong style='color:#34A5C9;'>".$value['name']."</strong>";
                                $p[$i]['id']	=$fil[1];
                            }
                          }  
                          $i++;	
                            }

                        }

                    

                  //echo "<pre>";print_r($p);die;

                }catch (Exception $e) {
                    
                    echo $e->getMessage();
                }
	echo json_encode($p); die;
	//echo "<pre>";print_r($p);
} else {
	
	$client = new SolrClient($config['solr_url']);
	$query = new SolrQuery();

//http://localhost:8983/solr/shopclue/select?q=*&facet=true&facet.field=brand_keyword&facet.field=metacategory_keyword&facet.field=product_keyword&facet.prefix=c&facet.limit=5&rows=0&facet.method=enum
      	$query->setQuery('*');
      	$query->setFacet(true);
        $query->addFacetField('brand_keyword');
        $query->addFacetField('metacategory_keyword');
        $query->addFacetField('product_keyword');
        //$query->setRows(0);
        $query->setFacetLimit(10);	
        $query->setFacetPrefix($term);
        $query->setFacetMethod('enum');
        //$query->setParam('wt','json');
		//$query->setParam('omitHeader','true'); 

	try {
 
		$solrResult = $client->query($query)->getResponse();

		$brandResp = $categoryResp = $productResp = array();

		$brandResp = $solrResult->facet_counts->facet_fields->brand_keyword;
		$categoryResp = $solrResult->facet_counts->facet_fields->metacategory_keyword;
		$productResp = $solrResult->facet_counts->facet_fields->product_keyword;
		
		$i=0;
		if(!empty($brandResp)) {
			
			foreach($brandResp as $key=>$val) {
				if($i < 3) {			
					$p[$i]['value'] = strip_tags($key);
					$p[$i]['label'] = str_ireplace($term, "<strong style='color:#000000;'>".$term."</strong>", $key);
				$i++;
				}			
			}
		}
		if(!empty($categoryResp)) {
		
			foreach($categoryResp as $key=>$val) {
				if($i < 6) {			
					$p[$max_key+$i]['value'] = strip_tags($key);
					$p[$max_key+$i]['label'] = str_ireplace($term, "<strong style='color:#000000;'>".$term."</strong>", $key);
				$i++;
				}
			}
		}
		if(!empty($productResp)) {		

			foreach($productResp as $key=>$val) {
				if($i < 10) {			
					$p[$max_key+$i]['value'] = strip_tags($key);
					$p[$max_key+$i]['label'] = str_ireplace($term, "<strong style='color:#000000;'>".$term."</strong>", $key);
				$i++;
				}
			}
		}
		
		//echo '<pre>'; print_r($p); die;
		echo json_encode($p); die;		
	} catch (Exception $e) {
		echo $e->getMessage();
	}
 }

}

?>

<?php


function get_products($params){

        if(Registry::get('config.solr')) {
        $limit = 32; 				// Default value for limit if only offset is passed.
        if(isset($params['limit']))
		$limit = $params['limit'];
        $client = new SolrClient(Registry::get('config.solr_url'));
        $query = new SolrQuery();
        if(!empty($params['q'])) {
            $mm = Registry::get('config.min_match');
            $query->setQuery(addslashes($params['q']));
            $query->setParam('defType','dismax');
            $query->setParam('qf','product meta_keywords search_words page_title');
            $query->setParam('mm', $mm);   
            $query->setRows(32);      // to override solr default value of 10.
        }

        //add filter for category_id
        if(isset($params['cid']) && ($params['cid'] != '0')){
            $query->addFilterQuery("metacategory_id:".$params['cid'] ." OR ". "category_id:".$params['cid']);
        }
        
        //add filter for price range
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
        //print_r($params);
        //die();
        
        //add filter for brands
        if(isset($params['br'])){
            $fq2='';
            foreach($params['br'] as $kb=>$vb){
                    if($kb > 0){
                            $fq2 .= ' OR ';
                    }
                    $fq2 .= 'brand_id:'.$vb;
                    print_r($fq2);
            }
            $query->addFilterQuery($fq2);
        }
        if(isset($params['sort_by'])){
            $sort_type = $params['sort_by'];
            if(strtolower($params['sort_order'])=="asc") {
                $query->addSortField($sort_type, SolrQuery::ORDER_ASC);
            } 
            else
                $query->addSortField($sort_type);                               
        }
        
        //add calculate the no of( items to display on the page
        if(isset($params['offset'])){
            $query->setStart($params['offset']);
            $query->setRows($limit);                   
        }       
        //echo $query;
        try{
            $solrResult = $client->query($query)->getResponse();
            $prd = $solrResult->response->docs;
            $products_count = $solrResult->response->numFound;
        }
        catch(Exception $e){
		
        }
        $products = array();
        $products['total_items'] =  $products_count;                    
        foreach($prd as $pr=>$pord){
            $products[] = (array) $pord;
        }
        //echo $products_count; 
        return $products;
    }
}

?>

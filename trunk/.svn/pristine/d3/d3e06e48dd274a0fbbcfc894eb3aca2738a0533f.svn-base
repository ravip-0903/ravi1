<?php
$config['mongoHost'] = "localhost";
$config['mongoDbname'] = "frontend_logs";
$config['log_filename'] = '/home/amit/projects/access_logs/t4.app_metric.txt';

$mongo = new Mongo("mongodb://{$config['mongoHost']}");
$mongodb = $mongo->$config['mongoDbname'];

$document_cache = array();

if(!$mongodb){
    die('Mongo db connection error - ' . implode(":", array_values($config)));
}
        
$c_mongo_app_metric = $mongodb->app_metric;

if(!$c_mongo_app_metric){
    die('Collection "app_metric" not found in current mongo db');
}

$file = new SplFileObject($config['log_filename']);
$file->setFlags(SplFileObject::READ_CSV);

$unique_log_identifiers = array('id', 'server_name', 'request_url', 'client_ip');
$sort_by = array('_id' => -1);

foreach($file as $row){
    //var_dump($row);
    unset($log);
    foreach($row as $kv){
        if(preg_match('/\s*([^=\.]+)=([^\n]*)\s*/', $kv, $m)){
            $log[$m[1]] = $m[2];
        }
    }
    
    if(isset($log)){
        //var_dump($log);
        if(isset($log['id']) && strlen($log['id'])>1){
            unset($q_params);
            $q_params['id'] = $log['id'];
            foreach($unique_log_identifiers as $id){
                if(isset($log[$id]) && strlen($log[$id])>1){
                    $q_params[$id] = $log[$id];
                }
            }
            
            unset($doc_exist);
            $doc_exist = find_document($q_params);
            if($doc_exist){
                $log = array_merge($doc_exist, $log);
            }
            $c_mongo_app_metric->save($log);
        }
    }
}

function find_document($q_params){
    global $document_cache, $sort_by, $c_mongo_app_metric;
    static $ptr=-1, $cache_size=10;
    
    if(count($document_cache) >= 1){
        foreach($document_cache as $doc){
            $doc_match = true;
            foreach($q_params as $k => $v){
                if(!isset($doc[$k]) || $doc[$k] != $v){
                    $doc_match = false;
                    break;
                }
            }
            if($doc_match){
                echo "cac " . $doc['id'] . "\n";
                $d = $c_mongo_app_metric->find(array("_id"=>$doc['_id']))->getNext();
                if($d){
                    return $d;
                }
            }
        }
    }
    
    $doc = $c_mongo_app_metric->find($q_params)->sort($sort_by)->limit(1)->getNext();
    if($doc){
        echo "new " . $doc['id'] . "\n";
        $ptr = ($ptr+1) % $cache_size;
        $document_cache[$ptr] = $doc;
        return $doc;
    }
    return null;
}


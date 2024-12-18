<?php
/**
 * Helper file for PhpApcCollector.py
 * collectes php apc statistics for Graphite with Diamond
 *
 * @author Károly Nagy <n_karoly@ymail.com>
 * http://charlesnagy.info/
 *
 */
$cache      = apc_cache_info();
$cache_user = apc_cache_info('user', 1);
$mem        = apc_sma_info();

$stats = array(
    "mem"=>array(
        "segments"       => (int)$mem['num_seg'],
        "segment_size"   => (int)$mem['seg_size'],
        "total"          => (int)$mem['num_seg'] * $mem['seg_size'],
	"available_memory"       => (int)$mem['avail_mem'],
	"memory_used" => (int)$mem['$total'] - $mem['$mem_avail'],
    ),
    "opcode"=>array(
        "hits"           => (int)$cache['num_hits'],
    ),
    "user"=>array(
        "vars_count" => (int)$cache_user['mem_size'],
    ),
);

echo json_encode($stats);


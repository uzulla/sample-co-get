<?php

$options = getopt("h::n:c:u:q::");

//var_dump($options);
//exit;
if(isset($options['h'])){
    echo "php self.php [-n TOTAL_REQUEST_NUM] [-c CONCURRENT_WORK_NUM] [-h] [-q] [-u http://example.jp]\n";
    exit;
}

$TOTAL_REQUEST_NUM = $options['n'] ?? 10;
$CONCURRENT_WORK_NUM = $options['c'] ?? 5;
$URL = $options['u'] ?? "http://s.cfe.jp/";
$QUIET = isset($options['q']);

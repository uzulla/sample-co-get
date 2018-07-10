<?php

require_once "sw.php";
require_once "getopt.php";

define('MAX_REQ', $TOTAL_REQUEST_NUM); // 規定処理数
define('URL', $URL); // 規定処理数

$req_num = 0;

StopWatch::reset();

while (1) {
    if(!$QUIET) echo ".";

    // 規定処理集を超えたので終了
    if ($req_num >= MAX_REQ) {
        break;
    }

    // 取得
    $req_num++;
    if(!$QUIET) echo "\nGET";
    $_ = file_get_contents(URL);
    if(!$QUIET) echo "\nGET OK";
}

StopWatch::say();

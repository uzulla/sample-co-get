<?php
require_once "sw.php";
require_once "getopt.php";

define('MAX_REQ', $TOTAL_REQUEST_NUM); // 規定処理数
define('MAX_WORKER_NUM', $CONCURRENT_WORK_NUM); // 多重度
define('VERBOSE', !$QUIET);
if(!preg_match("|(https?)://([^/]*)/?(.*)|u", $URL, $m)) {
    throw new \InvalidArgumentException("invalid url");
}

//var_dump($m);
//exit;

define('SCHEME', $m[1]);
define('PORT', ($m[1] === 'https') ? 443 : 80);
define('IS_HTTPS', $m[1] === 'https');
define('HOST', $m[2]);
define('PATH', "/".$m[3]);

go(function () {
    StopWatch::reset();

    $now_worker_num = 0;
    $req_num = 0;

    while (1) {
        // co::sleepを入れて明示でスケジューラに渡せるように
        // いれないと固まったりする
        co::sleep(0.001); // 調節しよう！
        if(VERBOSE) echo ".";

        // 規定処理集を超えたので終了
        if ($req_num >= MAX_REQ) {
            break;
        }

        // 規定ワーカー数を超えていたらワーカーを起動しない
        if ($now_worker_num >= MAX_WORKER_NUM) {
            continue;
        }

        // ワーカーを起動
        // シングルプロセスなので、参照渡しの変数操作をしても基本大丈夫
        go(function () use (&$now_worker_num, &$req_num) {
            $req_num++;
            $now_worker_num++;

            if(VERBOSE) echo "\nGET";
            $http = new \Swoole\Coroutine\Http\Client(HOST, PORT, IS_HTTPS);
            $http->setHeaders([
                'Host' => HOST,
                "User-Agent" => 'my UA',
                'Accept' => 'text/html,application/xhtml+xml,application/xml',
                'Accept-Encoding' => 'gzip',
            ]);
            $http->get(PATH);
            $_ = $http->body;

            if(VERBOSE) echo "\nGET OK";

            $now_worker_num--;

            if ($now_worker_num == 0 && $req_num >= MAX_REQ) {
                // 最後のワーカー終了時
                StopWatch::say();
            }
        });
    }
});


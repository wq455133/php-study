<?php

$data = array(
    'foo'=>'bar',
    'baz'=>'boom',
    'site'=>'www.example.net',
    'name'=>'nowa magic'
);

$data = http_build_query($data);

$options = array(
    'http' => array(
        'method' => 'POST',
        'header' => 'Content-type:application/x-www-form-urlencoded',
        'content' => $data
        //'timeout' => 60 * 60 // 超时时间（单位:s）
    )
);

$url = "http://php-study.test/php/request/test1.php";
$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

echo $result;
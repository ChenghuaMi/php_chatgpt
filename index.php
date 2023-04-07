<?php
$key="换成自己的key";
$header=[
    "Content-Type: application/json",
    "Authorization: Bearer ".$key
];
$data=[
    "model"=>"gpt-3.5-turbo",
    "messages"=>[["role"=>"user","content"=>"php"]],
    "temperature" => 0,
    "stream"=>true,
    "n"=> 1,
];
// $result = curl($header,json_encode($data));

readFiles("data.log");
function readFiles($file) {
    $result = "";
    $content = file_get_contents($file);

    if(trim(substr($content,-6)) == "[DONE]") {
        $info = trim(substr($content,0,-6))." {";
    }
    $info = "} ".$info;
   $infoArr = explode("} data: {",$info);
    array_shift($infoArr);
    array_pop($infoArr);
    foreach($infoArr as $key=>$val) {
        $val = "{".$val."}";
        $item = json_decode($val,true);
        if(isset($item["choices"][0]["delta"]["content"])) {
            $result .= $item["choices"][0]["delta"]["content"];
        }
    }
    echo $result;
}

function curl($header,$data) {
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
    curl_setopt($ch,CURLOPT_URL,'https://openai.1rmb.tk/v1/chat/completions');
    curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
    $result = curl_exec($ch);
    file_put_contents("request.log",$result);
    return $result;
}
<?php

if (!defined("CORE")) {
  responseJSON(403.8, [
    "error" => "非法访问 (not code:http)"
  ]);
}

header("x-powered-by:Rabbitmonth magic.");
header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

/**
 * 指定状态码响应客户端 json类型返回数据
 *
 * @param integer $status 状态码
 * @param [array] $data 响应的数据 assoc键值对数据组
 * @return void
 */
function responseJSON($status, $data = null)
{
  if (gettype($status) == "integer") {
    http_response_code($status);
  } else if (gettype($status) == "string") {
    header($status);
  }

  header("Content-type:application/json");
  print_r(json_encode($data));
  exit();
}

function responseText($status, $info = null)
{
  if (gettype($status) == "integer") {
    http_response_code($status);
  } else if (gettype($status) == "string") {
    header($status);
  }

  if ($info) {
    print_r($info);
  }
  exit();
}

function responseError($message)
{
  echo "<pre style='font-family:微软雅黑;white-space:pre-line;'>";
  echo $message;
  echo "</pre>";
  responseText(500);
}
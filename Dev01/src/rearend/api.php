<?php
if (!defined("CORE")) {
  responseJSON(403.8, [
    "error" => "非法访问 (not code)"
  ]);
}

$requestURL = explode("?", $_SERVER['REQUEST_URI']);

if (count($requestURL) == 0 || $requestURL[0] == "/") {
  responseJSON(403.8, [
    "error" => "非法访问 (not folder)"
  ]);
}
$url = explode("/", $requestURL[0]);
unset($url[0], $url[1]);
$url = array_values($url);

if (count($url) == 2) {
  $fileName = $url[0];
  $method = $url[1];
  $filePath = sprintf("%s/%s.php", CON_DIR, $fileName);
} else if (count($url) == 3) {
  $folder = $url[0];
  $fileName = $url[1];
  $method = $url[2];
  $filePath = sprintf("%s/%s/%s.php", CON_DIR, $folder, $fileName);
} else {
  responseJSON(403.8, [
    "error" => "非法访问 (not method)"
  ]);
}

if (!file_exists($filePath)) {
  responseJSON(403.8, [
    "error" => "非法访问 (File does not exist.)"
  ]);
}

include_once($filePath);
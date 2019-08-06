<?php

preg_match("/(\/\w+\.?\w*)*/", $_SERVER['REQUEST_URI'], $matchs);
$requestPath = $matchs[0];
$url = explode("/", $requestPath);
if (count($url) < 2) {
  responseJSON(404, [
    "error" => "Invalid file.(path)"
  ]);
}
unset($url[0], $url[1]);
$url = array_values($url);
$extendion = pathinfo($requestPath, PATHINFO_EXTENSION);
$filePath = ATTACHMENT_DIR . "/" . implode("/", $url);

if (!file_exists($filePath)) {
  responseJSON(404, [
    "error" => "Invalid file.(file)"
  ]);
}

$finfo = new finfo(FILEINFO_MIME);
$mime = explode(";", $finfo->file($filePath))[0];

header("Content-type: $mime");

readfile($filePath);
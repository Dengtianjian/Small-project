<?php

$methods = [
  "increment"
];

call_user_func("increment");

function increment()
{
  $ip = $_SERVER["REMOTE_ADDR"];
  $isExists = PDODB::fetchCount("sta", " WHERE `in_info`='$ip'");
  if (!$isExists) {
    PDODB::insert([
      "in_info" => $ip
    ], "sta");
  }
  responseJSON(200);
}
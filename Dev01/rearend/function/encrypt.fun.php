<?php

if (!defined("CORE")) {
  responseJSON(403.8, [
    "error" => "非法访问 (not code:encry)"
  ]);
}

/**
 * sha1 加密指定字符串
 *
 * @param string $str 被加密的字符串
 * @param string $salt 加密所需的salt
 * @return string 加密后的密文
 */
function enSha1($str, $salt)
{
  $str .= $salt;
  return sha1($str);
}

/**
 * sha1密文对比
 *
 * @param string $str 未加密的字符串
 * @param string $salt 加密所需的salt
 * @param string $comStr 被对比的密文
 * @return boolean 返回对比结果
 */
function compareSha1Pass($str, $salt, $comStr)
{
  $pass = enSha1($str, $salt);

  if (strcmp($pass, $comStr)) {
    return 1;
  } else {
    return 0;
  }
}
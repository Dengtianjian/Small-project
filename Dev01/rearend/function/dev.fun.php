<?php

if (!defined("CORE")) {
  responseJSON(403.8, [
    "error" => "非法访问 (not code:dev)"
  ]);
}

/**
 * 调式输出信息 不结束
 *
 * @param [String,Array] $output 输出内容
 * @return void
 */
function dbug($output)
{
  print_r("<pre style='font-family:微软雅黑;white-space:pre-line;'>");
  print_r($output);
  print_r("</pre>");
}

/**
 * 断点调式输出
 *
 * @param Array $message
 * @return void
 */
function dbugExit($message = null)
{
  dbug($message);
  exit();
}

/**
 * 生成随机指定长度的字符串
 *
 * @param integer $length 生成的长度 默认是10
 * @return string 随机生成的字符串
 */
function generateRandomStr($length = 10)
{
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, strlen($characters) - 1)];
  }
  return $randomString;
}

function generateRandomNum($length = 5)
{
  $numbers = '0123456789';
  $randomNum = 0;
  for ($i = 0; $i < $length; $i++) {
    $randomNum .= $numbers[rand(0, strlen($numbers) - 1)];
  }
  return $randomNum;
}

/**
 * 加载函数库文件
 *
 * @param string $funFileName 函数文件名称
 * @return void
 */
function includeFun($funFileName)
{
  if (!file_exists(FUN_DIR . "/$funFileName.fun.php")) {
    responseError("加载函数库：文件 （$funFileName） 不存在");
  }
  include_once(FUN_DIR . "/$funFileName.fun.php");
}

/**
 * 加载数据表处理文件
 *
 * @param [string] $folder 文件所在目录
 * @param [string] $tableName 数据表文件名称 对应去掉前缀的数据表名称
 * @return void
 */
function includeTableHandle($folder, $tableName)
{
  if (!file_exists(MYSQL_DB_DIR . "/$folder/$tableName.php")) {
    responseError("加载处理类：目录/文件 （$tableName 不存在");
  }
  include_once(MYSQL_DB_DIR . "/$folder/$tableName.php");
}

function includeClass($classFileName, $folder = null)
{
  if ($folder) {
    $path = CLASS_DIR . "/$folder/$classFileName.class.php";
  } else {
    $path = CLASS_DIR . "/$classFileName.class.php";
  }
  if (!file_exists($path)) {
    responseError("加载类文件：目录/文件 （$classFileName 不存在");
  }
  include_once($path);
}
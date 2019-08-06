<?php

if (!defined("CORE")) {
  responseJSON(404, [
    "error" => "非法访问 (not code:getCourse)"
  ]);
}

$methods = [
  "userLogin",
  "verifyAccount",
  "logout",
  "register"
];


if (!in_array($method, $methods)) {
  responseJSON(404, [
    "error" => "非法访问 (not code:method)"
  ]);
}

call_user_func($method);

function userLogin()
{
  $email = addslashes(trim($_POST['email']));
  $password = addslashes(trim($_POST['password']));
  $remember = addslashes(trim($_POST['remember']));
  if (!$email || !$password) {
    responseJSON(401, [
      "code" => 2,
      "error" => "Missed mailbox or password."
    ]);
  }
  $userInfo = PDODB::fetchOne("SELECT * FROM &t WHERE `user_email`=?", "users", [
    $email
  ]);
  if (gettype($userInfo) != "array") {
    responseJSON(401, [
      "code" => 1,
      "error" => "Email or password error."
    ]);
  }
  includeFun("encrypt");
  $salt = $userInfo['user_salt'];
  $compareResult = compareSha1Pass($password, $salt, $userInfo['user_password']);
  if ($compareResult) {
    $saveDay = $remember == "true" ? 7 : 0;
    $uKey = md5(generateRandomStr() . time());
    $result = PDODB::insert([
      "key_value" => $uKey,
      "key_date" => time(),
      "key_save_day" => $saveDay,
      "key_user" => $userInfo['user_id']
    ], "users_key", "key_value");

    unset($userInfo['user_id'], $userInfo['user_password'], $userInfo['user_salt']);
    responseJSON(200, [
      "data" => [
        "info" => $userInfo,
        "uKey" => $uKey
      ]
    ]);
  } else {
    responseJSON(401, [
      "code" => 1,
      "error" => "Email or password error."
    ]);
  }
}

function verifyAccount()
{
  $uKey = addslashes(trim($_GET['uKey']));
  $result = PDODB::fetchOne("SELECT `key_user` FROM &t WHERE `key_value`=?", "users_key", [
    $uKey
  ]);
  if (gettype($result) == "array") {
    $userInfo = PDODB::fetchOne("SELECT `user_id`,`user_name`,`user_nickname`,`user_email`,`user_regdate`,`user_groupid` FROM &t WHERE `user_id`=?", "users", [
      $result['key_user']
    ]);
    responseJSON(200, [
      "data" => $userInfo
    ]);
  } else {
    responseJSON(401.1, [
      "error" => "Key expired."
    ]);
  }
}

function logout()
{
  $uKey = trim($_POST['uKey']);
  if ($uKey) {
    PDODB::delete("users_key", " WHERE `key_value`=?", [
      $uKey
    ]);
  }
  responseJSON(200, [
    "result" => "true"
  ]);
}

function register()
{
  $email = addslashes(trim($_POST['email']));
  $password = addslashes(trim($_POST['password']));
  if (!$email || !$password) {
    responseJSON(403, [
      "code" => 1,
      "error" => "Missed mailbox or password."
    ]);
  }
  if (!preg_match("/\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,12}/", $email)) {
    responseJSON(403, [
      "code" => 3,
      "error" => "Entered mailbox error."
    ]);
  }
  if (!preg_match("/^.*(?=.{6,12})(?=.*\d+)(?=.*[a-z]).*$/", $password) || strlen($password) > 12 || strlen($password) < 6) {
    responseJSON(403, [
      "code" => 2,
      "error" => "Invalid password."
    ]);
  }
  includeFun("encrypt");
  $salt = crypt(generateRandomStr(20), "RM_");
  $password = enSha1($password, $salt);

  $result = PDODB::insert([
    "user_name" => "",
    "user_nickname" => "",
    "user_email" => $email,
    "user_regdate" => time(),
    "user_password" => $password,
    "user_salt" => $salt
  ], "users");

  if (isset($result['error'])) {
    switch ($result['code']) {
      case 1:
        responseJSON(403, [
          "code" => 4,
          "error" => "The mailbox has been registered."
        ]);
      case 2:
        responseJSON(500, [
          "code" => 5,
          "error" => "Server internal error."
        ]);
        break;
    }
  } else {
    responseJSON(200, [
      "result" => true
    ]);
  }
}
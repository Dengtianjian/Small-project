<?php

if (!defined("CORE")) {
  exit("No access.");
}

$methods = [
  "publishCourse", "uploadCover", 'deleteCover'
];
if (!in_array($method, $methods)) {
  exit("Access denied");
}

$coursesTable = "courses";
$usersKeyTable = "users_key";
call_user_func($method);

function uploadCover()
{
  $file = $_FILES['file'];
  include_once(FUN_DIR . "/upload.fun.php");
  $result = upload::image($file, ["course", 'cover'], 2097152, ['png', 'jpg', 'jpeg']);
  if (isset($result['error'])) {
    switch ($result['code']) {
      case 1:
        responseJSON(415, [
          "error" => "File too large.",
          "code" => 1
        ]);
        break;
      case 2:
        responseJSON(415, [
          "error" => "File type not allowed.",
          "code" => 2
        ]);
        break;
      case 3:
        responseJSON(415, [
          "error" => "File type not allowed.",
          "code" => 3
        ]);
        break;
    }
  } else {
    responseJSON(200, $result);
  }
}

function deleteCover()
{
  $deletePath = ATTACHMENT_DIR . "/" . $_POST['saveFolder'] . $_POST['completeFileName'];
  if (file_exists($deletePath)) {
    $result = unlink($deletePath);
    if ($result) {
      responseJSON(200, []);
    }
  }
}

function publishCourse()
{
  global $PDO, $coursesTable, $usersKeyTable;

  if (!isset($_POST['uKey'])) {
    responseJSON(401, [
      "error" => "Not logged in.",
      "code" => 1
    ]);
  } else {
    $uKey = addslashes(trim($_POST['uKey']));
    $result = $PDO->RM_fetchFirst($usersKeyTable, "SELECT `key_user` FROM &t WHERE `key_value`=? LIMIT 1 ", [
      $uKey
    ]);
    if ($result['key_user'] != $_POST['course_user']) {
      responseJSON(401, [
        "error" => "Verification failed.",
        "code" => 2
      ]);
    }
  }

  foreach ($_POST as $itemKey => &$item) {
    if (!$item) {
      responseJSON(403, [
        "error" => "Required fields are not filled in.",
        "code" => 1
      ]);
    }
    if ($itemKey == "course_description") {
      continue;
    }
    $item = addslashes(trim($item));
  }

  $courseStrID = generateRandomStr(rand(10, rand(15, 20))) . date("Ymd", time());

  $result = $PDO->RM_insert($coursesTable, [
    'course_strid' => $courseStrID,
    'course_name' => $_POST['course_name'],
    'course_cover' => $_POST['course_cover'],
    'course_source_link' => $_POST['course_source_link'],
    'course_publish_date' => $_POST['course_publish_date'],
    'course_introduction' => $_POST['course_introduction'],
    'course_type' => $_POST['course_type'],
    'course_description' => $_POST['course_description'],
    'course_user' => $_POST['course_user']
  ]);

  if (isset($result['error'])) {
    switch ($result['code']) {
      case 1:
      case 2:
        responseJSON(500, [
          "error" => "Server internal error.",
          "code" => 1
        ]);
        break;
    }
    return;
  }
  $courseStrID = $courseStrID . $result;
  $result = $PDO->RM_update($coursesTable, [
    'course_strid' => $courseStrID
  ], "WHERE `course_id`= $result");

  responseJSON(200, [
    "data" => $courseStrID
  ]);
}
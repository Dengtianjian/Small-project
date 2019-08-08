<?php

if (!defined("CORE")) {
  responseJSON(404, [
    "error" => "非法访问 (not code:getCourse)"
  ]);
}

$methods = [
  "publishCourse",
  "uploadCover",
  "deleteCover",
  "getCoursesLimit",
  "getCourseBySId"
];

if (!in_array($method, $methods)) {
  responseJSON(404, [
    "error" => "非法访问 (not code:method)"
  ]);
}

includeTableHandle("course", "courses");
$coursesHandle = new courses();

call_user_func($method);

function publishCourse()
{
  global $coursesHandle;
  // if (!isset($_POST['uKey'])) {
  //   responseJSON(401, [
  //     "error" => "Not logged in.",
  //     "code" => 1
  //   ]);
  // } else {
  //   $uKey = addslashes(trim($_POST['uKey']));
  //   $result = $PDO->RM_fetchFirst($usersKeyTable, "SELECT `key_user` FROM &t WHERE `key_value`=? LIMIT 1 ", [
  //     $uKey
  //   ]);
  //   if ($result['key_user'] != $_POST['course_user']) {
  //     responseJSON(401, [
  //       "error" => "Verification failed.",
  //       "code" => 2
  //     ]);
  //   }
  // }

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

  $result = $coursesHandle->publish([
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

  responseJSON(200, [
    "data" => $result
  ]);
}

function uploadCover()
{
  includeClass("upload");
  $result = Upload::up($_FILES['file'], ["course", 'cover'], 4194304);
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
  $deletePath = ATTACHMENT_DIR . "/" . $_POST['fileFolder'];
  if (file_exists($deletePath)) {
    $result = unlink($deletePath);
    if ($result) {
      responseJSON(200, []);
    }
  }
}

function getCoursesLimit()
{
  global $coursesHandle;
  $page = intval($_GET['page']);
  $limit = intval($_GET['limit']);
  $fields = isset($_GET['fields']) ? addslashes(trim($_GET['fields'])) : null;
  $order = isset($_GET['order']) ? addslashes(trim($_GET['order'])) : null;
  $courses = $coursesHandle->getAllCourses($page, $limit, $fields, $order);

  if (isset($courses['error'])) {
    response(500, $courses);
  }

  responseJSON(200, $courses);
}

function getCourseBySId()
{
  global $coursesHandle;
  if (!isset($_GET['courseSId'])) {
    responseJSON(400, [
      "error" => "Missed course sid."
    ]);
  }
  $courseSId = addslashes(trim($_GET['courseSId']));
  $fields = null;
  if (isset($_GET['fields'])) {
    $fields = addslashes(trim($_GET['fields']));
  }
  $course = $coursesHandle->getCourseByStrID($courseSId, $fields);

  if ($course) {
    responseJSON(200, [
      "data" => $course
    ]);
  }

  responseJSON(403, [
    "error" => "Invalid course id."
  ]);
}
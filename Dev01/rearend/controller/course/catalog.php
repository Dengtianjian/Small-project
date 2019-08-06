<?php

if (!defined("CORE")) {
  responseJSON(404, [
    "error" => "非法访问 (not code:getCourse)"
  ]);
}

$methods = [
  "modify",
  "getCatalogByCourseSId",
  "getVideoByChapter",
  "removeVideoByChapter",
  "uploadVideo",
  "deleteChapterByChapterSId"
];


if (!in_array($method, $methods)) {
  responseJSON(404, [
    "error" => "非法访问 (not code:method)"
  ]);
}


includeTableHandle("course", "courses");
$coursesHandle = new Courses();
includeTableHandle("course", "catalog");
$catalogHandle = new Catalog();
includeClass("course_catalog", "course");

call_user_func($method);

function modify()
{
  global $catalogHandle;
  $courseSId = addslashes(trim($_POST['courseSId']));
  $catalog = json_decode($_POST['catalog'], true);
  foreach ($catalog as $catalogIndex => $catalogItem) {
    if (strpos($catalogItem['parent']['chapter_sid'], "new_parent") === 0) {
      $parentID = $catalogHandle->addChapter([
        "chapter_name" => $catalogItem['parent']['chapter_name'],
        "chapter_up" => 0,
        "chapter_course" => $courseSId,
        "chapter_sort" => $catalogIndex + 1
      ]);
      $parentSID = generateRandomStr(5) . $parentID * generateRandomNum(2);
      PDODB::update("courses_catalog", " WHERE `chapter_id`=$parentID", [
        "chapter_sid" => $parentSID
      ]);

      foreach ($catalogItem['child'] as $childIndex => $childChapter) {
        $subID = $catalogHandle->addChapter([
          "chapter_name" => $childChapter['chapter_name'],
          "chapter_up" => $parentSID,
          "chapter_course" => $courseSId
        ]);
        $childSID = generateRandomStr(5) . $subID * generateRandomNum(2);
        PDODB::update("courses_catalog", " WHERE `chapter_id`=$subID", [
          "chapter_sid" => $childSID
        ]);
      }
    } else {
      $catalogHandle->updateChapter([
        "chapter_name" => $catalogItem['parent']['chapter_name'],
        "chapter_sort" => $catalogIndex + 1
      ], $catalogItem['parent']['chapter_sid']);
      foreach ($catalogItem['child'] as $childIndex => $childChapter) {
        if (strpos($childChapter['chapter_sid'], "new_child") === 0) {
          $subID = $catalogHandle->addChapter([
            "chapter_name" => $childChapter['chapter_name'],
            "chapter_up" => $catalogItem['parent']['chapter_sid'],
            "chapter_course" => $courseSId,
            "chapter_sort" => $childIndex + 1
          ]);
          $childSID = generateRandomStr(5) . $subID * generateRandomNum(2);
          PDODB::update("courses_catalog", " WHERE `chapter_id`=$subID", [
            "chapter_sid" => $childSID
          ]);
        } else {
          $catalogHandle->updateChapter([
            "chapter_name" => $childChapter['chapter_name'],
            "chapter_up" => $childChapter['chapter_up'],
            "chapter_sort" => $childIndex + 1
          ], $childChapter['chapter_sid']);
        }
      }
    }
  }

  $catalogsData = $catalogHandle->getCatalogByCourseStrId($courseSId);
  $chapters = CourseCatalog::chapterSorting($catalogsData);
  responseJSON(200, [
    "data" => $chapters
  ]);
}

function deleteChapterByChapterSId()
{
  global $catalogHandle;
  $chapterSId = addslashes(trim($_GET['chapterSId']));
  $chapterType = addslashes(trim($_GET['type']));
  if ($chapterType == "parent") {
    $catalogHandle->deleteChaptersByParentStrId($chapterSId);
  }
  $catalogHandle->deleteChapterByChapterStrId($chapterSId);
  responseJSON(200, [
    "result" => true
  ]);
}

function getCatalogByCourseSId()
{
  global $catalogHandle;
  if (!isset($_GET['courseSId'])) {
    responseJSON(400, [
      "error" => "Missed course id."
    ]);
  }

  $courseStrID = addslashes(trim($_GET['courseSId']));

  $isExists = PDODB::fetchCount("courses", "WHERE `course_strid`=?", [
    $courseStrID
  ]);
  if (!$isExists) {
    responseJSON(404, [
      "error" => "Invalid course sid"
    ]);
  }

  $catalogsData = $catalogHandle->getCatalogByCourseStrId($courseStrID);
  if ($catalogsData == 0) {
    responseJSON(200, [
      "data" => []
    ]);
  }
  $chapters = CourseCatalog::chapterSorting($catalogsData);
  responseJSON(200, [
    "data" => $chapters
  ]);
}

function getVideoByChapter()
{
  global $catalogHandle;
  if (!isset($_GET['chapterSId'])) {
    responseJSON(403, [
      "error" => "Missed chapter id."
    ]);
  }

  $chapterStrId = addslashes(trim($_GET['chapterSId']));

  $chapterVideo = $catalogHandle->getVideoByChapterSID($chapterStrId);
  if (!$chapterVideo) {
    responseJSON(403, [
      "error" => "Invalid chapter id."
    ]);
  }
  $chapterVideo['video_fileinfo'] = json_decode($chapterVideo['video_fileinfo']);

  responseJSON(200, [
    "responseTime" => time(),
    "data" => $chapterVideo
  ]);
}

function removeVideoByChapter()
{
  global $catalogHandle;
  if (!isset($_POST['chapterSID'])) {
    responseJSON(404, [
      "error" => "Missed chapter id."
    ]);
  }

  $chapterStrId = addslashes(trim($_POST['chapterSID']));

  $isExists = PDODB::fetchCount("courses_catalog", " WHERE `chapter_sid`=?", [
    $chapterStrId
  ]);
  if (!$isExists) {
    responseJSON(404, [
      "error" => "Chapter does not exists."
    ]);
  }
  $videoData = $catalogHandle->getVideoByChapterSID($chapterStrId);

  $fileInfo = json_decode($videoData['video_fileinfo'], true);
  $fileFolder = ATTACHMENT_DIR . "/" . $fileInfo['fileFolder'];
  if (file_exists($fileFolder)) {
    unlink($fileFolder);
  }
  $catalogHandle->removeVideoByChapterStrId($chapterStrId);

  responseJSON(200, [
    "result" => true
  ]);
}

function uploadVideo()
{
  global $catalogHandle;
  includeClass("upload");
  $chapterSId = addslashes($_POST['chapterSId']);
  $courseSId = addslashes(trim($_POST['courseSId']));
  $isExists = PDODB::fetchCount("courses_catalog", " WHERE `chapter_sid`=?", [
    $chapterSId
  ]);
  if (!$isExists) {
    responseJSON(403, [
      "error" => "Invalid chapter ID",
      "code" => 1
    ]);
  }

  $result = Upload::up($_FILES["file"], ["course", "catalog", $courseSId], 5368709120, ["mp4"]);
  if (isset($result['error'])) {
    switch ($result['code']) {
      case 1:
        responseJSON(403, [
          "code" => 2,
          "error" => "File too large."
        ]);
        break;
      case 2:
        responseJSON(403, [
          "code" => "3",
          "error" => "File type not allowed."
        ]);
        break;
      case 3:
        responseJSON(500, [
          "code" => "1",
          "error" => "Server internal error."
        ]);
        break;
    }
  } else {
    $oldFile = $catalogHandle->getVideoByChapterSID($chapterSId);
    if ($oldFile) {
      $fileInfo = json_decode($oldFile['video_fileinfo'], true);
      $filePath = ATTACHMENT_DIR . "/" . $fileInfo['fileFolder'];
      if (file_exists($filePath)) {
        unlink($filePath);
      }
      $catalogHandle->removeVideoByChapterStrId($oldFile['video_chapter']);
    }

    $catalogHandle->insertVideoFileInfo([
      "video_strid" => $result['fileName'],
      "video_course" => $courseSId,
      "video_fileinfo" => json_encode($result),
      "video_date" => time(),
      "video_chapter" => $chapterSId
    ]);
    responseJSON(200, [
      "data" => [
        "video_strid" => $result['fileName'],
        "video_course" => $courseSId,
        "video_fileinfo" => $result,
        "video_date" => time(),
        "video_chapter" => $chapterSId
      ]
    ]);
  }
}
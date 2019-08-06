<?php


class Catalog
{

  private $catalogTable = "courses_catalog";
  private $catalogVideoTable = "courses_catalog_videos";

  function addChapter($fiedlValue)
  {
    return PDODB::insert($fiedlValue, $this->catalogTable);
  }

  function updateChapter($fiedlValue, $chapterSID)
  {
    $result = PDODB::update($this->catalogTable, " WHERE `chapter_sid`='$chapterSID'", $fiedlValue);
  }

  function getCatalogByCourseStrId($courseStrID, $fields = null)
  {
    if ($fields) {
      if (gettype($fields) == "array") {
        $fields = join(",", $fields);
      }
    } else {
      $fields = "*";
    }

    $course = PDODB::fetchAll("SELECT $fields FROM &t WHERE `chapter_course`=? ORDER BY `chapter_sort`", $this->catalogTable, [
      $courseStrID
    ]);
    return $course;
  }

  function deleteChapterByChapterStrId($chapterStrId)
  {
    return PDODB::delete($this->catalogTable, " WHERE `chapter_sid`=?", [
      $chapterStrId
    ]);
  }

  function deleteChaptersByParentStrId($parentStrId)
  {
    return PDODB::delete($this->catalogTable, " WHERE `chapter_up`=?", [
      $parentStrId
    ]);
  }

  function getVideoByChapterSID($chapterSID)
  {
    return PDODB::fetchOne("SELECT * FROM &t WHERE `video_chapter`=? ", $this->catalogVideoTable, [
      $chapterSID
    ]);
  }

  function removeVideoByChapterStrId($chapterSID)
  {
    return PDODB::delete($this->catalogVideoTable, " WHERE `video_chapter`=?", [
      $chapterSID
    ]);
  }

  function insertVideoFileInfo($insertData)
  {
    return PDODB::insert($insertData, $this->catalogVideoTable);
  }
}
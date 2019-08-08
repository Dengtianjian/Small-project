<?php

class Courses
{
  private $coursesTable = "courses";

  function publish($fieldsValue)
  {
    $courseStrId = generateRandomStr(rand(10, rand(15, 20))) . date("Ymd", time());
    $fieldsValue["course_strid"] = $courseStrId;
    $result = $courseId = PDODB::insert($fieldsValue, $this->coursesTable);
    if (isset($result['error'])) {
      return $result;
    }

    $newStrid = $courseStrId . ($courseId * generateRandomNum(2));
    $result = PDODB::update($this->coursesTable, " WHERE `course_id`=$courseId", [
      "course_strid" => $newStrid
    ]);
    if (isset($result['error'])) {
      return $result;
    }
    return $newStrid;
  }

  function getAllCourses($start = null, $limit = null, $fields = null, $order = null)
  {
    if ($fields) {
      if (gettype($fields) == "array") {
        $fields = join(",", $fields);
      }
    } else {
      $fields = "*";
    }

    $sql = "SELECT $fields FROM &t";
    if ($order) {
      $sql .= " " . PDODB::orderSql($order);
    }

    if ($start && $limit) {
      if ($start < 0) {
        $start = 0;
      }
      $sql .= " LIMIT $start,$limit";
    } else if ($limit) {
      $sql .= " LIMIT $limit";
    }

    $courses = PDODB::fetchAll($sql, $this->coursesTable);

    return $courses;
  }

  function getCourseByStrID($coursesID, $fields = null)
  {
    if ($fields) {
      if (gettype($fields) == "array") {
        $fields = join(",", $fields);
      }
    } else {
      $fields = "*";
    }

    $course = PDODB::fetchOne("SELECT $fields FROM pre_courses WHERE `course_strid`=?", $this->coursesTable, [
      $coursesID
    ]);
    return $course;
  }
}
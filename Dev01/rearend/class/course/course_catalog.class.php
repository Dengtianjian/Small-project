<?php

class CourseCatalog
{

  static function chapterSorting($chaptersData)
  {
    $chapters = [];
    foreach ($chaptersData as $catalogItem) {
      if ($catalogItem['chapter_up'] == '0') {
        $child = [];
        foreach ($chaptersData as $catalogChildItem) {
          if ($catalogChildItem['chapter_up'] == $catalogItem['chapter_sid']) {
            array_push($child, $catalogChildItem);
          }
        }
        array_push($chapters, [
          "parent" => $catalogItem,
          "child" => $child
        ]);
      }
    }
    return $chapters;
  }
}
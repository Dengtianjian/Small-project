<?php

if (!defined("CORE")) {
  responseJSON(401, [
    "error" => "非法访问"
  ]);
}

class Upload
{
  static function up($file, $destination, $maxSize = 2097152, $allowedExtension = ['jpg', 'jpeg', 'png'])
  {
    if ($file['size'] > $maxSize) {
      return [
        "code" => 1,
        "error" => "File too large."
      ];
    }
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    if (!in_Array($fileExtension, $allowedExtension)) {
      return [
        "code" => 2,
        "error" => "File type not allowed."
      ];
    }
    $saveDir = ATTACHMENT_DIR . "/";
    $relativeDir = "/attachment/";
    $saveFolder = "";
    foreach ($destination as $des) {
      $saveDir .= "$des/";
      $relativeDir .= "$des/";
      $saveFolder .= "$des/";
    }
    if (!file_exists($saveDir)) {
      mkdir($saveDir, null, true);
    }
    $newFileName = generateRandomStr(8) . "_" . time();
    $completeFileName = "$newFileName.$fileExtension";
    $completeSaveFile = $saveDir . $completeFileName;
    if (file_exists($completeSaveFile)) {
      $newFileName = generateRandomStr(10) . "_" . time() . "." . $fileExtension;
      $completeFileName = "$newFileName.$fileExtension";
    }
    $completeSaveFile = $saveDir . $completeFileName;
    $relativeDir .= $completeFileName;

    $result = move_uploaded_file($file['tmp_name'], $completeSaveFile);
    if ($result) {
      return [
        "fileName" => $newFileName,
        "completeFileName" => $completeFileName,
        "saveDir" => $completeSaveFile,
        "relative" => $relativeDir,
        "saveFolder" => $saveFolder,
        "fileFolder" => $saveFolder . $completeFileName
      ];
    } else {
      return [
        "code" => 3,
        "error" => "Upload failed,Server internal error."
      ];
    }
  }

  static function image($file, $destination, $maxSize = 2097152, $allowedExtension = ['jpg', 'jpeg', 'png'])
  {
    if ($file['size'] > $maxSize) {
      return [
        "code" => 1,
        "error" => "File too large."
      ];
    }
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    if (!in_Array($fileExtension, $allowedExtension)) {
      return [
        "code" => 2,
        "error" => "File type not allowed."
      ];
    }
    $saveDir = ATTACHMENT_DIR . "/";
    $relativeDir = "./attachment/";
    $saveFolder = "";
    foreach ($destination as $des) {
      $saveDir .= "$des/";
      $relativeDir .= "$des/";
      $saveFolder .= "$des/";
    }
    $realtiveFolder = $relativeDir;
    if (!file_exists($saveDir)) {
      mkdir($saveDir, null, true);
    }
    $saveFile = "";
    $newFileName = generateRandomStr(8) . "_" . time();
    $completeFileName = "$newFileName.$fileExtension";
    $saveFile = $saveDir . $completeFileName;
    if (file_exists($saveFile)) {
      $newFileName = generateRandomStr(10) . "_" . time() . "." . $fileExtension;
      $completeFileName = "$newFileName.$fileExtension";
    }
    $saveFile = $saveDir . $completeFileName;
    $relativeDir .= $completeFileName;

    $result = move_uploaded_file($file['tmp_name'], $saveFile);
    if ($result) {
      return [
        "fileName" => $newFileName,
        "completeFileName" => $completeFileName,
        "saveDir" => $saveDir,
        "completeSaveDir" => $saveFile,
        "relative" => $relativeDir,
        "relativeFolder" => $realtiveFolder,
        "saveFolder" => $saveFolder,
        "fileDir" => $saveFolder . $completeFileName
      ];
    } else {
      return [
        "code" => 3,
        "error" => "Upload failed,Server internal error."
      ];
    }
  }
}
<?php

define("CORE", "YES");

define("ROOT", __DIR__); //根目录
define("CON_DIR", ROOT . "/controller"); //逻辑处理目录
define("FUN_DIR", ROOT . "/function"); //函数库目录
define("MYSQL_DB_DIR", ROOT . "/database");  //MYSQL数据库操作目录
define("CLASS_DIR", ROOT . "/class"); //类库目录
define("ATTACHMENT_DIR", ROOT . "/attachment"); //附件目录
define("ATTACHRELATIVE_DIR", "/attachment");

include_once("./function/dev.fun.php");
includeFun("http");

include_once("./database/pdodb.php");

error_reporting(E_ALL);
set_error_handler(
  function ($error_no, $error_msg, $error_file, $error_line) {
    echo <<<EOT
    error_no:$error_no <br/>
    error_message:$error_msg <br/>
    error_file:$error_file <br/>
    error_line:$error_line <br/>
EOT;
  },
  E_ALL | E_STRICT
);
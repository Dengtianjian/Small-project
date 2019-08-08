<?php

require_once("./core.php");
if (strpos($_SERVER['REQUEST_URI'], "api")) {
  include_once("./api.php");
} else if (strpos($_SERVER['REQUEST_URI'], "static")) {
  include_once("./static.php");
}
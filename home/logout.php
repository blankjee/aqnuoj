<?php

require_once('../includes/config.inc.php');
require_once("../includes/my_func.inc.php");

isLogined();
    session_start();
    //清空SESSION
    $_SESSION = array();
    session_unset();
    session_destroy();
    header("Location: index.php");
?>

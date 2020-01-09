<?php
require_once('../includes/config.inc.php');
require_once("../includes/my_func.inc.php");

isLogined();

    session_start();
    unset($_SESSION[$OJ_NAME.'_'.'user_id']);
    session_destroy();
    header("Location: /home/");
?>

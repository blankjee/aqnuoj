<?php @session_start();
	ini_set("display_errors","ON");  // DEBUG 模式应改为 ON
	ini_set("session.cookie_httponly", 1);   
	header('X-Frame-Options:SAMEORIGIN');

// connect db 
static 	$DB_HOST="localhost";
static 	$DB_NAME="aqnuoj";
static 	$DB_USER="root";
static 	$DB_PASS="";


require_once(dirname(__FILE__)."/pdo.php");

	if(isset($OJ_CSRF)&&$OJ_CSRF&&$OJ_TEMPLATE=="bs3"&&basename($_SERVER['PHP_SELF'])!="problem_judge")
		 require_once(dirname(__FILE__)."/csrf_check.php");


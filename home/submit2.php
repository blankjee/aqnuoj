<?php

require_once('../includes/config.inc.php');
require_once("../includes/my_func.inc.php");

isLogined();

require_once "../includes/memcache.php";
require_once "../includes/const.inc.php";


/*获取系统当前信息*/
$now = strftime("%Y-%m-%d %H:%M", time());
$user_id = $_SESSION[$OJ_NAME . '_' . 'user_id'];

//确定问题类型
if (isset($_POST['id'])){
    $ispractice = true;
}else{
    $ispractice = false;
}

/*间隔限制*/
if (!$OJ_BENCHMARK_MODE) {
    $sql = "SELECT count(1) FROM solution WHERE result<4";
    $result = mysql_query_cache($sql);
    $row = $result[0];

    if ($row[0] > 10) {         //如果未处理的solution超过50个，开启验证。
        $OJ_VCODE = true;
    }

    if ($OJ_VCODE) {
        $vcode = $_POST["vcode"];
    }
    $err_str = "";
    $err_cnt = 0;
    if (
        $OJ_VCODE &&
        ($_SESSION[$OJ_NAME . '_' . "vcode"] == null ||
            $vcode != $_SESSION[$OJ_NAME . '_' . "vcode"] ||
            $vcode == "" ||
            $vcode == null)
    ) {
        $_SESSION[$OJ_NAME . '_' . "vcode"] = null;
        $err_str = $err_str . "Verification Code Wrong!\\n";
        $err_cnt++;
        exit(0);
    }
}

/*查询问题的权限*/
if ($ispractice == false) {
    $pid = intval($_POST['pid']);
    $cid = intval($_POST['cid']);

    $sql = "SELECT problem_id, 'N' from contest_problem 
				where num='$pid' and contest_id='$cid'";
} else {
    $id = intval($_POST['id']);
    $sql = "SELECT problem_id,defunct from problem where problem_id='$id'";
    if (!isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) {
        $sql .= " and defunct='N' ";
    }
    $sql .= " and problem_id not in (select distinct problem_id from contest_problem where contest_id IN (
			SELECT contest_id FROM contest WHERE 
			(end_time>'$now' or private=1) ) )";      //and `defunct`='N'  隐藏的私有比赛题目依旧隐藏
}

$res = mysql_query_cache($sql);

if (isset($res) && count($res) < 1 && !isset($_SESSION[$OJ_NAME . '_' . 'administrator']) && !((isset($cid) && $cid <= 0) || (isset($id) && $id <= 0))) {
    $view_errors = "Where do find this link? No such problem.<br>";
    exit(0);
}
if ($res[0][1] != 'N' && !isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) {
    //	echo "res:$res,count:".count($res);
    //	echo "$sql";
    $view_errors = "Problem disabled.<br>";
    if (isset($_POST['ajax'])) {
        echo $view_errors;
    } else {
    }
    exit(0);
}

$test_run = false;
if ($ispractice == true) {
    $id = intval($_POST['id']);
    $test_run = $id <= 0;
} else {
    $pid = intval($_POST['pid']);
    $cid = intval($_POST['cid']);
    $test_run = $cid < 0;
    if ($test_run) {
        $cid = -$cid;
    }
    // check user if private
    $sql =
        "SELECT private FROM contest WHERE contest_id='$cid' AND start_time<='$now' AND end_time>'$now'";

    $result = pdo_query($sql);
    $rows_cnt = count($result);

    /*权限判断*/
    if ($rows_cnt != 1) {
        echo "您不能提交！因为该竞赛已经关闭或您不具有权限！";
        exit(0);
    } else {
        $row = $result[0];
        $isprivate = intval($row[0]);

        if ($isprivate == 1 && !isset($_SESSION[$OJ_NAME . '_' . 'c' . $cid])) {
            $view_errors = "您没有提交的权限！\n";
            exit(0);
        }
    }
    $sql =
        "SELECT problem_id FROM contest_problem WHERE contest_id='$cid' AND num='$pid'";
    $result = pdo_query($sql);
    $rows_cnt = count($result);
    if ($rows_cnt != 1) {
        $view_errors = "No Such Problem!\n";
        exit(0);
    } else {
        $row = $result[0];
        $id = intval($row['problem_id']);
        if ($test_run) {
            $id = -$id;
        }
    }
}

$language = intval($_POST['language']);
/*对语言选择做容错性判断*/
if ($language > count($language_name) || $language < 0) {
    $language = 0;
}
$language = strval($language);

$source = $_POST['source'];

//var_dump(get_magic_quotes_gpc());exit;

if (get_magic_quotes_gpc()) {
    $source = stripslashes($source);
}


$source_user = $source;
if ($test_run) {
    $id = -$id;
}

//use append Main code
//$prepend_file = "$OJ_DATA/$id/prepend." . $language_ext[$language];
$prepend_file = "$OJ_DATA\\$id\\prepend." . $language_ext[$language];   //Windows下
if (isset($OJ_APPENDCODE) && $OJ_APPENDCODE && file_exists($prepend_file)) {
    $source = file_get_contents($prepend_file) . "\n" . $source;
}
//$append_file = "$OJ_DATA/$id/append." . $language_ext[$language];
$append_file = "$OJ_DATA\\$id\\append." . $language_ext[$language];     //Windows下
//echo $append_file;
if (isset($OJ_APPENDCODE) && $OJ_APPENDCODE && file_exists($append_file)) {
    $source .= "\n" . file_get_contents($append_file);
    //echo "$source";
}
//end of append
if ($language == 6) {
    $source = "# coding=utf-8\n" . $source;
}
if ($test_run) {
    $id = 0;
}

$len = strlen($source);
//echo $source;

//setcookie('lastlang', $language, time() + 360000);

$ip = $_SERVER['REMOTE_ADDR'];
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $REMOTE_ADDR = $_SERVER['HTTP_X_FORWARDED_FOR'];
    $tmp_ip = explode(',', $REMOTE_ADDR);
    $ip = htmlentities($tmp_ip[0], ENT_QUOTES, "UTF-8");
}
if ($len < 2) {
    $view_errors = "代码太短了<br>";
    exit(0);
}
if ($len > 65536) {
    $view_errors = "代码太长了<br>";
    exit(0);
}

if (!$OJ_BENCHMARK_MODE) {
    // last submit
    $now = strftime("%Y-%m-%d %X", time() - 10);
    $sql =
        "SELECT in_date from solution where user_id='$user_id' and in_date>'$now' order by in_date desc limit 1";
    $res = pdo_query($sql);
    if (count($res) == 1) {
        $view_errors =
            "You should not submit more than twice in 10 seconds.....<br>";
        exit(0);
    }
}

if (~$OJ_LANGMASK & (1 << $language)) {
    if ($ispractice == true) {
        $sql = "insert INTO solution(problem_id, user_id, in_date, language, ip, code_length, result)
		VALUES(?, ?, ?, ?, ?, ?, 14)";
        $insert_id = pdo_query(
            $sql,
            $id,
            $user_id,
            $now,
            $language,
            $ip,
            $len
        );
     
    } else {
        $sql = "INSERT INTO solution (problem_id, user_id, in_date, language, ip, code_length, contest_id, num,result)
		VALUES(?, ?, ?, ?, ?, ?, ?, ?, 14)";
        //比赛采用noip中的仅保留最后一次提交的规则。true则在新提交发生时，将本场比赛该题老的提交计入练习。
        if (isset($OJ_OI_1_SOLUTION_ONLY) && $OJ_OI_1_SOLUTION_ONLY) {
            pdo_query(
                "update solution set contest_id =0 where contest_id=? and user_id=? and num=?",
                $cid,
                $user_id,
                $pid
            );
        }
        $insert_id = pdo_query(
            $sql,
            $id,
            $user_id,
            $now,
            $language,
            $ip,
            $len,
            $cid,
            $pid
        );
     
        $sql="SELECT problem_id FROM contest_problem WHERE contest_id=? AND num=?";
        $problem_id = pdo_query($sql);
    }
	//问题中submited数+1
    $sql = "UPDATE problem set submited=submited+1 where problem_id = ?";
    pdo_query($sql, $id);
    //此用户的submited数+1
    $sql = "UPDATE users set submited=submited+1 where user_id = ?";
    pdo_query($sql, $user_id);

    $sql = "INSERT INTO `source_code_user`(`solution_id`,`source`)VALUES(?,?)";
    pdo_query($sql, $insert_id, $source_user);
    $sql = "INSERT INTO `source_code`(`solution_id`,`source`)VALUES(?,?)";
    pdo_query($sql, $insert_id, $source);
    if ($test_run) {
        $sql =
            "INSERT INTO custominput(solution_id,input_text)VALUES('$insert_id','$input_text')";
        pdo_query($sql);
    }
    $sql = "update solution set result=0 where solution_id='$insert_id'";
    $res = pdo_query($sql);
    //using redis task queue
    if ($OJ_REDIS) {
        $redis = new Redis();
        $redis->connect($OJ_REDISSERVER, $OJ_REDISPORT);
        if (isset($OJ_REDISAUTH)) {
            $redis->auth($OJ_REDISAUTH);
        }
        $redis->lpush($OJ_REDISQNAME, $insert_id);
        $redis->close();
    }
}

//if ($OJ_BENCHMARK_MODE) {
//    echo $insert_id;
//    exit(0);
//}

$statusURI = strstr($_SERVER['REQUEST_URI'], "submit", true) . "status.php";
if (isset($cid)) {
    $statusURI .= "?cid=$cid";
}

$sid = "";
if (isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
    $sid .= session_id() . $_SERVER['REMOTE_ADDR'];
}
if (isset($_SERVER["REQUEST_URI"])) {
    $sid .= $statusURI;
}
// echo $statusURI."<br>";

$sid = md5($sid);
$file = "cache/cache_$sid.html";
//echo $file;
if ($OJ_MEMCACHE) {
    $mem = new Memcache();
    if ($OJ_SAE) {
        $mem = memcache_init();
    } else {
        $mem->connect($OJ_MEMSERVER, $OJ_MEMPORT);
    }
    $mem->delete($file, 0);
} elseif (file_exists($file)) {
    unlink($file);
}
//echo $file;

$statusURI = "status.php?user_id=" . $_SESSION[$OJ_NAME . '_' . 'user_id'];
if (isset($cid)) {
    $statusURI .= "&cid=$cid";
}

if (!$test_run) {
    header("Location: $statusURI");
} else {
    if (isset($_GET['ajax'])) {
        echo $insert_id;
    } else {
         ?><script>window.parent.setTimeout("fresh_result('<?php echo $insert_id; ?>')",1000);</script><?php
    }
}
?>

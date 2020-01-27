<?php
    require_once("../../includes/config.inc.php");
    //require_once("../../includes/check_post_key.php");
    require_once ("../../includes/my_func.inc.php");

    if(!authContestManage()){
        $result = ['code' => 0, 'msg' => '编辑失败！您没有权限！', 'url' => '/admin/editcontest.php'];
        echo json_encode($result);
        return ;
    }

    /*获取竞赛信息*/
    $contest_id = $_POST['id'];
    $starttime = $_POST['start_time'];
    $endtime = $_POST['end_time'];
    $title = $_POST['title'];
    $private = $_POST['private'];
    $cat = $_POST['cat'];
    $password = $_POST['password'];
    $description = $_POST['description'];

    if(get_magic_quotes_gpc()){
        $title = stripslashes($title);
        $private = stripslashes($private);
        $password = stripslashes($password);
        $description = stripslashes($description);
    }

    $description = str_replace("<p>", "", $description);
    $description = str_replace("</p>", "<br />", $description);
    $description = str_replace(",", "&#44; ", $description);
    $user_id=$_SESSION[$OJ_NAME.'_'.'user_id'];

    /*记录更新*/
    $sql = "UPDATE contest SET title='$title', start_time='$starttime', end_time='$endtime', private='$private', cat='$cat', description='$description', password='$password', user_id='$user_id' WHERE contest_id='$contest_id'";

    $result = pdo_query($sql);

    $cid = $contest_id;

    /*竞赛与问题ID进行绑定*/
    //首先清楚掉该竞赛号以前绑定的问题ID（安全性操作）
    $sql = "DELETE FROM contest_problem WHERE contest_id='$cid'";
    pdo_query($sql);
    //插入新的问题
    $plist = trim($_POST['cproblem']);
    $pieces = explode(",",$plist );
    if(count($pieces)>0 && intval($pieces[0])>0){
        $plist="";
        for($i=0; $i<count($pieces); $i++){
            if($plist)$plist.=",";
            $plist.=$pieces[$i];

            $sql_1 = "INSERT INTO contest_problem (contest_id, problem_id, num) VALUES ('$cid', '$pieces[$i]', $i)";
            $result = pdo_query($sql_1);
        }
        //echo $sql_1;
        $sql = "UPDATE problem SET defunct='N' WHERE problem_id IN ($plist)";
        pdo_query($sql);
    }

    /*权限设置*/
    $sql = "DELETE FROM privilege WHERE rightstr = 'c$cid'";
    pdo_query($sql);

    $user = $_SESSION[$OJ_NAME.'_'.'user_id'];
    $sql = "INSERT INTO privilege (user_id, rightstr) VALUES('$user_id', 'm$cid')";
    pdo_query($sql);

    $_SESSION[$OJ_NAME.'_'."c$cid"] = true;

    $url = "/admin/editcontest.php?id=" . $contest_id;
    if ($result){
        $result = ['code' => 1, 'msg' => '更新成功！', 'url' => $url];
    }else{
        $result = ['code' => 0, 'msg' => '更新失败！', 'url' => $url];
    }

    echo json_encode($result);


?>
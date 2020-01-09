<?php
    require_once("../../includes/config.inc.php");
    //require_once("../../includes/check_post_key.php");
    require_once ("../../includes/my_func.inc.php");
    require_once ("problem.php");


    if(!(isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'problem_editor']))){
        //如果不具有权限（高级权限&问题编辑权限），提示登录。
        $result = ['code' => 0, 'msg' => '添加失败！您没有权限！', 'url' => '/admin/addproblem'];
        echo json_encode($result);
    }else{
        /*POST字段处理*/
        $starttime = $_POST['start_time'].":00";
        $endtime = $_POST['end_time'].":00";
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
        $lang = $_POST['lang'];

        $langmask = 0;
        foreach($lang as $t){
            $langmask += 1<<$t;
        }
        //$langmask = ((1<<count($language_ext))-1)&(~$langmask);
        $description = str_replace("<p>", "", $description);
        $description = str_replace("</p>", "<br />", $description);
        $description = str_replace(",", "&#44; ", $description);
        $user_id=$_SESSION[$OJ_NAME.'_'.'user_id'];


        /*记录插入*/
        $sql = "INSERT INTO contest (title, start_time, end_time, private, cat, langmask, description, password, user_id)
              VALUES('$title', '$starttime', '$endtime', '$private', '$cat', '$langmask', '$description', '$password', '$user_id')";


        $cid = pdo_query($sql);

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
        $result = pdo_query($sql);

        $user = $_SESSION[$OJ_NAME.'_'.'user_id'];
        $sql = "INSERT INTO privilege (user_id, rightstr) VALUES('$user_id', 'm$cid')";
        pdo_query($sql);

        $_SESSION[$OJ_NAME.'_'."c$cid"] = true;

//        /*竞赛用户添加*/
//        $pieces = explode("\n", trim($_POST['ulist']));
//
//        if(count($pieces)>0 && strlen($pieces[0])>0){
//            for($i=0; $i<count($pieces); $i++){
//                $sql_1 = "INSERT INTO privilege (user_id, rightstr) VALUES ('$pieces[$i]', 'c$cid')";
//                mysql_query($sql_1);
//            }
//        }

        $result = ['code' => 1, 'msg' => '添加成功！现在返回竞赛&实验列表...', 'url' => "/admin/contestlist.php"];
        echo json_encode($result);
    }



?>

<?php
    require_once("../../includes/config.inc.php");


    if (isset($_POST['cid'])){
        $cid = $_POST['cid'];

        $error_return_url = "/home/contestverify.php?id=$cid";
        $success_retrun_url = "/home/contest.php?id=$cid";

        if (isset($_POST['password']) && $_POST['password'] != NULL){
            $password = $_POST['password'];

            $sql = "SELECT password from contest WHERE contest_id = ?";
            $result = pdo_query($sql, $cid);
            $pswd = $result[0][0];

            if ($pswd == $password){
                //给用户增加此竞赛的权限
                $_SESSION[$OJ_NAME.'_'."c$cid"] = true;
                $result = ['code' => 1, 'msg' => '验证通过！', 'url' => $success_retrun_url];
            }else{
                $result = ['code' => 0, 'msg' => '密码错误！', 'url' => $error_return_url];
            }
        }else{
            $result = ['code' => 0, 'msg' => '请输入密码！', 'url' => $error_return_url];
        }
    }else{
        $result = ['code' => 0, 'msg' => '无此竞赛！', 'url' => $error_return_url];
    }

    echo json_encode($result);



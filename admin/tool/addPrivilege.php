<?php
    require_once("../../includes/config.inc.php");
    //require_once("../../includes/check_post_key.php");
    require_once ("../../includes/my_func.inc.php");

    if (!isset($_POST['user_id']) || !isset($_POST['rightstr']) || $_POST['user_id'] == null || $_POST['rightstr'] == null){
        $res = ['code' => 0, 'msg' => '添加失败！请输入内容', 'url' => "/admin/addprivilege.php"];
    }else{
        $user_id = $_POST['user_id'];
        $rightstr = $_POST['rightstr'];

        /*检查是否存在此用户名*/
        $sql = "SELECT COUNT(*) FROM users WHERE user_id = '$user_id'";
        $result = pdo_query($sql);
        $nums = $result[0][0];
        if ($nums != 1){
            $res = ['code' => 0, 'msg' => '添加失败！无此用户名！', 'url' => "/admin/addprivilege.php"];
        }else{
            /*检查该用户名的是否已有该权限*/
            $sql = "SELECT COUNT(*) FROM privilege WHERE user_id = '$user_id' && rightstr = '$rightstr'";
            $result = pdo_query($sql);
            $nums = $result[0][0];
            if ($nums == 1){
                $res = ['code' => 0, 'msg' => '添加失败！此用户已有此权限！', 'url' => "/admin/addprivilege.php"];
            }else {
                $sql = "INSERT INTO privilege (user_id, rightstr, defunct) VALUES ('$user_id', '$rightstr', 'N')";
                $result = pdo_query($sql);
                if ($result) {
                    $res = ['code' => 1, 'msg' => '添加成功！', 'url' => "/admin/privilegelist.php"];
                } else {
                    $res = ['code' => 0, 'msg' => '添加失败！请重试...', 'url' => "/admin/addprivilege.php"];
                }
            }
        }
    }
    echo json_encode($res);
?>
